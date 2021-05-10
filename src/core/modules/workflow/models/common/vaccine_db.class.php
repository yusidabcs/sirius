<?php
namespace core\modules\workflow\models\common;
/**
 * Final medical_tracker db class.
 *
 * @final
 * @package		medical_tracker
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 Jun 2020
 */

final class vaccine_db extends \core\app\classes\module_base\module_db {
    public $table = 'workflow_vaccination_tracker';

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    public function updateVaccineAppointmentDue()
    {
        $sql = " UPDATE 
                    `$this->table`
                    SET `status` = 'request_file'
                    WHERE
                        `status` = 'request_appointment_date'
                    AND
                        `appointment_date_on` != '0000-00-00 00:00:00'
                    AND
                        `appointment_date_on` <= NOW() ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $out = $stmt->affected_rows;

        if ($stmt->error) {
            $out = $stmt->error;
        }

        $stmt->close();

        return $out;

    }

    public function updateVaccinationTrackerLevel($workflow) {
        $tracker_db = new db();
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`deployment_date`,
                    `workflow_".$workflow."_tracker`.`rejected_on`,
                    `workflow_".$workflow."_tracker`.`appointment_date_on`,
                    `workflow_".$workflow."_tracker`.`file_uploaded_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                JOIN `job_application` on `workflow_".$workflow."_tracker`.`address_book_id` = `job_application`.`address_book_id`
                JOIN `interview_result_principal` on `job_application`.`job_application_id` = `interview_result_principal`.`job_application_id`
                
                JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status` AND `workflow_".$workflow."_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')
                AND `job_application`.`status` NOT IN ('not_hired','canceled','reapply','allocated')";

        $data = $this->db->query_array($sql);
        //var_dump($data);
        $tracker_db->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}
}