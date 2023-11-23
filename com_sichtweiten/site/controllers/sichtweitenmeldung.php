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
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * Controller class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenControllerSichtweitenmeldung extends FormController
{
	protected $view_item = 'sichtweitenmeldung';

	protected $view_list = 'visibilities';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string $name   The model name. Optional
	 * @param   string $prefix The class prefix. Optional
	 * @param   array  $config Configuration array for model. Optional
	 *
	 * @return  object  The model
	 * @since 1.0.0
	 */
	public function getModel($name = 'sichtweitenmeldung', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Gets the URL arguments to append to an item redirect
	 *
	 * @param   int     $recordId  The primary key id for the item
	 * @param   string  $urlVar    The name of the URL variable for the id
	 *
	 * @return  string  The arguments to append to the redirect URL
	 * @throws \Exception
	 * @since 1.0.0
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

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
	 * @throws \Exception
	 * @since 1.0.0
	 */
	protected function getReturnPage()
	{
		$return = Factory::getApplication()->input->get('return', '', 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return)))
		{
			return Uri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved
	 *
	 * @param   BaseDatabaseModel $model     The data model object
	 * @param   array        $validData The validated data
	 *
	 * @return  void
	 * @since 1.0.0
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = array())
	{
		$model->insertSichtweiteneintrag($validData);
		$model->insertTauchpartner($validData);

		// Redirect to Tauchplatz
		$this->setRedirect(
			Route::_(
				'index.php?option=' . $this->option . '&view=location&id=' . $validData['tauchplatz_id'],
				false
			)
		);
	}
}
