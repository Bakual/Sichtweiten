<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * Sichtweiten Helper
 *
 * @since  1.0
 */
class SichtweitenHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param  string $vName The name of the active view.
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = 'main')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_SICHTWEITEN_MENU_VISIBILITYREPORTS'),
			'index.php?option=com_sichtweiten&view=visibilityreports',
			$vName == 'visibilityreports'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SICHTWEITEN_MENU_DIVESITES'),
			'index.php?option=com_sichtweiten&view=divesites',
			$vName == 'divesites'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SICHTWEITEN_MENU_WATERS'),
			'index.php?option=com_sichtweiten&view=waters',
			$vName == 'waters'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SICHTWEITEN_MENU_PLACES'),
			'index.php?option=com_sichtweiten&view=places',
			$vName == 'places'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SICHTWEITEN_MENU_COUNTRIES'),
			'index.php?option=com_sichtweiten&view=countries',
			$vName == 'countries'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SICHTWEITEN_MENU_HELP'),
			'index.php?option=com_sichtweiten&view=help',
			$vName == 'help'
		);
	}

	/**
	 * Get the actions for ACL
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$actions = JAccess::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/com_sichtweiten/access.xml',
			"/access/section[@name='component']/"
		);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, 'com_sichtweiten'));
		}

		return $result;
	}
}
