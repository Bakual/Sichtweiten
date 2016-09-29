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
 * HTML View class for the Sichtweiten Component
 *
* @since  1.0
 */
class SichtweitenViewDivesites extends JViewLegacy
{
	protected $items;

	protected $pagination;

	/**
	 * A state object
	 *
	 * @var    JObject
	 */
	protected $state;

	public $filterForm;

	public $activeFilters;

	protected $sidebar;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return mixed A string if successful, otherwise a Error object.
	 *
	 * @throws Exception
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
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SICHTWEITEN_MASTERDATA_NOT_AVAILABLE'), 'error');
			$app->redirect('index.php?option=com_sichtweiten');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		SichtweitenHelper::addSubmenu('divesites');
		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$canDo = SichtweitenHelper::getActions();

		JToolbarHelper::title(JText::_('COM_SICHTWEITEN_DIVESITES_TITLE'), 'users');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('divesite.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('divesite.edit');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::publish('divesites.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('divesites.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('COM_SICHTWEITEN_CONFIRM_DELETE', 'divesites.delete');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_sichtweiten');
		}
	}
}
