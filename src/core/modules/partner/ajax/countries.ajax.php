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
final class countries extends \core\app\classes\module_base\module_ajax {

	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter

    public function run()
    {
        $out = null;

        $core_db = new \core\app\classes\core_db\core_db;
        $country_codes = $_POST['country_codes'];
        $out['value'] = $this->option;
        foreach ($country_codes as $value )
        {
            $out[$value] = $core_db->getSubCountryCodes($value);
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