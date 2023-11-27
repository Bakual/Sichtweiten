<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\GroupedlistField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;

FormHelper::loadFieldClass('groupedlist');

/**
 * Locationlist Field class for the Sichtweiten.
 *
 * @package        Sichtweiten
 * @since          1.0
 */
class JFormFieldLocationlist extends GroupedlistField
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.0
	 */
	protected $type = 'Locationlist';

	/**
	 * Method to get the field options.
	 *
	 * @return   array    The field option objects.
	 * @since    1.0
	 */
	public function getGroups()
	{
		$db = Factory::getDbo();

		$query = $db->createQuery();
		$query->select('a.id AS value');
		$query->select("CONCAT(a.name, ', ', c.name) AS text");
		$query->from('#__sicht_tauchplatz AS a');

		$query->select('b.displayName');
		$query->join('LEFT', '#__sicht_gewaesser AS b ON a.gewaesser_id = b.id');
		$query->order('b.displayName ASC, a.name ASC');

		$query->join('LEFT', '#__sicht_ort AS c ON a.ort_id = c.id');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		$groups  = array();

		$default = new stdClass;
		$default->value = '';
		$default->text = Text::_('COM_SICHTWEITEN_OPTION_SELECT_LOCATION');
		$default->displayName = '';
		array_unshift($options, $default);

		foreach ($options as $option)
		{
			if (!isset($groups[$option->displayName]))
			{
				$groups[$option->displayName] = array();
			}

			$groups[$option->displayName][] = $option;
		}

		return $groups;
	}
}
