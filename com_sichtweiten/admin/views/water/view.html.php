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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a water.
 *
 * @package   Sichtweiten.Administrator
 * @since     1.3.0
 */
class SichtweitenViewWater extends HtmlView
{
	/**
	 * @var
	 * @since   1.3.0
	 */
	protected $state;

	/**
	 * @var
	 * @since   1.3.0
	 */
	protected $item;

	/**
	 * @var
	 * @since   1.3.0
	 */
	protected $form;

	/**
	 * Display the view
	 *
	 * @param null $tpl
	 *
	 * @since   1.3.0
	 * @return mixed
	 * @throws \Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.3.0
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$canDo = SichtweitenHelper::getActions();
		ToolbarHelper::title(Text::sprintf('COM_SICHTWEITEN_PAGE_EDIT', Text::_('COM_SICHTWEITEN_WATERS_TITLE'), Text::_('COM_SICHTWEITEN_WATER_TITLE')), 'pencil-2');

		// Since it's an existing record, check the edit permission
		if ($canDo->get('core.edit'))
		{
			ToolbarHelper::apply('water.apply');
			ToolbarHelper::save('water.save');
		}

		ToolbarHelper::cancel('water.cancel', 'JTOOLBAR_CLOSE');
	}
}