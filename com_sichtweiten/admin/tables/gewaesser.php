<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Waters Table class
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenTableGewaesser extends JTable
{
	/**
	 * Constructor
	 *
	 * @param  JDatabaseDriver $db JDatabaseDriver object.
	 *
	 * @since 1.3.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__sicht_gewaesser', 'id', $db);
	}
}
