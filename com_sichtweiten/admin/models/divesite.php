<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\AbstractEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\LanguageFactoryInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\Versioning\VersionableModelTrait;

/**
 * Divesite model.
 *
 * @package   Sichtweiten.Administrator
 *
 * @since     1.3.0
 */
class SichtweitenModelDivesite extends AdminModel
{
	use VersionableModelTrait;

	/**
	 * The type alias for this content type (for example, 'com_content.article').
	 *
	 * @var    string
	 * @since  2.1.0
	 */
	public $typeAlias = 'com_sichtweiten.divesite';

	/**
	 * @var     string    The prefix to use with controller messages.
	 * @since   1.3.0
	 */
	protected $text_prefix = 'COM_SICHTWEITEN';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return   Table    A database object
	 * @since    1.3.0
	 */
	public function getTable($name = 'Tauchplatz', $prefix = 'SichtweitenTable', $options = array())
	{
		return Table::getInstance($name, $prefix, $options);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 *
	 * @since    1.3.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sichtweiten.divesite', 'divesite', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  JObject|boolean  Object on success, false on failure.
	 *
	 * @since   1.3.0
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return    mixed    The data for the form.
	 * @since    1.3.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_sichtweiten.edit.divesite.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_sichtweiten.divesite', $data);

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table
	 *
	 * @since    1.3.0
	 *
	 */
	protected function prepareTable($table)
	{
		// Increment the content version number.
		$table->version++;
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   Form    $form
	 * @param   mixed   $data
	 * @param   string  $group
	 *
	 * @since    1.3.0
	 */
	protected function preprocessForm(Form $form, $data, $group = 'sichtweiten')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   2.2
	 */
	public function save($data)
	{
		$user         = $this->getCurrentUser();
		$canEditState = $user->authorise('com_visibilities', 'core.edit.state');
		$isNew        = empty($data['id']);

		if ($isNew || $canEditState)
		{
			// Save directly if it is a new dive site or the user is allowed to publish ('edit.state')
			$result = parent::save($data);
		}
		else
		{
			// Else, just write a version entry with the suggested changes. Basically what parent does, but skipping $table->store.
			$table   = $this->getTable();
			$context = $this->option . '.' . $this->name;
			$app     = Factory::getApplication();

			if (\array_key_exists('tags', $data) && \is_array($data['tags']))
			{
				$table->newTags = $data['tags'];
			}

			$input = Factory::getApplication()->getInput();
			$jform = $input->get('jform', [], 'array');

			$jform['version_note'] = Text::_('COM_SICHTWEITEN_DIVESITE_SUGGESTION_NOTE');
			$input->set('jform', $jform);

			$key = $table->getKeyName();
			$pk  = (isset($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');

			// Include the plugins for the save events.
			PluginHelper::importPlugin($this->events_map['save']);

			// Allow an exception to be thrown.
			try
			{
				// Load the row if saving an existing record.
				if ($pk > 0)
				{
					$table->load($pk);
				}

				// Bind the data.
				if (!$table->bind($data))
				{
					$this->setError($table->getError());

					return false;
				}

				// Prepare the row for saving
				$this->prepareTable($table);

				// Check the data.
				if (!$table->check())
				{
					$this->setError($table->getError());

					return false;
				}

				// Trigger the before save event.
				$result = $app->triggerEvent($this->event_before_save, [$context, $table, false, $data]);

				if (\in_array(false, $result, true))
				{
					$this->setError($table->getError());

					return false;
				}
			}
			catch (\Exception $e)
			{
				$this->setError($e->getMessage());

				return false;
			}

			// Post-processing by observers
			$event = AbstractEvent::create(
				'onTableAfterStore',
				[
					'subject' => $table,
					'result'  => &$result,
				]
			);
			$this->getDispatcher()->dispatch('onTableAfterStore', $event);
		}

		if (!$result)
		{
			return false;
		}

		// Send a message
		if (!$canEditState)
		{
			// Load table for new dive sites
			if ($isNew)
			{
				$id    = $this->getState($this->getName() . '.id');
				$table = $this->getTable();
				$table->load($id);
			}

			$app = Factory::getApplication();

			// Get the model for private messages
			$model_message = $app->bootComponent('com_messages')
				->getMVCFactory()->createModel('Message', 'Administrator');

			// Prepare Language for messages
			$defaultLanguage = ComponentHelper::getParams('com_languages')->get('administrator');
			$debug           = $app->get('debug_lang');

			$params        = ComponentHelper::getParams('com_sichtweiten');
			$userGroups    = $params->get('divesite_edit_notification');
			$userIds       = array();
			$titleString   = 'COM_SICHTWEITEN_DIVESITE_SUGGESTION_NOTIFICATION_TITLE_' . ($isNew ? 'NEW' : 'EDIT');
			$messageString = 'COM_SICHTWEITEN_DIVESITE_SUGGESTION_NOTIFICATION_MESSAGE_' . ($isNew ? 'NEW' : 'EDIT');
			$editUrl        = Uri::root() . '/administrator/index.php?option=com_sichtweiten&task=divesite.edit&id=' . $table->id;

			foreach ($userGroups as $userGroup)
			{
				$userIds = array_merge($userIds, Access::getUsersByGroup($userGroup));
			}

			$userIds = array_unique($userIds);

			// Send Email to receivers
			foreach ($userIds as $userId)
			{
				$receiver = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($userId);

				if ($receiver->authorise('core.manage', 'com_messages'))
				{
					// Load language for messaging
					$lang = Factory::getContainer()->get(LanguageFactoryInterface::class)->createLanguage($user->getParam('admin_language', $defaultLanguage), $debug);
					$lang->load('com_sichtweiten');
					$titleText   = Text::sprintf($titleString, $user->name, $table->title);
					$messageText = Text::sprintf($messageString, $editUrl, $table->title);

					$message = [
						'id'         => 0,
						'user_id_to' => $receiver->id,
						'subject'    => $titleText,
						'message'    => $messageText,
					];

					$model_message->save($message);
				}
			}
		}

		return true;
	}
}
