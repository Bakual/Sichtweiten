<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.multiselect');
HtmlHelper::_('dropdown.init');

$user      = Factory::getUser();
$canEdit   = $user->authorise('core.edit', 'com_sichtweiten');
$country   = $this->state->params->get('country', 'CH');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
?>
<form action="<?php echo Route::_('index.php?option=com_sichtweiten&view=divesites'); ?>" method="post"
	name="adminForm" id="adminForm">
		<div id="j-main-container">
			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
				<table class="table table-striped" id="divesiteList">
					<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value=""
								title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>"
								onclick="Joomla.checkAll(this)"/>
						</th>
						<th width="5%" class="nowrap center">
							<?php echo HtmlHelper::_('searchtools.sort', 'JSTATUS', 'tp.active', $listDirn, $listOrder); ?>
						</th>
						<th class="nowrap">
							<?php echo HtmlHelper::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_TAUCHPLATZ_LABEL', 'tp.name', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HtmlHelper::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL', 'g.name', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HtmlHelper::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_ORT_LABEL', 'o.name', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo HtmlHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'tp.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo HtmlHelper::_('grid.id', $i, $item->id); ?>
							</td>
							<td class="center">
								<div class="btn-group">
									<?php echo HtmlHelper::_('jgrid.published', $item->active, $i, 'divesites.', $canEdit, 'cb'); ?>
								</div>
							</td>
							<td>
								<?php if ($canEdit) : ?>
									<a href="<?php echo Route::_('index.php?option=com_sichtweiten&task=divesite.edit&id=' . (int) $item->id); ?>">
										<?php echo $item->name; ?></a>
								<?php else : ?>
									<?php echo $item->name; ?>
								<?php endif; ?>
								<?php if ($item->alt_name) : ?>
									<small>(<?php echo $item->alt_name; ?>)</small>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->gewaesser; ?>
								<?php if ($item->gewaesser_land && $item->gewaesser_land_kurz != $country) : ?>
									<?php if (HtmlHelper::_('image', 'mod_languages/' . $item->gewaesser_land_kurz . '.gif', null, null, true, true)) : ?>
										<?php echo HtmlHelper::_('image', 'mod_languages/' . $item->gewaesser_land_kurz . '.gif', $item->gewaesser_land, array('title' => $item->gewaesser_land), true); ?>
									<?php else : ?>
										<small>(<?php echo $item->gewaesser_land; ?>)</small>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->ort; ?>
								<?php if ($item->ort_land && $item->ort_land_kurz != $country) : ?>
									<?php if (HtmlHelper::_('image', 'mod_languages/' . $item->ort_land_kurz . '.gif', null, null, true, true)) : ?>
										<?php echo HtmlHelper::_('image', 'mod_languages/' . $item->ort_land_kurz . '.gif', $item->ort_land, array('title' => $item->ort_land), true); ?>
									<?php else : ?>
										<small>(<?php echo $item->gewaesser_land; ?>)</small>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td class="center hidden-phone">
								<?php echo (int) $item->id; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>

			<?php echo $this->pagination->getListFooter(); ?>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<?php echo HtmlHelper::_('form.token'); ?>
		</div>
</form>
