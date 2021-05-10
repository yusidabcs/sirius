<?php
namespace core\modules\education_application\models\common;


final class db extends \core\app\classes\module_base\module_db{
	public function __construct()
	{	
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;	
	}
	
	public function getAllCourse($address_book_id,$limit=false) {
		$out = array();

        $sql = "SELECT
                        `education_course`.`course_id`,
                        `education_course`.`course_name`,
                        `education_course`.`short_description`
                    FROM
                        `education_course`
					WHERE
						`education_course`.`status`='active'
					AND
						`education_course`.`course_id` NOT IN (
							SELECT 
								`education_course_request`.`course_id`
							FROM 
								`education_course_request`
							WHERE
							`education_course_request`.`address_book_id`=?
                            AND 
                            `education_course_request`.`status`<>'cancel'
						)
                    ORDER BY
                    `education_course`.`created_on` DESC
                ";
            
        if($limit != false){
            $sql .= "limit ".$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($course_id, $course_name, $short_description);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'course_id' => $course_id,
                'course_name' => $course_name,
                'short_description' => $short_description
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }
    
    public function getAllCourseApp($address_book_id) {
		$out = array();

        $sql = "SELECT
                        `education_course_request`.`course_request_id`,
                        `education_course_request`.`course_id`,
                        `education_course_request`.`address_book_id`,
                        `education_course_request`.`status`,
                        `education_course_request`.`created_on`,
                        `education_course_request`.`accepted_on`,
                        `education_course_request`.`enrolled_on`,
                        `education_course_request`.`finished_on`,
                        `education_course_request`.`cancelled_on`,
                        `education_course`.`course_name`,
                        `education_course`.`short_description`
                    FROM
                        `education_course_request`
                    LEFT JOIN
                        `education_course`
                    ON
                        `education_course_request`.`course_id` = `education_course`.`course_id`
					WHERE
						`education_course_request`.`address_book_id`=?
                    ORDER BY
                        `education_course_request`.`created_on` desc
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($course_request_id,$course_id,$address_book_id,$status,$created_on,$accepted_on,$enrolled_on,$finished_on,$cancelled_on, $course_name, $short_description);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $last_modified='';
            if($status=='request') {$last_modified=$created_on;}
            else if($status=='accepted') {$last_modified=$accepted_on;}
            else if($status=='enrolled') {$last_modified=$enrolled_on;}
            else if($status=='finish') {$last_modified=$finished_on;}
            else if($status=='cancel') {$last_modified=$cancelled_on;}
            $last_modified = date('d M Y',strtotime($last_modified));
            $created_on = date('d M Y',strtotime($created_on));
            $out[] = array(
                'course_request_id' => $course_request_id,
                'course_id' => $course_id,
                'address_book_id' => $address_book_id,
                'status' => $status,
                'created_on' => $created_on,
                'last_modified' => $last_modified,
                'course_name' => $course_name,
                'short_description' => $short_description
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }
    
    public function getDetailCourse($course_id) {
        $out = array();

        $sql = "SELECT
                        `course_id`,`course_name`,`short_description`,`description`,`status`,`created_on`,`updated_on`
					FROM
						`education_course`
					WHERE
						`course_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$course_id);
        $stmt->bind_result($course_id, $course_name, $short_description, $description, $status, $created_on, $updated_on);

        $stmt->execute();
        $stmt->store_result();

        while($stmt->fetch())
        {
            $out = array(
                'course_id' => $course_id,
                'course_name' => $course_name,
                'short_description' => $short_description,
                'description' => $description,
                'status' => $status,
                'created_on' => $created_on,
                'updated_on' => $updated_on
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function insertRequestCourse($data) {
        $sql = "INSERT INTO
					`education_course_request`
				SET   
                    `course_id` = ?,
                    `address_book_id` = ?,
                    `status` = ?,
                    `created_on`= CURRENT_TIMESTAMP
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis',$data['course_id'], $data['address_book_id'],$data['status']);
        $stmt->execute();
        $out = $stmt->insert_id;
        $stmt->close();
        return $out;
    }

}
?>