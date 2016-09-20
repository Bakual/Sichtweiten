<?php
defined('_JEXEC') or die;

class SichtweitenModelDivesites extends JModelList
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
				'tp.id',
			);

			// Searchtools
			$config['filter_fields'][] = 'tp.name';
			$config['filter_fields'][] = 'g.name';
			$config['filter_fields'][] = 'o.name';
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
		// Load the parameters.
		$params = JComponentHelper::getParams('com_sichtweiten');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('tp.name', 'asc');
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
					'tp.id',
					'tp.name',
					'tp.spezielles',
					'tp.einschraenkungen',
					'tp.bemerkungen',
				)
			)
		);
		$query->from('`#__sicht_tauchplatz` AS tp');

		// Join Gewaesser table
		$query->select('g.name AS gewaesser');
		$query->join('LEFT', '`#__sicht_gewaesser` AS g ON g.id = tp.gewaesser_id');

		// Join Gewaesser-Land table
		$query->select('lg.bezeichnung AS gewaesser_land, lg.kurzzeichen AS gewaesser_land_kurz');
		$query->join('LEFT', '`#__sicht_land` AS lg ON lg.id = g.land_id');

		// Join Ort table
		$query->select('o.name AS ort');
		$query->join('LEFT', '`#__sicht_ort` AS o ON o.id = tp.ort_id');

		// Join Ort-Land table
		$query->select('lo.bezeichnung AS ort_land, lo.kurzzeichen AS ort_land_kurz');
		$query->join('LEFT', '`#__sicht_land` AS lo ON lo.id = o.land_id');

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}