<?php
namespace core\modules\workflow;

/**
 * Final controller class.
 * 
 * Controller for the workflow module
 *
 * @final
 * @extends		module_controller
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
class scheduler {
	
	public function __construct()
	{
        
    }
    
    public function runRecruitmentTrackerGroup() {
        $this->_updateRequestVerificationTracker();
        $this->_sendEmailRequestVerificationTracker();

        $this->_updatePersonalReferenceTracker();
        $this->_sendEmailPersonalReferenceTracker();

        $this->_updateProfesionalReferenceTracker();
        $this->_sendEmailProfesionalReferenceTracker();

        $this->_updateEnglishTestTracker();
        $this->_sendEmailEnglishTestTracker();

        $this->_updatePremiumServiceTracker();
        $this->_sendEmailPremiumServiceTracker();
    }

    public function runInterviewTrackerGroup() {
        $this->_updateInterviewReadyTracker();
        $this->_sendEmailInterviewReadyTracker();
    }

    public function runDeploymentTrackerGroup() {
        $this->_updateVisaTracker();
        $this->_sendEmailVisaTracker();

        /*$this->_updateOktbTracker();
        $this->_sendEmailOktbTracker();*/

        $this->_updateStcwTracker();
        $this->_sendEmailStcwTracker();

        $this->_updateMedicalTracker();
        $this->_sendEmailMedicalTracker();

        $this->_updateVaccinationTracker();
        $this->_sendEmailVaccinationTracker();
        
        $this->_updateFlightTracker();
        $this->_sendEmailFlightTracker();
        
        $this->_updateSeamanTracker();
        $this->_sendEmailSeamanTracker();

        $this->_updatePoliceTracker();
        $this->_sendEmailPoliceTracker();

        $this->_updateTravelpackTracker();
        $this->_sendEmailTravelpackTracker();
    }

    public function runOtherTrackerGroup() {
        $this->_updateSecurityCheckTracker();
        $this->_sendEmailSecurityCheckTracker();

        $this->_updatePsfTracker();
        $this->_sendEmailPsfTracker();

        $this->_updatePrincipalTracker();
        $this->_sendEmailPrincipalTracker();

        $this->_updateEducationTracker();
        $this->_sendEmailEducationTracker();
    }

    private function _updateRequestVerificationTracker(){
        $db = new \core\modules\workflow\models\common\recruitment_db();
        $db->updateAllTrackerLevel();
    }

    private function _sendEmailRequestVerificationTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('recruitment','Personal Verification');
    
    }

    private function _updatePersonalReferenceTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateReferenceTrackerLevel('personal_reference');
    }

    private function _sendEmailPersonalReferenceTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('personal_reference','Personal Reference');
    }

    private function _updateProfesionalReferenceTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateReferenceTrackerLevel('profesional_reference');
    }

    private function _sendEmailProfesionalReferenceTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('profesional_reference','Profesional Reference');
    }

    private function _updateEnglishTestTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateEnglistTestTrackerLevel('english_test');
    }

    private function _sendEmailEnglishTestTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('english_test','English Test');
    }

    private function _updatePremiumServiceTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updatePremiumServiceTrackerLevel('premium_service');
    }

    private function _sendEmailPremiumServiceTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('premium_service','Premium Service');
    }

    private function _updateInterviewReadyTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateInterviewReadyTrackerLevel('interview_ready');
    }

    private function _sendEmailInterviewReadyTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('interview_ready','Interview Ready');
    }

    private function _updateSecurityCheckTracker(){
        $db = new \core\modules\workflow\models\common\security_check_db();
        $db->updateTrackerLevel();
    }

    private function _sendEmailSecurityCheckTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendWorkflowSecurityTrackerReport();
    }

    private function _updatePsfTracker(){
        $db = new \core\modules\workflow\models\common\psf_tracker_db();
        $db->updateTrackerLevel();
    }

    private function _sendEmailPsfTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendWorkflowPsfTrackerReport();
    }

    private function _updatePrincipalTracker(){
        $db = new \core\modules\workflow\models\common\principal_tracker_db();
        $db->updateTrackerLevel();
    }

    private function _sendEmailPrincipalTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendWorkflowPrincipalTrackerReport();
    }

    private function _updateTravelpackTracker(){
        $db = new \core\modules\workflow\models\common\travelpack_db();
        $db->updateTrackerLevel();
    }

    private function _sendEmailTravelpackTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendWorkflowTravelpackTrackerReport();
    }

    private function _updatePoliceTracker(){
        $db = new \core\modules\workflow\models\common\police_check_db();
        $db->updatePoliceTrackerLevel('police');
    }

    private function _sendEmailPoliceTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('police','Police Check');
    }

    private function _updateMedicalTracker(){
        $db = new \core\modules\workflow\models\common\medical_db();
        $db->updateMedicalTrackerLevel('medical');
    }

    private function _sendEmailMedicalTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('medical','Medical');
    }

    private function _updateVisaTracker(){
        $db = new \core\modules\workflow\models\common\visa_db();
        $db->updateVisaTrackerLevel('visa');
    }

    private function _sendEmailVisaTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('visa','Visa');
    }

    /*private function _updateOktbTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateOktbTrackerLevel('oktb');
    }

    private function _sendEmailOktbTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('oktb','OKTB');
    }*/

    private function _updateStcwTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateStcwTrackerLevel('stcw');
    }

    private function _sendEmailStcwTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('stcw','STCW');
    }

    private function _updateVaccinationTracker(){
        $db = new \core\modules\workflow\models\common\vaccine_db();
        $db->updateVaccinationTrackerLevel('vaccination');
    }

    private function _sendEmailVaccinationTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('vaccination','Vaccination');
    }

    private function _updateFlightTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateFlightTrackerLevel('flight');
    }

    private function _sendEmailFlightTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('flight','Flight');
    }

    private function _updateSeamanTracker(){
        $db = new \core\modules\workflow\models\common\db();
        $db->updateSeamanTrackerLevel('seaman');
    }

    private function _sendEmailSeamanTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendTrackerReport('seaman','Seaman');
    }

    private function _updateEducationTracker(){
        $db = new \core\modules\workflow\models\common\education_db();
        $db->updateEducationTrackerLevel('education');
    }

    private function _sendEmailEducationTracker(){
        $db = new \core\modules\workflow\models\common\common();
        $db->sendWorkflowEducationTrackerReport();
    }

    private function _deploymentQueue()
    {
        $db = new \core\modules\workflow\models\common\common();
        $db->triggerQueue();
    }
}

?>