<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Event\AbstractEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
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
	 * @param string $name    The table name. Optional.
	 * @param string $prefix  The class prefix. Optional.
	 * @param array  $options Configuration array for model. Optional.
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
	 * @param   array   $data     Data for the form.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
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
		if (!$this->canEditState((object) $data)) {
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
	 * @since    1.3.0
	 *
	 * @param Table $table
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
	 * @param Form $form
	 * @param mixed  $data
	 * @param string $group
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
		if ($this->getCurrentUser()->authorise('com_visibilities', 'core.edit.state'))
		{
			// Save directly if user is allowed to publish ('edit.state')
			return parent::save($data);
		}
		else
		{
			// Else, just write a version entry with the suggested changes. Basically what parent does, but skipping $table->store.
			$table      = $this->getTable();
			$context    = $this->option . '.' . $this->name;
			$app        = Factory::getApplication();

			if (\array_key_exists('tags', $data) && \is_array($data['tags'])) {
				$table->newTags = $data['tags'];
			}

			$key   = $table->getKeyName();
			$pk    = (isset($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
			$isNew = true;

			// Include the plugins for the save events.
			PluginHelper::importPlugin($this->events_map['save']);

			// Allow an exception to be thrown.
			try {
				// Load the row if saving an existing record.
				if ($pk > 0) {
					$table->load($pk);
					$isNew = false;
				}

				// Bind the data.
				if (!$table->bind($data)) {
					$this->setError($table->getError());

					return false;
				}

				// Prepare the row for saving
				$this->prepareTable($table);

				// Check the data.
				if (!$table->check()) {
					$this->setError($table->getError());

					return false;
				}

				// Trigger the before save event.
				$result = $app->triggerEvent($this->event_before_save, [$context, $table, $isNew, $data]);

				if (\in_array(false, $result, true)) {
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

			return $result;
		}
	}
}
