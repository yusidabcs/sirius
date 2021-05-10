<?php
namespace core\modules\personal\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = false;
	public $connection = null;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	protected function main()
	{	
		$this->authorize();
		//if tab option is set
		$valid_tab_array = array('main','lang','checks','passp','ids','employ','edu','med','tat','ref','police','documents');
		$document_tab_array = array('english','psf','passport','ids','police','sbk','stcw','medical','flight','seaman');
		$this->jobs = [];
		if(isset($this->page_options[0]) && in_array($this->page_options[0],$valid_tab_array) )
		{
			$this->active_tab = 'tab_'.$this->page_options[0];
		} else {
			$this->active_tab = 'tab_main';
		}
        if(isset($this->page_options[1]) && in_array($this->page_options[1],$document_tab_array) )
        {
            $this->docs_active_tab = 'tab_'.$this->page_options[1];
        } else {
            $this->docs_active_tab = 'tab_passport';
        }

		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0)
		{
			//Get all the user information
			$user_db = new \core\modules\user\models\common\user_db;
			$this->mode = 'personal';	
			$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

			// check if there is option id, if there is then it should be for looking another personal data
			if ((isset($this->page_options[0]) && is_numeric($this->page_options[0])))
			{
				
				//check user security level
				if((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] ))
				{
					// looking for another user personal data
					$this->mode = 'recruitment';
					$param_id = $this->page_options[0];
					$this->user_info = $user_db->getUserInfoFromAdressBookId($param_id);
                    $this->connection = $address_book_db->getAddressBookConnection($param_id,'lp');
					//check if user data with parameter address book id exist
					
					if (empty($this->user_info))
					{	
						$msg = "No user data found with that address book id!  You need to make one before you can using personal!";
						throw new \RuntimeException($msg);
						exit();
					}
					
					if(isset($this->page_options[1]) && in_array($this->page_options[1],$valid_tab_array) )
					{
						$this->active_tab = 'tab_'.$this->page_options[1];
					} else {
						$this->active_tab = 'tab_main';
					}

                    if(isset($this->page_options[2]) && in_array($this->page_options[2],$document_tab_array) )
                    {
                        $this->docs_active_tab = 'tab_'.$this->page_options[2];
                    } else {
                        $this->docs_active_tab = 'tab_passport';
                    }
					
					//set personal session data from parameter id
					$_SESSION['personal']['user_id'] = $this->user_info['user_id'];
					$_SESSION['personal']['address_book_id'] = $param_id;

					$job_db = new \core\modules\job\models\common\db();
					$this->jobs = $job_db->getJobApplications($param_id);

				}else{
					$msg = "Only admin can access this feature!";
					throw new \RuntimeException($msg);
				}
			}else{
				//if there is no page option or it's the same id, show the logged in user personal data
				$_SESSION['personal']['user_id'] = $_SESSION['user_id'];
				$user_info_array = $user_db->selectUserDetails($_SESSION['personal']['user_id']);
				$this->user_info = $user_info_array[$_SESSION['personal']['user_id']];
				$_SESSION['personal']['address_book_id'] = $address_book_db->getPersonhAddressBookIdFromEmail($this->user_info['email']);

				//check if address book empty
				if(empty($_SESSION['personal']['address_book_id']))
				{
					unserialize($_SESSION['personal']);
					$msg = "We do not have an address book entry!  You need to make one before you can using personal!";
					throw new \RuntimeException($msg);
				}
			}

		} else {
			$msg = "Wow you should never see this error ... very bad!";
			throw new \RuntimeException($msg);
		}

		//include generic
		$this->generic = \core\app\classes\generic\generic::getInstance();
		
		//include common
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		
		//main file
		$this->main_file = $view_core->getContentViewFile('main');
		
		//address file
		$this->address_file = $view_core->getContentViewFile('address');
		
		//pots file
		$this->pots_file = $view_core->getContentViewFile('pots');
		
		//internet file
		$this->internet_file = $view_core->getContentViewFile('internet');
		
		//avatar file
		$this->avatar_file = $view_core->getContentViewFile('avatar');
		
		//we need personal db
		$this->personal_db = new \core\modules\personal\models\common\db;
		
		$this->workflow_db = new \core\modules\workflow\models\common\db;
		
		//need core db and get country codes
		$this->core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $this->core_db->getAllCountryCodes();
		
		//dialCodes
		$this->countryDialCodes = $this->core_db->getAllDialCodes();
		
		//languageCodes
		$this->languageCodes = $this->core_db->getMajorLanguageCodes();
				
		//checklist
		$this->checklist = $this->_getChecklistInfo();
		
		$this->general = $this->_getGeneral();
		
		$this->language = $this->_getLanguage();

		$this->passportList = $this->_getPassportList();
		
		$this->employmentList = $this->_getEmploymentList();
		
		$this->educationList = $this->_getEducationList();

		$this->idcardList = $this->_getIdcardList();
		
		$this->tattooList = $this->_getTattooList();
		
		$this->referenceList = $this->_getReferenceList();
			
		$this->verification = $this->_checkVerified();
        $this->progress = ($this->verification['total_requirement'] - count(is_array($this->verification['verification_info']) ? $this->verification['verification_info'] : [])) / $this->verification['total_requirement'] * 100;
		$this->list_status = ['unverified','request','process','verified','rejected'];

		$this->medicalWorkflowList = $this->_getMedicalWorkflow();
		$this->vaccineWorkflowList = $this->_getVaccineWorkflow();
		$this->visaWorkflowList = $this->_getVisaWorkflow();
		$this->bgcWorkflow = $this->_getBgcWorkflow();

        if(count($this->view_variables_obj->getViewVariables()['avatar']) == 0){
            $flash_message = new \core\app\classes\flash_message\flash_message();
            $flash_message->error('Please complete profile data such as Avatar, Address and Phone Number',HTTP_TYPE.SITE_WWW.'/address-book/edit/'.$this->page_options[0]);
        }

        if(empty($this->view_variables_obj->getViewVariables()['address']['main'])){
            $flash_message = new \core\app\classes\flash_message\flash_message();
            $flash_message->error('Please complete profile data such as Avatar, Address and Phone Number',HTTP_TYPE.SITE_WWW.'/address-book/edit/'.$this->page_options[0]);
        }

        if(empty($this->view_variables_obj->getViewVariables()['pots'])){
            $flash_message = new \core\app\classes\flash_message\flash_message();
            $flash_message->error('Please complete profile data such as Avatar, Address and Phone Number',HTTP_TYPE.SITE_WWW.'/address-book/edit/'.$this->page_options[0]);
        }

		$this->icon_internet = [
			'skype' => '<i class="fab fa-skype fa-lg mb-2"></i>',
			'facebook' => '<i class="fab fa-facebook-square fa-lg mb-2"></i>',
			'youtube-video' => '<i class="fab fa-youtube-square fa-lg mb-2"></i>',
			'youtube-channel' => '<i class="fab fa-youtube-square fa-lg mb-2"></i>',
			'twitter' => '<i class="fab fa-twitter-square fa-lg mb-2"></i>',
			'linked-in' => '<i class="fab fa-linkedin fa-lg mb-2"></i>',
			'google-plus' => '<i class="fab fa-google-plus-square fa-lg mb-2"></i>',
			'instagram' => '<i class="fab fa-instagram-square fa-lg mb-2"></i>' 
		];

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
		$this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useEkkoLightBox();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useFlatpickr();
		$this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useMoment();

		//only use datatable if it's admin to reduce network load datatable.min.js
		//if ($this->mode == 'recruitment')
			$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->addViewVariables('baseURL',$this->baseURL);
		$this->view_variables_obj->addViewVariables('profile_link','/'.$this->menu_register->getModuleLink('profile'));
		$this->view_variables_obj->addViewVariables('address_book_link','/'.$this->menu_register->getModuleLink('address_book'));
		$this->view_variables_obj->addViewVariables('general_link',$this->baseURL.'/general');
		$this->view_variables_obj->addViewVariables('checklist_link',$this->baseURL.'/checklist');
		$this->view_variables_obj->addViewVariables('passport_link',$this->baseURL.'/passport');
		$this->view_variables_obj->addViewVariables('visa_link',$this->baseURL.'/visa');
		$this->view_variables_obj->addViewVariables('oktb_link',$this->baseURL.'/oktb');
		$this->view_variables_obj->addViewVariables('idcard_link',$this->baseURL.'/idcard');
		$this->view_variables_obj->addViewVariables('english_link',$this->baseURL.'/english');
		$this->view_variables_obj->addViewVariables('language_link',$this->baseURL.'/language');
		$this->view_variables_obj->addViewVariables('employment_link',$this->baseURL.'/employment');
		$this->view_variables_obj->addViewVariables('education_link',$this->baseURL.'/education');
		$this->view_variables_obj->addViewVariables('tattoo_link',$this->baseURL.'/tattoo');
		$this->view_variables_obj->addViewVariables('reference_link',$this->baseURL.'/reference');
		$this->view_variables_obj->addViewVariables('reference_warning_link', $this->mode == 'recruitment' ? $this->baseURL.'/home/'.$this->param_id.'/ref' : $this->baseURL.'/home/ref');
		$this->view_variables_obj->addViewVariables('medical_link',$this->baseURL.'/medical');
		$this->view_variables_obj->addViewVariables('vaccination_link',$this->baseURL.'/vaccination');
		$this->view_variables_obj->addViewVariables('idcheck_link',$this->baseURL.'/idcheck');
		$this->view_variables_obj->addViewVariables('police_link',$this->baseURL.'/police');
		$this->view_variables_obj->addViewVariables('seaman_link',$this->baseURL.'/seaman');
		$this->view_variables_obj->addViewVariables('flight_link',$this->baseURL.'/flight');

		$this->view_variables_obj->addViewVariables('address_book_id',$_SESSION['personal']['address_book_id']);
		$this->view_variables_obj->addViewVariables('recruitment_home','/'.$this->menu_register->getModuleLink('recruitment'));
        $this->view_variables_obj->addViewVariables('jobapplication_link','/'.$this->menu_register->getModuleLink('job_application').'/listjob/'.$_SESSION['personal']['address_book_id']);
        $this->view_variables_obj->addViewVariables('personal_jobapplication_link','/'.$this->menu_register->getModuleLink('job_application').'/listjob');
		$this->view_variables_obj->addViewVariables('jobApply_link','/'.$this->menu_register->getModuleLink('job_application').'/applyjob/');

		$this->view_variables_obj->addViewVariables('mode',$this->mode);
		$this->view_variables_obj->addViewVariables('user_info',$this->user_info);
		$this->view_variables_obj->addViewVariables('connection',$this->connection);
		$this->view_variables_obj->addViewVariables('checklist',$this->checklist);
		$this->view_variables_obj->addViewVariables('general',$this->general);
		$this->view_variables_obj->addViewVariables('language',$this->language);
		$this->view_variables_obj->addViewVariables('languageCodes',$this->languageCodes);
		$this->view_variables_obj->addViewVariables('employmentList',$this->employmentList);
		$this->view_variables_obj->addViewVariables('educationList',$this->educationList);
		$this->view_variables_obj->addViewVariables('tattooList',$this->tattooList);
		$this->view_variables_obj->addViewVariables('referenceList',$this->referenceList);

		$this->view_variables_obj->addViewVariables('verification',$this->verification);
		$this->view_variables_obj->addViewVariables('list_status',$this->list_status);
		$this->view_variables_obj->addViewVariables('progress',$this->progress);
		$this->view_variables_obj->addViewVariables('latest_verification',$this->latest_verification);
		

		$this->view_variables_obj->addViewVariables('active_tab',$this->active_tab);
		$this->view_variables_obj->addViewVariables('docs_active_tab',$this->docs_active_tab);

		$this->view_variables_obj->addViewVariables('medicalWorkflowList', $this->medicalWorkflowList);
		$this->view_variables_obj->addViewVariables('vaccineWorkflowList', $this->vaccineWorkflowList);
		$this->view_variables_obj->addViewVariables('visaWorkflowList', $this->visaWorkflowList);
		$this->view_variables_obj->addViewVariables('bgcWorkflowList', $this->bgcWorkflow);
		$this->view_variables_obj->addViewVariables('icon_internet',$this->icon_internet);
		$this->view_variables_obj->addViewVariables('jobs',$this->jobs);
		$cv_link = $this->_getHashCV($_SESSION['personal']['address_book_id'], 'template1');

		$this->view_variables_obj->addViewVariables('cv_link', $cv_link);

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
	
	private function _getChecklistInfo()
	{
		$out = array();
		
		//valid checklists
		$checklists = array('character','health');
		
		foreach($checklists as $type)
		{
			$not_specified = 0;
			$yes = 0;
			$yes_array = array();
			$no = 0;
			
			$info = $this->personal_db->getChecklist($_SESSION['personal']['address_book_id'],$type);
			
			if($info)
			{
				//date last updated
				$out[$type]['date'] = date("j M Y",strtotime($info[1]['modified_on']));
				
				//process the result
				foreach($info as $question_id => $value)
				{
					switch ($value['answer']) 
					{
					    case "not specified":
					        $not_specified++;
					        break;
					        
					    case "yes":
					        $yes++;
					        //keep an array to get the information for the display
					        $yes_array[$question_id] = $value['text'];
					        break;
					        
					    case "no":
					        $no++;
					        break;
					}
				}
						
				if($not_specified > 0)
				{
					$out[$type]['result'] = 'NOT FINISHED';
				} else if($yes > 0) { //means there is stuff to review
					$out[$type]['result'] = 'Review';
				} else {
					$out[$type]['result'] = 'All Good';
				}
							
				//display
				if($yes_array)
				{
					$answer_headings = $this->core_db->getChecklistAnswerHeadings($type,array_keys($yes_array));
					
					foreach($yes_array as $question_id => $text)
					{
						$out[$type]['display'][] = array(
							
							'heading' => $answer_headings[$question_id],
							'text' => $text
						);
					}
					
					
				} else {
					$out[$type]['display'] = false;
				}
				
				
				
			} else {
			
				$out[$type] = array(
					'date' => '-',
					'result' => 'NOT STARTED',
					'display' => false
				);
				
			}
		
		}
		
		return $out;
	}
	
	private function _getIdcardList()
	{
		$out = array();
		
		$info = $this->personal_db->getIdcardList($_SESSION['personal']['address_book_id']);
		
		foreach($info as $key => $value)
		{
			$country = $this->countryCodes[$value['countryCode_id']];
			
			$ts_from = strtotime($value['from_date']);


			$short_from = date('m/y', $ts_from);
			if($value['to_date'] == 0){
                $ts_to = strtotime($value['to_date']);
                $short_to = 'NO EXPIRED';
            }else{
                $ts_to = strtotime($value['to_date']);
                $short_to = date('m/y', $ts_to);
            }

			
			$view_from = date('d M Y', $ts_from);
			$view_to = date('d M Y', $ts_to);
			
			if($value['active'] == 'not_active')
			{
				$tr_status = 'rgba-red-slight';
			} else if($ts_to < strtotime('+6 months') && ($value['to_date'] != 0)) {
				$tr_status = 'rgba-orange-slight';
			} else {
				$tr_status = 'rgba-green-slight';
			}
			
			$thumb = $value['filename'].'-thumb';
			$thumb_back = $value['filename_back'].'-thumb';
			
			$out[$key] = array(
					'idcard_safe' => $value['idcard_safe'],
					'idcard_orig' => $value['idcard_orig'],
					'country' => $country,
					'short_from' => $short_from, 
					'short_to' => $short_to,
					'view_from' => $view_from,
					'view_to' => $view_to,
					'family_name' => $value['family_name'],
					'given_names' => $value['given_names'],
					'full_name' => $value['full_name'],
					'type' => $value['type'],
					'authority' => $value['authority'],
					'active' => $value['active'],
					'filename' => $value['filename'],
					'thumb' => $thumb,
					'filename_back' => $value['filename_back'],
					'thumb_back' => $thumb_back,
					'tr_status' => $tr_status
				);
		}
				
		return $out;
	}
	
	private function _getGeneral()
	{
		$out = array();
		
		$info = $this->personal_db->getGeneral($_SESSION['personal']['address_book_id']);
		
		if(!empty($info))
		{
			
			$tattoo = $info['tattoo'] == 'yes' ? true : false ;
			$children = $info['children'] == 'yes' ? true : false ;
			$seafarer = $info['seafarer'] == 'yes' ? true : false ;
			$migration = $info['migration'] == 'yes' ? true : false ;
			$passport = $info['passport'] == 'yes' ? true : false ;
			$travelled_overseas = $info['travelled_overseas'] == 'yes' ? true : false ;
			
			$has_partner_array = array('committed','married');
			$relationship_show = in_array($info['relationship'], $has_partner_array) ? true : false;
			
			$country_born = $this->countryCodes[$info['country_born']];
			$country_residence = $this->countryCodes[$info['country_residence']];
			$nok_country = (!empty($info['nok_country'])) ? $this->countryCodes[$info['nok_country']] : '';
			
			$nok_name = '';
			
			if( !empty($info['nok_family_name']) && !empty($info['nok_given_names']) )
			{
				$nok_name = strtoupper($info['nok_family_name']).', '.$info['nok_given_names'];
			} else if(!empty($info['nok_family_name'])) {
				$nok_name = $info['nok_family_name'];
			} else if(!empty($info['nok_given_names'])) {
				$nok_name = $info['nok_given_names'];
			}
			
			$nok_address = '';
			if(!empty($info['nok_line_1'])) $nok_address .= $info['nok_line_1'];
			if(!empty($info['nok_line_2'])) 
			{
				$nok_address .= !empty($info['nok_line_1']) ? ', '.$info['nok_line_2'] : $info['nok_line_2'];
			}
			if(!empty($info['nok_line_3']))
			{
				$nok_address .= !empty($info['nok_line_1']) || !empty($info['nok_line_2']) ? ', '.$info['nok_line_3'] : $info['nok_line_3'];
			}
			if(!empty($nok_address) && !empty($info['nok_country']))
			{
				$nok_address .= ', '.$nok_country;
			}
			
			$nok_phone = '';
			if( !empty($info['nok_number'])) $nok_phone .= $info['nok_number'];
			if( !empty($info['nok_type_number'])) $nok_phone .= ' ('.$info['nok_type_number'].')';
						
			$thumb = $info['filename'].'-thumb';
			
			$out = array(
					'height_weight' => $info['height_weight'],
					'height_cm' => $info['height_cm'],
					'weight_kg' => $info['weight_kg'],
					'height_in' => $info['height_in'],
					'weight_lb' => $info['weight_lb'],
					'bmi' => $info['bmi'],
					'tattoo' => $tattoo,
					'relationship' => $info['relationship'],
					'relationship_show' => $relationship_show,
					'children' => $children,
					'employment' => $info['employment'],
					'job_hunting' => $info['job_hunting'],
					'seafarer' => $seafarer,
					'migration' => $migration,
					'country_born' => $country_born,
					'country_residence' => $country_residence,
					'passport' => $passport,
					'travelled_overseas' => $travelled_overseas,
					'nok_name' => $nok_name,
					'nok_relationship' => $info['nok_relationship'],
					'nok_address' => $nok_address,
					'nok_email' => $info['nok_email'],
					'nok_skype' => $info['nok_skype'],
					'nok_phone' => (!empty($info['nok_country']))? $this->countryDialCodes[$info['nok_country']]['dialCode'].$nok_phone : $nok_phone,
					'filename' => $info['filename'],
					'signature_filename' => $info['signature_filename'],
					'thumb' => $thumb
				);

		}
			
		return $out;
	}
	
	private function _getPassportList()
 	{
 		$out = array();
 		
 		$info = $this->personal_db->getPassportList($_SESSION['personal']['address_book_id']);
 		
 		foreach($info as $key => $value)
 		{
 			$country = $this->countryCodes[$value['countryCode_id']];
 			
 			$ts_from = strtotime($value['from_date']);
 			$ts_to = strtotime($value['to_date']);
 
 			$short_from = date('m/y', $ts_from);
 			$short_to = date('m/y', $ts_to);
 			
 			$view_from = date('d M Y', $ts_from);
 			$view_to = date('d M Y', $ts_to);
 			
 			$view_dob = date('d M Y', strtotime($value['dob']));
 			
 			if($value['active'] == 'not_active')
 			{
 				$tr_status = 'rgba-red-slight';
 			} else if($ts_to < strtotime('+6 months')) {
 				$tr_status = 'rgba-orange-slight';
 			} else {
 				$tr_status = 'rgba-green-slight';
 			}
 
 			$thumb = $value['filename'].'-thumb';
 			
 			$out[$key] = array(
 					'country' => $country,
 					'short_from' => $short_from, 
 					'short_to' => $short_to,
 					'view_from' => $view_from,
 					'view_to' => $view_to,
 					'view_dob' => $view_dob,
 					'family_name' => $value['family_name'],
 					'given_names' => $value['given_names'],
 					'full_name' => $value['full_name'],
 					'nationality' => $value['nationality'],
 					'sex' => $value['sex'],
 					'place_issued' => $value['place_issued'],
 					'dob' => $view_dob,
 					'pob' => $value['pob'],
 					'type' => $value['type'],
 					'code' => $value['code'],
 					'authority' => $value['authority'],
 					'active' => $value['active'],
 					'filename' => $value['filename'],
 					'thumb' => $thumb,
 					'tr_status' => $tr_status
 				);
 		}
 				
 		return $out;
	}
	
	private function _getLanguage()
	{
		$out = $this->personal_db->getLanguage($_SESSION['personal']['address_book_id']);
					
		return $out;
	}
	
	private function _getEmploymentList()
	{
		$out = array();
		
		$info = $this->personal_db->getEmploymentList($_SESSION['personal']['address_book_id']);
		$job_category_db = new  \core\modules\job\models\common\job_category_db();
		foreach($info as $key => $value)
		{
			$country = $this->countryCodes[$value['countryCode_id']];
			
			$thumb = $value['filename'].'-thumb';
			
			$from_ts = strtotime($value['from_date']);
			
			$view_from = date('d M Y', $from_ts);
			$short_from = date('m/y', $from_ts);
			
			if($value['to_date'] == '0000-00-00')
			{
				$to_ts = strtotime(date("Y-m-d"));
				$view_to = '';
				$to_date = '';
				$short_to =  date('m/y', $to_ts);
			} else {
				
				$to_ts = strtotime($value['to_date']);
				$view_to = date('d M Y', $to_ts);
				$to_date = $value['to_date'];
				$short_to = date('m/y', $to_ts);
			}
			
			$safe_id = substr(md5($value['filename']), 0, 8);
			
			$length = $this->generic->tsDiffStr($from_ts,$to_ts,2);
            $job_category = $job_category_db->get($value['job_speedy_category_id']);

			$out[$key] = array(
					'safe_id' => $safe_id,
					'short_from' => $short_from,
					'short_to' => $short_to,
					'view_from' => $view_from,
					'view_to' => $view_to,
					'from_date' => $value['from_date'],
					'to_date' => $to_date,
					'job_speedy_category' => $job_category ? $job_category['name'] : '',
					'employer' => $value['employer'],
					'country' => $country,
					'website' => $value['website'],
					'email' => $value['email'],
					'phone' => $value['phone'],
					'job_title' => $value['job_title'],
					'type' => $value['type'],
					'description' => $value['description'],
					'active' => $value['active'],
					'filename' => $value['filename'],
					'thumb' => $thumb,
					'length' => $length
				);
		}
				
		return $out;
	}
	
	private function _getEducationList()
	{
		$out = array();
		
		$info = $this->personal_db->getEducationList($_SESSION['personal']['address_book_id']);

		foreach($info as $key => $value)
		{
			$country = $this->countryCodes[$value['countryCode_id']];
			
			$attended_country = $this->countryCodes[$value['attended_countryCode_id']];
			
			$thumb = $value['filename'].'-thumb';
			
			$from_ts = strtotime($value['from_date']);
			
			$view_from = date('d M Y', $from_ts);
			$short_from = date('m/y', $from_ts);
			
			if($value['to_date'] == '0000-00-00')
			{
				$to_ts = '';
				$view_to = '';
				$to_date = '';
				$short_to = ' - ';
			} else {
				
				$to_ts = strtotime($value['to_date']);
				$view_to = date('d M Y', $to_ts);
				$to_date = $value['to_date'];
				$short_to = date('m/y', $to_ts);
			}
			
			if($value['certificate_date'] == '0000-00-00')
			{
				$certificate_date = '';
				$view_certificate_date = '';
			} else {
				
				$certificate_date = $value['certificate_date'];
				$view_certificate_date = date('d M Y', strtotime($value['certificate_date']));
			}
			
			if($value['certificate_expiry'] == '0000-00-00')
			{
				$expiry_ts = '';
				$certificate_expiry = '';
				$view_certificate_expiry = '';
				
			} else {
				
				$expiry_ts = strtotime($value['certificate_expiry']);
				$certificate_expiry = $value['certificate_expiry'];
				$view_certificate_expiry = date('d M Y', $expiry_ts);
				
			}
			
			$safe_id = substr(md5($value['filename']), 0, 8);
			
			if(empty($expiry_ts))
			{
				$tr_status = 'default';
			} else if( $expiry_ts <= time() ) {
				$tr_status = 'rgba-red-slight';
			} else if( $expiry_ts < strtotime('+6 months')) {
				$tr_status = 'rgba-orange-slight';
			} else {
				if($value['level'] == 'stcw')
				{
					$tr_status = 'info';
				} else {
					$tr_status = 'rgba-green-slight';
				}
			}
			
			$length = $this->generic->tsDiffStr($from_ts,$to_ts,2);
						
			$out[$key] = array(
					'safe_id' => $safe_id,
					'short_from' => $short_from,
					'short_to' => $short_to,
					'view_from' => $view_from,
					'view_to' => $view_to,
					'from_date' => $value['from_date'],
					'to_date' => $to_date,
					'institution' => $value['institution'],
					'country' => $country,
					'website' => $value['website'],
					'email' => $value['email'],
					'phone' => $value['phone'],
					'qualification' => $value['qualification'],
					'type' => $value['type'],
					'description' => $value['description'],
					'level' => $value['level'],
					'english' => $value['english'],
					'attended_country' => $attended_country,
					'active' => $value['active'],
					'status' => $value['status'],
					'certificate_date' => $certificate_date,
					'view_certificate_date' => $view_certificate_date,
					'certificate_number' => $value['certificate_number'],
					'certificate_expiry' => $certificate_expiry,
					'view_certificate_expiry' => $view_certificate_expiry,
					'filename' => $value['filename'],
					'thumb' => $thumb,
					'tr_status' => $tr_status,
					'length' => $length
				);
		}
				
		return $out;
	}
	
	private function _getTattooList()
	{
		$out = array();
		
		$info = $this->personal_db->getTattooList($_SESSION['personal']['address_book_id']);

		foreach($info as $key => $value)
		{
			$thumb = $value['filename'].'-thumb';
			$safe_id = substr(md5($value['filename']), 0, 8);
						
			$out[$key] = array(
					'location' => $value['location'],
					'short_description' => $value['short_description'],
					'concealable' => $value['concealable'],
					'filename' => $value['filename'],
					'thumb' => $thumb,
					'safe_id' => $safe_id
				);
		}
				
		return $out;
	}
	
	private function _getReferenceList()
	{
		$out = array('work' => array(), 'personal' => array());
		
		$info = $this->personal_db->getReferenceList($_SESSION['personal']['address_book_id']);
		
		foreach($info as $key => $value)
		{
			$country = empty($value['countryCode_id']) ? '' : $this->countryCodes[$value['countryCode_id']];
			$thumb = $value['filename'].'-thumb';
			$safe_id = substr(md5($value['filename']), 0, 8);
			
			//Status needs to be fixed up
            $reference_check = $this->personal_db->getLatestReferenceCheck($key);

            if($reference_check && $reference_check['status'] == 'confirmed'){
                $tr_status = 'rgba-green-slight';
            }elseif($reference_check && $reference_check['status'] == 'completed'){
                $tr_status = 'rgba-orange-slight';
            }else{
                $tr_status = 'rgba-red-slight';
            }
			

			$out[$value['type']][$key] = array(
					'entity_name' => $value['entity_name'],
					'family_name' => $value['family_name'],
					'given_names' => $value['given_names'],
					'relationship' => $value['relationship'],
					'line_1' => $value['line_1'],
					'line_2' => $value['line_2'],
					'line_3' => $value['line_3'],
					'countryCode_id' => $value['countryCode_id'],
					'number_type' => $value['number_type'],
					'number' => $value['countryCode_id'] ? $this->countryDialCodes[$value['countryCode_id']]['dialCode'].$value['number'] : $value['number'],
					'email' => $value['email'],
					'skype' => $value['skype'],
					'comment' => $value['comment'],
					'filename' => $value['filename'],
					'country' => $country,
					'thumb' => $thumb,
					'safe_id' => $safe_id,
					'tr_status' => $tr_status,
					'reference_checks' => $this->personal_db->getReferenceCheckList($key)
				);
			$out[$value['type']][$key]['general']['signature_filename'] = $this->general['signature_filename'];
		}
				
		return $out;
	}
	
	
	private function _checkVerified()
	{
		$verified = true;
		$total_requirement = 6;

		//check if already in database
		$data = $this->personal_db->checkVerification($_SESSION['personal']['address_book_id']);
		$this->latest_verification = $data;
		$info = [];
		
		if ( empty($data) || (isset($data) && $data['status'] != 'verified') ) //if empty, check from existing data for each listing 
		{ 
			
			if (empty($this->idcardList))
			{
				$verified = false;
				$info[] = 'idcard';
			}
			if (empty($this->general))
			{
				$verified = false;
				$info[] = 'general';
			}else{
                if ($this->general['job_hunting'] == 'no' && empty($data)){
                    $verified = false;
                }
				if ($this->general['seafarer'] || $this->general['migration']) {

					if ($this->checklist['character']['result'] == 'NOT STARTED')
					{
						$verified = false;
						$info[] = 'checklist_character';
						$total_requirement++;
					}
					if ($this->checklist['health']['result'] == 'NOT STARTED')
					{
						$verified = false;
						$info[] = 'checklist_health';
						$total_requirement++;
					}
					if (($this->general['passport']) && (empty($this->passportList)))
					{
						$verified = false;
						$info[] = 'passport';
						$total_requirement++;
					}
				}
				// check if have tattoo
				if ($this->general['tattoo'])
				{
					if (empty($this->tattooList))
					{
						$verified = false;
						$info[] = 'tattoo';
						$total_requirement++;
					}
				}
			}
			if (empty($this->language))
			{
				$verified = false;
				$info[] = 'language';
			}
			if (empty($this->educationList))
			{
				$verified = false;
				$info[] = 'education';
			}
			$total_work_ref = count($this->referenceList['work'] ?? []);
			$total_personal_ref = count($this->referenceList['personal'] ?? []);
			$ref = true;
			if (empty($this->referenceList['personal']))
			{
				$verified = false;
				$ref = false;
			}
			
			else if ( ( $total_work_ref < 2 && $total_personal_ref == 0 ))
			{
				$verified = false;
				$ref = false;
			}

			else if ( ( $total_work_ref == 0 && $total_personal_ref < 2 ))
			{
				$verified = false;
				$ref = false;
			}
			
			if($ref != true){
				$info[] = 'reference_personal';
			}

			
			
			//if everything is verified
			if ($verified)
			{
				if (isset($data) && $data['status'] != 'verified') {
					//return all the list that need to be completed
					$data = array (
						'status' => $data['status'],
						'verification_info' => $data['verification_info'],
						'verified_by' => '',
						'updated_at' => '',
					);
				}else{
					$data = array( 
						'status' => 'ready',
						'verification_info' => 'ready to submit',
						'verified_by' => '',
						'updated_at' => '',
					);
				}
				
			} else {

                if ($this->general && $this->general['job_hunting'] == 'no'){
                    //return all the list that need to be completed
                    $data = array (
                        'status' => 'no_job_hunting',
                        'verification_info' => 'job_hunting',
                        'verified_by' => '',
                        'updated_at' => '',
                    );
                }else{
					if (isset($data) && $data['status'] != 'verified') {
						//return all the list that need to be completed
						$data = array (
							'status' => $data['status'],
							'verification_info' => $info,
							'verified_by' => '',
							'updated_at' => '',
						);
					}else{
						//return all the list that need to be completed
						$data = array (
							'status' => 'notready',
							'verification_info' => $info,
							'verified_by' => '',
							'updated_at' => '',
						);
					}
                    
					
                }


			}
		}
		$data['total_requirement'] = $total_requirement;

		if (count($info) === 0) {
			$mailing_common = new \core\modules\send_email\models\common\common;
			$mailing_db = new \core\modules\send_email\models\common\db;

			$address_book = $this->personal_db->getAddressBook($_SESSION['personal']['address_book_id']);
			$collection = $mailing_db->getCollectionByName('personal_not_complete');

			if ($mailing_db->checkEmailInCollection($address_book['main_email'], $collection['collection_id'])) {
				# code...

				$mailing_db->detachSubscriber($collection['collection_id'], $address_book['main_email']);
			}
		}
		
		return $data;
		
	}

	private function _getFlightList()
	{
		$data = $this->personal_db->getFlightList($_SESSION['personal']['address_book_id']);

		$info = [];
		foreach ($data as $key => $value) {
			$info[$value['flight_number']] = array(
				'address_book_id' => $value['address_book_id'],
				'departure_date' => date('M d, Y', strtotime($value['departure_date'])),
				'filename' => $value['filename'],
				'status' => $value['status'],
				'url' => '/ab/show/' . $value['filename']
			);
		}

		return $info;
	}

	private function _getHashCV($address_book_id,$template) {
        //check hash personal cv
        $hash_cv ='123';
        $cv_db = new \core\modules\cv\models\common\db;
        $data_hash_cv = $cv_db->checkHashPersonalCV($address_book_id);
        if(count($data_hash_cv)>0) {
            $hash_cv = $data_hash_cv[0]['hash'];
        } else {
            $unix = false;
            do {
                $random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
                $hash_cv = md5($random_string);

                //check hash unix
                $data_personal_cv = $cv_db->getIDHashPersonalCV($hash_cv);
                if(count($data_personal_cv)>0) {
                    $unix = true;
                }
            } while($unix);

            $data_to_db = [
                'address_book_id' => $address_book_id,
                'hash' => $hash_cv,
                'template' => $template
            ];
            $cv_db->insertHashPersonalCV($data_to_db);
        }
        //update status file address book
		$cv_db->updateAddressBookFileCV($address_book_id);
		
		return HTTP_TYPE.SITE_WWW.'/'.$this->menu_register->getModuleLink('cv').'/share/'.$hash_cv;
    }

	private function _getMedicalWorkflow()
	{
		return $this->workflow_db->getTrackerDatatableFor($_SESSION['personal']['address_book_id'], 'workflow_medical_tracker', array('notes', 'appointment_date_on'));
	}

	private function _getVaccineWorkflow()
	{
		return $this->workflow_db->getTrackerDatatableFor($_SESSION['personal']['address_book_id'], 'workflow_vaccination_tracker', array('notes', 'appointment_date_on'));
	}
	
	private function _getVisaWorkflow()
	{
		return $this->workflow_db->getTrackerDatatableFor($_SESSION['personal']['address_book_id'], 'workflow_visa_tracker', array('notes', 'send_notification_on', 'visa_type', 'country_code', 'docs_application_on'));
	}
	
	private function _getBgcWorkflow()
	{
		return $this->workflow_db->getTrackerDatatableFor($_SESSION['personal']['address_book_id'], 'workflow_bgc_tracker', array('notes', 'notification_on'));
	}
}
?>