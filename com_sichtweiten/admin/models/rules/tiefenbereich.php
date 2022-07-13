<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\Registry\Registry;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @since  1.0
 */
class JFormRuleTiefenbereich extends JFormRule
{
	/**
	 * Method to test the value.
	 *
	 * @param   SimpleXMLElement                $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed                           $value    The form field value to validate.
	 * @param   null                            $group    The field name group control value. This acts as as an array container for the field.
	 *                                                    For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                                    full field name would end up being "bar[foo]".
	 * @param   \Joomla\Registry\Registry|null  $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   \JForm|null                     $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   1.0
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, JForm $form = null)
	{
		return ($value != (string) $element['default']);
	}
}