<?php

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\QueryInterface;

class SichtweitenModelCountries extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param  array $config An optional associative array of configuration settings.
	 *
	 * @see    JController
	 * @since  1.3.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			// Filter Fields define valid ordering fields.
			$config['filter_fields'] = array(
				'l.id',
				'l.bezeichnung',
				'l.kurzzeichen',
				'l.flag',
				'l.displaynr',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 * @since   1.3.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$params = ComponentHelper::getParams('com_sichtweiten');
		$this->setState('params', $params);

		parent::populateState('l.bezeichnung', 'asc');
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
					'l.id',
					'l.bezeichnung',
					'l.kurzzeichen',
					'l.flag',
					'l.displaynr',
				)
			)
		);
		$query->from('`#__sicht_land` AS l');

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('l.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(l.bezeichnung LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
