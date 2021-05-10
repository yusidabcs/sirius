<?php
namespace core\modules\personal\ajax;

use FPDF;

/**
 * Final default class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	survey_client
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 December 2017
 */
final class datatable extends \core\app\classes\module_base\module_ajax {
	
	public function run()
	{
        $this->personal_db = new \core\modules\personal\models\common\db();
        
		switch($this->option) 
		{			
			case 'passport':
                $out = $this->personal_db->getPassportDatatable($_SESSION['personal']['address_book_id']);
                break;

            case 'visa':
                $out = $this->personal_db->getVisaDatatable($_SESSION['personal']['address_book_id']);
				break;
				
			case 'oktb':
				$out = $this->personal_db->getOktbDatatable($_SESSION['personal']['address_book_id']);
				break;
			case 'ids':
				$out = $this->personal_db->getIdCardDatatable($_SESSION['personal']['address_book_id']);
				break;
			case 'idcheck':
				$out = $this->personal_db->getIdCheckDatatable($_SESSION['personal']['address_book_id']);
				break;
			case 'police':
				$out = $this->personal_db->getPoliceDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
			case 'medical':
				$out = $this->personal_db->getMedicalDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
			case 'vaccine':
				$out = $this->personal_db->getVaccinationDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
			case 'seaman':
				$out = $this->personal_db->getSeamanDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
			case 'stcw':
				$out = $this->personal_db->getStcwDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
			case 'flight':
				$out = $this->personal_db->getFlightDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
			case 'english':
				$out = $this->personal_db->getEnglishDatatable($_SESSION['personal']['address_book_id']);
				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}
				break;
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