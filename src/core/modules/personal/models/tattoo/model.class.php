<?php
namespace core\modules\personal\models\tattoo;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 January 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'tattoo';
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
		}
		
		//make sure we have a specific tattoo nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$tattoo_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no tattoo specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		if($tattoo_id == 'new')
		{
			$this->tattoo = array(
				'tattoo_id' => '',
				'location' => '',
				'short_description' => '',
				'concealable' => '',
				'filename' => ''
			);
			
		} else {
			
			//get the existing information (if any)
			$personal_db = new \core\modules\personal\models\common\db;
			$this->tattoo = $personal_db->getTattoo($tattoo_id);
			if(empty($this->tattoo))
			{
				$msg = "What no tattoo information! How did that happen?";
				throw new \RuntimeException($msg);
			}
		}
		
		//set main details for the view (i.e. $main['sex'])
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file
		
		//set location array
		$this->locationArray = array('ankle','deltoid','elbow','face','foot','hand','head','knee','lower arm','lower leg','neck','other','upper arm','upper back','upper chest','upper leg','wrist');
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('tattoo');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the gallery and the image
		$this->view_variables_obj->useEkkoLightBox();
		$this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useSweetAlert();
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'tat');
		$this->view_variables_obj->addViewVariables('tattoo',$this->tattoo);
		$this->view_variables_obj->addViewVariables('locationArray',$this->locationArray);
				
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