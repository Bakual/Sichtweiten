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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$this->tab_name         = 'com-sichtweiten-locationform';
$this->ignore_fieldsets = [];
$this->useCoreUI        = true;
?>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form action="<?php echo Route::_('index.php?option=com_sichtweiten&view=locationform&id=' . (int) $this->item->id); ?>"
			method="post" name="adminForm" id="adminForm" class="form-validate form form-vertical">
		<fieldset>
			<?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'details', 'recall' => true, 'breakpoint' => 768]); ?>

			<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
			<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</fieldset>
		<div class="mb-2">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('location.save')">
				<span class="icon-check" aria-hidden="true"></span>
				<?php echo Text::_('JSAVE') ?>
			</button>
			<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('location.cancel')">
				<span class="icon-times" aria-hidden="true"></span>
				<?php echo Text::_('JCANCEL') ?>
			</button>
			<?php if ($this->params->get('save_history', 0) && $this->item->id) : ?>
				<?php echo $this->form->getInput('contenthistory'); ?>
			<?php endif; ?>
		</div>
	</form>
</div>
