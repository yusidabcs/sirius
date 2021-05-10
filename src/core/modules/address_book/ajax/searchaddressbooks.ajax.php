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
final class searchaddressbooks extends \core\app\classes\module_base\module_ajax {
	
	protected $optionRequired = true;
	
	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter


	public function run()
	{	
		$out = [];
		if ( (isset($this->page_options[0])) && (isset($this->page_options[1])))
		{
			$email = $this->page_options[1];
			$module = $this->page_options[0];
			$type = (isset($_POST['type'])) ? $_POST['type'] : false;
			$partner = (isset($_POST['partner'])) ? $_POST['partner'] : false;
			$ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();
			$out = $ab_db->searchAddressBookbyEmail($email,($type ? $type : 'ent'),$module,$partner);
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