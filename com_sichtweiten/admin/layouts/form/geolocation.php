<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $inputType       Options available for this field.
 * @var   string   $accept          File types that are accepted.
 * @var   string   $dataAttribute   Miscellaneous data attributes preprocessed for HTML output
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*.
 * @var   string   $dirname         The directory name
 * @var   string   $addonBefore     The text to use in a bootstrap input group prepend
 * @var   string   $addonAfter      The text to use in a bootstrap input group append
 */

HTMLHelper::script('com_sichtweiten/geolocation.js', ['relative' => true]);

$attributes = [
	!empty($class) ? 'class="form-control ' . $class . '"' : 'class="form-control"',
	!empty($size) ? 'size="' . $size . '"' : '',
	!empty($description) ? 'aria-describedby="' . ($id ?: $name) . '-desc"' : '',
	$disabled ? 'disabled' : '',
	$readonly ? 'readonly' : '',
	$dataAttribute,
	strlen($hint) ? 'placeholder="' . htmlspecialchars($hint, ENT_COMPAT, 'UTF-8') . '"' : '',
	$onchange ? ' onchange="' . $onchange . '"' : '',
	!empty($maxLength) ? $maxLength : '',
	$required ? 'required' : '',
	!empty($autocomplete) ? 'autocomplete="' . $autocomplete . '"' : '',
	$autofocus ? ' autofocus' : '',
	$spellcheck ? '' : 'spellcheck="false"',
	!empty($inputmode) ? $inputmode : '',
	!empty($counterlabel) ? $counterlabel : '',
	!empty($pattern) ? 'pattern="' . $pattern . '"' : '',

	// @TODO add a proper string here!!!
	!empty($validationtext) ? 'data-validation-text="' . $validationtext . '"' : '',
];

$addonAfterHtml  = '<span class="input-group-text">' . Text::_($addonAfter) . '</span>';
?>
<div class="input-group">
	<input
		type="text"
		name="<?php echo $name; ?>"
		id="<?php echo $id; ?>"
		value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>"
		<?php echo $dirname; ?>
		<?php echo implode(' ', $attributes); ?>
	>
	<button class="btn btn-secondary geolocation" type="button" data-target="<?php echo $id; ?>">
		<span class="fa fa-location-dot" data-target="<?php echo $id; ?>"></span>
	</button>
	<button class="btn btn-secondary geomap" type="button">
		<span class="fa fa-map-location-dot" data-target="<?php echo $id; ?>"></span>
	</button>
</div>
