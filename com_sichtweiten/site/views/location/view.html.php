<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenViewLocation extends HtmlView
{
	/**
	 * Contains model state
	 *
	 * @var    Joomla\Registry\Registry
	 */
	protected $state;

	/**
	 * Array of objects
	 *
	 * @var    array
	 */
	protected $item;

	/**
	 * Contains the merged component and menuitem params
	 *
	 * @var    \Joomla\Registry\Registry
	 */
	protected $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @throws  Exception
	 *
	 * @return  void
	 *
	 * @see     HtmlView::loadTemplate()
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		$app = Factory::getApplication();

		if (!$app->input->get('id', 0, 'int'))
		{
			$app->redirect(Route::_('index.php?view=visibilities'), Text::_('JGLOBAL_RESOURCE_NOT_FOUND'), 'error');
		}

		// Get data from the model
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');

		if (!$this->item)
		{
			$app->redirect(Route::_('index.php?view=visibilities'), Text::_('JGLOBAL_RESOURCE_NOT_FOUND'), 'error');
		}

		$this->params = $this->state->get('params');

		/** @var SichtweitenModelVisibilities $visibilities_model */
		$visibilities_model = BaseDatabaseModel::getInstance('Visibilities', 'SichtweitenModel');
		$this->vis_state = $visibilities_model->getState();
		$this->vis_state->set('filter.period', 0);
		$this->vis_state->set('filter.location', (int) $this->item->id);

		if ($this->vis_state->get('list.ordering') == 'g.displayName')
		{
			$this->vis_state->set('list.ordering', 'datum');
			$this->vis_state->set('list.direction', 'DESC');
		}

		$this->items      = $visibilities_model->getItems();
		$this->pagination = $visibilities_model->getPagination();

		parent::display($tpl);
	}
}
