<?php
namespace core\modules\education\models\common;


final class master extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local');
		return;
    }

    public function getAllEducationCourse(){
        $request = $_POST;
        $this->validateRequest($request, ['status', 'start_date','end_date']);
        $table = 'education_course';

        $primaryKey = 'education_course.course_id';

        $columns = array(
            array( 'db' => 'education_course.`course_id`', 'dt' => 'course_id' ),
            array( 'db' => 'education_course.`course_name`', 'dt' => 'course_name' ),
            array( 'db' => 'education_course.`created_on`', 'dt' => 'created_on', 'formatter' => function( $d, $row ) {return date( 'd M Y', strtotime($d));} ),
            array( 'db' => 'education_course.`short_description`', 'dt' => 'short_description' ),
            array( 'db' => 'education_course.`description`', 'dt' => 'description' ),
            array( 'db' => 'education_course.`filename`', 'dt' => 'filename' ),
            array( 'db' => 'education_course.`status`', 'dt' => 'status' ),
            array( 'db' => 'education_course.`updated_on`', 'dt' => 'updated_on' )
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );


        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `education_course`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `education_course`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `education_course`.`status` = "'.$request['status'].'" ';
        }


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table` ";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];
        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function insert($data){
        $sql = "INSERT INTO
					`education_course`
				SET   
                    `course_name` = ?,
                    `status` = ?,
                    `short_description` = ?,
                    `description` = ?,
                    `created_on`= CURRENT_TIMESTAMP
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssss',$data['course_name'], $data['status'],$data['short_description'], $data['description']);
        $stmt->execute();
        $out = $stmt->insert_id;
        $stmt->close();
        return $out;
    }

    public function get($id)
    {
        $out = array();

        $sql = "SELECT
                        `course_id`,`course_name`,`short_description`,`description`,`status`,`created_on`,`updated_on`
					FROM
						`education_course`
					WHERE
						`course_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
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

    public function getFiles($course_id){
        $out = array();
        $sql = "SELECT
                  `address_book_file`.`filename`,
                  `address_book_file`.`model_code`
					FROM
						`address_book_file`
					WHERE
						`address_book_file`.`address_book_id` = ?
					AND  `address_book_file`.`model_sub_code` = 'education_course'
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $course_id);
        $stmt->bind_result($filename, $model_code);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $out[] = array(
                'filename' => $filename,
                'model_code' => $model_code,
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function update($course_id,$data) {
        $out =0;
        $sql = "UPDATE
					`education_course`
				SET 
                    `course_name` = ?,
                    `short_description` = ?,
                    `description` = ?,
                    `status` = ?,
					`updated_on`= CURRENT_TIMESTAMP 
				WHERE
				    `course_id` = ?
				";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssssi',$data['e_course_name'],$data['e_short_description'],$data['e_description'],$data['e_status'],$course_id);
            $stmt->execute();
            $out = $stmt->affected_rows;
            $stmt->close();
    }

    public function delete($course_id){
        $out = [];

        $sql = "DELETE
					FROM `education_course`
                WHERE
					`course_id` = ? 
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$course_id);
        $out = $stmt->execute();
        $stmt->close();

        return $out;
    }
}