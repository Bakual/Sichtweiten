<?php
// no direct access
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

// Load the tooltip behavior.
HTMLHelper::_('bootstrap.tooltip');

$app             = Factory::getApplication();
$input           = $app->input;
$this->useCoreUI = true;
?>
<form action="<?php echo Route::_('index.php?option=com_sichtweiten&layout=edit&id=' . (int) $this->item->id); ?>"
	  method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_SICHTWEITEN_GENERAL_FIELDSET_LABEL')); ?>

		<div class="row">
			<div>
				<?php echo $this->form->renderFieldset('general'); ?>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="return" value="<?php echo $input->getBase64('return'); ?>">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>