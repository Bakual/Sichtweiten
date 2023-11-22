<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\Router\Rules\MenuRules;

/**
 * Routing class
 *
 * @since  2.1.0
 */
class SichtweitenRouter extends RouterView
{
	/**
	 * Class constructor.
	 *
	 * @param   \Joomla\CMS\Application\CMSApplication  $app   Application-object that the router should use
	 * @param   \Joomla\CMS\Menu\AbstractMenu           $menu  Menu-object that the router should use
	 *
	 * @since   2.1.0
	 */
	public function __construct($app = null, $menu = null)
	{
		$this->registerView(new RouterViewConfiguration('visibilities'));

		$location = new RouterViewConfiguration('location');
		$location->setKey('id');
		$this->registerView($location);

		$user = new RouterViewConfiguration('user');
		$user->setKey('id');
		$this->registerView($user);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}

	/**
	 * Method to get the segment(s) for a user
	 *
	 * @param   string  $id     ID of the user to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since 2.1.0
	 */
	public function getUserSegment($id, $query)
	{
		return [(int) $id => $id];
	}

	/**
	 * Method to get the segment(s) for the location
	 *
	 * @param   string  $id     ID of the location to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since 2.1.0
	 */
	public function getLocationSegment($id, $query)
	{
		return [(int) $id => $id];
	}

	/**
	 * Generic method to preprocess a URL
	 *
	 * @param   array  $query  An associative array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function preprocess($query)
	{
		return parent::preprocess($query);
	}

	/**
	 * Build the route
	 *
	 * @param   array  $query  An array of URL arguments
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
			$menuView = $menuItem->query['view'] ?? '';
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
	 * @param   array &$segments  The segments of the URL to parse.
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
			case 'user':
				unset($segments[0]);
				$vars['view'] = 'user';
				$id           = explode(':', $segments[1]);
				$vars['id']   = (int) $id[0];
				unset($segments[1]);

				break;
			case 'visibilities':
				unset($segments[0]);
				$vars['view']      = 'visibilities';
				$gewaesser         = explode(':', $segments[1]);
				$vars['gewaesser'] = (int) $gewaesser[0];
				unset($segments[1]);

				break;
		}

		return $vars;
	}
}
