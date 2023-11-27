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

HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx', '')); ?>">
	<div class="items">
		<?php if (!count($this->items)) : ?>
			<div class="no_entries alert alert-error"><?php echo Text::sprintf('COM_SICHTWEITEN_NO_ENTRIES', Text::_('COM_SICHTWEITEN_LOCATIONS')); ?></div>
		<?php else : ?>
			<?php echo HTMLHelper::_('bootstrap.startAccordion', 'landAccordion', ['active' => 'collapse' . $this->items[0]->land_gewaesser_id]); ?>
			<?php echo HTMLHelper::_('bootstrap.startAccordion', 'locationsAccordion'); ?>
			<?php $land      = ''; ?>
			<?php $gewaesser = ''; ?>
			<?php foreach($this->items as $i => $item) : ?>
				<?php if ($item->gewaesser_name != $gewaesser) : ?>
					<?php // Close old table if there was already a Gewaesser before ?>
					<?php if ($gewaesser) : ?>
							</tbody>
							</table>
						<?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
						</div>
					<?php endif; ?>
					<?php if ($item->land_gewaesser_kurzzeichen != $land) : ?>
						<?php if ($land) : ?>
							<?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
						<?php endif; ?>
						<?php $land = $item->land_gewaesser_kurzzeichen; ?>
						<?php echo HTMLHelper::_('bootstrap.addSlide', 'landAccordion', $item->land_gewaesser_bezeichnung, 'collapse' . $item->land_gewaesser_id); ?>
					<?php endif; ?>
					<?php $gewaesser = $item->gewaesser_name; ?>
					<div class="mb-2 bg-light">
						<?php echo HTMLHelper::_('bootstrap.addSlide', 'locationsAccordion', $item->gewaesser_displayName, 'collapse' . $item->gewaesser_id); ?>
						<table class="table table-striped table-hover table-condensed">
						<thead><tr>
							<th class="ort"><?php echo Text::_('COM_SICHTWEITEN_FIELD_ORT_LABEL'); ?></th>
							<th class="location"><?php echo Text::_('COM_SICHTWEITEN_FIELD_LOCATION_LABEL'); ?></th>
							<th class="alternate"><?php echo Text::_('COM_SICHTWEITEN_FIELD_ALT_NAMES_LABEL'); ?></th>
							<th class="bemerkungen"><?php echo Text::_('COM_SICHTWEITEN_FIELD_BEMERKUNGEN_LABEL'); ?></th>
						</tr></thead>
						<tbody>
				<?php endif; ?>
				<tr>
					<td class="ort">
						<?php echo $item->ort_name; ?>
					</td>
					<td class="location">
						<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=location&id=' . $item->id); ?>">
							<?php echo $item->title; ?>
						</a>
					</td>
					<td class="alternate">
						<?php echo $item->alt_names ? htmlspecialchars($item->alt_names) : '-'; ?>
					</td>
					<td class="bemerkungen">
						<?php echo htmlspecialchars($item->bemerkungen); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			</div>
			<?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
			<?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>
		<?php endif; ?>
	</div>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo Text::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
