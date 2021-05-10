<?php
namespace core\modules\register\models\edit;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'edit';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		/*
			This is mostly for me so I will add more to it later but for now I am just making it work.
			It really should have error testing and indeed error testing in the form!
		*/
		
		//register db
		$register_db = new \core\modules\register\models\common\register_db;
		
		//delete
		if(!empty($_POST['delete']))
		{
			foreach($_POST['delete'] as $code => $value)
			{
				//delete the main information
				$register_db->deleteInfo($code);
			}
		}
		
		//get the current info
		$country_code_info = $register_db->getInfoArray();
		
		foreach($country_code_info as $code => $value)
		{
			if($value['heading'] != $_POST['heading'][$code])
			{
				$register_db->updateInfoHeading($code,$_POST['heading'][$code]);
			}
			
			if($value['type'] != $_POST['type'][$code])
			{
				$register_db->updateInfoType($code,$_POST['type'][$code]);
			}
			
			if($value['short_description'] != $_POST['short_description'][$code])
			{
				$register_db->updateInfoShortDescription($code,$_POST['short_description'][$code]);
			}
			
			if(empty($value['countries']))
			{
				$value['countries'] = array();
			} else {
				sort($value['countries']);
			}
			
			if(empty($_POST['countries'][$code]))
			{
				$_POST['countries'][$code] = array();
			} else {
				sort($_POST['countries'][$code]);
			}
			
			if($value['countries'] != $_POST['countries'][$code])
			{
				$register_db->deleteCountryCode($code); //delete all the existing ones
				
				foreach ($_POST['countries'][$code] as $country)
				{
					$register_db->insertCountryCode($country,$code);
				}
			}
		}
		
		//add a new code to register
		if(!empty($_POST['code_new']))
		{
			$register_db->insertInfo( $_POST['code_new'], $_POST['type_new'], $_POST['heading_new'], $_POST['short_description_new']);
			
			//add a link up the countries this code attaches too
			if(!empty($_POST['countries_new']))
			{
				foreach ($_POST['countries_new'] as $country)
				{
					$register_db->insertCountryCode($country,$_POST['code_new']);
				}
			}
		}
		
		return;
	}

}
?>