<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen');

HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'sichtweitenmeldung.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div class="sichtweiten-container<?php echo $this->pageclass_sfx; ?> edit item-page<?php echo $this->pageclass_sfx; ?>">
	<form action="<?php echo JRoute::_('index.php?option=com_sichtweiten&view=sichtweitenmeldung'); ?>" method="post" name="adminForm" id="adminForm" class="form form-validate form-horizontal">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('sichtweitenmeldung.save')">
					<i class="icon-ok"></i> <?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('sichtweitenmeldung.cancel')">
					<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
		</div>
		<fieldset>
			<?php foreach($this->form->getFieldset('general') as $field): ?>
				<?php echo $this->form->renderField($field->fieldname); ?>
			<?php endforeach; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo JText::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
