<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Register Helperclasses for autoloading
JLoader::discover('SichtweitenHelper', JPATH_COMPONENT . '/helpers');

// Load languages and merge with fallbacks
$jlang = Factory::getLanguage();
$jlang->load('com_sichtweiten', JPATH_COMPONENT, 'en-GB', true);
$jlang->load('com_sichtweiten', JPATH_COMPONENT, null, true);

$controller = BaseController::getInstance('Sichtweiten');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
