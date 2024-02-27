<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.tooltip');

HTMLHelper::stylesheet('system/joomla-fontawesome.min.css', ['relative' => true]);
HTMLHelper::stylesheet('com_sichtweiten/sichtweiten.css', ['relative' => true]);
HTMLHelper::script('com_sichtweiten/sichtweiten.js', ['relative' => true]);

// Prepare Accordion
$accordionOptions = array();

if ($gewaesser = Factory::getApplication()->getInput()->getInt('gewaesser', 0))
{
	$accordionOptions['active'] = 'collapse' . $gewaesser;
}
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx', '')); ?>">
	<div class="items">
		<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm"
			  name="adminForm">
			<div class="com-sichtweiten__filter btn-group">
				<label class="filter-search-lbl visually-hidden" for="filter-search">
					<?php echo Text::_('COM_SICHTWEITEN_FILTER_LABEL'); ?>
				</label>
				<input type="text" name="filter-search" id="filter-search"
					   value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="inputbox"
					   onchange="document.adminForm.submit();"
					   placeholder="<?php echo Text::_('COM_SICHTWEITEN_FILTER_LABEL'); ?>">

				<button type="submit" name="filter_submit"
						class="btn btn-primary"><span class="fa fa-search"></span></button>
				<button type="reset" name="filter-clear-button" class="btn btn-secondary reset-button">
					<span class="d-sm-none">
						<span class="fa fa-eraser hasTooltip" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>"></span>
					</span>
					<span class="d-none d-sm-block"><?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?></span>
				</button>
			</div>
			<?php if (!count($this->gewaesser)) : ?>
				<div class="no_entries alert alert-error mt-2"><?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?></div>
			<?php else : ?>
				<button class="btn btn-primary accordion-toggle btn-group float-end" type="button" data-accordion="#gewaesserAccordion">
					<span class="fa fa-plus"></span></button>
				<div class="clearfix"></div>
				<?php echo HTMLHelper::_('bootstrap.startAccordion', 'gewaesserAccordion', $accordionOptions); ?>
				<?php foreach ($this->gewaesser as $see) : ?>
					<div class="mb-3 bg-light">
						<?php $title = $see->displayName; ?>
						<?php if ($see->land_kurzzeichen != 'CH') : ?>
							<?php $title .= '&nbsp;<small>(' . $see->land_bezeichnung . ')</small>'; ?>
						<?php endif; ?>
						<?php $title .= '<div class="sichtweite-average"><div class="sichtweite' . $see->sichtweite_avg . '"></div></div>'; ?>
						<?php echo HTMLHelper::_('bootstrap.addSlide', 'gewaesserAccordion', $title, 'collapse' . $see->id); ?>
						<table class="sichtweitentable table table-light table-hover table-responsive">
							<thead>
							<tr>
								<th class="ort"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_LOCATION_LABEL', 'tp.title', $listDirn, $listOrder); ?></th>
								<th class="datum"><?php echo HTMLHelper::_('grid.sort', 'JDATE', 'datum', $listDirn, $listOrder); ?></th>
								<?php for ($sw = 0; $sw <= 5 ; $sw++) : ?>
									<th class="tiefe"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_TIEFENBEREICH' . $sw . '_LABEL', 'sichtweite_id_' . $sw, $listDirn, $listOrder); ?></th>
								<?php endfor; ?>
								<th class="kommentar d-none d-md-table-cell"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_FIELD_KOMMENTAR_LABEL', 'kommentar', $listDirn, $listOrder); ?></th>
								<th class="user d-none d-md-table-cell"><?php echo HTMLHelper::_('grid.sort', 'COM_SICHTWEITEN_USER', 'user_id', $listDirn, $listOrder); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($see->visibilities as $item) : ?>
								<tr>
									<td class="ort">
										<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=location&id=' . $item->id); ?>"
										   class="hasTooltip"
										   title="<?php echo Text::_('COM_SICHTWEITEN_FIELD_ORT_LABEL') . ': ' . $item->ort_name; ?><br />
											<?php echo Text::_('COM_SICHTWEITEN_FIELD_LAND_LABEL') . ': ' . $item->land_ort_bezeichnung; ?>">
											<?php echo $item->title; ?>
										</a>
									</td>
									<td class="datum">
										<span class="d-none d-md-inline"><?php echo HTMLHelper::_('date', $item->datum, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?></span>
										<span class="d-md-none"><?php echo HTMLHelper::_('date', $item->datum, Text::_('COM_SICHTWEITEN_DATE_FORMAT_SHORT'), 'UTC'); ?></span>
									</td>
									<?php for ($sw = 0; $sw <= 5 ; $sw++) : ?>
										<?php $prop = 'sichtweite_id_' . $sw; ?>
										<td class="tiefe sichtweite<?php echo $this->visibilities[$item->$prop]->value ?? '0'; ?>">
											<span class="d-none d-sm-inline"><?php echo $this->visibilities[$item->$prop]->displayText ?? '-'; ?></span>
										</td>
									<?php endfor; ?>
									<td class="kommentar d-none d-md-table-cell">
										<?php $kommentar = $item->kommentar ?: ''; ?>
										<?php $kommentar = htmlspecialchars($kommentar); ?>
										<?php $kommentar_truncated = HTMLHelper::_('string.truncate', $kommentar, $this->params->get('kommentar_length', 50)); ?>
										<?php if ($kommentar_truncated === $kommentar) : ?>
											<?php echo $kommentar_truncated; ?>
										<?php else : ?>
											<span title="<?php echo $kommentar; ?>"
												  class="hasTooltip"><?php echo $kommentar_truncated; ?></span>
										<?php endif; ?>
										<?php if ($item->buddy_names) : ?>
											<?php if ($kommentar) : ?>
												|
											<?php endif; ?>
											<?php echo Text::_('COM_SICHTWEITEN_BUDDIES'); ?>:
											<?php echo SichtweitenHelperSichtweiten::getBuddies($item->buddy_names, $item->buddy_emails); ?>
										<?php endif; ?>
									</td>
									<td class="user d-none d-md-table-cell">
										<?php if ($item->user_id) : ?>
											<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=user&id=' . $item->user_id); ?>">
												<?php echo $item->user_name; ?>
											</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
					</div>
				<?php endforeach; ?>
				<?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>
			<?php endif; ?>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		</form>
		<div class="mb-2">
			<a href="<?php echo Route::_('index.php?option=com_sichtweiten&view=sichtweitenmeldung'); ?>" role="button" class="btn btn-primary"><?php echo Text::_('COM_SICHTWEITEN_NEW_SICHTWEITE'); ?></a>
		</div>
	</div>
	<?php if ($this->params->get('copyright')) : ?>
		<div class="copyright"><small><?php echo Text::_('COM_SICHTWEITEN_COPYRIGHT'); ?></small></div>
	<?php endif; ?>
</div>
