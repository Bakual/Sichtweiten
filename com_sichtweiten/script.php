<?php
/**
 * Scriptfile for the Sichtweiten installation
 *
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use Joomla\CMS\Installer\InstallerScript;

/**
 * Class Com_SichtweitenInstallerScript
 *
 * @since  1.0
 */
class Com_SichtweitenInstallerScript extends InstallerScript
{
	/**
	 * The extension name. This should be set in the installer script.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $extension = 'com_sermonspeaker';
	/**
	 * Minimum PHP version required to install the extension
	 *
	 * @var    string
	 * @since  2.4.0
	 */
	protected $minimumPhp = '8.0.0';
	/**
	 * Minimum Joomla! version required to install the extension
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $minimumJoomla = '4.0.0';
	/**
	 * @var  Joomla\CMS\Application\CMSApplication  Holds the application object
	 *
	 * @since 1.0
	 */
	private $app;
	/**
	 * @var  string  During an update, it will be populated with the old release version
	 *
	 * @since 1.0
	 */
	private $oldRelease;

	/**
	 *  Constructor
	 * @since  1.0
	 */
	public function __construct()
	{
		$this->app = Factory::getApplication();
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   string                     $type   'install', 'update' or 'discover_install'
	 * @param   JInstallerAdapterComponent $parent Installerobject
	 *
	 * @since  1.0
	 *
	 * @return  boolean  false will terminate the installation
	 */
	public function preflight($type, $parent)
	{
		// Storing old release number for process in postflight
		if (strtolower($type) == 'update')
		{
			$manifest         = $this->getItemArray('manifest_cache', '#__extensions', 'element', $this->extension);
			$this->oldRelease = $manifest['version'];

			// Check if update is allowed (only update from 5.6.0 and higher)
			if (version_compare($this->oldRelease, '5.6.0', '<'))
			{
				$this->app->enqueueMessage(Text::sprintf('COM_SERMONSPEAKER_UPDATE_UNSUPPORTED', $this->oldRelease, '5.6.0'), 'error');

				return false;
			}
		}

		return parent::preflight($type, $parent);
	}

	/**
	 * Method to install the component
	 *
	 * @param   Joomla\CMS\Installer\Adapter\ComponentAdapter  $parent  Installerobject
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function install($parent)
	{
	}

	/**
	 * Method to uninstall the component
	 *
	 * @param   Joomla\CMS\Installer\Adapter\ComponentAdapter  $parent  Installerobject
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
	}

	/**
	 * method to update the component
	 *
	 * @param   Joomla\CMS\Installer\Adapter\ComponentAdapter  $parent  Installerobject
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function update($parent)
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @param   string                                         $type    'install', 'update' or 'discover_install'
	 * @param   Joomla\CMS\Installer\Adapter\ComponentAdapter  $parent  Installerobject
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		// Set extern_db to true if updating from 1.2.0 or earlier.
		if ($type == 'update')
		{
			if (version_compare($this->oldRelease, '1.2.0', '<='))
			{
				$db    = Factory::getDbo();
				$query = $db->getQuery(true);

				$query->select($db->quoteName('params'));
				$query->from('#__extensions');
				$query->where($db->quoteName('element') . ' = ' . $db->quote('com_sichtweiten'));

				$db->setQuery($query);
				$params            = json_decode($db->loadResult());
				$params->extern_db = 1;

				$query = $db->getQuery(true);

				$query->update('#__extensions');
				$query->set($db->quoteName('params') . ' = ' . $db->quote(json_encode($params)));
				$query->where($db->quoteName('element') . ' = ' . $db->quote('com_sichtweiten'));

				$db->setQuery($query);
				$db->execute();
			}
		}
	}
}
