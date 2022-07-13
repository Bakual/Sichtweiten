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
use Joomla\CMS\Uri\Uri;

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');

HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
	<div class="items">
		<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm" name="adminForm">
			<?php if (!count($this->items)) : ?>
				<div class="no_entries alert alert-error"><?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></div>
			<?php else : ?>
				<?php if ($listOrder == 'g.displayName') : ?>
	 				<?php echo $this->loadTemplate('gewaesser'); ?>
				<?php else: ?>
	 				<?php echo $this->loadTemplate('table'); ?>
				<?php endif; ?>
			<?php endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		</form>
	</div>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo Text::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
