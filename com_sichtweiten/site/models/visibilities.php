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
 * @since  1.0
 */
class SichtweitenModelVisibilities extends JModelList
{
	/**
	 * An array of items
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $item;

	/**
	 * A blacklist of filter variables to not merge into the model's state
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $filterBlacklist = array('period');

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
		$this->filter_fields = array(
			'tp.name',
			'datum',
			'kommentar',
			'g.displayName',
			'sichtweite_id_0',
			'sichtweite_id_1',
			'sichtweite_id_2',
			'sichtweite_id_3',
			'sichtweite_id_4',
			'sichtweite_id_5',
			'user_id',
		);

		$params = JFactory::getApplication()->getParams();

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
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the table
		$query->select(
			$db->quoteName(
				array(
					'tp.id',
					'tp.name',
					'tp.bemerkungen',
					'tp.active',
				)
			)
		);

		$query->from('`#__sicht_tauchplatz` AS tp');

		// Filter by a specific location. Needed for single location view.
		if ($location = (int) $this->getState('filter.location'))
		{
			$query->where($db->quoteName('tp.id') . ' = ' . $location);
		}

		// Join over Gewaesser table
		$query->select(
			array(
				$db->quoteName('g.name', 'gewaesser_name'),
				$db->quoteName('g.displayName', 'gewaesser_displayName'),
			)
		);

		$query->join('LEFT', '#__sicht_gewaesser AS g ON tp.gewaesser_id = g.id');

		// Join over Land table
		$query->select(
			$db->quoteName(
				array(
					'lg.bezeichnung',
					'lg.kurzzeichen',
				),
				array(
					'land_gewaesser_bezeichnung',
					'land_gewaesser_kurzzeichen',
				)
			)
		);

		$query->join('LEFT', '#__sicht_land AS lg ON g.land_id = lg.id');

		// Join over Ort table
		$query->select(
			array(
				$db->quoteName('o.name', 'ort_name'),
			)
		);

		$query->join('LEFT', '#__sicht_ort AS o ON tp.ort_id = o.id');

		// Join over Land table
		$query->select(
			$db->quoteName(
				array(
					'lo.bezeichnung',
					'lo.kurzzeichen',
				),
				array(
					'land_ort_bezeichnung',
					'land_ort_kurzzeichen',
				)
			)
		);

		$query->join('LEFT', '#__sicht_land AS lo ON o.land_id = lo.id');

		// Join over Sichtweitenmeldung table
		$query->select(
			$db->quoteName(
				array(
					'swm.datum',
					'swm.kommentar',
					'swm.user_id',
				)
			)
		);

		$query->join('LEFT', '#__sicht_sichtweitenmeldung AS swm ON swm.tauchplatz_id = tp.id');

		if ($period = (int) $this->getState('filter.period'))
		{
			$query->where($db->quoteName('swm.datum') . ' >= DATE_SUB(CURDATE(), INTERVAL ' . $period . ' DAY)');
		}

		if ($user_id = (int) $this->getState('filter.user'))
		{
			$query->where($db->quoteName('swm.user_id') . ' = ' . $user_id);
		}

		// Join over Sicht_User table
		$query->select(
			$db->quoteName(
				array(
					'user.name',
					'user.joomla_id',
				),
				array(
					'user_name',
					'user_joomla_id',
				)
			)
		);

		$query->join('LEFT', '#__sicht_user AS user ON swm.user_id = user.id');

		// Join over Tauchpartner table
		$query->select("GROUP_CONCAT(buddy.name SEPARATOR '|') AS buddy_names");
		$query->select("GROUP_CONCAT(buddy.email SEPARATOR '|') AS buddy_emails");

		$query->join('LEFT', '#__sicht_tauchpartner AS buddy ON buddy.sichtweitenmeldung_id = swm.id');
		$query->group('swm.id');

		if ($period = (int) $this->getState('filter.period'))
		{
			$query->where($db->quoteName('swm.datum') . ' >= DATE_SUB(CURDATE(), INTERVAL ' . $period . ' DAY)');
		}

		// Join over Sichtweiteneintrag table
		$tiefenbereich = $this->getState('filter.tiefe');
		$tiefenbereich = \Joomla\Utilities\ArrayHelper::toInteger($tiefenbereich);

		foreach ($tiefenbereich as $key => $value)
		{
			$query->select(
					$db->quoteName(
							array(
									'swe' . $key . '.sichtweite_id',
									'swe' . $key . '.tiefenbereich_id',
							),
							array(
									'sichtweite_id_' . $key,
									'tiefenbereich_id_' . $key,
							)
					)
			);

			$query->join('LEFT', '#__sicht_sichtweiteneintrag AS swe' . $key . ' ON swe' . $key . '.sichtweitenmeldung_id = swm.id');
			$query->where('swe' . $key . '.tiefenbereich_id = ' . $value);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if ($search)
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where($db->quoteName('tp.name') . ' LIKE ' . $search . ')');
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where($db->quoteName('tp.active') . ' = ' . (int) $state);
		}

		// Add the list ordering clause.
		if ($this->getState('list.ordering', 'g.displayName') == 'g.displayName')
		{
			$query->order('g.displayName ' . $db->escape($this->getState('list.direction', 'ASC')) . ', swm.datum DESC');
		}
		else
		{
			$query->order($db->escape($this->getState('list.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
		}

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app    = JFactory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);

		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_sichtweiten')) && (!$user->authorise('core.edit', 'com_sichtweiten')))
		{
			// Filter on published for those who do not have edit or edit.state rights.
			$this->setState('filter.state', 1);
		}

		$this->setState('filter.period', $params->get('list_period', 14));

		$tiefenbereich = $app->getUserStateFromRequest($this->context . '.filter.tiefe', 'filter-tiefe', '', 'ARRAY');

		if (!$tiefenbereich)
		{
			$tiefenbereich = array(79, 80, 81, 82, 83, 84);
		}

		$this->setState('filter.tiefe', $tiefenbereich);

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter-search', '', 'STRING');
		$this->setState('filter.search', $search);

		parent::populateState('g.displayName', 'ASC');
	}
}
