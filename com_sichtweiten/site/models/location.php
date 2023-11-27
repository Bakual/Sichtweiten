<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;

/**
 * Model class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenModelLocation extends ItemModel
{
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
		$app    = Factory::getApplication();
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
		// Initialise variables.
		$id = ($id) ?: (int) $this->getState('location.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$id]))
		{
			try
			{
				$db    = $this->getDatabase();
				$query = $db->getQuery(true);

				$query->select(
					$db->quoteName(
						array(
							'tp.id',
							'tp.title',
							'tp.bemerkungen',
							'tp.state',
							'tp.alt_names',
						)
					)
				);

				$query->from('#__sicht_tauchplatz AS tp');

				$query->where($db->quoteName('tp.id') . ' = ' . (int) $id);
				$query->where($db->quoteName('tp.state') . ' = 1');

				// Join over Gewaesser table
				$query->select(
					$db->quoteName(
						array(
							'g.id',
							'g.name',
							'g.displayName',
						),
						array(
							'gewaesser_id',
							'gewaesser_name',
							'gewaesser_displayName',
						)
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
						$db->quoteName('o.plz', 'ort_plz'),
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

				$db->setQuery($query);

				$data = $db->loadObject();

				if (!$data)
				{
					throw new Exception(Text::_('JGLOBAL_RESOURCE_NOT_FOUND'), 404);
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
