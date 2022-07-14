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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('formbehavior.chosen');

HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task === 'sichtweitenmeldung.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div class="sichtweiten-container<?php echo $this->pageclass_sfx; ?> edit item-page<?php echo $this->pageclass_sfx; ?>">
	<form action="<?php echo Route::_('index.php?option=com_sichtweiten&view=sichtweitenmeldung'); ?>" method="post" name="adminForm" id="adminForm" class="form form-validate form-horizontal">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('sichtweitenmeldung.save')">
					<span class="icon-ok"></span> <?php echo Text::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('sichtweitenmeldung.cancel')">
					<span class="icon-cancel"></span> <?php echo Text::_('JCANCEL') ?>
				</button>
			</div>
		</div>
		<fieldset>
			<?php foreach($this->form->getFieldset('general') as $field): ?>
				<?php echo $this->form->renderField($field->fieldname); ?>
			<?php endforeach; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			<?php echo HTMLHelper::_('form.token'); ?>
		</fieldset>
	</form>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo Text::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
