<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');

/**
 * Sichtweitenlist Field class for the Sichtweiten.
 *
 * @package        Sichtweiten
 * @since          1.0
 */
class JFormFieldSichtweitenlist extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.0
	 */
	protected $type = 'Sichtweitenlist';

	/**
	 * Method to get the field options.
	 *
	 * @return   array    The field option objects.
	 * @since    1.0
	 */
	public function getOptions()
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
		$query->from('#__sicht_sichtweite AS a');
		$query->order('a.id ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		foreach ($options as $option)
		{
			$option->text  = JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $option->value);
			$option->class = 'tiefe sichtweite' . $option->value;
		}

		return $options;
	}
}
