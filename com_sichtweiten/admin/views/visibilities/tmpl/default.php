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
<form action="<?php echo JRoute::_('index.php?option=com_sichtweiten&view=visibilities'); ?>" method="post"
	name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => false))); ?>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
				<table class="table table-striped" id="countryList">
					<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value=""
								title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
								onclick="Joomla.checkAll(this)"/>
						</th>
						<th class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 's.bezeichnung', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JText::_('COM_SICHTWEITEN_FIELD_DISPLAYNAME_LABEL'); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 's.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>
							<td>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_sichtweiten&task=visibility.edit&id=' . (int) $item->id); ?>">
										<?php echo $item->bezeichnung; ?></a>
								<?php else : ?>
									<?php echo $item->bezeichnung; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo JText::_('COM_SICHTWEITEN_SICHTWEITE_VALUE_' . $item->id); ?>
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
