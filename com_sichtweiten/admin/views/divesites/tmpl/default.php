<?php
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user      = JFactory::getUser();
$canEdit   = $user->authorise('core.edit', 'com_sichtweiten');
$country   = $this->state->params->get('country', 'CH');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sichtweiten&view=divesites'); ?>" method="post"
	name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
				<table class="table table-striped" id="divesiteList">
					<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value=""
								title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
								onclick="Joomla.checkAll(this)"/>
						</th>
						<th width="5%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'tp.active', $listDirn, $listOrder); ?>
						</th>
						<th class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_TAUCHPLATZ_LABEL', 'tp.name', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL', 'g.name', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_ORT_LABEL', 'o.name', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'tp.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>
							<td class="center">
								<div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->active, $i, 'divesites.', $canEdit, 'cb'); ?>
								</div>
							</td>
							<td>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_sichtweiten&task=divesite.edit&id=' . (int) $item->id); ?>">
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
									<?php if (JHtml::_('image', 'mod_languages/' . $item->gewaesser_land_kurz . '.gif', null, null, true, true)) : ?>
										<?php echo JHtml::_('image', 'mod_languages/' . $item->gewaesser_land_kurz . '.gif', $item->gewaesser_land, array('title' => $item->gewaesser_land), true); ?>
									<?php else : ?>
										<small>(<?php echo $item->gewaesser_land; ?>)</small>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->ort; ?>
								<?php if ($item->ort_land && $item->ort_land_kurz != $country) : ?>
									<?php if (JHtml::_('image', 'mod_languages/' . $item->ort_land_kurz . '.gif', null, null, true, true)) : ?>
										<?php echo JHtml::_('image', 'mod_languages/' . $item->ort_land_kurz . '.gif', $item->ort_land, array('title' => $item->ort_land), true); ?>
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
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
