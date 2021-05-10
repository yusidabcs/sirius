<?php
namespace core\modules\personal\models\stcw;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 3 January 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'stcw';
	protected $processPost = true;
	public $stcw;
	
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
		}
		
		//make sure we have a specific english nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$stwc_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no stwc specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
				
		if($stwc_id == 'new')
		{
			$this->stwc = array(
				'stwc_id' => '',
				'type' => '',
				'overall' => '',
				'breakdown' => '',
				'where' => '',
				'when' => '',
				'filename' => ''
			);
			
		} else {
			
			//need the common db
			$personal_db = new \core\modules\personal\models\common\db;

			//get the existing information (if any)
			$this->stcw = $personal_db->getEnglish($stcw_id);
			
			if(empty($this->stcw))
			{
				$msg = "What no english information! How did that happen?";
				throw new \RuntimeException($msg);
			}
		}
				
		//set main details for the view (i.e. $main['sex'])
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file
				
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('stcw');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useFlatpickr();
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'lang');
		$this->view_variables_obj->addViewVariables('stcw',$this->stcw);
				
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