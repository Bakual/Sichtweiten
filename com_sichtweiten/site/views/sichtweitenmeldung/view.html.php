<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenViewSichtweitenmeldung extends JViewLegacy
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
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->user = JFactory::getUser();

		// Get model data
		$this->state       = $this->get('State');
		$this->params      = $this->state->params;
		$this->form        = $this->get('Form');
		$this->return_page = $this->get('ReturnPage');

		if (!$this->user->authorise('core.create', 'com_sichtweiten'))
		{
			if ($this->user->guest)
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'message');
				$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			}
			else
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			}
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		parent::display($tpl);
	}
}
