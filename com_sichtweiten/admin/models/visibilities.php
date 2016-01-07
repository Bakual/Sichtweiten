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
				'id',
			);

			// Searchtools
			$config['filter_fields'][] = 'datum';
			$config['filter_fields'][] = 'meldedatum';
			$config['filter_fields'][] = 'user';
		}

		$params = JComponentHelper::getParams('com_sichtweiten');

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

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
