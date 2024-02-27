<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenViewWaters extends HtmlView
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
	 * @var    Form
	 * @since  1.3.0
	 */
	public $filterForm;

	/**
	 * @var    array
	 * @since  1.3.0
	 */
	public $activeFilters;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
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

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		parent::display($tpl);
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

		ToolbarHelper::title(Text::_('COM_SICHTWEITEN_WATERS_TITLE'), 'users');

		if ($canDo->get('core.create'))
		{
			ToolbarHelper::addNew('water.add');
		}

		if ($canDo->get('core.edit'))
		{
			ToolbarHelper::editList('water.edit');
		}

		if ($canDo->get('core.delete'))
		{
			ToolbarHelper::deleteList('COM_SICHTWEITEN_CONFIRM_DELETE', 'waters.delete');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_sichtweiten');
		}
	}
}
