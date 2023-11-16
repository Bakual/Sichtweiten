<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

?>
<form action="<?php echo Route::_('index.php?option=com_sichtweiten&view=help'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<h1>Sichtweiten <?php echo $this->version; ?></h1>
		<h2>License</h2>
		<div>Sichtweiten is released under the <a href="https://www.gnu.org/licenses/gpl-3.0.html">GNU/GPL license</a></div>
	</div>
</form>
