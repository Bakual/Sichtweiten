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

HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('behavior.multiselect');
HtmlHelper::_('dropdown.init');

$user      = Factory::getUser();
$canEdit   = $user->authorise('core.edit', 'com_sichtweiten');
$country   = $this->state->params->get('country', 'CH');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
?>
<form action="<?php echo Route::_('index.php?option=com_sichtweiten&view=countries'); ?>" method="post"
	name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => false))); ?>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
				<table class="table table-striped" id="countryList">
					<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value=""
								title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>"
								onclick="Joomla.checkAll(this)"/>
						</th>
						<th class="nowrap">
							<?php echo HtmlHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'l.bezeichnung', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HtmlHelper::_('searchtools.sort', 'COM_SICHTWEITEN_FIELD_KURZZEICHEN_LABEL', 'l.kurzzeichen', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo Text::_('COM_SICHTWEITEN_FLAG'); ?>
						</th>
						<th>
							<?php echo HtmlHelper::_('searchtools.sort', 'JFIELD_ORDERING_LABEL', 'l.displaynr', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo HtmlHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'l.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center hidden-phone">
								<?php echo HtmlHelper::_('grid.id', $i, $item->id); ?>
							</td>
							<td>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_sichtweiten&task=country.edit&id=' . (int) $item->id); ?>">
										<?php echo $item->bezeichnung; ?></a>
								<?php else : ?>
									<?php echo $item->bezeichnung; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->kurzzeichen; ?>
							</td>
							<td>
								<?php if (HtmlHelper::_('image', 'mod_languages/' . $item->kurzzeichen . '.gif', null, null, true, true)) : ?>
									<?php echo HtmlHelper::_('image', 'mod_languages/' . $item->kurzzeichen . '.gif', $item->kurzzeichen, array('title' => $item->kurzzeichen), true); ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $item->displaynr; ?>
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
