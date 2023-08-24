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
HTMLHelper::_('bootstrap.tooltip');

$app             = Factory::getApplication();
$input           = $app->input;
$this->useCoreUI = true;
?>
<form action="<?php echo Route::_('index.php?option=com_sichtweiten&layout=edit&id=' . (int) $this->item->id); ?>"
	  method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<?php echo $this->form->renderFieldset('general'); ?>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>