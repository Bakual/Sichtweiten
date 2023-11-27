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
	protected $extension = 'com_sichtweiten';
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
		$this->deleteFiles[] = '/components/com_sichtweiten/views/visibilities/tmpl/default_gewaesser.php';
		$this->deleteFiles[] = '/components/com_sichtweiten/views/visibilities/tmpl/default_table.php';

		if (version_compare($this->oldRelease, '2.1.1', '<'))
		{
			// Migrate alt names from separate table into textfield.
			$db = Factory::getDbo();

			$query= 'UPDATE ' . $db->quoteName('#__sicht_tauchplatz') . ' AS t '
			. 'INNER JOIN ('
				. 'SELECT `tauchplatz_id`, GROUP_CONCAT(`name`) AS names '
				. 'FROM ' . $db->quoteName('#__sicht_bezeichnung') . ' AS b '
				. 'GROUP BY b.`tauchplatz_id`'
			. ') AS alt ON alt.tauchplatz_id = t.id '
			. 'SET t.alt_names = alt.names;';
			$db->setQuery($query);
			$db->execute();
		}
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
		$this->removeFiles();
	}
}
