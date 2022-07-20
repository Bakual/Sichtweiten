<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// Load the tooltip behavior.
HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.formvalidation');
HtmlHelper::_('behavior.keepalive');

$app   = Factory::getApplication();
$input = $app->input;
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task === 'divesite.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo Route::_('index.php?option=com_sichtweiten&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span8">
				<?php foreach($this->form->getFieldset('general') as $field): ?>
					<?php echo $field->getControlGroup(); ?>
				<?php endforeach; ?>
			</div>
			<div class="span4">
				<h3><?php echo Text::_('COM_SICHTWEITEN_FIELD_DIVESITE_SUBFORM_LABEL'); ?></h3>
				<?php foreach($this->form->getFieldset('divesite_subform') as $field): ?>
					<?php echo $field->getControlGroup(); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>"/>
		<?php echo HtmlHelper::_('form.token'); ?>
	</div>
</form>