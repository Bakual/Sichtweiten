<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Visibility Table class
 *
 * @package        Sichtweiten.Administrator
 */
class SichtweitenTableVisibility extends JTable
{
	/**
	 * Constructor
	 *
	 * @param  JDatabaseDriver $db JDatabaseDriver object.
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__sicht_sichtweitenmeldung', 'id', $db);
	}
}
