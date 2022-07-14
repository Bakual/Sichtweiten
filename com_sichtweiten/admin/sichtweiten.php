<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_sichtweiten'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Register Helperclass for autoloading
JLoader::register('SichtweitenHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/sichtweiten.php');

$controller = BaseController::getInstance('Sichtweiten');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
