<?php
namespace core\modules\user\models\home;

/**
 * Final model class.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 January 2015
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'home';
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
		//Security Level and Group Description
		$this->security_title = $this->system_register->getSecurityTitle($_SESSION['user_security_level']);
		$this->group_title = $this->system_register->getGroupTitle($_SESSION['user_group']);
		
		$this->_normal = false;
		$this->_edit = false;
		$this->_password = false;
		
		if(isset($this->page_options[0]))
		{
			if($this->page_options[0] == 'edit')
			{
				$this->_edit = true;
			} else if($this->page_options[0] == 'password') {
				$this->_password = true;
			} else {
				$this->_normal = true;
			}
		} else {
			$this->_normal = true;
		}
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('home');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
				
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('goback',$this->modelURL);
		$this->view_variables_obj->addViewVariables('changeDetails',$this->myURL.'/edit');
		$this->view_variables_obj->addViewVariables('changePassword',$this->myURL.'/password');
		$this->view_variables_obj->addViewVariables('gotoAdmin',$this->baseURL.'/admin');
		
		$this->view_variables_obj->addViewVariables('normal',$this->_normal);
		$this->view_variables_obj->addViewVariables('editForm',$this->_edit);
		$this->view_variables_obj->addViewVariables('passwordForm',$this->_password);
		
		
		$this->view_variables_obj->addViewVariables('isAdmin',$this->isAdmin);
		
		$this->view_variables_obj->addViewVariables('user_id',$_SESSION['user_id']);
		$this->view_variables_obj->addViewVariables('username',$_SESSION['user_name']);
		$this->view_variables_obj->addViewVariables('email',$_SESSION['user_email']);
		
		$this->view_variables_obj->addViewVariables('security',$this->security_title);
		$this->view_variables_obj->addViewVariables('group',$this->group_title);
	
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