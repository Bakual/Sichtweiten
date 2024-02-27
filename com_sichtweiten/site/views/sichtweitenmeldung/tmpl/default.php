<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

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
		<fieldset>
			<?php foreach($this->form->getFieldset('general') as $field): ?>
				<?php echo $this->form->renderField($field->fieldname); ?>
			<?php endforeach; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			<?php echo HTMLHelper::_('form.token'); ?>
		</fieldset>
		<div class="mb-2">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('sichtweitenmeldung.save')">
				<span class="icon-check"></span> <?php echo Text::_('JSAVE') ?>
			</button>
			<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('sichtweitenmeldung.cancel')">
				<span class="icon-times"></span> <?php echo Text::_('JCANCEL') ?>
			</button>
		</div>
	</form>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo Text::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
