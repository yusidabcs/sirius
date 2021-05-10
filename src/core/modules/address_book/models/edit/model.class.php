<?php
namespace core\modules\address_book\models\edit;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 December 2016
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'edit';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();		
		//if page is set in options
		if(isset($this->page_options[0]) && $this->page_options[0] > 0)
		{
			$address_book_id = $this->page_options[0];
		} else {
			$msg = "No valid address book id given!";
			throw new \RuntimeException($msg);
		}
		
		//include common
		$edit_core = \core\modules\address_book\models\common\edit\core::getInstance($address_book_id);
		
		//main file
		$this->main_file = $edit_core->getContentViewFile('main');
		
		//address file
		$this->address_file = $edit_core->getContentViewFile('address');
		
		//pots file
		$this->pots_file = $edit_core->getContentViewFile('pots');
		
		//internet file
		$this->internet_file = $edit_core->getContentViewFile('internet');
		
		//avatar file
		$this->avatar_file = $edit_core->getContentViewFile('avatar');

        //setup the security information
        foreach($this->system_register->getSecurityArray() as $key => $item)
        {
            if($item['level'] < 60){

                $this->select_security[] = array($key,$item['title']);
            }
        }
        $this->flash_message = new \core\app\classes\flash_message\flash_message();
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('edit');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//include
		$this->view_variables_obj->addViewVariables('main_file',$this->main_file);
		$this->view_variables_obj->addViewVariables('address_file',$this->address_file);
		$this->view_variables_obj->addViewVariables('pots_file',$this->pots_file);
		$this->view_variables_obj->addViewVariables('internet_file',$this->internet_file);
		$this->view_variables_obj->addViewVariables('avatar_file',$this->avatar_file);
        $this->view_variables_obj->addViewVariables('select_security',$this->select_security);
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		$this->view_variables_obj->addViewVariables('baseURL',$this->baseURL);

        $this->view_variables_obj->addViewVariables('flash_message',$this->flash_message);

		$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
		$user_db = new $user_db_common_ns();

		$this->view_variables_obj->addViewVariables('roles', $user_db->getAllRoles());

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
		}
		return;
	}
		
}
?>