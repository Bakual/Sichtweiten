<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Register Helperclasses for autoloading
JLoader::discover('SichtweitenHelper', JPATH_BASE . '/components/com_sichtweiten/helpers');

$controller = BaseController::getInstance('Sichtweiten');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
