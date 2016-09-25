<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

JFormHelper::loadFieldClass('groupedlist');

/**
 * Locationlist Field class for the Sichtweiten.
 *
 * @package        Sichtweiten
 * @since          1.0
 */
class JFormFieldLocationlist extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.0
	 */
	protected $type = 'Locationlist';

	/**
	 * Method to get the field options.
	 *
	 * @return   array    The field option objects.
	 * @since    1.0
	 */
	public function getGroups()
	{
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

			$db = JDatabaseDriver::getInstance($option);
		}
		else
		{
			$db = JFactory::getDbo();
		}

		$query = $db->getQuery(true);
		$query->select('a.id AS value');
		$query->select("CONCAT(a.name, ', ', c.name) AS text");
		$query->from('#__sicht_tauchplatz AS a');

		$query->select('b.displayName');
		$query->join('LEFT', '#__sicht_gewaesser AS b ON a.gewaesser_id = b.id');
		$query->order('b.displayName ASC, a.name ASC');

		$query->join('LEFT', '#__sicht_ort AS c ON a.ort_id = c.id');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		$groups  = array();

		$default = new stdClass;
		$default->value = '';
		$default->text = JText::_('COM_SICHTWEITEN_OPTION_SELECT_LOCATION');
		$default->displayName = '';
		array_unshift($options, $default);

		foreach ($options as $option)
		{
			if (!isset($groups[$option->displayName]))
			{
				$groups[$option->displayName] = array();
			}

			$groups[$option->displayName][] = $option;
		}

		return $groups;
	}
}
