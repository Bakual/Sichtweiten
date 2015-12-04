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
class SichtweitenModelLocation extends JModelItem
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
		/** @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$params = $app->getParams();

		// Load the object state.
		$id = $app->input->get('id', 0, 'int');
		$this->setState('location.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   int $id The id of the object to get.
	 *
	 * @return mixed Object on success, false on failure.
	 * @throws \Exception
	 */
	public function getItem($id = null)
	{
		$user = JFactory::getUser();

		// Initialise variables.
		$id = ($id) ? $id : (int) $this->getState('location.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$id]))
		{
			try
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

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

				$query->from('#__sicht_tauchplatz AS tp');

				$query->where($db->quoteName('tp.id') . ' = ' . (int) $id);
				$query->where($db->quoteName('tp.active') . ' = 1');

				// Join over Gewaesser table
				$query->select(
						$db->quoteName('g.name', 'gewaesser_name'),
						$db->quoteName('g.displayName', 'gewaesser_displayName')
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

				$db->setQuery($query);

				$data = $db->loadObject();

				if ($error = $db->getErrorMsg())
				{
					throw new Exception($error);
				}

				if (!$data)
				{
					throw new Exception(JText::_('JGLOBAL_RESOURCE_NOT_FOUND'), 404);
				}

				$this->_item[$id] = $data;
			}
			catch (Exception $e)
			{
				$this->_item[$id] = false;

				throw $e;
			}
		}

		return $this->_item[$id];
	}
}
