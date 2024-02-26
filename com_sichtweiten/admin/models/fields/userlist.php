<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;

/**
 * Userlist Field class for the Sichtweiten.
 *
 * @package  Sichtweiten
 * @since    1.0
 */
class JFormFieldUserlist extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Userlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array    The field option objects.
	 * @since   1.0
	 */
	public function getOptions()
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);
		$query->select('id AS value');
		$query->select('name AS text');
		$query->from('#__sicht_user');

		// Get the options.
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
