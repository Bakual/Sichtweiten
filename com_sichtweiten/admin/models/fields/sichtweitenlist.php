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
use Joomla\CMS\Language\Text;

/**
 * Sichtweitenlist Field class for the Sichtweiten.
 *
 * @package        Sichtweiten
 * @since          1.0
 */
class JFormFieldSichtweitenlist extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.0
	 */
	protected $type = 'Sichtweitenlist';

	/**
	 * Method to get the field options.
	 *
	 * @return   array    The field option objects.
	 * @since    1.0
	 */
	public function getOptions()
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);
		$query->select('a.id AS value');
		$query->from('#__sicht_sichtweite AS a');
		$query->order('a.ordering ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		foreach ($options as $option)
		{
			$option->text  = Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $option->value);
			$option->class = 'tiefe sichtweite' . $option->value;
		}

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $options);

		return $options;
	}
}
