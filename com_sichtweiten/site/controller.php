<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Sichtweiten Component Controller
 */
class SichtweitenController extends BaseController
{
	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $default_view = 'visibilities';

	/**
	 * View method.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  A JControllerLegacy object to support chaining.
	 *
	 * @since   1.0
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$cachable = !Factory::getUser()->get('id');

		$params   = Factory::getApplication()->getParams();

		if ($params->get('css_icomoon'))
		{
			HTMLHelper::stylesheet('jui/icomoon.css', ['relative' => true]);
		}

		$urlparams = array(
				'id'               => 'INT',
				'limit'            => 'INT',
				'limitstart'       => 'INT',
				'filter_order'     => 'CMD',
				'filter_order_Dir' => 'CMD',
				'filter-search'    => 'STRING',
				'return'           => 'BASE64',
				'Itemid'           => 'INT',
		);

		return parent::display($cachable, $urlparams);
	}
}
