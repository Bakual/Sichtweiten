<?php
// No direct access.
defined('_JEXEC') or die;

/**
 * Divesite model.
 *
 * @package   Sichtweiten.Administrator
 *
 * @since     1.3.0
 */
class SichtweitenModelDivesite extends JModelAdmin
{
	/**
	 * @var     string    The prefix to use with controller messages.
	 * @since   1.3.0
	 */
	protected $text_prefix = 'COM_SICHTWEITEN';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param    string $type   The table type to instantiate
	 * @param    string $prefix A prefix for the table class name. Optional.
	 * @param    array  $config Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 * @since    1.3.0
	 */
	public function getTable($type = 'Tauchplatz', $prefix = 'SichtweitenTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array   $data     Data for the form.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm|boolean  A JForm object on success, false on failure
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
			$db = $this->getDbo();
			$query = $db->getQuery(true);
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
		$data = JFactory::getApplication()->getUserState('com_sichtweiten.edit.divesite.data', array());

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
	 * @param \JTable $table
	 */
	protected function prepareTable($table)
	{
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param \JForm $form
	 * @param mixed  $data
	 * @param string $group
	 *
	 * @since    1.3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'sichtweiten')
	{
		parent::preprocessForm($form, $data, $group);
	}
}
