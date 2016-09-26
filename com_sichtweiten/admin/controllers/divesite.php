<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * Visibility controller class.
 *
 * @package        Sichtweiten.Administrator
 *
 * @since          1.3.0
 */
class SichtweitenControllerDivesite extends JControllerForm
{
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param    array $data An array of input data.
	 *
	 * @since          1.3.0
	 *
	 * @return    boolean
	 */
	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @param   array  $data An array of input data.
	 * @param   string $key  The name of the key for the primary key.
	 *
	 * @since          1.3.0
	 *
	 * @return  boolean
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		return parent::allowEdit($data, $key);
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModelLegacy $model     The data model object.
	 * @param   array        $validData The validated data.
	 *
	 * @since   1.3.0
	 *
	 * @return  void
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
	}
}