<?php
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sichtweiten&view=visibilities'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="visibilityList">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value=""
								   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
								   onclick="Joomla.checkAll(this)"/>
						</th>
						<th class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JDATE', 'swm.datum', $listDirn, $listOrder); ?>
						</th>
						<th class="center">
							<?php echo JHtml::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_TAUCHPLATZ_LABEL', 'swm.name', $listDirn, $listOrder); ?>
						</th>
						<th class="center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_MELDEDATUM_LABEL', 'swm.meldedatum', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'swm.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="nowrap center">
							<?php echo JHtml::Date($item->datum, JText::_('DATE_FORMAT_LC4')); ?>
						</td>
						<td class="nowrap center">
							<?php echo $item->tauchplatz; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo JHtml::Date($item->meldedatum, JText::_('DATE_FORMAT_LC2')); ?>
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
