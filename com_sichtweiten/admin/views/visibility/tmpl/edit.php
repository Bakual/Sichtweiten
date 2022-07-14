<?php
// no direct access
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

// Load the tooltip behavior.
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('formbehavior.chosen', 'select');

$app   = JFactory::getApplication();
$input = $app->input;
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task === 'visibility.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo Route::_('index.php?option=com_sichtweiten&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<?php foreach($this->form->getFieldset('general') as $field): ?>
			<?php echo $field->getControlGroup(); ?>
		<?php endforeach; ?>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>