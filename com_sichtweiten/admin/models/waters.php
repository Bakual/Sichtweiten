<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

class SichtweitenModelWaters extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param array $config An optional associative array of configuration settings.
	 *
	 * @see    JController
	 * @since  1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			// Filter Fields define valid ordering fields.
			$config['filter_fields'] = array(
				'g.id',
				'g.name',
				'g.displayName',
				'g.maxTiefe',
				'g.meterUeberMeer',
				'g.land_id',
				'lg.bezeichnung',
			);

			// Parent::getActiveFilters uses them for SearchTools. Has to match filter name (eg "foo" for "filters.foo")
			$config['filter_fields'][] = 'land';
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param string $ordering  An optional ordering field.
	 * @param string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 * @since   1.3.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$params = ComponentHelper::getParams('com_sichtweiten');
		$this->setState('params', $params);

		parent::populateState('g.name', 'asc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  QueryInterface
	 * @since   1.3.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$db->quoteName(
				array(
					'g.id',
					'g.name',
					'g.displayName',
					'g.maxTiefe',
					'g.land_id',
					'g.meterUeberMeer',
				)
			)
		);
		$query->from('`#__sicht_gewaesser` AS g');

		// Join Gewaesser-Land table
		$query->select('lg.bezeichnung AS gewaesser_land, lg.kurzzeichen AS gewaesser_land_kurz');
		$query->join('LEFT', '`#__sicht_land` AS lg ON lg.id = g.land_id');

		// Filter by Land
		$land = $this->getState('filter.land');

		if (is_numeric($land))
		{
			$query->where('g.land_id = ' . (int) $land);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('g.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(g.name LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
