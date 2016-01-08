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
	 * Method to get the field input markup fora grouped list.
	 * Multiselect is enabled by using the multiple attribute.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration(
			"jQuery(document).ready(function(){
				document.formvalidator.setHandler('abovezero', function (value) {
					return (parseInt(value) > 0);
				});
			});"
		);

		return parent::getInput();
	}

	/**
	 * Method to get the field options.
	 *
	 * @return   array    The field option objects.
	 * @since    1.0
	 */
	public function getGroups()
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
		$query->select('a.id AS value');
		$query->select('a.name AS text');
		$query->select('b.displayName');
		$query->from('#__sicht_tauchplatz AS a');
		$query->join('LEFT', '#__sicht_gewaesser AS b ON a.gewaesser_id = b.id');
		$query->order('b.displayName ASC, a.name ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		$groups  = array();

		$groups[] = array(JText::_('COM_SICHTWEITEN_OPTION_SELECT_LOCATION'));

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
