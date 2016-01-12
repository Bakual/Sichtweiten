<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * Model class for the Sichtweiten Component
 *
 * @since  5
 */
class SichtweitenModelSichtweitenmeldung extends JModelAdmin
{
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
		$params = JFactory::getApplication()->getParams();

		// Taken from https://docs.joomla.org/Connecting_to_an_external_database
		$option = array();

		$option['driver']   = $params->get('db_type', 'mysqli');
		$option['host']     = $params->get('db_host', 'localhost');
		$option['database'] = $params->get('db_database');
		$option['user']     = $params->get('db_user');
		$option['password'] = $params->get('db_pass');
		$option['prefix']   = $params->get('db_prefix', 'jos_');

		$config['dbo'] = JDatabaseDriver::getInstance($option);

		parent::__construct($config);
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  Ordering column
	 * @param   string $direction 'ASC' or 'DESC'
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$return = $jinput->get('return', '', 'base64');

		if (!JUri::isInternal(base64_decode($return)))
		{
			$return = null;
		}

		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $jinput->get('layout'));
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array   $data     Data for the form.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_sichtweiten.sichweitenmeldung', 'sichtweitenmeldung', array('control' => 'jform', 'load_data' => $loadData));

		return ($form) ? $form : false;
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
	public function getTable($type = 'Sichtweitenmeldung', $prefix = 'SichtweitenTable', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable $table The JTable object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function prepareTable($table)
	{
		$table->meldedatum = JFactory::getDate()->toSql();

		$user = JFactory::getUser();

		if (!$user->guest)
		{
			$table->user_id = $user->id;
		}
	}

	/**
	 * Save the Sichtweiteeintrag during postSave
	 *
	 * @param   object $validData The validated data to be saved
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function insertSichtweiteneintrag($validData)
	{
		$tiefenbereiche = array(
			79 => 'tiefenbereich0',
			80 => 'tiefenbereich1',
			81 => 'tiefenbereich2',
			82 => 'tiefenbereich3',
			83 => 'tiefenbereich4',
			84 => 'tiefenbereich5',
		);

		$id = (int) $this->state->get('sichtweitenmeldung.id');

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->insert('#__sicht_sichtweiteneintrag');
		$query->columns(array('sichtweite_id', 'sichtweitenmeldung_id', 'tiefenbereich_id'));

		foreach ($tiefenbereiche as $key => $value)
		{
			$query->values((int) $validData[$value] . ',' . $id . ',' . (int) $key);
		}

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Save the Tauchpartners during postSave
	 *
	 * @param   object $validData The validated data to be saved
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function insertTauchpartner($validData)
	{
		$id = (int) $this->state->get('sichtweitenmeldung.id');

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->insert('#__sicht_tauchpartner');
		$query->columns(array('sichtweitenmeldung_id', 'name', 'email'));

		$execute = false;

		for ($i = 1; $i <= 3; $i++)
		{
			if ($validData['tauchpartner_' . $i . '_name'])
			{
				$query->values($id . ',' . $db->quote($validData['tauchpartner_' . $i . '_name']) . ',' . $db->quote($validData['tauchpartner_' . $i . '_email']));
				$execute = true;
			}
		}

		if ($execute)
		{
			$db->setQuery($query);
			$db->execute();
		}
	}
}
