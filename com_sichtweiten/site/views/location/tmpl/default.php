<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();
?>
<div class="sichtweiten-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
	<h1><?php echo $this->escape(JText::_('COM_SICHTWEITEN_LOCATION_VIEW_DEFAULT_TITLE')); ?></h1>
	<div class="tauchplatz <?php echo ($this->item->active) ? '' : 'system-unpublished'; ?>">
		<dl class="dl-horizontal">
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_TAUCHPLATZ_NAME_LABEL'); ?></dt>
			<dd><?php echo $this->item->name; ?></dd>
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_GEWAESSER_LABEL'); ?></dt>
			<dd><?php echo $this->item->gewaesser_name; ?></dd>
			<dt><?php echo JText::_('COM_SICHTWEITEN_FIELD_BEMERKUNGEN_LABEL'); ?></dt>
			<dd><?php echo $this->item->bemerkungen; ?></dd>
		</dl>
	</div>
</div>
