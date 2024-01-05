<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$listOrder = $this->vis_state->get('list.ordering');
$listDirn  = $this->vis_state->get('list.direction');

HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx', '')); ?>">
	<h1><?php echo $this->escape(Text::_('COM_SICHTWEITEN_USER_VIEW_DEFAULT_TITLE')); ?></h1>
	<div class="user">
		<dl class="dl-horizontal">
			<dt><?php echo Text::_('COM_SICHTWEITEN_FIELD_USER_NAME_LABEL'); ?></dt>
			<dd><?php echo $this->item->name; ?></dd>
		</dl>
	</div>
	<h3><?php echo Text::_('COM_SICHTWEITEN_HISTORY'); ?></h3>
	<div class="items">
		<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm" name="adminForm">
			<div class="filters btn-toolbar">
				<div class="btn-group pull-right">
					<label class="element-invisible">
						<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<?php if (!count($this->items)) : ?>
				<div class="no_entries alert alert-error"><?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></div>
			<?php else : ?>
				<table class="table table-striped table-hover table-condensed">
					<thead><tr>
						<th class="ort"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_LOCATION_LABEL', 'tp.title', $listDirn, $listOrder); ?></th>
						<th class="gewaesser"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL', 'g.displayName', $listDirn, $listOrder); ?></th>
						<th class="datum"><?php echo HTMLHelper::_('grid.sort', 'JDATE', 'datum', $listDirn, $listOrder); ?></th>
						<?php for ($sw = 0; $sw <= 5 ; $sw++) : ?>
							<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH' . $sw . '_LABEL', 'sichtweite_id_' . $sw, $listDirn, $listOrder); ?></th>
						<?php endfor; ?>
						<th class="kommentar"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL', 'kommentar', $listDirn, $listOrder); ?></th>
					</tr></thead>
					<tbody>
					<?php foreach($this->items as $i => $item) : ?>
						<tr>
							<td class="ort">
								<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=location&id=' . $item->id ); ?>">
									<?php echo $item->title; ?>
								</a>
							</td>
							<td class="gewaesser">
								<?php echo $item->gewaesser_name; ?>
							</td>
							<td class="datum">
								<?php echo HTMLHelper::_('date', $item->datum, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?>
							</td>
							<?php for ($sw = 0; $sw <= 5 ; $sw++) : ?>
								<?php $prop = 'sichtweite_id_' . $sw; ?>
								<td class="tiefe sichtweite<?php echo $item->$prop; ?>">
									<span class="d-none d-sm-inline"><?php echo $this->visibilities[$item->$prop]->displayText ?? '-'; ?></span>
								</td>
							<?php endfor; ?>
							<td class="kommentar">
								<?php $kommentar = $item->kommentar ?: ''; ?>
								<?php $kommentar = htmlspecialchars($kommentar); ?>
								<?php $kommentar_truncated = HTMLHelper::_('string.truncate', $kommentar, $this->params->get('kommentar_length', 50)); ?>
								<?php if ($kommentar_truncated === $kommentar) : ?>
									<?php echo $kommentar_truncated; ?>
								<?php else : ?>
									<span title="<?php echo $kommentar; ?>" class="hasTooltip">
										<?php echo $kommentar_truncated; ?>
									</span>
								<?php endif; ?>
								<?php if ($item->buddy_names) : ?>
									| <?php echo Text::_('COM_SICHTWEITEN_BUDDIES'); ?>:
									<?php echo SichtweitenHelperSichtweiten::getBuddies($item->buddy_names, $item->buddy_emails); ?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php if ($this->pagination->pagesTotal > 1) : ?>
				<div class="pagination">
					<p class="counter pull-right">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
					<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
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
