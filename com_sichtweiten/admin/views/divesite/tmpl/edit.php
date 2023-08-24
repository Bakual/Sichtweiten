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

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

// Load the tooltip behavior.
HtmlHelper::_('bootstrap.tooltip');

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
			<div class="col-lg-8">
					<?php echo $this->form->renderFieldset('general'); ?>
			</div>
			<div class="col-lg-4">
				<fieldset class="options-form">
					<legend><?php echo Text::_('COM_SICHTWEITEN_FIELD_DIVESITE_SUBFORM_LABEL'); ?></legend>
					<?php echo $this->form->renderFieldset('divesite_subform'); ?>
				</fieldset>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>"/>
		<?php echo HtmlHelper::_('form.token'); ?>
	</div>
</form>