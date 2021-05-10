<?php
namespace core\modules\register\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->use_partner = false;
		$this->partner_file = null;
		$this->partner_data = null;

		if (isset($this->page_options[0])){
			$slug = $this->page_options[0];
			$register_db = new \core\modules\register\models\common\register_db;
			$data = $register_db->getPartnerByPartnerCode($slug);
			if ( empty($data) )
			{
				// no partner with the partner code embedded
                $htmlpage_ns = NS_HTML.'\\htmlpage';
                $htmlpage = new $htmlpage_ns(404);
                exit();
			}else{
				$this->use_partner = true;
                $partner_db = new \core\modules\partner\models\common\db;
                $this->partner_data = $partner_db->getPartnerDetail($data['address_book_id']);
                $this->partner_file = $partner_db->getPartnerFile($data['address_book_id']);
			}
		}

		//values for date of birth picker
		$min_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MAX_AGE );
		$max_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MIN_AGE );

		$this->dob_min = date('c', $min_date);
		$this->dob_max = date('c', $max_date);

		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		if($this->use_partner)
		{
			$this->countries = $core_db->getAllCountryCodes($this->partner_data['countryCode_id']);
        }else{
            $this->countries = $core_db->getAllCountryCodes();
		}
		$geoip = new \core\app\classes\geoip\geoip('country', $this->getUserIP());
		$this->current_location = $geoip->getLocationDetails();
		

		//register db
		$register_db = new \core\modules\register\models\common\register_db;
		$this->country_code_info = $register_db->getInfoArray();
		$this->countries_info_code = $register_db->getCountriesInfoCode();

		//make sure our ajax is only used by us
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
		$numberOfCharacter = strlen($characters) - 1;
		$this->hash = '';

		for($x=1;$x<=25;$x++){
			$position = rand(0,$numberOfCharacter);
			$this->hash .= substr($characters,$position,1);
		}
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('home');		
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		
		//needed items
		$this->view_variables_obj->useSweetAlert();
		//$this->view_variables_obj->useFlatpickr();
		
		//variables
		$this->view_variables_obj->addViewVariables('isAdmin',$this->isAdmin);
		$this->view_variables_obj->addViewVariables('edit_link',$this->baseURL.'/edit');
		
		$this->view_variables_obj->addViewVariables('submitted',false);
		
		$this->view_variables_obj->addViewVariables('country','not specified');
		$this->view_variables_obj->addViewVariables('current_location', $this->current_location);
		$this->view_variables_obj->addViewVariables('countries',$this->countries);
		
		$this->view_variables_obj->addViewVariables('country_code_info',$this->country_code_info);
		$this->view_variables_obj->addViewVariables('countries_info_code',$this->countries_info_code);
		
		$this->view_variables_obj->addViewVariables('title','');
		$this->view_variables_obj->addViewVariables('family_name','');
		$this->view_variables_obj->addViewVariables('given_name','');
		$this->view_variables_obj->addViewVariables('middle_names','');
		$this->view_variables_obj->addViewVariables('dob','');
		$this->view_variables_obj->addViewVariables('dob_min',$this->dob_min);
		$this->view_variables_obj->addViewVariables('dob_max',$this->dob_max);
		$this->view_variables_obj->addViewVariables('sex','not specified');
		$this->view_variables_obj->addViewVariables('main_email','');

		if (isset($this->errors)){
			$this->view_variables_obj->addViewVariables('errors',$this->errors);
		}
		if (isset($this->use_partner)){
			$this->view_variables_obj->addViewVariables('partner_data',$this->partner_data);
			$this->view_variables_obj->addViewVariables('partner_file',$this->partner_file);
		}
		$this->view_variables_obj->addViewVariables('register','');
		$this->view_variables_obj->addViewVariables('accurate','');
		$this->view_variables_obj->addViewVariables('english','');

		$this->view_variables_obj->addViewVariables('recaptcha',$this->system_register->site_info('SITE_RECAPTCHA_KEY'));
		
		$_SESSION['register_ajax'] = $this->hash;
		$_SESSION['email_check_count'] = 0;
		$this->view_variables_obj->addViewVariables('register_ajax',$this->hash);
		
		$this->view_variables_obj->addViewVariables('use_captcha',false);
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$errors = $this->input_obj->getErrors();
				if(array_key_exists('reCAPTCHA', $errors))
				{
					$this->view_variables_obj->addViewVariables('use_captcha',true);
				}
				$this->view_variables_obj->addViewVariables('errors',$errors);
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

	function getUserIP() {
		if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
				$addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
				return trim($addr[0]);
			} else {
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
		
}
?>