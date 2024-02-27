<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Visibilities list controller class.
 *
 * @package        Sichtweiten.Administrator
 * @since          1.3.0
 */
class SichtweitenControllerVisibilities extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.3.0
	 */
	protected $text_prefix = 'COM_SICHTWEITEN';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.3.0
	 */
	public function getModel($name = 'Visibility', $prefix = 'SichtweitenModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
}