<?php
namespace core\modules\workflow\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 13 Jul 2020
 */
final class job_application_tracker extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$out = null;
		
        $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
        $workflow_db = new \core\modules\workflow\models\common\db();
		switch($this->option) 
		{
            case 'list-personal-reference':
                
                if($this->useEntity){
                    $out = $workflow_db->getReferenceTrackerDatatable('workflow_personal_reference_tracker',[],$this->entity['address_book_ent_id'],'workflow_personal_reference_workflow');
                }
                else{
                    $out = $workflow_db->getReferenceTrackerDatatable('workflow_personal_reference_tracker',[],false,'workflow_personal_reference_workflow');
                }
            break;
            case 'workflow-personal-reference':

                if($this->useEntity){
                    $out = $workflow_db->getTrackerDatatable('workflow_personal_reference_tracker',[],$this->entity['address_book_ent_id']);
                }
                else{
                    $out = $workflow_db->getTrackerDatatable('workflow_personal_reference_tracker');
                }
                
            break;
            
            case 'list-profesional-reference':
                if($this->useEntity){
                    $out = $workflow_db->getReferenceTrackerDatatable('workflow_profesional_reference_tracker',[],$this->entity['address_book_ent_id'],'workflow_profesional_reference_workflow');
                }
                else{
                    $out = $workflow_db->getReferenceTrackerDatatable('workflow_profesional_reference_tracker',[],false,'workflow_profesional_reference_workflow');
                }
                
            break;
            case 'workflow-profesional-reference':
                if($this->useEntity){
                    $out = $workflow_db->getTrackerDatatable('workflow_profesional_reference_tracker',[],$this->entity['address_book_ent_id']);
                }
                else{
                    $out = $workflow_db->getTrackerDatatable('workflow_profesional_reference_tracker');
                }
            break;

            case 'list-english-test':
                if($this->useEntity){
                    $out = $job_application_db->getWorkflowDatatable('workflow_english_test_tracker',$this->entity['address_book_ent_id'],'workflow_english_test_workflow');
                }
                else{
                    $out = $job_application_db->getWorkflowDatatable('workflow_english_test_tracker',false,'workflow_english_test_workflow');
                }
            break;
            case 'workflow-english':
                if($this->useEntity){
                    $out = $workflow_db->getTrackerDatatable('workflow_english_test_tracker',[],$this->entity['address_book_ent_id']);
                }
                else{
                    $out = $workflow_db->getTrackerDatatable('workflow_english_test_tracker');
                }
            break;

            case 'list-premium-service':
                if($this->useEntity){
                    $out = $job_application_db->getWorkflowDatatable('workflow_premium_service_tracker',$this->entity['address_book_ent_id'],'workflow_premium_service_workflow');
                }
                else{
                    $out = $job_application_db->getWorkflowDatatable('workflow_premium_service_tracker',false,'workflow_premium_service_workflow');
                }
            break;
            case 'workflow-premium-service':
                if($this->useEntity){
                    $out = $workflow_db->getTrackerDatatable('workflow_premium_service_tracker',[],$this->entity['address_book_ent_id']);
                }
                else{
                    $out = $workflow_db->getTrackerDatatable('workflow_premium_service_tracker');
                }
            break;

            case 'list-interview':
                if($this->useEntity){
                    $out = $job_application_db->getWorkflowDatatable('workflow_interview_ready_tracker',$this->entity['address_book_ent_id'],'workflow_interview_ready_workflow');
                }
                else{
                    $out = $job_application_db->getWorkflowDatatable('workflow_interview_ready_tracker',false,'workflow_interview_ready_workflow');
                }
            break;
            case 'workflow-interview':
                if($this->useEntity){
                    $out = $workflow_db->getTrackerDatatable('workflow_interview_ready_tracker',[],$this->entity['address_book_ent_id']);
                }
                else{
                    $out = $workflow_db->getTrackerDatatable('workflow_interview_ready_tracker');
                }
            break;

            case 'list-stcw':
                if($this->useEntity){
                    $out = $job_application_db->getWorkflowDatatable('workflow_stcw_tracker',$this->entity['address_book_ent_id']);
                }
                else{
                    $out = $job_application_db->getWorkflowDatatable('workflow_stcw_tracker');
                }
            break;

            case 'total-profesional-reference':
                $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
                $out = $job_application_db->getTotalTrackerByLevel('workflow_profesional_reference_tracker', (!empty($this->entity['address_book_ent_id'])) ?  $this->entity['address_book_ent_id']:false);
            break;

            case 'total-english-test':
                $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
                $out = $job_application_db->getTotalTrackerByLevel('workflow_english_test_tracker', (!empty($this->entity['address_book_ent_id'])) ?  $this->entity['address_book_ent_id']:false);
            break;

            case 'total-personal-reference':
                $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
                $out = $job_application_db->getTotalTrackerByLevel('workflow_personal_reference_tracker', (!empty($this->entity['address_book_ent_id'])) ?  $this->entity['address_book_ent_id']:false);
            break;

            case 'total-premium-service':
                $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
                $out = $job_application_db->getTotalTrackerByLevel('workflow_premium_service_tracker', (!empty($this->entity['address_book_ent_id'])) ?  $this->entity['address_book_ent_id']:false);
            break;

            case 'total-interview':
                $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
                $out = $job_application_db->getTotalTrackerByLevel('workflow_interview_ready_tracker', (!empty($this->entity['address_book_ent_id'])) ?  $this->entity['address_book_ent_id']:false);
            break;

            case 'total-stcw':
                $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
                $out = $job_application_db->getTotalTrackerByLevel('workflow_stcw_tracker', (!empty($this->entity['address_book_ent_id'])) ?  $this->entity['address_book_ent_id']:false);
            break;

            case 'confirm-english':	
                
                if (!$this->system_register->getModuleIsInstalled('workflow')) {
                    header('Content-Type: application/json; charset=utf-8');
			        return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                }
                
                $personal_db = new \core\modules\personal\models\common\db;
                $workflow_db = new \core\modules\workflow\models\common\db();
                $address_book_id = $personal_db->getEnglish($this->page_options[1])['address_book_id'];
                
                if ($workflow_db->getActiveWorkflow('workflow_english_test_tracker', 'address_book_id', $address_book_id)) {
                    # code...
                    $workflow = $workflow_db->updateTrackers('workflow_english_test_tracker', $address_book_id, [
                        'accepted_on' => date('Y-m-d H:i:s'),
                        'accepted_by' => $_SESSION['user_id'],
                        'level' => 1,
                        'status' => 'accepted',
                        'notes' => 'All goods'
                    ]);

                    if ($workflow !== 1) {
                        $out = [
                            'message' => 'Error updating tracker '.$workflow,
                            'status' => 'error'
                        ];
                        header('Content-Type: application/json; charset=utf-8');
                        return json_encode($out); 
                    }
                }

                $personal_db->validateEnglish($this->page_options[1]);

                $out = [
                    'message' => 'English test has been accepted!',
                    'status' => 'success'
                ];

                break;
            case 'reject-english':
                
                if (!$this->system_register->getModuleIsInstalled('workflow')) {
                    header('Content-Type: application/json; charset=utf-8');
			        return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                }

                $personal_db = new \core\modules\personal\models\common\db;
                $workflow_db = new \core\modules\workflow\models\common\db();
                $english_test = $personal_db->getEnglish($this->page_options[1]);
                
                if ($workflow_db->getActiveWorkflow('workflow_english_test_tracker', 'address_book_id',  $english_test['address_book_id'])) {
                    # code...

                    $workflow = $workflow_db->updateTrackers('workflow_english_test_tracker', $english_test['address_book_id'], [
                        'rejected_on' => date('Y-m-d H:i:s'),
                        'rejected_by' => $_SESSION['user_id'],
                        'level' => 1,
                        'status' => 'rejected',
                        'notes' => 'english test rejected, trying to request file again'
                    ]);
    
                    if ($workflow !== 1) {
                        $out = [
                            'message' => 'Error updating tracker '.$workflow,
                            'status' => 'error'
                        ];
                        header('Content-Type: application/json; charset=utf-8');
                        return json_encode($out); 
                    }
                }


                $personal_db->deleteEnglish($english_test['english_id'], $english_test['address_book_id']);

                $out = [
                    'message' => 'English test has been rejected!',
                    'status' => 'success'
                ];
                break;
                case 'confirm-stcw':	
                
                    if (!$this->system_register->getModuleIsInstalled('workflow')) {
                        header('Content-Type: application/json; charset=utf-8');
                        return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                    }
                    
                    $personal_db = new \core\modules\personal\models\common\db;
                    $workflow_db = new \core\modules\workflow\models\common\db();
                    $address_book_id = $personal_db->getEducation($this->page_options[1])['address_book_id'];
                    
                    if ($workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id', $address_book_id)) {
                        # code...
                        $workflow = $workflow_db->updateTrackers('workflow_stcw_tracker', $address_book_id, [
                            'accepted_on' => date('Y-m-d H:i:s'),
                            'accepted_by' => $_SESSION['user_id'],
                            'status' => 'accepted',
                            'notes' => 'All goods'
                        ]);
    
                        if ($workflow !== 1) {
                            $out = [
                                'message' => 'Error updating tracker '.$workflow,
                                'status' => 'error'
                            ];
                            header('Content-Type: application/json; charset=utf-8');
                            return json_encode($out); 
                        }
                    }
    
                    $personal_db->validateStcw($this->page_options[1]);
    
                    $out = [
                        'message' => 'STCW document has been accepted!',
                        'status' => 'success'
                    ];
    
                    break;

                    case 'reject-stcw':
                
                        if (!$this->system_register->getModuleIsInstalled('workflow')) {
                            header('Content-Type: application/json; charset=utf-8');
                            return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                        }
        
                        $personal_db = new \core\modules\personal\models\common\db;
                        $workflow_db = new \core\modules\workflow\models\common\db();
                        $education = $personal_db->getEducation($this->page_options[1]);
                        
                        if ($workflow_db->getActiveWorkflow('workflow_stcw_tracker', 'address_book_id',  $education['address_book_id'])) {
                            # code...
        
                            $workflow = $workflow_db->updateTrackers('workflow_stcw_tracker', $education['address_book_id'], [
                                'rejected_on' => date('Y-m-d H:i:s'),
                                'rejected_by' => $_SESSION['user_id'],
                                'status' => 'rejected',
                                'notes' => 'STCW document rejected, trying to request file again'
                            ]);
            
                            if ($workflow !== 1) {
                                $out = [
                                    'message' => 'Error updating tracker '.$workflow,
                                    'status' => 'error'
                                ];
                                header('Content-Type: application/json; charset=utf-8');
                                return json_encode($out); 
                            }
                        }
        
        
                        $personal_db->deleteEducation($education['education_id'], $education['address_book_id']);
        
                        $out = [
                            'message' => 'STCW document has been rejected!',
                            'status' => 'success'
                        ];
                    break;
                    case 'confirm-medical':	
                
                        if (!$this->system_register->getModuleIsInstalled('workflow')) {
                            header('Content-Type: application/json; charset=utf-8');
                            return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                        }
                        
                        $personal_db = new \core\modules\personal\models\common\db;
                        $workflow_db = new \core\modules\workflow\models\common\db();
                        $address_book_id = $personal_db->getMedical($this->page_options[1])['address_book_id'];
                        
                        if ($workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $address_book_id)) {
                            # code...
                            $workflow = $workflow_db->updateTrackers('workflow_medical_tracker', $address_book_id, [
                                'accepted_on' => date('Y-m-d H:i:s'),
                                'accepted_by' => $_SESSION['user_id'],
                                'status' => 'accepted',
                                'notes' => 'All goods'
                            ]);
        
                            if ($workflow !== 1) {
                                $out = [
                                    'message' => 'Error updating tracker '.$workflow,
                                    'status' => 'error'
                                ];
                                header('Content-Type: application/json; charset=utf-8');
                                return json_encode($out); 
                            }
                        }
        
                        $personal_db->validateMedical($this->page_options[1]);
        
                        $out = [
                            'message' => 'Medical has been accepted!',
                            'status' => 'success'
                        ];
        
                    break;
                    case 'reject-medical':	
                
                        if (!$this->system_register->getModuleIsInstalled('workflow')) {
                            header('Content-Type: application/json; charset=utf-8');
                            return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                        }
                        
                        $personal_db = new \core\modules\personal\models\common\db;
                        $workflow_db = new \core\modules\workflow\models\common\db();
                        $address_book_id = $personal_db->getMedical($this->page_options[1])['address_book_id'];
                        
                        if ($workflow_db->getActiveWorkflow('workflow_medical_tracker', 'address_book_id', $address_book_id)) {
                            # code...
                            $workflow = $workflow_db->updateTrackers('workflow_medical_tracker', $address_book_id, [
                                'accepted_on' => date('Y-m-d H:i:s'),
                                'accepted_by' => $_SESSION['user_id'],
                                'status' => 'rejected',
                                'level' => 1,
                                'notes' => 'Medical rejected, trying to request file again'
                            ]);
        
                            if ($workflow !== 1) {
                                $out = [
                                    'message' => 'Error updating tracker '.$workflow,
                                    'status' => 'error'
                                ];
                                header('Content-Type: application/json; charset=utf-8');
                                return json_encode($out); 
                            }
                        }
        
                        $personal_db->deleteMedical($this->page_options[1],$address_book_id);
        
                        $out = [
                            'message' => 'Medical has been rejected!',
                            'status' => 'success'
                        ];
        
                    break;
                    case 'confirm-vaccination':	
                
                        if (!$this->system_register->getModuleIsInstalled('workflow')) {
                            header('Content-Type: application/json; charset=utf-8');
                            return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                        }
                        
                        $personal_db = new \core\modules\personal\models\common\db;
                        $workflow_db = new \core\modules\workflow\models\common\db();
                        $address_book_id = $personal_db->getVaccination($this->page_options[1])['address_book_id'];
                        
                        if ($workflow_db->getActiveWorkflow('workflow_vaccination_tracker', 'address_book_id', $address_book_id)) {
                            # code...
                            $workflow = $workflow_db->updateTrackers('workflow_vaccination_tracker', $address_book_id, [
                                'accepted_on' => date('Y-m-d H:i:s'),
                                'accepted_by' => $_SESSION['user_id'],
                                'status' => 'accepted',
                                'notes' => 'All goods'
                            ]);
        
                            if ($workflow !== 1) {
                                $out = [
                                    'message' => 'Error updating tracker '.$workflow,
                                    'status' => 'error'
                                ];
                                header('Content-Type: application/json; charset=utf-8');
                                return json_encode($out); 
                            }
                        }
        
                        $personal_db->validateVaccination($this->page_options[1]);
        
                        $out = [
                            'message' => 'Vaccination has been accepted!',
                            'status' => 'success'
                        ];
        
                    break;
                    case 'reject-vaccination':	
                
                        if (!$this->system_register->getModuleIsInstalled('workflow')) {
                            header('Content-Type: application/json; charset=utf-8');
                            return json_encode(array('message' => 'Module workflow not installed, please contact administration')); 
                        }
                        
                        $personal_db = new \core\modules\personal\models\common\db;
                        $workflow_db = new \core\modules\workflow\models\common\db();
                        $address_book_id = $personal_db->getVaccination($this->page_options[1])['address_book_id'];
                        
                        if ($workflow_db->getActiveWorkflow('workflow_vaccination_tracker', 'address_book_id', $address_book_id)) {
                            # code...
                            $workflow = $workflow_db->updateTrackers('workflow_vaccination_tracker', $address_book_id, [
                                'accepted_on' => date('Y-m-d H:i:s'),
                                'accepted_by' => $_SESSION['user_id'],
                                'status' => 'rejected',
                                'level' => 1,
                                'notes' => 'Vaccination rejected, trying to request file again'
                            ]);
        
                            if ($workflow !== 1) {
                                $out = [
                                    'message' => 'Error updating tracker '.$workflow,
                                    'status' => 'error'
                                ];
                                header('Content-Type: application/json; charset=utf-8');
                                return json_encode($out); 
                            }
                        }
        
                        $personal_db->deleteVaccination($this->page_options[1],$address_book_id);
        
                        $out = [
                            'message' => 'Vaccination has been rejected!',
                            'status' => 'success'
                        ];
        
                    break;
		
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
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