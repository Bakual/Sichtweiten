<?php
// No direct access.
defined('_JEXEC') or die;

/**
 * Visibility model.
 *
 * @package        Sichtweiten.Administrator
 */
class SichtweitenModelVisibility extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_SICHTWEITEN';

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 * @since   1.0
	 */
	public function __construct($config = array())
	{
		$params = JComponentHelper::getParams('com_sichtweiten');

		if ($params->get('extern_db'))
		{
			// Taken from https://docs.joomla.org/Connecting_to_an_external_database
			$option = array();

			$option['driver']   = $params->get('db_type', 'mysqli');
			$option['host']     = $params->get('db_host', 'localhost');
			$option['database'] = $params->get('db_database');
			$option['user']     = $params->get('db_user');
			$option['password'] = $params->get('db_pass');
			$option['prefix']   = $params->get('db_prefix', 'jos_');

			$config['dbo'] = JDatabaseDriver::getInstance($option);
		}

		parent::__construct($config);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param    type      The table type to instantiate
	 * @param    string    A prefix for the table class name. Optional.
	 * @param    array     Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 * @since    1.0
	 */
	public function getTable($type = 'Visibility', $prefix = 'SichtweitenTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param    array   $data     An optional array of data for the form to interogate.
	 * @param    boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return    JForm    A JForm object on success, false on failure
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sichtweiten.visibility', 'visibility', array('control' => 'jform', 'load_data' => $loadData));

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
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sichtweiten.edit.visibility.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_sichtweiten.visibility', $data);

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since    1.6
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
	 * @return  void
	 * @since    3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'sichtweiten')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array &$pks An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   1.0
	 */
	public function delete(&$pks)
	{
		$user = JFactory::getUser();

		if (!$user->authorise('core.delete', $this->option))
		{
			JFactory::getApplication()->enqueueMessage('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED', 'error');

			return false;
		}

		$pks = (array) $pks;
		$pks = \Joomla\Utilities\ArrayHelper::toInteger($pks);

		$db    = $this->getDbo();
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
