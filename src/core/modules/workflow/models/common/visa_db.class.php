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

final class visa_db extends \core\app\classes\module_base\module_db {
    public $table = 'workflow_medical_tracker';

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    public function updateVisaInterviewOverdue($address_book_id)
    {
        $sql = " UPDATE 
                    `workflow_visa_tracker`
                    SET `status` = upload_visa
                    WHERE
                        `address_book_id` = ?
                    AND
                        `status` = 'interview'";

        $stmt = $this->db->prepare($sql);
        
        if (typeof($address_book_id) === 'array') {
            $id = $address_book_id[0];
            $stmt->bind_param('i', $id);
            for ($i=1; $i < count($address_book_id); $i++) { 
                $id = $address_book_id;
                $stmt->execute();
            }
        } else {
            $stmt->bind_param('i', $address_book_id);
            $stmt->execute();
        }

        $out = $stmt->affected_rows;

        if ($stmt->error) {
            $out = $stmt->error;
        }

        $stmt->close();

        return $out;

    }

    public function updateVisaTrackerLevel($workflow) {
        $tracker_db = new db();
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`deployment_date`,
                    `workflow_".$workflow."_tracker`.`rejected_on`,
                    `workflow_".$workflow."_tracker`.`send_notification_on`,
                    `workflow_".$workflow."_tracker`.`register_visa_on`,
                    `workflow_".$workflow."_tracker`.`docs_application_on`,
                    `workflow_".$workflow."_tracker`.`docs_application_date`,
                    `workflow_".$workflow."_tracker`.`docs_rejected_on`,
                    `workflow_".$workflow."_tracker`.`upload_visa_on`,
                    `workflow_".$workflow."_tracker`.`interview_date`,
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

                JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status` AND `workflow_".$workflow."_workflow`.`visa_type` = `workflow_".$workflow."_tracker`.`visa_type`
                
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('not_active', 'accepted')
                AND `job_application`.`status` NOT IN ('not_hired','canceled','reapply','allocated')";

        $data = $this->db->query_array($sql);
        $tracker_db->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}
}