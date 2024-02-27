<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Event\Contact\SubmitContactEvent;
use Joomla\CMS\Event\Contact\ValidateContactEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Mail\Exception\MailDisabledException;
use Joomla\CMS\Mail\MailTemplate;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\UserFactoryAwareInterface;
use Joomla\CMS\User\UserFactoryAwareTrait;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Utilities\ArrayHelper;
use PHPMailer\PHPMailer\Exception as phpMailerException;

require_once JPATH_ADMINISTRATOR . '/components/com_sichtweiten/controllers/divesite.php';

/**
 * Controller for single location view
 *
 * @since  2.1.0
 */
class SichtweitenControllerLocation extends SichtweitenControllerDivesite
{
	use UserFactoryAwareTrait;
	use VersionableControllerTrait;

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  2.1.0
	 */
	protected $view_item = 'locationform';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  2.1.0
	 */
	protected $view_list = 'locations';

	/**
	 * The context for storing internal data, e.g. record.
	 *
	 * @var    string
	 * @since  2.1.0
	 */
	protected $context = 'locationform';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 *
	 * @since   2.1.0
	 */
	public function getModel($name = 'locationform', $prefix = '', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string    The arguments to append to the redirect URL.
	 *
	 * @since   2.1.0
	 */
	protected function getRedirectToItemAppend($recordId = 0, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		if ($return = $this->getReturnPage())
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string  The return URL.
	 *
	 * @since   2.1.0
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return))) {
			return Uri::base();
		}

		return base64_decode($return);
	}
}
