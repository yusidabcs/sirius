<?php
namespace core\modules\partner\ajax;

/**
 * Final countrysubcodes class.
 *
 * @final
 * @package 	partner
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee module_admin
 */
final class checkpartnercode extends \core\app\classes\module_base\module_ajax {

	protected $optionRequired = true; //we must have an option to work
	
	
	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter

    public function run()
    {
        $out = null;

        if( $this->option )
		{
            $common_db = new \core\modules\partner\models\common\db;
            $partner_code = $this->option;
            $address_book_id = isset($this->page_options[1]) ? $this->page_options[1] : false;
            $out['duplicate'] = $common_db->checkPartnerCodeExist($partner_code, $address_book_id);
            
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