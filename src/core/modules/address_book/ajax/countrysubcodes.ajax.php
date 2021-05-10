<?php
namespace core\modules\address_book\ajax;

/**
 * Final countrysubcodes class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee module_admin
 */
final class countrysubcodes extends \core\app\classes\module_base\module_ajax {
	
	protected $optionRequired = true; //we must have an option to work
	
	
	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter


	public function run()
	{	
		$out = null;
		
		if($this->option)
		{
			$core_db = new \core\app\classes\core_db\core_db;
			$out = $core_db->getSubCountryCodes($this->option);
		}
		
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>