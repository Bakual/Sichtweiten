<?php
// No direct access
defined('_JEXEC') or die;

/**
 * View to edit a place.
 *
 * @package   Sichtweiten.Administrator
 * @since     1.3.0
 */
class SichtweitenViewPlace extends JViewLegacy
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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$canDo = SichtweitenHelper::getActions();
		JToolbarHelper::title(JText::sprintf('COM_SICHTWEITEN_PAGE_EDIT', JText::_('COM_SICHTWEITEN_PLACES_TITLE'), JText::_('COM_SICHTWEITEN_PLACE_TITLE')), 'pencil-2');

		// Since it's an existing record, check the edit permission
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::apply('place.apply');
			JToolbarHelper::save('place.save');
		}

		JToolbarHelper::cancel('place.cancel', 'JTOOLBAR_CLOSE');
	}
}