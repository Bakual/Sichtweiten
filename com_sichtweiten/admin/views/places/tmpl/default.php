<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('dropdown.init');

$user      = Factory::getUser();
$canEdit   = $user->authorise('core.edit', 'com_sichtweiten');
$country   = $this->state->params->get('country', 'CH');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
?>
<form action="<?php echo Route::_('index.php?option=com_sichtweiten&view=places'); ?>" method="post"
	name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
				<table class="table table-striped" id="placeList">
					<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value=""
								title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>"
								onclick="Joomla.checkAll(this)"/>
						</th>
						<th class="nowrap">
							<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'o.name', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_LAND_LABEL', 'lo.bezeichnung', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_PLZ_LABEL', 'o.plz', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'o.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>
							<td>
								<?php if ($canEdit) : ?>
									<a href="<?php echo Route::_('index.php?option=com_sichtweiten&task=place.edit&id=' . (int) $item->id); ?>">
										<?php echo $item->name; ?></a>
								<?php else : ?>
									<?php echo $item->name; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->ort_land; ?>
								<?php if (HTMLHelper::_('image', 'mod_languages/' . $item->ort_land_kurz . '.gif', null, null, true, true)) : ?>
									<?php echo HTMLHelper::_('image', 'mod_languages/' . $item->ort_land_kurz . '.gif', $item->ort_land_kurz, array('title' => $item->ort_land_kurz), true); ?>
								<?php else : ?>
									<small>(<?php echo $item->ort_land_kurz; ?>)</small>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->plz; ?>
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
			<?php echo HTMLHelper::_('form.token'); ?>
		</div>
</form>
