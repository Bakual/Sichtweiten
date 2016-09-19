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
	 * @since  1.0
	 */
	private $app;

	/**
	 * @var  string  During an update, it will be populated with the old release version
	 * @since  1.0
	 */
	private $oldRelease;

	/**
	 *  Constructor
	 * @since  1.0
	 */
	public function __construct()
	{
		$this->app = JFactory::getApplication();
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
		$min_version = (string) $parent->get('manifest')->attributes()->version;

		$jversion = new JVersion;

		if (!$jversion->isCompatible($min_version))
		{
			$this->app->enqueueMessage(JText::sprintf('COM_SICHTWEITEN_VERSION_UNSUPPORTED', $min_version), 'error');

			return false;
		}

		// Storing old release number for process in postflight
		if ($type == 'update')
		{
			$this->oldRelease = $this->getParam('version');
		}

		return true;
	}

	/**
	 * Method to install the component
	 *
	 * @param   JInstallerAdapterComponent $parent Installerobject
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
	 * @param   JInstallerAdapterComponent $parent Installerobject
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
	 * @param   JInstallerAdapterComponent $parent Installerobject
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
	 * @param   string                     $type   'install', 'update' or 'discover_install'
	 * @param   JInstallerAdapterComponent $parent Installerobject
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
				$db    = JFactory::getDbo();
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

	/*
	 * Get a variable from the manifest file (actually, from the manifest cache).
	 *
	 * @since  1.0
	 *
	 * return string
	 */
	private function getParam($name)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('manifest_cache'));
		$query->from('#__extensions');
		$query->where($db->quoteName('name') . ' = ' . $db->quote('com_sichtweiten'));

		$db->setQuery($query);
		$manifest = json_decode($db->loadResult(), true);

		return $manifest[$name];
	}
}
