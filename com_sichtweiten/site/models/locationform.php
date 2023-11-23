<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

require_once JPATH_ADMINISTRATOR . '/components/com_sichtweiten/models/divesite.php';

/**
 * Model class for the Sichtweiten Component
 *
 * @since  2.1.0
 */
class SichtweitenModelLocationform extends SichtweitenModelDivesite
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var    string
	 *
	 * @since  2.1.0
	 */
	public $typeAlias = 'com_sichtweiten.location';

	/**
	 * Name of the form
	 *
	 * @var    string
	 *
	 * @since  2.1.0
	 */
	protected $formName = 'locationform';

	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 *
	 * @since   2.1.0
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page', ''));
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws  \Exception
	 * @since   2.1.0
	 *
	 */
	protected function populateState()
	{
		parent::populateState();

		$app   = Factory::getApplication();
		$input = $app->getInput();

		$return = $input->get('return', '', 'base64');
		$this->setState('return_page', base64_decode($return));
	}

}
