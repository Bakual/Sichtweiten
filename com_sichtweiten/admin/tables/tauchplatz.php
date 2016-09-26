<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Divesite Table class
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenTableTauchplatz extends JTable
{
	/**
	 * Array with alias for "special" columns such as ordering, hits etc etc
	 *
	 * @var    array
	 *
	 * @since 1.3.0
	 */
	protected $_columnAlias = array('published' => 'active');

	/**
	 * Constructor
	 *
	 * @param  JDatabaseDriver $db JDatabaseDriver object.
	 *
	 * @since 1.3.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__sicht_tauchplatz', 'id', $db);
	}
}
