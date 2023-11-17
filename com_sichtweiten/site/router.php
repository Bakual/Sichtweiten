<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Component\Router\RouterBase;
use Joomla\CMS\Factory;

defined('_JEXEC') or die();

/**
 * Routing class 
 *
 * @since  2.1.0
 */
class SichtweitenRouter extends RouterBase
{
	/**
	 * Build the route
	 *
	 * @param array $query An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @throws Exception
	 * @since   2.1.0
	 */
	public function build(&$query)
	{
		$segments = array();
		$app      = Factory::getApplication();

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		$menu = $app->getMenu();

		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
		}

		// If there is a task, remove the view query if present.
		if (isset($query['task']) && isset($query['view']))
		{
			unset($query['view']);
		}

		// Calculate View
		if (isset($query['view']))
		{
			$menuView = isset($menuItem->query['view']) ? $menuItem->query['view'] : '';
			$view     = $query['view'];
			unset($query['view']);

			// Check if menuitem matches the query
			if (isset($query['id']))
			{
				$menuId = isset($menuItem->query['id']) ? $menuItem->query['id'] : 0;

				if ($menuView == $view && $menuId == (int) $query['id'])
				{
					unset($query['id']);
				}
				else
				{
					$segments[] = $view;
				}
			}
			else
			{
				if ($view !== $menuView)
				{
					$segments[] = $view;
				}
			}
		}
		else
		{
			// Get view from Itemid
			if (isset($menuItem->query['view']))
			{
				$segments[] = $menuItem->query['view'];
			}
		}

		if (isset($query['id']))
		{
			$segments[] = $query['id'];
			unset($query['id']);
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array &$segments The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   2.1.0
	 */
	public function parse(&$segments)
	{
		$vars = array();

		switch ($segments[0])
		{
			case 'location':
				unset($segments[0]);
				$vars['view'] = 'location';
				$id           = explode(':', $segments[1]);
				$vars['id']   = (int) $id[0];
				unset($segments[1]);

				break;
		}

		return $vars;
	}
}
