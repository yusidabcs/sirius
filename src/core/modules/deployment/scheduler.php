<?php
namespace core\modules\deployment;

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
        $this->_deploymentQueue();
        $this->_vaccinationAndMedicalTracker();
        $this->_deploymentStatus();

		return;
    }

    private function _deploymentQueue()
    {
        $db = new \core\modules\workflow\models\common\common();
        $db->triggerQueue();
    }

    private function _vaccinationAndMedicalTracker()
    {
        $db = new \core\modules\workflow\models\common\common();

        $db->triggerMedical();
        $db->triggerVaccination();
    }

    private function _deploymentStatus()
    {
        $db = new \core\modules\deployment\models\common\db();

        $db->updateDeploymentStatusOverdue();
    }
}

?>