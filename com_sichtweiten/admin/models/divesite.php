<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
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
		$item = parent::getItem($pk);

		if ($item->id)
		{
			$db = $this->getDatabase();
			$query = $db->createQuery();
			$query->select($db->quoteName('name'));
			$query->from('#__sicht_bezeichnung');
			$query->where($db->quoteName('tauchplatz_id') . ' = ' . (int) $item->id);
			$query->order($db->quoteName('id'));

			$db->setQuery($query);
			$rows = $db->loadRowList();

			foreach ($rows as $row)
			{
				$item->altnames[]['alt_name'] = $row[0];
			}
		}

		return $item;
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
}
