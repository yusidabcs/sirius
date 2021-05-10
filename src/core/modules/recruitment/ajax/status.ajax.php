<?php
namespace core\modules\recruitment\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class status extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
		$this->authorizeAjax('status');	
        $out = null;
		
		switch($this->option) 
		{		
			case 'requestVerification':

				$personal_common = new \core\modules\personal\models\common\common;
				
				if($personal_common->requestVerification($_SESSION['personal']['address_book_id']))
				{
					$out['success'] = 'true';
					$out['message'] = 'Successfully submit verification request.';

				} else {
					throw new \Exception('Major issue submit verification request. ID ('.$_SESSION['personal']['address_book_id'].')');
				}
				
				break;	
			case 'getVerificationList':

				$personal_db = new \core\modules\personal\models\common\db;
				if (isset($this->page_options[1]))
				{	
					$out = $personal_db->getVerificationList($this->page_options[1]);
				}else{
					$out = $personal_db->getVerificationList();
				}
				if(!$out)
				{
					throw new \Exception('Major issue get verification list ');
				}
				
				break;				

            case 'changeVerification':

                $status ='';
                $verification_info ='';
                $id ='';
                
                if ( isset($_POST['dt_id']) ){
                    $id = $_POST['dt_id'];
                }
                if ( isset($_POST['dt_status']) ){
                    $status = $_POST['dt_status'];
                }
                if ( isset($_POST['dt_verification_info']) ){
                    $verification_info = $_POST['dt_verification_info'];
                }
                if (($id != '') && ($status != '')){
                    $personal_db = new \core\modules\personal\models\common\db;
                    $address_book_db = new \core\modules\address_book\models\common\address_book_common_obj;
                    //insert verification status
                    if ($status === 'verified') {
                        $this->_initVar();
                        $personal_complete = $this->_checkVerified($id);

                        if (!$personal_complete) {
                            return $this->response([
                                'message' => 'Please complete candidate personal data before verified',
                                'status' => 'warning'
                            ], 406);
                        }
                    }

					$out = $personal_db->insertVerification($id, $status, $verification_info);
					if (!$personal_db->checkPersonal($id)) {
						$insert_personal = $personal_db->insertPersonal($id, $status);
					} else {
						$update_personal = $personal_db->updateVerificationStatus($id, $status);
					}

                    if ($status === 'verified' || $status === 'rejected' || $status === 'process') {
                        $personal_common = new \core\modules\personal\models\common\common;
                        $address_book = $address_book_db->getAddressBookMainDetails($id);

                        $site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

                        $generic_obj = \core\app\classes\generic\generic::getInstance();
                        $fullname = $generic_obj->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                        $to_name  = $address_book['title'].' '.$fullname;
                        
                        $personal_common->sendVerifiedNotificationEmail($status, $to_name,$address_book['main_email'], $site_a['SITE_EMAIL_NAME'], $site_a['SITE_EMAIL_ADD'], $verification_info);


                        //update recruitment tracker
                        if($status === 'verified'){
                            $status = 'accepted';
                        } else if($status ==='process') {
                            $status = 'process';
                        }
                        $recruitment_workflow_db = new \core\modules\workflow\models\common\recruitment_db();
                        $recruitment_workflow_db->updateTrackerStatus($id, $status);
                    }

                    
                }
                if(!$out)
                {
                    throw new \Exception('Major issue change verification ('.$status .'-'.$verification_info. '-'.$id.')');
                }
                
                break;					

            case 'getVerificationHistory':

                $id = '';

                if ( isset($_POST['dt_id']) )
                {
                    $id = $_POST['dt_id'];
                }
                if ( $id != '' )
                {
                    $personal_db = new \core\modules\personal\models\common\db;
                    $out = $personal_db->getVerificationHistory($id);	
                }
                
                break;					

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);				
		}
						
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}	
    }

    private function _initVar()
    {
        $this->core_db = new \core\app\classes\core_db\core_db;
        $this->personal_db = new \core\modules\personal\models\common\db;
        $this->generic = \core\app\classes\generic\generic::getInstance();
        $this->countryCodes = $this->core_db->getAllCountryCodes();
        $this->countryDialCodes = $this->core_db->getAllDialCodes();
    }

    private function _checkVerified($address_book_id)
	{
		$verified = true;
		$total_requirement = 6;

        //check if already in database
        $this->general = $this->_getGeneral($address_book_id);

		$data = $this->personal_db->checkVerification($address_book_id);
		$info = [];
		
		if ( empty($data) || $data['status'] !== 'verified') //if empty, check from existing data for each listing 
		{ 
			$this->idcardList = $this->_getIdcardList($address_book_id);
			if (empty($this->idcardList))
			{
				return false;
			}
			if (empty($this->general))
			{
				return false;
			}else{
                if ($this->general['job_hunting'] == 'no'){
                    return false;
                }
				if ($this->general['seafarer'] || $this->general['migration']) {

                    $this->checklist = $this->_getChecklistInfo($address_book_id);
					if ($this->checklist['character']['result'] == 'NOT STARTED')
					{
						return false;
                    }
                    
					if ($this->checklist['health']['result'] == 'NOT STARTED')
					{
						return false;
                    }
                    
                    $this->passportList = $this->_getPassportList($address_book_id);
					if (($this->general['passport']) && (empty($this->passportList)))
					{
						return false;
					}
				}
				// check if have tattoo
				if ($this->general['tattoo'])
				{
                    $this->tattooList = $this->_getTattooList($address_book_id);
					if (empty($this->tattooList))
					{
						return false;
					}
				}
            }

            $this->language = $this->_getLanguage($address_book_id);
			if (empty($this->language))
			{
				return false;
            }
            
            $this->educationList = $this->_getEducationList($address_book_id);
			if (empty($this->educationList))
			{
				return false;
            }
            
            $this->referenceList = $this->_getReferenceList($address_book_id);
			if (empty($this->referenceList['personal']))
			{
				return false;
            }
            
        }
			
		return true;
		
    }

    private function _getTattooList($address_book_id)
	{
		$out = array();
		
		$info = $this->personal_db->getTattooList($address_book_id);

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

    private function _getIdcardList($address_book_id)
	{
		$out = array();
		
		$info = $this->personal_db->getIdcardList($address_book_id);
		
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

    private function _getReferenceList($address_book_id)
	{
		$out = array('work' => array(), 'personal' => array());
		
		$info = $this->personal_db->getReferenceList($address_book_id);
		
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

    private function _getEducationList($address_book_id)
	{
		$out = array();
		
		$info = $this->personal_db->getEducationList($address_book_id);

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

    private function _getLanguage($address_book_id)
	{
		$out = $this->personal_db->getLanguage($address_book_id);
					
		return $out;
	}
    
    private function _getChecklistInfo($address_book_id)
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
			
			$info = $this->personal_db->getChecklist($address_book_id,$type);
			
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
    
    private function _getGeneral($address_book_id)
    {
        $out = array();
        $info = $this->personal_db->getGeneral($address_book_id);
		
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

    private function _getPassportList($address_book_id)
 	{
 		$out = array();
 		
 		$info = $this->personal_db->getPassportList($address_book_id);
 		
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
}
?>