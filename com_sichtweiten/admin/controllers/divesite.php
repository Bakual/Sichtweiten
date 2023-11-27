<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Versioning\VersionableControllerTrait;

/**
 * Divesite controller class.
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenControllerDivesite extends FormController
{
	use VersionableControllerTrait;

	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	public $typeAlias = 'com_sichtweiten.divesite';

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
		$db = Factory::getDbo();

		$recordId = (int) $model->getState($this->context . '.id');

		if (empty($validData['id']))
		{
			$validData['id'] = $recordId;
		}

		$query = $db->getQuery(true);
		$query->delete('#__sicht_bezeichnung');
		$query->where($db->quoteName('tauchplatz_id') . ' = ' . (int) $validData['id']);
		$db->setQuery($query);
		$db->execute();

		if (!empty($validData['altnames']))
		{
			$tupel                = new stdClass;
			$tupel->id            = 0;
			$tupel->tauchplatz_id = (int) $validData['id'];

			foreach ($validData['altnames'] as $tmp)
			{
				if (empty($tmp['alt_name']))
				{
					continue;
				}

				$tupel->name = $tmp['alt_name'];
				$db->insertObject('#__sicht_bezeichnung', $tupel);
			}
		}
	}
}