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
	  method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span8">
				<?php echo $this->form->renderFieldset('general'); ?>
			</div>
			<div class="span4">
				<h3><?php echo Text::_('COM_SICHTWEITEN_FIELD_DIVESITE_SUBFORM_LABEL'); ?></h3>
				<?php echo $this->form->renderFieldset('divesite_subform'); ?>
			</div>
		</div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>"/>
		<?php echo HtmlHelper::_('form.token'); ?>
	</div>
</form>