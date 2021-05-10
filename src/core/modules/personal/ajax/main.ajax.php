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
final class main extends \core\app\classes\module_base\module_ajax {
	
	public function run()
	{
		$this->authorizeAjax('main');
		
        if($this->option == 'getReferenceQuestion'){
            $out = [];
            $personal_db = new \core\modules\personal\models\common\db;
            if (isset($this->page_options[1]))
            {
                $group_name = $this->page_options[1];
                $out = $personal_db->getReferenceQuestions($group_name);

            }else{
                $out = 'Error group name not set';
            }
            if(!$out)
            {
                throw new \Exception('Major issue get verification list ');
            }
            return $this->response($out);
        }
		
		//exclude getCV & getCVPdf to be accessed beyond personal but only with security clearance
		if ($this->option == 'getCurriculumVitae' || $this->option == 'getCurriculumVitaePDF')
		{
			if (isset($this->page_options[1]))
			{	
				$address_book_id = $this->page_options[1];
			}else{
				$msg = "Error, address book id not set!";
				throw new \RuntimeException($msg);
			}

			$user_db = new \core\modules\user\models\common\user_db;
			$user_data = $user_db->getUserInfoFromAdressBookId($address_book_id);

			if (!empty($user_data))
			{
				//check if looking for another person data, check it's security level
				if ($user_data['user_id'] != $_SESSION['user_id'] )
				{
					$level_staff = $this->system_register->getSecurityLevel('STAFF');
					//check if security level != STAFF or higher
					if( !((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $level_staff) || ( isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] >= $level_staff)) )
					{
						$msg = 'You do not have the security level needed to access this data';
						throw new \RuntimeException($msg);
					}	
				}
			}else{
				$msg = "Error, no data found with that address book id (${address_book_id})!";
				throw new \RuntimeException($msg);
			}
		}else{
			if(empty($_SESSION['personal']['user_id']) || empty($_SESSION['personal']['address_book_id']))
			{
				$msg = "Wow you should never see this error ... very bad!";
				throw new \RuntimeException($msg);
			}
		}
		
		$out = null;
		
		switch($this->option) 
		{			
			case 'deletePassport':
			
				//the survey_master_id
				if(isset($_POST['passport_id']) && isset($_POST['passport_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					if($personal_common->deletePassport($_POST['passport_id'],$_SESSION['personal']['address_book_id'],$_POST['passport_current']))
					{
						$out['success'] = $_POST['passport_id'];
                        $out['message'] = 'Successfully delete the passport.';
					} else {
						throw new \Exception('Major issue deleting passport. PASSPORT_ID ('.$_POST['passport_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
			case 'previewPassport':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getPassport($_POST['passport_id']);
				break;
			case 'previewVisa':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getVisa($_POST['visa_id']);
				break;
			case 'previewOktb':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getOktb($_POST['oktb_id']);
				break;
			case 'previewIdCard':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getIdCard($_POST['id_card']);
				break;
			case 'previewIdCheck':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getIdCheck($_POST['id_check']);
				break;
			case 'previewPolice':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getPolice($_POST['police_id']);
				break;
			case 'previewMedical':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getMedical($_POST['medical_id']);
				break;
			case 'previewVaccination':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getVaccination($_POST['vaccine_id']);
				break;
			case 'previewSeaman':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getSeaman($_POST['sbk_id']);
				break;
			case 'previewStcw':
				$personal_common = new \core\modules\personal\models\common\db;

				if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
					$out['mode'] = 'personal';

					if ((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )) {
						$out['mode'] = 'recruitment';
					}
				}

				$out['data'] = $personal_common->getEducation($_POST['education_id']);
				break;
			case 'previewEnglish':
				$personal_common = new \core\modules\personal\models\common\db;

				$out['data'] = $personal_common->getEnglish($_POST['english_id']);
				break;
            case 'deletePolice':

                //the survey_master_id
                if(isset($_POST['police_id']) && isset($_POST['police_current']))
                {
                    $personal_common = new \core\modules\personal\models\common\common;
                    if($personal_common->deletePolice($_POST['police_id'],$_SESSION['personal']['address_book_id'],$_POST['police_current']))
                    {
                        $out['success'] = $_POST['police_id'];
                        $out['message'] = 'Successfully delete the police.';
                    } else {
                        throw new \Exception('Major issue deleting police. POLICE_ID ('.$_POST['police_id'].')');
                    }
                } else {
                    throw new \Exception('Well that will never do.');
                }

                break;
			
			case 'deleteVisa':
			
				//the survey_master_id
				if(isset($_POST['visa_id']) && isset($_POST['visa_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteVisa($_POST['visa_id'],$_SESSION['personal']['address_book_id'],$_POST['visa_current']))
					{
						$out['success'] = $_POST['visa_id'];
                        $out['message'] = 'Successfully delete visa.';
					} else {
						throw new \Exception('Major issue deleting visa. ID ('.$_POST['visa_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteIDCard':
			
				//the survey_master_id
				if( isset($_POST['idcard_id']) && isset($_POST['idcard_current']) && isset($_POST['idcard_back_current']) )
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteIDCard($_POST['idcard_id'],$_SESSION['personal']['address_book_id'],$_POST['idcard_current'],$_POST['idcard_back_current']))
					{
						$out['success'] = $_POST['idcard_id'];
                        $out['message'] = 'Successfully delete the idcard';
					} else {
						throw new \Exception('Major issue deleting idcard. ID_Card ('.$_POST['idcard_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteEnglish':
			
				//the survey_master_id
				if(isset($_POST['english_id']) && isset($_POST['english_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteEnglish($_POST['english_id'],$_SESSION['personal']['address_book_id'],$_POST['english_current']))
					{
						$out['success'] = $_POST['english_id'];
						$out['message'] = 'Successfully delete the test';
					} else {
						throw new \Exception('Major issue deleting english. ID ('.$_POST['english_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteEmployment':
			
				//the survey_master_id
				if(isset($_POST['employment_id']) && isset($_POST['employment_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteEmployment($_POST['employment_id'],$_SESSION['personal']['address_book_id'],$_POST['employment_current']))
					{
						$out['success'] = $_POST['employment_id'];
                        $out['message'] = 'Successfully delete employment';
					} else {
						throw new \Exception('Major issue deleting employment. ID ('.$_POST['employment_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteEducation':
			
				//the survey_master_id
				if(isset($_POST['education_id']) && isset($_POST['education_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteEducation($_POST['education_id'],$_SESSION['personal']['address_book_id'],$_POST['education_current']))
					{
						$out['success'] = $_POST['education_id'];
                        $out['message'] = 'Successfully delete education.';
					} else {
						throw new \Exception('Major issue deleting education. ID ('.$_POST['education_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteTattoo':
			
				//the survey_master_id
				if(isset($_POST['tattoo_id']) && isset($_POST['tattoo_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteTattoo($_POST['tattoo_id'],$_SESSION['personal']['address_book_id'],$_POST['tattoo_current']))
					{
						$out['success'] = $_POST['tattoo_id'];
                        $out['message'] = 'Successfully delete tattoo.';
					} else {
						throw new \Exception('Major issue deleting tattoo. ID ('.$_POST['tattoo_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteReference':
			
				//the survey_master_id
				if(isset($_POST['reference_id']) && isset($_POST['reference_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteReference($_POST['reference_id'],$_SESSION['personal']['address_book_id'],$_POST['reference_current']))
					{
						$out['success'] = $_POST['reference_id'];
                        $out['message'] = 'Successfully delete reference.';
					} else {
						throw new \Exception('Major issue deleting reference. ID ('.$_POST['reference_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
			
			case 'deleteMedical':
			
				//the survey_master_id
				if(isset($_POST['medical_id']) && isset($_POST['medical_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteMedical($_POST['medical_id'],$_SESSION['personal']['address_book_id'],$_POST['medical_current']))
					{
						$out['success'] = $_POST['medical_id'];
                        $out['message'] = 'Successfully delete medical.';
					} else {
						throw new \Exception('Major issue deleting medical. ID ('.$_POST['medical_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
				
			case 'deleteVaccination':
			
				//the survey_master_id
				if(isset($_POST['vaccination_id']) && isset($_POST['vaccination_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteVaccination($_POST['vaccination_id'],$_SESSION['personal']['address_book_id'],$_POST['vaccination_current']))
					{
						$out['success'] = $_POST['vaccination_id'];
                        $out['message'] = 'Successfully delete the vaccination.';
					} else {
						throw new \Exception('Major issue deleting vaccination. ID ('.$_POST['vaccination_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
			
			case 'deleteIdcheck':
			
				//the survey_master_id
				if(isset($_POST['idcheck_id']) && isset($_POST['idcheck_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteIdcheck($_POST['idcheck_id'],$_SESSION['personal']['address_book_id'],$_POST['idcheck_current']))
					{
						$out['success'] = $_POST['idcheck_id'];
                        $out['message'] = 'Successfully delete the idcheck.';

					} else {
						throw new \Exception('Major issue deleting idcheck. ID ('.$_POST['idcheck_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
				
				break;
			case 'requestVerification':
				$data = $_POST;
				$personal_common = new \core\modules\personal\models\common\common;
				
				if($personal_common->requestVerification($_SESSION['personal']['address_book_id'],$data))
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
			case 'getCurriculumVitae':
				
				$personal_db = new \core\modules\personal\models\common\db;

				if (isset($this->page_options[1]))
				{	
					$address_book_id = $this->page_options[1];
					$out = $personal_db->getCurriculumVitae($address_book_id);

				}else{
					$out = 'Error id not set';
				}

				if(!$out)
				{
					throw new \Exception('Major issue get Curriculum Vitae ');
				}

				break;	
			case 'getCurriculumVitaePDF':
			
				$personal_db = new \core\modules\personal\models\common\db;

				if (isset($this->page_options[1]))
				{	
					require DIR_LIB.'/fpdf/fpdf.php';
					$address_book_id = $this->page_options[1];
					$data = $personal_db->getCurriculumVitae($address_book_id);

					if (empty($data) )
						return;

					if (
						empty($data['name']) ||
						empty($data['dob']) ||
						empty($data['address']) ||
						empty($data['country']) ||
						empty($data['sex']) ||
						empty($data['hw']) ||
						empty($data['number']) ||
						empty($data['main_email'])
						)
						return "Incomplete Data";

					$pdf = new FPDF();
					$pdf->AddPage();
					$pdf->SetMargins(20, 20 ,20);
					$pdf->SetFont('Times','B',14);
					$pdf->MultiCell(0,7,'CURRICULUM VITAE',0,'C');

					$education_count = $data['education_count'];
					$employment_count = $data['employment_count'];

					$education_list = $data['education_list'];
					$employment_list = $data['employment_list'];
					
					unset($data['education_count']);
					unset($data['employment_count']);
					unset($data['education_list']);
					unset($data['employment_list']);

					$text = array(
						'name' => 'Name',
						'dob' => 'Date of Birth',
						'address' => 'Address',
						'country' => 'Nationality',
						'sex' => 'Sex',
						'hw' => 'Height/Weight',
						'number' => 'Phone Number',
						'main_email' => 'Email'
					);	

					$pdf->Ln();
					$pdf->SetFont('Times','B',12);
					$pdf->Cell(0,7,'Personal Data');
					$pdf->SetFont('Times','',12);
					$pdf->Ln();

					foreach ($data as $key => $row)
					{
						$pdf->Cell(40,7,$text[$key],0);
						$pdf->Cell(10,7,':',0,0,'R');

						if ( $key == 'address' )
						{
							$pdf->MultiCell(0,7, ucwords($row));
						}else{
							$pdf->Cell(0,7, ucwords($row));
							$pdf->Ln();
						}
					}
					
					if ($education_count > 0)
					{
						$pdf->Ln();
						$pdf->SetFont('Times','B',12);
						$pdf->Cell(0,7,'Education Background');
						$pdf->SetFont('Times','',12);
						$pdf->Ln();
						
						foreach($education_list as $education)
						{
							$pdf->Cell(40,7, ucwords($education['level']),0);
							$pdf->Cell(10,7,':',0,0,'R');
							$pdf->MultiCell(0,7, $education['from_date']. ' - ' .$education['to_date'].'   '.$education['institution']);
						}
					}

					if ($employment_count > 0)
					{
						$pdf->Ln();
						$pdf->SetFont('Times','B',12);
						$pdf->Cell(0,7,'Work Experience');
						$pdf->SetFont('Times','',12);
						$pdf->SetMargins(25,0,20);
						$pdf->Ln();
						
						foreach($employment_list as $employment)
						{
							$pdf->Cell(5,7,'-');
							$pdf->MultiCell(0,7, 'I have been working at '.$employment['employer'].', as a '.$employment['job_title'].' from '.$employment['from_date'].' until '.$employment['to_date']);		
						}
					}
					$pdf->Output('D','CV.pdf');
					return ;

				}else{
					$out = 'Error id not set';
				}


				break;	
			case 'getListJob':

				$job_db = new \core\modules\job\models\common\db();
				$out = $job_db->getJobSpeedyWithDemandDatatable();

				if(!$out)
				{
					throw new \Exception('Major issuegetListJob ');
				}

				break;

            case 'getSeamanBook':
                $address_book_id = $_SESSION['personal']['address_book_id'];
                $personal_db = new \core\modules\personal\models\common\db;
                $data = $personal_db->getSeamanBookList($address_book_id);
                return $this->response($data);
			break;
			case 'deleteSeaman':
				if(isset($_POST['seaman_id']) && isset($_POST['seaman_current']))
                {
                    $personal_common = new \core\modules\personal\models\common\common;
                    if($personal_common->deleteSeaman($_POST['seaman_id'],$_SESSION['personal']['address_book_id'],$_POST['seaman_current']))
                    {
                        $out['success'] = $_POST['seaman_id'];
                        $out['message'] = 'Successfully delete the seaman.';
                    } else {
                        throw new \Exception('Major issue deleting seaman. SEAMAN_ID ('.$_POST['seaman_id'].')');
                    }
                } else {
                    throw new \Exception('Well that will never do.');
                }
			break;

			case 'deleteFlight':
				if(isset($_POST['flight_number']) && isset($_POST['flight_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteFlight($_POST['flight_number'],$_SESSION['personal']['address_book_id'],$_POST['flight_current']))
					{
						$out['success'] = $_POST['flight_number'];
                        $out['message'] = 'Successfully delete flight.';
					} else {
						throw new \Exception('Major issue deleting flight. ID ('.$_POST['flight_number'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}
			break;

			case 'confirmedical':

				$personal_db = new \core\modules\personal\models\common\db();
				$workflow_db = new \core\modules\workflow\models\common\db();

				if ($workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id', $this->page_options[1])) {
					$workflow_db->updateTrackers('workflow_medical_tracker', $this->page_options[1], [
						'accepted_on' => date('Y-m-d H:i:s'),
						'accepted_by' => $_SESSION['user_id'],
						'notes' => 'Medical document accepted, wating for user upload again',
						'status' => 'accepted'
					]);
				}

				$update = $personal_db->updateMedicalStatus($this->page_options[2], $this->page_options[3], 'accepted');

				if ($update === 1) {
					$out['message'] = 'Medical document has been accepted';
				}else {
					$out['message'] = $update;
				}
			break;
			
			case 'rejectmedical':

				$personal_db = new \core\modules\personal\models\common\db();
				$workflow_db = new \core\modules\workflow\models\common\db();

				if ($workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id', $this->page_options[1])) {
					$workflow_db->updateTrackers('workflow_medical_tracker', $this->page_options[1], [
						'rejected_on' => date('Y-m-d H:i:s'),
						'rejected_by' => $_SESSION['user_id'],
						'notes' => 'Medical document rejected, wating for user upload again',
						'status' => 'rejected'
					]);
				}

				$update = $personal_db->updateMedicalStatus($this->page_options[2], $this->page_options[3], 'rejected');

				if ($update === 1) {
					$out['message'] = 'Medical document has been rejected';
				}else {
					$out['message'] = $update;
				}
			break;

			case 'oktb-file':
				$personal_db = new \core\modules\personal\models\common\db();
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$file_exists = $address_book_common->checkAddressBookFileExists($_SESSION['personal']['address_book_id'], 'oktb', $_POST['oktb_number']);

				if ($file_exists) {
					$filename = $this->_processOktbFile($_SESSION['personal']['address_book_id'], $file_exists['filename'], $_POST['file_base64'], $_POST['oktb_number']);
				} else {
					$filename = $this->_processOktbFile($_SESSION['personal']['address_book_id'], null, $_POST['file_base64'], $_POST['oktb_number']);
				}


				$out = [
					'message' => 'OKTB file has been uploaded',
					'filename' => $filename,
					'url' => 'http://' . SITE_WWW . '/ab/show/' . $filename
				];
			break;

			case 'deleteOktb':
				if(isset($_POST['oktb_id']) && isset($_POST['oktb_current']))
				{
					$personal_common = new \core\modules\personal\models\common\common;
					
					if($personal_common->deleteOktb($_POST['oktb_id'],$_SESSION['personal']['address_book_id'],$_POST['oktb_current']))
					{
						$out['success'] = $_POST['oktb_id'];
                        $out['message'] = 'Successfully delete oktb.';
					} else {
						throw new \Exception('Major issue deleting oktb. ID ('.$_POST['oktb_id'].')');
					}
				} else {
					throw new \Exception('Well that will never do.');
				}

			break;
			case 'listJob':
				$job_db =  new \core\modules\job\models\common\db;
				$out['job_category'] = $job_db->getAllJobCategory();
				$out['job_speedy'] = $job_db->getAllJobSpeedy();
				$out['status']='ok';
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

	private function _processOktbFile($address_book_id,$oktb_current,$oktb_base64,$oktb_id)
	{
		$filename = 'none';
		
		//decode
        $data = $oktb_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($oktb_current)
		{
			//delete the current oktb image
			$address_book_common->deleteAddressBookFile($oktb_current,$address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'oktb',0,$oktb_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in oktb for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'oktb',0,$oktb_id);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in oktb for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} 
		
		return $filename;
	}
	
}
?>