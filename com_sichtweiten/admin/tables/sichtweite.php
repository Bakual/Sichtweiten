<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
/**
 * Visibility Table class
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenTableSichtweite extends Table
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since 1.3.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__sicht_sichtweite', 'id', $db);
	}
}
