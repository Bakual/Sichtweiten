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
use Joomla\CMS\MVC\View\HtmlView;

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  1.3.0
 */
class SichtweitenViewVisibilities extends HtmlView
{
	/**
	 * @var    array
	 * @since  1.3.0
	 */
	protected $items;

	/**
	 * @var    JPagination
	 * @since  1.3.0
	 */
	protected $pagination;

	/**
	 * A state object
	 *
	 * @var    JObject
	 * @since  1.3.0
	 */
	protected $state;

	/**
	 * @var    JForm
	 * @since  1.3.0
	 */
	public $filterForm;

	/**
	 * @var    array
	 * @since  1.3.0
	 */
	public $activeFilters;

	/**
	 * @var    string
	 * @since  1.3.0
	 */
	protected $sidebar;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @throws  Exception
	 * @since   1.3.0
	 */
	public function display($tpl = null)
	{
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		if ($this->state->params->get('extern_db'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('COM_SICHTWEITEN_MASTERDATA_NOT_AVAILABLE'), 'error');
			$app->redirect('index.php?option=com_sichtweiten');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		SichtweitenHelper::addSubmenu('visibilities');
		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @since   1.3.0
	 */
	protected function addToolbar()
	{
		$canDo = SichtweitenHelper::getActions();

		JToolbarHelper::title(Text::_('COM_SICHTWEITEN_VISIBILITIES_TITLE'), 'users');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('visibility.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('visibility.edit');
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('COM_SICHTWEITEN_CONFIRM_DELETE', 'visibilities.delete');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_sichtweiten');
		}
	}
}
