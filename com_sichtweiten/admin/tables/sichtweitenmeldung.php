<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * Visibilityreport Table class
 *
 * @package        Sichtweiten.Administrator
 */
class SichtweitenTableSichtweitenmeldung extends Table
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
		parent::__construct('#__sicht_sichtweitenmeldung', 'id', $db);
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @throws \Exception
	 * @since   1.0
	 * @link    https://docs.joomla.org/Table/check
	 */
	public function check()
	{
		$date_now = Factory::getDate('now', 'UTC');

		if (!$date_form = date_create($this->datum, new DateTimeZone('UTC')))
		{
			throw new Exception(Text::_('COM_SICHTWEITEN_ERROR_INVALID_DATE'));
		}

		if ($date_form > $date_now)
		{
			throw new Exception(Text::_('COM_SICHTWEITEN_ERROR_DATE_IN_FUTURE'));
		}

		$this->datum = $date_form->format('Y-m-d');

		return true;
	}
}
