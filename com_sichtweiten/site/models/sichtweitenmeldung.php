<?php
/**
 * @package     Sichtweiten
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <bakual@bakual.ch>
 * @copyright   2024 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

/**
 * Model class for the Sichtweiten Component
 *
 * @since  1.0.0
 */
class SichtweitenModelSichtweitenmeldung extends AdminModel
{
	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 *
	 * @since 1.0.0
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Ordering column
	 * @param   string  $direction  'ASC' or 'DESC'
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app   = Factory::getApplication();
		$input = $app->getInput();

		$this->setState('sichtweiten.tp', $input->getInt('tp'));

		$return = $input->get('return', '', 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $input->getString('layout'));
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  the data for the form.
	 *
	 * @since   2.1.0
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();
		$data = $app->getUserState('com_sichtweiten.edit.sichtweitenmeldung.data', []);

		$tp  = $app->getInput()->getInt('tp', 0);

		if ($tp && empty($data['tauchplatz_id']))
		{
			$data['tauchplatz_id'] = $tp;
		}

		$this->preprocessData('com_sichtweiten.sichtweitenmeldung', $data);

		return $data;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @throws \Exception
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_sichtweiten.sichweitenmeldung', 'sichtweitenmeldung', array('control' => 'jform', 'load_data' => $loadData));

		return ($form) ?: false;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param string $name    The table name. Optional.
	 * @param string $prefix  The class prefix. Optional.
	 * @param array  $options Configuration array for model. Optional.
	 *
	 * @return   Table    A database object
	 * @since    1.0
	 */
	public function getTable($name = 'Sichtweitenmeldung', $prefix = 'SichtweitenTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table $table The Table object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function prepareTable($table): void
	{
		$table->meldedatum = Factory::getDate()->toSql();

		$user = $this->getCurrentUser();

		if (!$user->guest)
		{
			// Lookup the matching user_id
			$db    = $this->getDatabase();
			$query = $db->getQuery(true);

			$query->select('id');
			$query->from('#__sicht_user');
			$query->where('joomla_id = ' . (int) $user->id);

			$db->setQuery($query);
			$id = $db->loadResult();

			if (!$id)
			{
				$queryInsert = $db->getQuery(true);
				$queryInsert->insert('#__sicht_user');
				$queryInsert->columns($db->quoteName(array('name', 'joomla_id')));
				$queryInsert->values($db->quote($user->username) . ', ' . (int) $user->id);
				$db->setQuery($queryInsert);
				$db->execute();
				$id = $db->insertid();
			}

			if ($id)
			{
				$table->user_id = $id;
			}
		}
	}

	/**
	 * Save the Sichtweiteeintrag during postSave
	 *
	 * @param   array $validData The validated data to be saved
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function insertSichtweiteneintrag($validData)
	{
		$tiefenbereiche = array(
			79 => 'tiefenbereich0',
			80 => 'tiefenbereich1',
			81 => 'tiefenbereich2',
			82 => 'tiefenbereich3',
			83 => 'tiefenbereich4',
			84 => 'tiefenbereich5',
		);

		$id = (int) $this->state->get('sichtweitenmeldung.id');

		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		$query->insert('#__sicht_sichtweiteneintrag');
		$query->columns(array('sichtweite_id', 'sichtweitenmeldung_id', 'tiefenbereich_id'));

		foreach ($tiefenbereiche as $key => $value)
		{
			$query->values((int) $validData[$value] . ',' . $id . ',' . $key);
		}

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Save the Tauchpartners during postSave
	 *
	 * @param   array $validData The validated data to be saved
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function insertTauchpartner($validData)
	{
		$id = (int) $this->state->get('sichtweitenmeldung.id');

		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		$query->insert('#__sicht_tauchpartner');
		$query->columns(array('sichtweitenmeldung_id', 'name', 'email'));

		$execute = false;

		for ($i = 1; $i <= 3; $i++)
		{
			if ($validData['tauchpartner_' . $i . '_name'])
			{
				$query->values($id . ',' . $db->quote($validData['tauchpartner_' . $i . '_name']) . ',' . $db->quote($validData['tauchpartner_' . $i . '_email']));
				$execute = true;
			}
		}

		if ($execute)
		{
			$db->setQuery($query);
			$db->execute();
		}
	}
}
