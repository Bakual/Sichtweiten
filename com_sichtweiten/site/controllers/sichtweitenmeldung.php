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
 * Controller class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenControllerSichtweitenmeldung extends JControllerForm
{
	protected $view_item = 'sichtweitenmeldung';

	protected $view_list = 'locations';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string $name   The model name. Optional
	 * @param   string $prefix The class prefix. Optional
	 * @param   array  $config Configuration array for model. Optional
	 *
	 * @return  object  The model
	 */
	public function getModel($name = 'sichtweitenmeldung', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Gets the URL arguments to append to an item redirect
	 *
	 * @param   int    $recordId The primary key id for the item
	 * @param   string $urlVar   The name of the URL variable for the id
	 *
	 * @return  string  The arguments to append to the redirect URL
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		if ($itemId = JFactory::getApplication()->input->get('Itemid', 0, 'int'))
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($return = $this->getReturnPage())
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string  The return URL
	 */
	protected function getReturnPage()
	{
		$return = JFactory::getApplication()->input->get('return', '', 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JURI::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved
	 *
	 * @param   JModelLegacy $model     The data model object
	 * @param   array        $validData The validated data
	 *
	 * @return  void
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();

		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_sichtweiten&view=locations', false));
		}
	}

	/**
	 * Method to save a record
	 *
	 * @param   string $key    The name of the primary key of the URL variable
	 * @param   string $urlVar The name of the URL variable if different from the primary key (sometimes required to
	 *                         avoid router collisions)
	 *
	 * @return  Boolean  True if successful, false otherwise
	 */
	public function save($key = null, $urlVar = 's_id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page
		if ($result)
		{
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}
}
