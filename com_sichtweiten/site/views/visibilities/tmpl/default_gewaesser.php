<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction', 'asc');

$menu   = Factory::getApplication()->getMenu();
$active = $menu->getActive();
$itemId = $active->id;

HTMLHelper::_('bootstrap.tooltip');
echo HTMLHelper::_('bootstrap.startAccordion', 'gewaesserAccordion');
?>
<?php $gewaesser = ''; ?>
<?php foreach($this->items as $i => $item) : ?>
<?php if ($item->gewaesser_name != $gewaesser) : ?>
<?php // Close old table if there was already a Gewaesser before ?>
<?php if ($gewaesser) : ?>
	</tbody>
	</table>
	</div>
	<?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
<?php endif; ?>
<?php $gewaesser = $item->gewaesser_name; ?>
<div class="mb-3 bg-light">
	<?php $title = $item->gewaesser_displayName; ?>
	<?php if ($item->land_gewaesser_kurzzeichen != 'CH') : ?>
		<?php $title .= '&nbsp;<small>(' . $item->land_gewaesser_bezeichnung . ')</small>'; ?>
	<?php endif; ?>
	<?php echo HTMLHelper::_('bootstrap.addSlide', 'gewaesserAccordion', $title, 'collapse' . $item->gewaesser_id); ?>
	<table class="table table-striped table-hover table-condensed">
		<thead><tr>
			<th class="ort"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_LOCATION_LABEL', 'tp.name', $listDirn, $listOrder); ?></th>
			<th class="datum"><?php echo HTMLHelper::_('grid.sort', 'JDATE', 'datum', $listDirn, $listOrder); ?></th>
			<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH0_LABEL', 'sichtweite_id_0', $listDirn, $listOrder); ?></th>
			<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH1_LABEL', 'sichtweite_id_1', $listDirn, $listOrder); ?></th>
			<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH2_LABEL', 'sichtweite_id_2', $listDirn, $listOrder); ?></th>
			<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH3_LABEL', 'sichtweite_id_3', $listDirn, $listOrder); ?></th>
			<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH4_LABEL', 'sichtweite_id_4', $listDirn, $listOrder); ?></th>
			<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH5_LABEL', 'sichtweite_id_5', $listDirn, $listOrder); ?></th>
			<th class="kommentar"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL', 'kommentar', $listDirn, $listOrder); ?></th>
			<th class="user"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_USER', 'user_id', $listDirn, $listOrder); ?></th>
		</tr></thead>
		<tbody>
		<?php endif; ?>
		<tr>
			<td class="ort">
				<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=location&id=' . $item->id . '&Itemid=' . $itemId); ?>"
				   class="hasTooltip"
				   title="<?php echo Text::_('COM_SICHTWEITEN_FIELD_ORT_LABEL') . ': ' . $item->ort_name; ?><br />
					<?php echo Text::_('COM_SICHTWEITEN_FIELD_LAND_LABEL') . ': ' . $item->land_ort_bezeichnung; ?>">
					<?php echo $item->name; ?>
				</a>
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
					<?php if ($kommentar) : ?>
						|
					<?php endif; ?>
					<?php echo Text::_('COM_SICHTWEITEN_BUDDIES'); ?>:
					<?php echo SichtweitenHelperSichtweiten::getBuddies($item->buddy_names, $item->buddy_emails); ?>
				<?php endif; ?>
			</td>
			<td class="user">
				<?php if ($item->user_id) : ?>
					<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=user&id=' . $item->user_id . '&Itemid=' . $itemId); ?>"><?php echo $item->user_name; ?></a>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>
