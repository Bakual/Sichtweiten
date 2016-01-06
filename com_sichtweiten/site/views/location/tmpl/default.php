<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

$listOrder = $this->loc_state->get('list.ordering');
$listDirn  = $this->loc_state->get('list.direction');

JHtml::stylesheet('com_sichtweiten/sichtweiten.css', '', true);
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
	<h1><?php echo $this->escape(JText::_('COM_SICHTWEITEN_LOCATION_VIEW_DEFAULT_TITLE')); ?></h1>
	<div class="tauchplatz <?php echo ($this->item->active) ? '' : 'system-unpublished'; ?>">
		<dl class="dl-horizontal">
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_TAUCHPLATZ_NAME_LABEL'); ?></dt>
			<dd><?php echo $this->item->name; ?></dd>
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL'); ?></dt>
			<dd>
				<?php echo $this->item->gewaesser_displayName; ?>
				<?php if ($this->item->land_gewaesser_kurzzeichen != 'CH') : ?>
					<small>(<?php echo $this->item->land_gewaesser_bezeichnung; ?>)</small>
				<?php endif; ?>
			</dd>
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_ORT_LABEL'); ?></dt>
			<dd>
				<?php echo $this->item->ort_name; ?>
				<?php if ($this->item->land_ort_kurzzeichen != 'CH') : ?>
					<small>(<?php echo $this->item->land_ort_bezeichnung; ?>)</small>
				<?php endif; ?>
			</dd>
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_BEMERKUNGEN_LABEL'); ?></dt>
			<dd><?php echo $this->item->bemerkungen; ?></dd>
		</dl>
	</div>
	<h3><?php echo JText::_('COM_SICHTWEITEN_HISTORY'); ?></h3>
	<div class="items">
		<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" id="adminForm" name="adminForm">
			<?php if (!count($this->items)) : ?>
				<div class="no_entries alert alert-error"><?php echo JText::sprintf('COM_SICHTWEITEN_NO_ENTRIES', JText::_('COM_SICHTWEITEN_SICHTWEITENMELDUNGEN')); ?></div>
			<?php else : ?>
				<table class="table table-striped table-hover table-condensed">
					<thead><tr>
						<th class="datum"><?php echo JHtml::_('grid.sort', 'JDATE', 'datum', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH0_LABEL', 'sichtweite_id_0', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH1_LABEL', 'sichtweite_id_1', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH2_LABEL', 'sichtweite_id_2', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH3_LABEL', 'sichtweite_id_3', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH4_LABEL', 'sichtweite_id_4', $listDirn, $listOrder); ?></th>
						<th class="tiefe"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH5_LABEL', 'sichtweite_id_5', $listDirn, $listOrder); ?></th>
						<th class="kommentar"><?php echo JHtml::_('grid.sort', 'COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL', 'kommentar', $listDirn, $listOrder); ?></th>
					</tr></thead>
					<tbody>
					<?php foreach($this->items as $i => $item) : ?>
						<tr>
							<td class="datum">
								<?php echo JHtml::_('date', $item->datum, JText::_('DATE_FORMAT_LC4'), 'UTC'); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_0; ?>">
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_0); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_1; ?>">
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_1); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_2; ?>">
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_2); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_3; ?>">
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_3); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_4; ?>">
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_4); ?>
							</td>
							<td class="tiefe sichtweite<?php echo $item->sichtweite_id_5; ?>">
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->sichtweite_id_5); ?>
							</td>
							<td class="kommentar">
								<?php echo $item->kommentar; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		</form>
	</div>
</div>
