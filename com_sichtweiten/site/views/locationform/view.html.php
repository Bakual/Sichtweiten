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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  2.1.0
 */
class SichtweitenViewLocationform extends HtmlView
{
	protected $form;

	protected $item;

	protected $return_page;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return void|boolean
	 *
	 * @throws \Exception
	 * @since 2.1.0
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$user = $this->getCurrentUser();
		$app  = Factory::getApplication();

		// Get model data.
		$this->state       = $this->get('State');
		$this->item        = $this->get('Item');
		$this->form        = $this->get('Form');
		$this->return_page = $this->get('ReturnPage');

		if (!$user->authorise('core.create', 'com_sichtweiten'))
		{
			if ($user->guest)
			{
				$app->enqueueMessage(Text::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'message');
				$app->redirect(Route::_('index.php?option=com_users&view=login', false));
			}
			else
			{
				$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
				$app->setHeader('status', 403, true);

				return false;
			}
		}

		// Check for errors.
		if (\count($errors = $this->get('Errors'))) {
			$app->enqueueMessage(implode("\n", $errors), 'error');

			return false;
		}

		// Create a shortcut to the parameters.
		$this->params = $this->state->params;

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx', ''));

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @since 2.1.0
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $app->getMenu()->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('JEDITOR'));
		}

		$title = $this->params->get('page_title', '');

		$this->setDocumentTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->getDocument->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->getDocument->setMetaData('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->getDocument->setMetaData('robots', $this->params->get('robots'));
		}
	}
}
