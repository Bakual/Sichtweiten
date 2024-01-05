<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenViewUser extends HtmlView
{
	/**
	 * Contains model state
	 *
	 * @var    Joomla\Registry\Registry
	 * @since 1.0
	 */
	protected $state;

	/**
	 * Array of visibilities
	 *
	 * @var    array
	 * @since 2.3.0
	 */
	protected $visibilities;

	/**
	 * Array of objects
	 *
	 * @var    array
	 * @since 1.0
	 */
	protected $item;

	/**
	 * Contains the merged component and menuitem params
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since 1.0
	 */
	protected $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
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
		$this->vis_state    = $visibilities_model->getState();
		$this->vis_state->set('filter.period', 0);
		$this->vis_state->set('filter.user', (int) $this->item->id);

		$this->items        = $visibilities_model->getItems();
		$this->visibilities = $visibilities_model->getVisibilities();
		$this->pagination   = $visibilities_model->getPagination();

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @throws Exception
	 * @since 2.1.0
	 */
	protected function _prepareDocument()
	{
		$app     = Factory::getApplication();
		$pathway = $app->getPathway();

		/**
		 * Because the application sets a default page title,
		 * we need to get it from the menu item itself
		 */
		$menu = $app->getMenu()->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		// If the menu item does not concern this item
		if ($menu && ($menu->query['option'] != 'com_sichtweiten' || $menu->query['view'] != 'user' || $menu->query['id'] != $this->item->id))
		{
			if ($this->item->name)
			{
				$title = Text::_('COM_SICHTWEITEN_USER') . ': ' . $this->item->name;
			}

			$pathway->addItem($title, '');
		}

		if (empty($title))
		{
			$title = Text::_('COM_SICHTWEITEN_USER') . ': ' . $this->item->name;
		}

		$this->setDocumentTitle($title);
	}
}
