<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\MVC\View\HtmlView;

/**
 * HTML View class for the Sichtweiten Component
 *
 * @since  1.0
 */
class SichtweitenViewVisibilities extends HtmlView
{
	/**
	 * Contains model state
	 *
	 * @var    Joomla\Registry\Registry
	 * @since 1.0
	 */
	protected $state;

	/**
	 * Array of objects
	 *
	 * @var    array
	 * @since 1.0
	 */
	protected $items;

	/**
	 * Array of gewaesser
	 *
	 * @var    array
	 * @since 2.1.0
	 */
	protected $gewaesser;

	/**
	 * Array of visibilities
	 *
	 * @var    array
	 * @since 2.3.0
	 */
	protected $visibilities;

	/**
	 * Contains the merged component and menuitem params
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since 1.0
	 */
	protected $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @see     HtmlView::loadTemplate()
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		// Get data from the models
		$this->state = $this->get('State');
		$this->state->set('list.start', 0);
		$this->state->set('list.limit', 0);
		$this->gewaesser    = $this->get('Gewaesser');
		$this->visibilities = $this->get('Visibilities');

		foreach ($this->gewaesser as $see)
		{
			$this->state->set('filter.gewaesser', $see->id);
			$see->visibilities = $this->get('Items');
		}

		// Check for errors
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->params = $this->state->get('params');

		parent::display($tpl);
	}
}
