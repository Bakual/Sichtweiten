<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

?>
<div id="j-main-container" class="j-main-container sichtweiten-container">
	<div class="row row-cols-1 row-cols-md-2 g-2">
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-folder fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=visibilityreports">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_VISIBILITYREPORTS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-question fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=help">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_HELP_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
	</div>
	<h3 class="card card-body my-2 text-center"><?php echo Text::_('COM_SICHTWEITEN_STAMMDATEN'); ?></h3>
	<div class="row row-cols-1 row-cols-md-3 g-2">
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-map-marker-alt fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=divesites">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_DIVESITES_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-flag fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=waters">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_WATERS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-compass fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=places">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_PLACES_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-globe-europe fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=countries">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_COUNTRIES_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-eye fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=visibilities">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_VISIBILITIES_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col" title="<?php echo Text::_('COM_SICHTWEITEN_CURRENTLY_NOT_USED'); ?>">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-arrow-down fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_sichtweiten&view=depths">
						<h3 class="card-title"><?php echo Text::_('COM_SICHTWEITEN_DEPTHS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
