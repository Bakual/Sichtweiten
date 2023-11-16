<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * HTML View class for the Sichtweiten Component
 *
* @since  1.0
 */
class SichtweitenViewHelp extends HtmlView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @see     HtmlView::loadTemplate()
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		// Get current version of Sichtweiten
		$component     = ComponentHelper::getComponent('com_sichtweiten');
		$extensions    = Table::getInstance('extension');
		$extensions->load($component->id);
		$manifest      = json_decode($extensions->manifest_cache);
		$this->version = $manifest->version;

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$canDo = SichtweitenHelper::getActions();
		ToolbarHelper::title(Text::_('JHELP'), 'support');

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_sichtweiten');
		}
	}
}
