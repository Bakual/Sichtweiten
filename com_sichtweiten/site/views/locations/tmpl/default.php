<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

JHtml::stylesheet('com_sichtweiten/sichtweiten.css', '', true);
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
	<div class="items">
		<?php if (!count($this->items)) : ?>
			<div class="no_entries alert alert-error"><?php echo JText::sprintf('COM_SICHTWEITEN_NO_ENTRIES', JText::_('COM_SICHTWEITEN_LOCATIONS')); ?></div>
		<?php else : ?>
			<?php $land      = ''; ?>
			<?php $gewaesser = ''; ?>
			<?php foreach($this->items as $i => $item) : ?>
				<?php if ($item->gewaesser_name != $gewaesser) : ?>
					<?php // Close old table if there was already a Gewaesser before ?>
					<?php if ($gewaesser) : ?>
						</tbody>
						</table>
					<?php endif; ?>
					<?php if ($item->land_gewaesser_kurzzeichen != $land) : ?>
						<?php $land = $item->land_gewaesser_kurzzeichen; ?>
						<h2><?php echo $item->land_gewaesser_bezeichnung; ?></h2>
					<?php endif; ?>
					<?php $gewaesser = $item->gewaesser_name; ?>
					<h3><?php echo $item->gewaesser_displayName; ?></h3>
					<table class="table table-striped table-hover table-condensed">
					<thead><tr>
						<th class="ort"><?php echo JText::_('COM_SICHTWEITEN_FIELD_ORT_LABEL'); ?></th>
						<th class="location"><?php echo JText::_('COM_SICHTWEITEN_FIELD_LOCATION_LABEL'); ?></th>
						<th class="alternate"><?php echo JText::_('COM_SICHTWEITEN_FIELD_ALT_NAME_LABEL'); ?></th>
						<th class="bemerkungen"><?php echo JText::_('COM_SICHTWEITEN_FIELD_BEMERKUNGEN_LABEL'); ?></th>
					</tr></thead>
					<tbody>
				<?php endif; ?>
				<tr>
					<td class="ort">
						<?php echo $item->ort_name; ?>
					</td>
					<td class="location">
						<a href="<?php echo JRoute::_('index.php?option=com_sichtweiten&view=location&id=' . $item->id); ?>">
							<?php echo $item->name; ?>
						</a>
					</td>
					<td class="alternate">
						<?php echo $item->alt_name ? htmlspecialchars($item->alt_name) : '-'; ?>
					</td>
					<td class="bemerkungen">
						<?php echo htmlspecialchars($item->bemerkungen); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		<?php endif; ?>
	</div>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo JText::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
