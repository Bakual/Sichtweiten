<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2015 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HtmlHelper::_('bootstrap.tooltip');
$session = Factory::getSession();
$externDb = ComponentHelper::getParams('com_sichtweiten')->get('extern_db');
HtmlHelper::stylesheet('administrator/components/com_sichtweiten/sichtweiten.css');
?>
<div id="j-main-container" class="sichtweiten-container">
	<ul class="thumbnails">
		<li class="span6">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=visibilityreports">
				<div class="icon"><span class="icon-drawer-2"></span></div>
				<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_VISIBILITYREPORTS_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span6">
			<a class="thumbnail" href="index.php?option=com_sichtweiten&view=help">
				<div class="icon"><span class="icon-help"></span></div>
				<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_HELP_TITLE'); ?></h3>
			</a>
		</li>
	</ul>
	<h3><?php echo Text::_('COM_SICHTWEITEN_STAMMDATEN'); ?></h3>
	<?php if (!$externDb) : ?>
		<ul class="thumbnails">
			<li class="span2">
				<a class="thumbnail" href="index.php?option=com_sichtweiten&view=divesites">
					<div class="icon"><span class="icon-location"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_DIVESITES_TITLE'); ?></h3>
				</a>
			</li>
			<li class="span2">
				<a class="thumbnail" href="index.php?option=com_sichtweiten&view=waters">
					<div class="icon"><span class="icon-flag"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_WATERS_TITLE'); ?></h3>
				</a>
			</li>
			<li class="span2">
				<a class="thumbnail" href="index.php?option=com_sichtweiten&view=places">
					<div class="icon"><span class="icon-compass"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_PLACES_TITLE'); ?></h3>
				</a>
			</li>
			<li class="span2">
				<a class="thumbnail" href="index.php?option=com_sichtweiten&view=countries">
					<div class="icon"><span class="icon-flag-3"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_COUNTRIES_TITLE'); ?></h3>
				</a>
			</li>
			<li class="span2">
				<a class="thumbnail" href="index.php?option=com_sichtweiten&view=visibilities">
					<div class="icon"><span class="icon-eye"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_VISIBILITIES_TITLE'); ?></h3>
				</a>
			</li>
			<li class="span2 disabled hasTooltip" title="<?php echo Text::_('COM_SICHTWEITEN_CURRENTLY_NOT_USED'); ?>">
				<a class="thumbnail" href="index.php?option=com_sichtweiten&view=depths">
					<div class="icon"><span class="icon-arrow-down-4"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_DEPTHS_TITLE'); ?></h3>
				</a>
			</li>
		</ul>
	<?php endif; ?>
	<?php if ($externDb) : ?>
		<div class="well well-large">
			<?php echo Text::_('COM_SICHTWEITEN_MASTERDATA_NOT_AVAILABLE'); ?>
		</div>
	<?php endif; ?>
	<?php if ($externDb) : ?>
		<h3><?php echo Text::_('COM_SICHTWEITEN_TOOLS'); ?></h3>
		<ul class="thumbnails">
			<li class="span12 hasTooltip" title="<?php echo Text::_('COM_SICHTWEITEN_MIGRATE_DESC'); ?>">
				<a class="thumbnail"
				   href="index.php?option=com_sichtweiten&task=tools.migrate&<?php echo $session->getName() . '=' . $session->getId() . '&' . JSession::getFormToken(); ?>=1">
					<div class="icon"><span class="icon-database"></span></div>
					<h3 class="center"><?php echo Text::_('COM_SICHTWEITEN_MIGRATE_TITLE'); ?></h3>
				</a>
			</li>
		</ul>
	<?php endif; ?>
</div>