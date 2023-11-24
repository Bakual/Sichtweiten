<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Model class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenModelLocations extends ListModel
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
			'tp.bemerkungen',
			'g.displayName',
		);

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
		$db    = $this->getDatabase()
		$query = $db->createQuery();

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

		// Join over Gewaesser table
		$query->select(
			array(
				$db->quoteName('g.id', 'gewaesser_id'),
				$db->quoteName('g.name', 'gewaesser_name'),
				$db->quoteName('g.displayName', 'gewaesser_displayName'),
			)
		);

		$query->join('LEFT', '#__sicht_gewaesser AS g ON tp.gewaesser_id = g.id');

		// Join over Land table
		$query->select(
			$db->quoteName(
				array(
					'lg.id',
					'lg.bezeichnung',
					'lg.kurzzeichen',
				),
				array(
					'land_gewaesser_id',
					'land_gewaesser_bezeichnung',
					'land_gewaesser_kurzzeichen',
				)
			)
		);

		$query->join('LEFT', '#__sicht_land AS lg ON g.land_id = lg.id');

		// Join over Bezeichnung table
		$query->select("GROUP_CONCAT(b.name SEPARATOR ', ') AS alt_name");
		$query->join('LEFT', '#__sicht_bezeichnung AS b ON tp.id = b.tauchplatz_id');
		$query->group('tp.id');

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

		// Add a hardcoded list ordering clause.
		$query->order('lg.bezeichnung DESC, g.displayName ASC, tp.name ASC');

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
		$app    = Factory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);

		$user = Factory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_sichtweiten')) && (!$user->authorise('core.edit', 'com_sichtweiten')))
		{
			// Filter on published for those who do not have edit or edit.state rights.
			$this->setState('filter.state', 1);
		}

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter-search', '', 'STRING');
		$this->setState('filter.search', $search);

		parent::populateState('g.displayName', 'ASC');
	}
}
