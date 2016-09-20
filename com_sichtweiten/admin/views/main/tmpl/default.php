<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

JHtml::stylesheet('administrator/components/com_sichtweiten/sichtweiten.css');
?>
<div id="j-main-container" class="sichtweiten-container">
	<ul class="thumbnails">
		<li class="span4">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=visibilities">
				<div class="icon"><span class="icon-drawer-2"></span></div>
				<h3 class="center"><?php echo JText::_('COM_SICHTWEITEN_VISIBILITIES_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span4">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=divesites">
				<div class="icon"><span class="icon-location"></span></div>
				<h3 class="center"><?php echo JText::_('COM_SICHTWEITEN_DIVESITES_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span4">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=help">
				<div class="icon"><span class="icon-help"></span></div>
				<h3 class="center"><?php echo JText::_('COM_SICHTWEITEN_HELP_TITLE'); ?></h3>
			</a>
		</li>
	</ul>
</div>