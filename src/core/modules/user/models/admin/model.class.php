<?php
namespace core\modules\user\models\admin;

/**
 * Final model class.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'admin';
	protected $processPost = true;
		
	public function __construct()
	{
		parent::__construct();
		$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
		$this->user_db = new $user_db_common_ns();
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		//if page is set in options
		if(isset($this->page_options[0]) && $this->page_options[0] > 0)
		{
			$page = $this->page_options[0];
		} else {
			$page = 0;
		}

		$ignore_me = true;
		
		//pagination information
		$this->my_pagination = $this->user_db->getPaginationInfo($this->link_id,$this->model_name,$page,$ignore_me);
		
		$this->my_rows = $this->user_db->selectAllUsers();

		//setup the security information
		
		
		//setup the group information
		

		//values for date of birth picker
		$min_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MAX_AGE );
		$max_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MIN_AGE );

		$this->dob_min = date('c', $min_date);
		$this->dob_max = date('c', $max_date);

		$core_db = new \core\app\classes\core_db\core_db;
		$this->countries = $core_db->getAllCountryCodes();
		//register db
		$register_db = new \core\modules\register\models\common\register_db;
		$this->country_code_info = $register_db->getInfoArray();
		$this->countries_info_code = $register_db->getCountriesInfoCode();
		$this->roles = $this->user_db->getAllRoles();

		$this->defaultView();
		return;
	}
		
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('admin');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();

		//give it the pagination information
		$this->view_variables_obj->addViewVariables('paginationInfo',$this->my_pagination);
		
		//row information
		$this->view_variables_obj->addViewVariables('rows',$this->my_rows);
		
		//groups and security selections
			
		//POST Variable
		$this->view_variables_obj->addViewVariables('modelURL',$this->modelURL);
		$this->view_variables_obj->addViewVariables('goback',$this->baseURL);
		
		//set the defaults
		$this->view_variables_obj->addViewVariables('username','');
		$this->view_variables_obj->addViewVariables('email','');
		$this->view_variables_obj->addViewVariables('security_level_id','NONE');
		$this->view_variables_obj->addViewVariables('group_id','ALL');

		$this->view_variables_obj->addViewVariables('title','');
		$this->view_variables_obj->addViewVariables('family_name','');
		$this->view_variables_obj->addViewVariables('given_name','');
		$this->view_variables_obj->addViewVariables('middle_names','');
		$this->view_variables_obj->addViewVariables('dob','');
		$this->view_variables_obj->addViewVariables('dob_min',$this->dob_min);
		$this->view_variables_obj->addViewVariables('dob_max',$this->dob_max);
		$this->view_variables_obj->addViewVariables('sex','not specified');
		$this->view_variables_obj->addViewVariables('main_email','');
		$this->view_variables_obj->addViewVariables('country','not specified');
		$this->view_variables_obj->addViewVariables('countries',$this->countries);
		$this->view_variables_obj->addViewVariables('country_code_info',$this->country_code_info);
		$this->view_variables_obj->addViewVariables('countries_info_code',$this->countries_info_code);
		$this->view_variables_obj->addViewVariables('roles',$this->roles);
		
		//password
		$generic_obj = \core\app\classes\generic\generic::getInstance();
		$this->view_variables_obj->addViewVariables('password',$generic_obj->generateRandomPassword(10));

		//over write and handle submit
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
			}
			
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
			if($this->input_obj->hasMessages())
            {
                $this->view_variables_obj->addViewVariables('messages',$this->input_obj->getMessages());
            }
		}
		
		return;
	}
		
}
?>