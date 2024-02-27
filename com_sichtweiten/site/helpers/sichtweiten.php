<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * Sichtweiten Component Sichtweiten Helper
 *
 * @since  1.0
 */
class SichtweitenHelperSichtweiten
{
	/**
	 * Generate linked buddies from | separated strings
	 *
	 * @param string $buddies
	 * @param string $emails
	 *
	 * @return string
	 * @since 1.0
	 */
	public static function getBuddies($buddies, $emails)
	{
		$buddies = htmlspecialchars($buddies);
		$buddies = explode('|', $buddies);

		if ($emails)
		{
			$emails = explode('|', $emails);

			foreach ($buddies as $key => &$value)
			{
				if (!empty($emails[$key]))
				{
					$value = '<a href="mailto:' . $emails[$key] . '">' . htmlspecialchars($value) . '</a>';
				}
			}
		}

		return implode(', ', $buddies);
	}
}
