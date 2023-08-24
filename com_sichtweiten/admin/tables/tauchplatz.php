<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * Divesite Table class
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenTableTauchplatz extends Table
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
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since 1.3.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__sicht_tauchplatz', 'id', $db);
	}
}
