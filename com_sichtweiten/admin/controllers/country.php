<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Water controller class.
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenControllerCountry extends FormController
{
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 * @since   1.3.0
	 *
	 */
	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 * @since   1.3.0
	 *
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		return parent::allowEdit($data, $key);
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   BaseDatabaseModel  $model      The data model object.
	 * @param   array              $validData  The validated data.
	 *
	 * @return  void
	 * @since   1.3.0
	 *
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = array())
	{
	}
}