<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

/**
 * Userlist Field class for the Sichtweiten.
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
		$params = ComponentHelper::getParams('com_sichtweiten');

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
			$db = Factory::getDbo();
		}

		$query = $db->getQuery(true);
		$query->select('id AS value');
		$query->select('name AS text');
		$query->from('#__sicht_user');

		// Get the options.
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
