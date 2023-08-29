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
						<th class="ort"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_LOCATION_LABEL', 'tp.name', $listDirn, $listOrder); ?></th>
						<th class="gewaesser"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL', 'g.displayName', $listDirn, $listOrder); ?></th>
						<th class="datum"><?php echo HTMLHelper::_('grid.sort', 'JDATE', 'datum', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH0_LABEL', 'sichtweite_id_0', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH1_LABEL', 'sichtweite_id_1', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH2_LABEL', 'sichtweite_id_2', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH3_LABEL', 'sichtweite_id_3', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH4_LABEL', 'sichtweite_id_4', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH5_LABEL', 'sichtweite_id_5', $listDirn, $listOrder); ?></th>
						<th class="kommentar"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL', 'kommentar', $listDirn, $listOrder); ?></th>
					</tr></thead>
					<tbody>
					<?php foreach($this->items as $i => $item) : ?>
						<tr>
							<td class="ort">
								<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=location&id=' . $item->id); ?>">
									<?php echo $item->name; ?>
								</a>
							</td>
							<td class="gewaesser">
								<?php echo $item->gewaesser_name; ?>
							</td>
							<td class="datum">
								<?php echo HTMLHelper::_('date', $item->datum, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_0; ?>">
								<?php echo Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_0); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_1; ?>">
								<?php echo Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_1); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_2; ?>">
								<?php echo Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_2); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_3; ?>">
								<?php echo Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_3); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_4; ?>">
								<?php echo Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_4); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_5; ?>">
								<?php echo Text::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_5); ?>
							</td>
							<td class="kommentar">
								<?php echo htmlspecialchars($item->kommentar); ?>
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
