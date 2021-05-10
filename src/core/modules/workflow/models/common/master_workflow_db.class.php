<?php

namespace core\modules\workflow\models\common;

/**
 * Final master_workflow db class.
 *
 * @final
 * @package		master_workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 Jun 2020
 */

 class master_workflow_db extends \core\app\classes\module_base\module_db {
     
    public function __construct()
    {
         parent::__construct('local');

         return;
    }

    public function checkWorkflow($principal_code, $type, $step)
    {
         $out = false;

         $sql = 'SELECT * 
                    FROM 
                        `workflow_master_workflow`
                    WHERE
                        `principal_code` = ?
                    AND
                        `type` = ?
                    AND
                        `step` = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $principal_code, $type, $step);
        $stmt->bind_result($_principal_code, $_step, $_type, $reference_direction, $reference_milestone, $reference_table, $days);

        $stmt->execute();

        if ($stmt->num_rows) {
            $stmt->close();

            return $out;
        }

        while ($stmt->fetch()) {
            $out[] = array(
                'principal_code' => $_principal_code,
                'step' => $_step,
                'type' => $_type,
                'reference_direction' => $reference_direction,
                'reference_milestone' => $reference_milestone,
                'reference_table' => $reference_table,
                'days' => $days
            );
        }

        $stmt->close();

        return $out;
    }

    public function getFromReferenceTable($reference_table, $primary_field, $primary_value)
    {
        $out = false;

        $sql = "SELECT * 
                    FROM 
                        $reference_table
                    WHERE
                        `$primary_field` = '$primary_value'
                    AND
                        `status` NOT IN ('interview','hired','not_hired','allocated')
                    LIMIT 1";
                    

        $stmt = $this->db->query($sql);

        if ($data = $stmt->fetch_assoc()) {
            $out = $data;
        }

        $stmt->close();

        return $out;
    }

    public function getDeploymentWorkflowByStep($step)
    {
        $out = [];

        $sql = "SELECT 
                    *
                FROM 
                    `workflow_master_workflow`
                WHERE 
                    `type` = 'deployment'
                AND
                    `step` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $step);
        $stmt->bind_result($principal_code, $_step, $type, $reference_direction, $reference_milestone, $reference_table, $days);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'principal_code' => $principal_code,
                'step' => $_step,
                'reference_direction' => $reference_direction,
                'reference_milestone' => $reference_milestone,
                'reference_table' => $reference_table,
                'days' => $days
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getDeploymentWorkflow()
    {
        $out = [];

        $sql = "SELECT 
                    *
                FROM 
                    `workflow_master_workflow`
                WHERE 
                    `type` = 'deployment'";

        $stmt = $this->db->query($sql);

        while ($data = $stmt->fetch_assoc()) {
            $out[] = $data;
        }

        $stmt->close();

        return $out;
    }

    public function getQueueToWork($limit = 10)
    {
        $out = array();
        $sql = "SELECT
                    *
                FROM
                    `workflow_tracker_queue`
                WHERE
                    DATE(`reserved_at`) <= CURRENT_TIMESTAMP
                AND
                    `status` = 'pending'
                LIMIT $limit";
                
        $stmt = $this->db->query($sql);

        while ($data = $stmt->fetch_assoc()) {
            $out[] = $data;
        }

        $stmt->close();

        return $out;
    }

    public function updateQueueStatus($id, $status)
    {
        $out = false;

        $sql = "UPDATE
                    `workflow_tracker_queue`
                SET
                    `status` = ?
                WHERE
                    `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $id);

        $stmt->execute();

        if ($stmt->affected_rows >= 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function updateQueueAttempts($id, $add)
    {
        $out = false;

        $sql = "UPDATE
                    `workflow_tracker_queue`
                SET
                    `attempts` = ?
                WHERE
                    `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $add, $id);

        $stmt->execute();

        if ($stmt->affected_rows >= 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getQueue($id)
    {
        $out = array();

        $sql = "SELECT
                    `id`,
                    `primary_field`,
                    `tracker_table`,
                    `deployment_type`,
                    `deployment_date`,
                    `reserved_at`,
                    `attempts`,
                    `status`,
                    `oktb_type`,
                    `visa_type`,
                    `stcw_type`,
                    `medical_type`,
                    `vaccine_type`
                FROM
                    `workflow_tracker_queue`
                WHERE
                    `id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->bind_result($_id, $primary_field, $tracker_table, $deployment_type, $deployment_date, $reserved_at, $attempts, $status, $oktb_type, $visa_type, $stcw_type, $medical_type, $vaccine_type);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'id' => $_id,
                'primary_field' => $primary_field,
                'tracker_table' => $tracker_table,
                'deployment_type' => $deployment_type,
                'deployment_date' => $deployment_date,
                'reserved_at' => $reserved_at,
                'attempts' => $attempts,
                'status' => $status,
                'oktb_type' => $oktb_type,
                'visa_type' => $visa_type,
                'stcw_type' => $stcw_type,
                'medical_type' => $medical_type,
                'vaccine_type' => $vaccine_type
            );
        }

        $stmt->close();

        return $out;
    }
 }