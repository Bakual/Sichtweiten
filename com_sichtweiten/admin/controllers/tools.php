<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;

/**
 * Tools Sichtweiten Controller
 *
 * @since  1.2.0
 */
class SichtweitenControllerTools extends BaseController
{
	/**
	 * Migrates data from extern database
	 *
	 * @throws Exception
	 *
	 * @since 1.2.0
	 */
	public function migrate()
	{
		// Check for request forgeries
		Session::checkToken('request') or jexit(Text::_('JINVALID_TOKEN'));

		$app    = Factory::getApplication();
		$params = ComponentHelper::getParams('com_sichtweiten');

		if (!$params->get('extern_db'))
		{
			$app->enqueueMessage('Extern DB not enabled!');
			$app->redirect('index.php?option=com_sichtweiten&view=main');

			return;
		}

		$db = Factory::getDbo();

		// Taken from https://docs.joomla.org/Connecting_to_an_external_database
		$option = array();

		$option['driver']   = $params->get('db_type', 'mysqli');
		$option['host']     = $params->get('db_host', 'localhost');
		$option['database'] = $params->get('db_database');
		$option['user']     = $params->get('db_user');
		$option['password'] = $params->get('db_pass');
		$option['prefix']   = $params->get('db_prefix', 'jos_');

		$dbExtern = JDatabaseDriver::getInstance($option);

		$tables = array(
			'#__sicht_adresse',
			'#__sicht_bezeichnung',
			'#__sicht_gewaesser',
			'#__sicht_land',
			'#__sicht_ort',
			'#__sicht_sichtweite',
			'#__sicht_sichtweiteneintrag',
			'#__sicht_sichtweitenmeldung',
			'#__sicht_tauchpartner',
			'#__sicht_tauchplatz',
			'#__sicht_tiefenbereich',
			'#__sicht_user',
		);

		$query = $dbExtern->getQuery(true);
		$query->select('*');

		foreach ($tables as $table)
		{
			$query->clear('from');
			$query->from($table);

			$dbExtern->setQuery($query);
			$list = $dbExtern->loadObjectList();

			foreach ($list as $tupel)
			{
				$db->insertObject($table, $tupel);
			}
		}

		$app->enqueueMessage('Data migrated!');
		$app->redirect('index.php?option=com_sichtweiten&view=main');
	}

	/**
	 * Truncates the intern tables
	 *
	 * @throws Exception
	 *
	 * @since 1.2.0
	 */
	public function truncate()
	{
		// Check for request forgeries
		Session::checkToken('request') or jexit(Text::_('JINVALID_TOKEN'));

		$app = Factory::getApplication();
		$db  = Factory::getDbo();

		$tables = array(
			'#__sicht_adresse',
			'#__sicht_bezeichnung',
			'#__sicht_gewaesser',
			'#__sicht_land',
			'#__sicht_ort',
			'#__sicht_sichtweite',
			'#__sicht_sichtweiteneintrag',
			'#__sicht_sichtweitenmeldung',
			'#__sicht_tauchpartner',
			'#__sicht_tauchplatz',
			'#__sicht_tiefenbereich',
			'#__sicht_user',
		);

		foreach ($tables as $table)
		{
			$query = 'TRUNCATE ' . $table;

			$db->setQuery($query);
			$db->execute();
		}

		$app->enqueueMessage('Tables Truncated!');
		$app->redirect('index.php?option=com_sichtweiten&view=main');
	}
}
