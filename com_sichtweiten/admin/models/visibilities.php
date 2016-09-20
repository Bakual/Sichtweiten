<?php
defined('_JEXEC') or die;

class SichtweitenModelVisibilities extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param  array $config An optional associative array of configuration settings.
	 *
	 * @see    JController
	 * @since  1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'swm.id',
			);

			// Searchtools
			$config['filter_fields'][] = 'swm.datum';
			$config['filter_fields'][] = 'swm.meldedatum';
			$config['filter_fields'][] = 'swm.user';
			$config['filter_fields'][] = 'tp.name';
		}

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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sichtweiten');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('swm.datum', 'desc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$db->quoteName(
				array(
					'swm.id',
					'swm.datum',
					'swm.meldedatum',
					'swm.kommentar',
				)
			)
		);
		$query->from('`#__sicht_sichtweitenmeldung` AS swm');

		// Join Tauchplatz table
		$query->select('tp.name AS tauchplatz');
		$query->join('LEFT', '`#__sicht_tauchplatz` AS tp ON tp.id = swm.tauchplatz_id');


		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
