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
 * @since  1.0
 */
class SichtweitenViewSichtweitenmeldung extends HtmlView
{
	protected $form;

	protected $item;

	protected $return_page;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @throws  Exception
	 *
	 * @return  void|boolean
	 *
	 * @see     HtmlView::loadTemplate()
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$user = $this->getCurrentUser();
		$app  = Factory::getApplication();

		// Get model data
		$this->state       = $this->get('State');
		$this->params      = $this->state->params;
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

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx', ''));

		parent::display($tpl);
	}
}
