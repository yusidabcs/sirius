<?php
namespace core\modules\address_book\models\add;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'add';
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
		//check if there is redirect option, and check if in allowerd redirect	
		$this->redirect_to = '';
		if (isset($this->page_options[0]))
		{
			$redirect_to = $this->page_options[0];
			if ( in_array($redirect_to,['ab','rec']) )
			{
				$this->redirect_to = $redirect_to;
			}
		}
		//include common
		$add_core = \core\modules\address_book\models\common\add\core::getInstance();
		
		//main file
		$this->main_file = $add_core->getContentViewFile('main');
		
		//address file
		$this->address_file = $add_core->getContentViewFile('address');
		
		//pots file
		$this->pots_file = $add_core->getContentViewFile('pots');
		
		//internet file
		$this->internet_file = $add_core->getContentViewFile('internet');
		
		//avatar file
		$this->avatar_file = $add_core->getContentViewFile('avatar');

		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('add');
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
		$this->view_variables_obj->addViewVariables('personOnly',$this->useEntity);
		$this->view_variables_obj->addViewVariables('redirect_to',$this->redirect_to);

		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);

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