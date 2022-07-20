<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;

/**
 * Sichtweiten Helper
 *
 * @since  1.0
 */
class SichtweitenHelper
{
	/**
	 * Get the actions for ACL
	 */
	public static function getActions()
	{
		$user   = Factory::getUser();
		$result = new CMSObject();

		$actions = Access::getActionsFromFile(
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
