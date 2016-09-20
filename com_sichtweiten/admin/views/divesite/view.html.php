<?php
// No direct access
defined('_JEXEC') or die;

/**
 * View to edit a visibility.
 *
 * @package        Sichtweiten.Administrator
 */
class SichtweitenViewDivesite extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
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
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo      = SichtweitenHelper::getActions();
		JToolbarHelper::title(JText::sprintf('COM_SICHTWEITEN_PAGE_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT')), JText::_('COM_SICHTWEITEN_DIVESITES_TITLE'), JText::_('COM_SICHTWEITEN_DIVESITE')), 'pencil-2');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::apply('divesite.apply');
				JToolBarHelper::save('divesite.save');
			}
			JToolbarHelper::cancel('divesite.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolBarHelper::apply('divesite.apply');
					JToolBarHelper::save('divesite.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('divesite.save2new');
					}
				}
			}

			// If checked out, we can still save to copy
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('divesite.save2copy');
			}

			JToolbarHelper::cancel('divesite.cancel', 'JTOOLBAR_CLOSE');
		}

		if ($this->state->params->get('save_history') && $user->authorise('core.edit'))
		{
			JToolbarHelper::versions('com_sichtweiten.divesite', $this->item->id);
		}
	}
}