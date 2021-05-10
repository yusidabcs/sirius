<?php
namespace core\modules\personal\models\checklist;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 3 December 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'checklist';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function main()
	{	
		$this->authorize();
		//if Session Resume Address_book_id is not set then we should not be here
		if(!isset($_SESSION['personal']['address_book_id']))
		{
			header('Location: '.$this->baseURL);
			exit();
		} else {
			$this->address_book_id = $_SESSION['personal']['address_book_id'];
		}
		
		$acceptable_checklists = array("character","health");
		
		if(isset($this->page_options[0]) && in_array($this->page_options[0], $acceptable_checklists))
		{
			$this->checklist_type = $this->page_options[0];
			
		} else {
			
			$msg = "What no checklist specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		//get the existing information (if any)
		$personal_db = new \core\modules\personal\models\common\db;
		
		$answer_array = $personal_db->getChecklist($this->address_book_id,$this->checklist_type);
		
		//get the base checklist data
		$core_db =  new \core\app\classes\core_db\core_db;
		
		switch ($this->checklist_type) 
		{
		    case 'character':
		        $this->checklist = $core_db->getChecklistCharacter();
		        break;
		        
		    case 'health':
		        $this->checklist = $core_db->getChecklistHealth();
		        break;
		    default:
		       $msg = "They checklist type is not correct! How did that happen?";
			   throw new \RuntimeException($msg);
		}
		
		//put them together
		foreach($this->checklist as $question_id => $values)
		{
			if( isset($answer_array[$question_id]) )
			{
				$answer = $answer_array[$question_id]['answer'];
				$text = $answer_array[$question_id]['text'];
			} else {
				$answer = 'not specified';
				$text = '';
			}
			
			$this->checklist[$question_id]['answer'] = $answer;
			$this->checklist[$question_id]['text'] = $text;
		}
		
		//set main details for the view (i.e. $main['sex'])
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('checklist');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'checks');
		
		$this->view_variables_obj->addViewVariables('checklist_type',$this->checklist_type);
		$this->view_variables_obj->addViewVariables('checklist',$this->checklist);
		
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