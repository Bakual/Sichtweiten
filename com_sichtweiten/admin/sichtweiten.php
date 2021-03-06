<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_sichtweiten'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Register Helperclass for autoloading
JLoader::register('SichtweitenHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/sichtweiten.php');

$controller = JControllerLegacy::getInstance('Sichtweiten');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
