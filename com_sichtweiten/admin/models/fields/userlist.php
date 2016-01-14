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
class JFormFieldUserlist extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.0
	 */
	protected $type = 'Userlist';

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
		$db_options = array();

		$db_options['driver']   = $params->get('db_type', 'mysqli');
		$db_options['host']     = $params->get('db_host', 'localhost');
		$db_options['database'] = $params->get('db_database');
		$db_options['user']     = $params->get('db_user');
		$db_options['password'] = $params->get('db_pass');
		$db_options['prefix']   = $params->get('db_prefix', 'jos_');

		$db = JDatabaseDriver::getInstance($db_options);

		$query = $db->getQuery(true);
		$query->select('id AS value');
		$query->select('name AS text');
		$query->from('#__sicht_user');

		// Get the options.
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
