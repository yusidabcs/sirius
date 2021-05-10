<?php
namespace core\modules\interview\models\common;
/*
 * Final interview/db class.
 *
 * @final
 *
 */
final class secure_file extends \core\app\classes\module_base\module_db  {

    public function __construct()
    {

        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }


    public function getSecureFile($file_id){
        $out = null;
        $sql =  "SELECT 
                    `secure_file`.`hash`,
                    `secure_file`.`file_id`,
                     `secure_file`.`status`,
                     `secure_file`.`type`
                FROM
                    `secure_file`
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $hash,
            $file_id,
            $status,
            $type
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = array(
                'hash' => $hash,
                'file_id' => $file_id,
                'status' => $status,
                'type' => $type
            );

        }

        $stmt->close();
        return $out;
    }

    public function getSecureFileByHash($hash){
        $out = null;
        $sql =  "SELECT 
                    `secure_file`.`hash`,
                    `secure_file`.`file_id`,
                    `secure_file`.`status`,
                    `secure_file`.`type`
                FROM
                    `secure_file`
                WHERE
                    `hash` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$hash);
        $stmt->bind_result(
            $hash,
            $file_id,
            $status,
            $type
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = array(
                'hash' => $hash,
                'file_id' => $file_id,
                'status' => $status,
                'type' => $type
            );

        }

        $stmt->close();
        return $out;
    }


    public function insertSecureFile($data){
        $out = null;
        $sql =  "INSERT INTO
                    `secure_file`
                SET
                    `hash` = ?,
                    `file_id` = ?,
                    `type` = ?,
                    `status` = 1
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss',
            $data['hash'],
            $data['file_id'],
            $data['type']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function checkDuplicateSecureFileHash($hash){
        $out = false;
        $sql =  "SELECT
                    count(`hash`) as total
                FROM
                    `secure_file`
                WHERE
                    `hash` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$hash);
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->fetch();
        if($total > 0){
            $out = true;
        }
        $stmt->close();
        return $out;
    }

    public function disableSecureFile($hash){
        $out = null;
        $sql =  "UPDATE
                    `secure_file`
                SET
                    `status` = 0
                WHERE
                    `hash` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$hash);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteSecureFile($file_id){
        $out = null;
        $sql =  "DELETE
                    FROM `secure_file`
                  WHERE
                      `file_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$file_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
}
?>