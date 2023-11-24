<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

class SichtweitenModelDivesites extends ListModel
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
				'tp.id',
				'tp.name',
				'tp.active',
				'g.name',
				'o.name',
			);

			// Parent::getActiveFilters uses them for SearchTools. Has to match filter name (eg "foo" for "filters.foo")
			$config['filter_fields'][] = 'gewaesser';
			$config['filter_fields'][] = 'ort';
			$config['filter_fields'][] = 'published';
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

		parent::populateState('tp.name', 'asc');
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
		$query = $db->createQuery();

		// Select the required fields from the table.
		$query->select(
			$db->quoteName(
				array(
					'tp.id',
					'tp.name',
					'tp.active',
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

		// Filter by Gewaesser
		$gewaesser = $this->getState('filter.gewaesser');

		if (is_numeric($gewaesser))
		{
			$query->where('tp.gewaesser_id = ' . (int) $gewaesser);
		}

		// Join Ort table
		$query->select('o.name AS ort');
		$query->join('LEFT', '`#__sicht_ort` AS o ON o.id = tp.ort_id');

		// Join Ort-Land table
		$query->select('lo.bezeichnung AS ort_land, lo.kurzzeichen AS ort_land_kurz');
		$query->join('LEFT', '`#__sicht_land` AS lo ON lo.id = o.land_id');

		// Filter by Ort
		$ort = $this->getState('filter.ort');

		if (is_numeric($ort))
		{
			$query->where('tp.ort_id = ' . (int) $ort);
		}

		// Join over Bezeichnung table
		$query->select("GROUP_CONCAT(b.name SEPARATOR ', ') AS alt_name");
		$query->join('LEFT', '#__sicht_bezeichnung AS b ON tp.id = b.tauchplatz_id');
		$query->group('tp.id');

		// Filter by published state.
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where($db->quoteName('tp.active') . ' = ' . (int) $published);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('tp.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(tp.name LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
