<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * Waters Table class
 *
 * @package  Sichtweiten.Administrator
 *
 * @since    1.3.0
 */
class SichtweitenTableGewaesser extends Table
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object     *
	 *
	 * @since 1.3.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__sicht_gewaesser', 'id', $db);
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property
	 * values. If no primary key value is set a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.3.0
	 */
	public function store($updateNulls = false)
	{
		// We want to be able to differ a null (empty) value from an altitude 0
		return parent::store(true);
	}
}
