<?php
namespace core\modules\personal\models\flight;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 December 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'flight';
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
		
		//make sure we have a specific flight nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$flight_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no flight specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		//get the passport list because all flight's must be linked to a passport
		$personal_db = new \core\modules\personal\models\common\db;
		$passportList = $personal_db->getPassportList($_SESSION['personal']['address_book_id']);
		
		if(empty($passportList))
		{
			$msg = "What no passports? You can not add a flight without a passport?";
			throw new \RuntimeException($msg);	
		} else {
			$this->passportArray = array_keys($passportList);
		}
		
		if($flight_id == 'new')
		{
			$this->flight = array(
				'flight_number' => '',
				'departure_date' => '',
				'filename' => ''
			);
			
		} else {
			
			//get the existing information (if any)
			$this->flight = $personal_db->getFlight($flight_id);
			if(empty($this->flight))
			{
				$msg = "What no flight information! How did that happen?";
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
		$this->view_variables_obj->setViewTemplate('flight');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useCroppie("2.5.1");
		$this->view_variables_obj->useSweetAlert("6.6.2");
		$this->view_variables_obj->useFlatpickr("3.0.6");
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'passp');
		$this->view_variables_obj->addViewVariables('flight',$this->flight);
				
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