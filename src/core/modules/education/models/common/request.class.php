<?php
namespace core\modules\education\models\common;


final class request extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local');
		return;
    }

    public function getAllRequestEducationCourse($ent=false){
        $request = $_POST;
        $this->validateRequest($request, ['status', 'start_date','end_date','partner']);
        $table = 'education_course_request';
        $primaryKey = 'education_course_request.course_id';
        $columns = array(
            array( 'db' => 'education_course_request.`course_request_id`', 'dt' => 'course_request_id' ),
            array( 'db' => 'education_course_request.`course_id`', 'dt' => 'course_id' ),
            array( 'db' => 'education_course_request.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => 'education_course_request.`status`', 'dt' => 'status' ),
            array( 'db' => 'education_course_request.`created_on`', 'dt' => 'created_on', 'formatter' => function( $d, $row ) {return date( 'd M Y', strtotime($d));} ),
            array( 'db' => 'education_course_request.`accepted_on`', 'dt' => 'accepted_on' ),
            array( 'db' => 'education_course_request.`enrolled_on`', 'dt' => 'enrolled_on' ),
            array( 'db' => 'education_course_request.`finished_on`', 'dt' => 'finished_on' ),
            array( 'db' => 'education_course.`course_name`', 'dt' => 'course_name' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'fullname', 'dt' => 'fullname' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
            array('db' => 'partner.entity_family_name', 'as' => 'partner_name', 'dt' => 'partner_name'),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $join = 'LEFT JOIN `address_book` on `address_book`.`address_book_id` = `education_course_request`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_connection` ON `address_book_connection`.`address_book_id` = `education_course_request`.`address_book_id` ';
        $join .= 'LEFT JOIN `education_course` on `education_course`.`course_id` = `education_course_request`.`course_id` ';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';

        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `education_course_request`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `education_course_request`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `education_course_request`.`status` = "'.$request['status'].'" ';
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }

        if(isset($request['partner']) && $request['partner'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `partner`.`address_book_id` = "'.$request['partner'].'" ';
        }

        $order .= (strpos(strtolower($order),'order by') === false)? ' ORDER BY ' :  ',';
        $order .= '`'.$table.'`.`created_on` DESC';
        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
             FROM `$table`
             $join
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
             $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table` $join ";
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

    public function getAllRequestEducationCourseDashboard($ent=false) {
        $request = $_POST;
        $this->validateRequest($request, ['period']);
        $table = 'education_course_request';
        $primaryKey = 'education_course_request.course_request_id';
        $columns = array(
            array( 'db' => 'education_course_request.`course_request_id`', 'dt' => 'course_request_id' ),
            array( 'db' => 'education_course_request.`course_id`', 'dt' => 'course_id' ),
            array( 'db' => 'education_course_request.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => 'education_course_request.`status`', 'dt' => 'status' ),
            array( 'db' => 'education_course_request.`created_on`', 'dt' => 'created_on'),
            array( 'db' => 'education_course_request.`accepted_on`', 'dt' => 'accepted_on' ),
            array( 'db' => 'education_course_request.`enrolled_on`', 'dt' => 'enrolled_on' ),
            array( 'db' => 'education_course_request.`finished_on`', 'dt' => 'finished_on' ),
            array( 'db' => 'education_course.`course_name`', 'dt' => 'course_name' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'fullname', 'dt' => 'fullname' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' )
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $join = 'LEFT JOIN `address_book` on `address_book`.`address_book_id` = `education_course_request`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_connection` ON `address_book_connection`.`address_book_id` = `education_course_request`.`address_book_id` ';
        $join .= 'LEFT JOIN `education_course` on `education_course`.`course_id` = `education_course_request`.`course_id` ';

        $where = $this->filter( $request, $columns, $bindings  );

         //filter by period
         if (isset($request['period']) && !empty($request['period'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            if($request['period']=='today') {
                $where .= "DATE_FORMAT(`".$table."`.`created_on`,'%Y-%m-%d') = CURDATE()";
            } else {
                $where .= "DATE_FORMAT(`".$table."`.`created_on`,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')";
            }
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
             FROM `$table`
             $join
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
             $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table` $join ";
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
    public function updateStatusRequest($course_request_id,$status_select,$col) {
        $out =0;
        $sql = "UPDATE
					`education_course_request`
				SET 
                    `status` = '{$status_select}'";
                if($col!='') {
                    $sql .= ",`{$col}`= CURRENT_TIMESTAMP"; 
                }
					
                $sql .="
                WHERE
				    `course_request_id` = {$course_request_id}
				";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $out = $stmt->affected_rows;
            $stmt->close();

            return $out;
    }

    public function getDataEducationRequest($status,$partner,$start,$end,$ent=false) {
        $out = array();

        $sql = "SELECT
                    `education_course`.`course_name`,
                    `education_course_request`.`status`,
                    `education_course_request`.`created_on`,
                    CONCAT(`address_book`.`entity_family_name`,' ',`address_book`.number_given_name) AS fullname,
                    `address_book`.`main_email`,
                    `partner`.`entity_family_name` AS partner_name,
                    `partner`.`address_book_id` AS partner_id
					FROM
						`education_course_request`
                    LEFT JOIN
                        `education_course` ON `education_course_request`.`course_id`=`education_course`.`course_id`
                    LEFT JOIN
                        `address_book` ON `education_course_request`.`address_book_id`=`address_book`.`address_book_id`
                    LEFT JOIN 
                        `address_book_connection` ON `address_book_connection`.`address_book_id` = `education_course_request`.`address_book_id`
                    LEFT JOIN
                        `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id`
                ";
        $where='';
        if ($start!='') {
            $start_date = date('Y-m-d 00:00:00', strtotime($start));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `education_course_request`.`created_on` >= "'.$start_date.'" ';
        }
        if ($partner!='') {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `partner`.`address_book_id` = "'.$partner.'" ';
        }

        if ($end!='') {
            $end_date = date('Y-m-d 00:00:00', strtotime($end));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `education_course_request`.`created_on` <= "'.$end_date.'" ';
        }

        if($status!=''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `education_course_request`.`status` = "'.$status.'" ';
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }

        $order = 'ORDER BY `education_course_request`.`created_on` DESC';

        $stmt = $this->db->prepare($sql.$where.$order);
        $stmt->bind_result($course_name, $status, $created_on, $fullname, $main_email, $partner_name, $partner_id);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'course_name' => $course_name,
                'fullname' => $fullname,
                'status' => $status,
                'created_on' => $created_on,
                'main_email' => $main_email,
                'partner_name' => $partner_name,
                'partner_id' => $partner_id
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function getCountRequestCourse($data,$ent=false) {
        $out = [];
		$sql = "SELECT
					count(*) as all_status, 
					count(if(`status`='request',`education_course_request`.`course_request_id` ,NULL))  as request, 
					count(if(`status`='accepted',`education_course_request`.`course_request_id` ,NULL))  as accepted,
					count(if(`status`='enrolled',`education_course_request`.`course_request_id` ,NULL))  as enrolled, 
					count(if(`status`='finish',`education_course_request`.`course_request_id` ,NULL))  as finish,
                    count(if(`status`='cancel',`education_course_request`.`course_request_id` ,NULL))  as cancel 
                FROM  `education_course_request`
                LEFT JOIN 
                    `address_book_connection` ON `address_book_connection`.`address_book_id` = `education_course_request`.`address_book_id` ";
                //filter by period
                $where="";
                if (isset($data['period']) && !empty($data['period'])) {
                    $where .=' WHERE ';
                    if($data['period']=='today') {
                        $where .= "DATE_FORMAT(`education_course_request`.`created_on`,'%Y-%m-%d') = CURDATE()";
                    } else {
                        $where .= "DATE_FORMAT(`education_course_request`.`created_on`,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')";
                    }
                }

                if ($ent != false) {
                    $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                    $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
                }
		$stmt = $this->db->prepare($sql.$where);
		$stmt->bind_result($all_status, $request, $accepted, $enrolled, $finish, $cancel);
		$stmt->execute();
		if ($stmt->fetch()) {
			$out = [
				'all_status' => $all_status,
				'request' => $request,
				'accepted' => $accepted,
				'enrolled' => $enrolled,
                'finish' => $finish,
                'cancel' => $cancel
			];
		}

		$stmt->close();

		return $out;
    }

    public function getDataEducationTracker($period,$start,$end,$ent=false) {
        $out = array();

        $sql = "SELECT
                    `workflow_education_tracker`.`course_request_id`,
                    `workflow_education_tracker`.`address_book_id`,
                    `workflow_education_tracker`.`level`,
                    `workflow_education_tracker`.`status`,
                    `workflow_education_tracker`.`created_on`,
                    `education_course`.`course_name`,
                    `address_book`.`entity_family_name`,
                    `address_book`.`number_given_name`,
                    `address_book`.`main_email`,
                    `partner`.`entity_family_name` AS partner_name,
                    `partner`.`address_book_id` AS partner_id
					FROM
						`workflow_education_tracker`
                    LEFT JOIN 
                        `education_course_request` on `workflow_education_tracker`.`course_request_id` = `education_course_request`.`course_request_id`
                    LEFT JOIN 
                        `education_course` on `education_course_request`.`course_id` = `education_course`.`course_id`
                    LEFT JOIN
                        `address_book` ON `workflow_education_tracker`.`address_book_id`=`address_book`.`address_book_id`
                    LEFT JOIN 
                        `address_book_connection` ON `address_book_connection`.`address_book_id` = `workflow_education_tracker`.`address_book_id` AND `address_book_connection`.`connection_type` = 'lp'
                    LEFT JOIN
                        `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id`
                ";
        $where = " WHERE `workflow_education_tracker`.`status` NOT IN ('finish','cancel')";
        if ($period!='') {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            if($period=='today') {
                $where .= "DATE_FORMAT(`workflow_education_tracker`.`created_on`,'%Y-%m-%d') = CURDATE()";
            } else {
                $where .= "DATE_FORMAT(`workflow_education_tracker`.`created_on`,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')";
            }
        }
        if ($start!='') {
            $start_date = date('Y-m-d 00:00:00', strtotime($start));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `workflow_education_tracker`.`created_on` >= "'.$start_date.'" ';
        }

        if ($end!='') {
            $end_date = date('Y-m-d 00:00:00', strtotime($end));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `workflow_education_tracker`.`created_on` <= "'.$end_date.'" ';
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }

        $order = 'ORDER BY `workflow_education_tracker`.`created_on` DESC';

        $stmt = $this->db->prepare($sql.$where.$order);
        $stmt->bind_result($course_request_id,$address_book_id,$level,$status,$created_on,$course_name, $entity_family_name, $number_given_name, $main_email, $partner_name, $partner_id);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'course_request_id' => $course_request_id,
                'address_book_id' => $address_book_id,
                'level' => $level,
                'status' => $status,
                'created_on' => $created_on,
                'course_name' => $course_name,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
                'main_email' => $main_email,
                'partner_name' => $partner_name,
                'partner_id' => $partner_id
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }
}