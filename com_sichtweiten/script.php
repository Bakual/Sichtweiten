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

/**
 * Class Com_SichtweitenInstallerScript
 *
 * @since  1.0
 */
class Com_SichtweitenInstallerScript
{
	/**
	 * @var  JApplicationCms  Holds the application object
	 */
	private $app;

	/**
	 * @var  string  During an update, it will be populated with the old release version
	 */
	private $oldRelease;

	/**
	 *  Constructor
	 */
	public function __construct()
	{
		$this->app = JFactory::getApplication();
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   string                      $type    'install', 'update' or 'discover_install'
	 * @param   JInstallerAdapterComponent  $parent  Installerobject
	 *
	 * @return  boolean  false will terminate the installation
	 */
	public function preflight($type, $parent)
	{
		$min_version = (string) $parent->get('manifest')->attributes()->version;

		$jversion = new JVersion;

		if (!$jversion->isCompatible($min_version))
		{
			$this->app->enqueueMessage(JText::sprintf('COM_SICHTWEITEN_VERSION_UNSUPPORTED', $min_version), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Method to install the component
	 *
	 * @param   JInstallerAdapterComponent  $parent  Installerobject
	 *
	 * @return void
	 */
	public function install($parent)
	{
	}

	/**
	 * Method to uninstall the component
	 *
	 * @param   JInstallerAdapterComponent  $parent  Installerobject
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
	}

	/**
	 * method to update the component
	 *
	 * @param   JInstallerAdapterComponent  $parent  Installerobject
	 *
	 * @return void
	 */
	public function update($parent)
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @param   string                      $type    'install', 'update' or 'discover_install'
	 * @param   JInstallerAdapterComponent  $parent  Installerobject
	 *
	 * @return void
	 */
 	public function postflight($type, $parent)
	{
	}
}
