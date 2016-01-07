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
 * Locationlist Field class for the Sichtweiten.
 *
 * @package        Sichtweiten
 * @since          1.0
 */
class JFormFieldLocationlist extends JFormFieldList
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
	public function getOptions()
	{
		$params = JComponentHelper::getParams('com_sichtweiten');

		// Taken from https://docs.joomla.org/Connecting_to_an_external_database
		$option = array();

		$option['driver']   = $params->get('db_type', 'mysqli');
		$option['host']     = $params->get('db_host', 'localhost');
		$option['database'] = $params->get('db_database');
		$option['user']     = $params->get('db_user');
		$option['password'] = $params->get('db_pass');
		$option['prefix']   = $params->get('db_prefix', 'jos_');

		$db = JDatabaseDriver::getInstance($option);

		$query = $db->getQuery(true);
		$query->select('a.id AS value');
		$query->select('a.name AS text');
		$query->from('#__sicht_tauchplatz AS a');
		$query->order('a.name');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
