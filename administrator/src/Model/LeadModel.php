<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Algosemueve
 * @author     Ciro Artigot Cordero <info@algosemueve.es>
 * @copyright  2021 Ciro Artigot Cordero
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Algosemueve\Component\Algosemueve\Administrator\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\Table\Table;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Plugin\PluginHelper;
use \Joomla\CMS\MVC\Model\AdminModel;
use \Joomla\CMS\Helper\TagsHelper;

/**
 * Algosemueve model.
 *
 * @since  1.6
 */
class LeadModel extends AdminModel
{
	/**
	 * @var      string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $text_prefix = 'COM_ALGOSEMUEVE';

	/**
	 * @var   	string  	Alias to manage history control
	 * @since   3.2
	 */
	public $typeAlias = 'com_algosemueve.lead';

	/**
	 * @var null  Item data
	 * @since  1.6
	 */
	protected $item = null;

        
        
        
        
        
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return    Table    A database object
	 *
	 * @since    1.6
	 */
	public function getTable($type = 'Lead', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form  A Form object on success, false on failure
	 *
	 * @since    1.6
     *
     * @throws
	 */
	public function getForm($data = array(), $loadData = true)
	{
            // Initialise variables.
            $app = Factory::getApplication();

            // Get the form.
            $form = $this->loadForm(
                    'com_algosemueve.lead', 'lead',
                    array('control' => 'jform',
                            'load_data' => $loadData
                    )
            );

            

            if (empty($form))
            {
                return false;
            }

            return $form;
	}

	

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return   mixed  The data for the form.
	 *
	 * @since    1.6
     *
     * @throws
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_algosemueve.edit.lead.data', array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;
                        

			// Support for multiple or not foreign key field: catid
			$array = array();

			foreach ((array) $data->catid as $value)
			{
				if (!is_array($value))
				{
					$array[] = $value;
				}
			}
			if(!empty($array)){

			$data->catid = $array;
			}
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function getItem($pk = null)
	{
            
            if ($item = parent::getItem($pk))
            {
            	if (isset($item->params))
            	{
            		$item->params = json_encode($item->params);
            	}
            	
			if ( !empty($item->id) ) {
					$tagsHelper = new TagsHelper;
					$item->tags = $tagsHelper->getTagIds($item->id, 'com_algosemueve.lead');
				}
                // Do any procesing on fields here if needed
            }

            return $item;
            
	}

	/**
	 * Method to duplicate an Lead
	 *
	 * @param   array  &$pks  An array of primary key IDs.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @throws  Exception
	 */
	public function duplicate(&$pks)
	{
        $app = Factory::getApplication();
		$user = Factory::getUser();

		// Access checks.
		if (!$user->authorise('core.create', 'com_algosemueve'))
		{
			throw new \Exception(Text::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$context    = $this->option . '.' . $this->name;

		// Include the plugins for the save events.
		PluginHelper::importPlugin($this->events_map['save']);

		$table = $this->getTable();

		foreach ($pks as $pk)
		{
                    
			if ($table->load($pk, true))
			{
				// Reset the id to create a new record.
				$table->id = 0;

				if (!$table->check())
				{
					throw new \Exception($table->getError());
				}
				

				// Trigger the before save event.
				$result = $app->triggerEvent($this->event_before_save, array($context, &$table, true, $table));

				if (in_array(false, $result, true) || !$table->store())
				{
					throw new \Exception($table->getError());
				}

				// Trigger the after save event.
				$app->triggerEvent($this->event_after_save, array($context, &$table, true));
			}
			else
			{
				throw new \Exception($table->getError());
			}
                    
		}

		// Clean cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table  Table Object
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = Factory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__algosemueve_leads');
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}
}
