<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

class SichtweitenModelVisibilityreports extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param  array $config An optional associative array of configuration settings.
	 *
	 * @see    JController
	 * @since  1.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			// Filter Fields define valid ordering fields.
			$config['filter_fields'] = array(
				'swm.id',
				'swm.datum',
				'swm.meldedatum',
				'swm.user',
				'tp.title',
			);

			// Parent::getActiveFilters uses them for SearchTools. Has to match filter name (eg "foo" for "filters.foo")
			$config['filter_fields'][] = 'tauchplatz';
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$params = ComponentHelper::getParams('com_sichtweiten');
		$this->setState('params', $params);

		parent::populateState('swm.datum', 'desc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   QueryInterface
	 * @since    1.0
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
					'swm.id',
					'swm.datum',
					'swm.meldedatum',
					'swm.kommentar',
				)
			)
		);
		$query->from('`#__sicht_sichtweitenmeldung` AS swm');

		// Join Tauchplatz table
		$query->select('tp.title AS tauchplatz');
		$query->join('LEFT', '`#__sicht_tauchplatz` AS tp ON tp.id = swm.tauchplatz_id');

		// Filter by dive site
		$tauchplatz = $this->getState('filter.tauchplatz');

		if (is_numeric($tauchplatz))
		{
			$query->where('swm.tauchplatz_id = ' . (int) $tauchplatz);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('swm.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(swm.datum LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
