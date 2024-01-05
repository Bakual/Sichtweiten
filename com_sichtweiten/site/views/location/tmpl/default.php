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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$listOrder = $this->vis_state->get('list.ordering');
$listDirn  = $this->vis_state->get('list.direction');

$user = $this->getCurrentUser();

HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx', '')); ?>">
	<h1><?php echo $this->escape(Text::_('COM_SICHTWEITEN_LOCATION_VIEW_DEFAULT_TITLE')); ?></h1>
	<?php if ($user->authorise('core.edit', 'com_sichtweiten')) : ?>
		<div class="icons">
			<div class="float-end">
				<div>
					<?php $returnPage = base64_encode(Uri::getInstance()); ?>
					<a href="<?php echo Route::_('index.php?option=com_sichtweiten&task=location.edit&id=' . $this->item->id . '&return=' . $returnPage); ?>">
						<span class="icon-edit" aria-hidden="true"></span>
						<?php echo Text::_('JGLOBAL_EDIT'); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="tauchplatz <?php echo ($this->item->state) ? '' : 'system-unpublished'; ?>">
		<dl class="dl-horizontal">
			<dt><?php echo Text::_('COM_SICHTWEITEN_FIELD_TAUCHPLATZ_NAME_LABEL'); ?></dt>
			<dd><?php echo htmlspecialchars($this->item->title); ?></dd>
			<dt><?php echo Text::_('COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL'); ?></dt>
			<dd>
				<?php echo htmlspecialchars($this->item->gewaesser_displayName); ?>
				<?php if ($this->item->land_gewaesser_kurzzeichen != 'CH') : ?>
					<small>(<?php echo htmlspecialchars($this->item->land_gewaesser_bezeichnung); ?>)</small>
				<?php endif; ?>
			</dd>
			<dt><?php echo Text::_('COM_SICHTWEITEN_FIELD_ORT_LABEL'); ?></dt>
			<dd>
				<?php echo htmlspecialchars($this->item->ort_name); ?>
				<?php if ($this->item->land_ort_kurzzeichen != 'CH') : ?>
					<small>(<?php echo htmlspecialchars($this->item->land_ort_bezeichnung); ?>)</small>
				<?php endif; ?>
			</dd>
			<?php if ($this->item->alt_names) : ?>
				<dt><?php echo Text::_('COM_SICHTWEITEN_FIELD_ALT_NAMES_LABEL'); ?></dt>
				<dd><?php echo htmlspecialchars($this->item->alt_names); ?></dd>
			<?php endif; ?>
			<dt><?php echo Text::_('COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL'); ?></dt>
			<dd><?php echo $this->item->bemerkungen; ?></dd>
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
				<div class="no_entries alert alert-error"><?php echo Text::sprintf('COM_SICHTWEITEN_NO_ENTRIES', Text::_('COM_SICHTWEITEN_VISIBILITIES')); ?></div>
			<?php else : ?>
				<table class="table table-striped table-hover table-condensed">
					<thead><tr>
						<th class="datum"><?php echo HTMLHelper::_('grid.sort', 'JDATE', 'datum', $listDirn, $listOrder); ?></th>
						<?php for ($sw = 0; $sw <= 5 ; $sw++) : ?>
							<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH0_LABEL', 'sichtweite_id_' . $sw, $listDirn, $listOrder); ?></th>
						<?php endfor; ?>
						<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH5_LABEL', 'sichtweite_id_5', $listDirn, $listOrder); ?></th>
						<th class="kommentar"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL', 'kommentar', $listDirn, $listOrder); ?></th>
						<th class="user"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_USER', 'user_id', $listDirn, $listOrder); ?></th>
					</tr></thead>
					<tbody>
					<?php foreach($this->items as $i => $item) : ?>
						<tr>
							<td class="datum">
								<?php echo HTMLHelper::_('date', $item->datum, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?>
							</td>
							<?php for ($sw = 0; $sw <= 5 ; $sw++) : ?>
								<?php $prop = 'sichtweite_id_' . $sw; ?>
								<td class="tiefe sichtweite<?php echo $item->$prop; ?>">
									<span class="d-none d-sm-inline"><?php echo $this->visibilities[$item->$prop]->displayText; ?></span>
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
							<td class="user">
								<?php if ($item->user_id) : ?>
									<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=user&id=' . $item->user_id ); ?>">
										<?php echo $item->user_name; ?>
									</a>
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
		<div class="mb-2">
			<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=sichtweitenmeldung&tp=' . $this->item->id); ?>" role="button" class="btn btn-primary"><?php echo Text::_('COM_SICHTWEITEN_NEW_SICHTWEITE'); ?></a>
		</div>
	</div>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo Text::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
