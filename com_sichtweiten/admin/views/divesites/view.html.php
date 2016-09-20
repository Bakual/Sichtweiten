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

		JToolBarHelper::title(JText::_('COM_SICHTWEITEN_DIVESITES_TITLE'), 'users');

		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('COM_SICHTWEITEN_CONFIRM_DELETE', 'divesites.delete', 'JTOOLBAR_DELETE');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_sichtweiten');
		}
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'divesites.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'divesites.state' => JText::_('JSTATUS'),
			'divesites.title' => JText::_('COM_SICHTWEITEN_FIELD_NAME_LABEL'),
			'divesites.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
