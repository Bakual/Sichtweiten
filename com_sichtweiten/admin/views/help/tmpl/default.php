<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php?option=com_sichtweiten&view=help'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<h1>Sichtweiten <?php echo $this->version; ?></h1>
		<h2>License</h2>
		<div>Sichtweiten is released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU/GPL license</a></div>
	</div>
</form>
