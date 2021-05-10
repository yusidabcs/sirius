<?php
namespace core\modules\deployment\models\common;
/**
 * Final cv/db class.
 *
 * @final
 * 
 */
final class db extends \core\app\classes\module_base\module_db {


    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    public function updateDeploymentStatus($address_book_id, $status)
    {
        $out = false;

        $sql = "UPDATE 
                    `deployment_master`
                SET
                    `status` = ?
                WHERE
                    `address_book_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $address_book_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        return $out;
            
    }

    public function updateDeploymentStatusOverdue()
    {
        $out = false;

        $sql = "UPDATE 
                    `deployment_master`
                SET
                    `status` = 'processing'
                WHERE
                    `deploy_date` <= NOW()
                AND
                    `status` = 'pending'";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        if ($stmt->affected_rows >= 1) {
            $out = true;
        }

        return $out;
    }
}
?>