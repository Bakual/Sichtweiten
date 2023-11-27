<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;

/**
 * Visibility model.
 *
 * @package  Sichtweiten.Administrator
 * @since    1.0.0
 */
class SichtweitenModelVisibilityreport extends AdminModel
{
	/**
	 * @var   string    The prefix to use with controller messages.
	 *
	 * @since 1.0.0
	 */
	protected $text_prefix = 'COM_SICHTWEITEN';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param string $name    The table name. Optional.
	 * @param string $prefix  The class prefix. Optional.
	 * @param array  $options Configuration array for model. Optional.
	 *
	 * @return    Table    A database object
	 * @since    1.0
	 */
	public function getTable($name = 'Sichtweitenmeldung', $prefix = 'SichtweitenTable', $options = array())
	{
		return Table::getInstance($name, $prefix, $options);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 * @since    1.3.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sichtweiten.visibilityreport', 'visibilityreport', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
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
		$data = Factory::getApplication()->getUserState('com_sichtweiten.edit.visibilityreport.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_sichtweiten.visibilityreport', $data);

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table
	 *
	 * @since    1.6
	 *
	 */
	protected function prepareTable($table)
	{
		$table->kommentar = htmlspecialchars_decode($table->kommentar, ENT_QUOTES);
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
	 * @since    3.0
	 */
	protected function preprocessForm(Form $form, $data, $group = 'sichtweiten')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   1.3.0
	 */
	public function delete(&$pks)
	{
		$user = Factory::getUser();

		if (!$user->authorise('core.delete', $this->option))
		{
			Factory::getApplication()->enqueueMessage('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED', 'error');

			return false;
		}

		$pks = (array) $pks;
		$pks = ArrayHelper::toInteger($pks);

		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		$query->delete('#__sicht_sichtweitenmeldung');
		$query->where($db->quoteName('id') . ' IN (' . implode(',', $pks) . ')');

		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);

		$query->delete('#__sicht_sichtweiteneintrag');
		$query->where($db->quoteName('sichtweitenmeldung_id') . ' IN (' . implode(',', $pks) . ')');

		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);

		$query->delete('#__sicht_tauchpartner');
		$query->where($db->quoteName('sichtweitenmeldung_id') . ' IN (' . implode(',', $pks) . ')');

		$db->setQuery($query);
		$db->execute();

		return true;
	}
}
