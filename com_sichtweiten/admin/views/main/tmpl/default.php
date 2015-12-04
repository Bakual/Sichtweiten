<?php
defined('_JEXEC') or die;
?>
<div id="j-main-container">
	<ul class="thumbnails">
		<li class="span6">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=visibilities">
				<img src="<?php echo JURI::base() . 'components/com_sichtweiten/images/visibilities.png'; ?>"/>
				<h3 class="center"><?php echo JText::_('COM_SICHTWEITEN_VISIBILITIES_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span6">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=help">
				<img src="<?php echo JURI::base() . 'components/com_sichtweiten/images/help.png'; ?>"/>
				<h3 class="center"><?php echo JText::_('COM_SICHTWEITEN_MENU_HELP'); ?></h3>
			</a>
		</li>
	</ul>
</div>