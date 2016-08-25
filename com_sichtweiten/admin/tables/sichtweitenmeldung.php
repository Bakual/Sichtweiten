<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Visibility Table class
 *
 * @package        Sichtweiten.Administrator
 */
class SichtweitenTableSichtweitenmeldung extends JTable
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

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @link    https://docs.joomla.org/JTable/check
	 * @since   1.0
	 */
	public function check()
	{
		$date_now  = JFactory::getDate('now', 'UTC');

		if (!$date_form = date_create($this->datum, new DateTimeZone('UTC')))
		{
			throw new Exception(JText::_('COM_SICHTWEITEN_ERROR_INVALID_DATE'));
		}

		if ($date_form > $date_now)
		{
			throw new Exception(JText::_('COM_SICHTWEITEN_ERROR_DATE_IN_FUTURE'));
		}

		$this->datum = $date_form->format('Y-m-d');

		return true;
	}
}
