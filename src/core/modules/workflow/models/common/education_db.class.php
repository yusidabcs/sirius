<?php
namespace core\modules\workflow\models\common;

final class education_db extends \core\app\classes\module_base\module_db {

    public $table = 'workflow_education_tracker';
    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    public function insertEducationTracker($course_request_id,$address_book_id,$course_id) {
		$sql =  "INSERT INTO
                    $this->table
                SET 
                    `status`= 'request', 
                    `created_on`= CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `address_book_id` = ?,
                    `course_id` = ?,
                    `course_request_id` = ?,
                    `level` = 1
                    ";

        $statement = $this->db->prepare($sql);
        $statement->bind_param('iii', $address_book_id,$course_id,$course_request_id);
        $statement->execute();
        $out = $statement->affected_rows;
        $statement->close();
        return $out;
    }

    public function updateEducationTrackerStatus($course_request_id,$status,$col=false){
        $out = null;
        $sql =  "UPDATE
                    $this->table
                SET
                    `status` = ?";
                if($col!=false) {
                    $sql .=",`".$col."_on` = CURRENT_TIMESTAMP,
                    `".$col."_by` = {$this->user_id}";
                }
                $sql .=" WHERE
                    `course_request_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status,$course_request_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getTrackerDatatable()
	{
        $tablename ='workflow_education_tracker';
		$request = $_POST;
        $this->validateRequest($request, ['status', 'level','startDate','endDate']);
		$primaryKey = $tablename.'.course_request_id';

        $columns = array(
            array( 'db' => $tablename.'.`course_request_id`', 'dt' => 'course_request_id' ),
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.created_on', 'dt' => 'created_on'),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
			array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
			array( 'db' => 'user.user_id', 'dt' => 'user_id')
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "LEFT JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
		$join .= " LEFT JOIN `user` on address_book.main_email = user.email";

		

        if (isset($request['status']) && !empty($request['status'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "$tablename.`status` = '".$request['status']."'";
        }

        if (isset($request['level'])) {
            if ($request['level'] > 0) {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .= "$tablename.`level` = ".$request['level'];
            }
		}
		
		//filter by date
		if ((isset($request['startDate']) && !empty($request['startDate']))) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`created_on`) >= '".date('Y-m-d',strtotime($request['startDate']))."'";
		}
		if (isset($request['endDate']) && !empty($request['endDate'])) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`created_on`) <= '".date('Y-m-d',strtotime($request['endDate']))."'";
		}

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join";
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
    
    public function getTrackerDatatableDashboard($ent=false) {
        $tablename ='workflow_education_tracker';
		$request = $_POST;
        $this->validateRequest($request, ['period','start_date','end_date']);
		$primaryKey = $tablename.'.course_request_id';

        $columns = array(
            array( 'db' => $tablename.'.`course_request_id`', 'dt' => 'course_request_id' ),
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.created_on', 'dt' => 'created_on'),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
			array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
			array( 'db' => 'user.user_id', 'dt' => 'user_id'),
            array( 'db' => 'education_course.course_name', 'dt' => 'course_name')
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        $join = " LEFT JOIN `education_course_request` on $tablename.`course_request_id` = education_course_request.course_request_id";
        $join .= " LEFT JOIN `education_course` on `education_course_request`.`course_id` = education_course.course_id";
        $join .= " LEFT JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
        $join .= " LEFT JOIN 
        `address_book_connection` ON `address_book_connection`.`address_book_id` = `".$tablename."`.`address_book_id` AND `address_book_connection`.`connection_type` = 'lp'";
		$join .= " LEFT JOIN `user` on address_book.main_email = user.email";

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= " `".$tablename."`.`status` NOT IN ('finish','cancel') ";
        //filter by period
        if (isset($request['period']) && !empty($request['period'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            if($request['period']=='today') {
                $where .= " DATE_FORMAT(`".$tablename."`.`created_on`,'%Y-%m-%d') = CURDATE() ";
            } else if($request['period']=='this_month') {
                $where .= " DATE_FORMAT(`".$tablename."`.`created_on`,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m') ";
            }
        }

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `'.$tablename.'`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `'.$tablename.'`.`created_on` <= "'.$end_date.'" ';
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }
       

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM  `".$tablename."`  $join";
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

    public function getCountTrackerEducation($data,$ent=false)
	{
		$out = [];
		$sql = "SELECT
					count(*) as all_level, 
					count(if(`level`='1',`workflow_education_tracker`.`course_request_id` ,NULL))  as normal, 
					count(if(`level`='2',`workflow_education_tracker`.`course_request_id` ,NULL))  as soft_warning,
					count(if(`level`='3',`workflow_education_tracker`.`course_request_id` ,NULL))  as hard_warning, 
					count(if(`level`='4',`workflow_education_tracker`.`course_request_id` ,NULL))  as deadline 
				FROM  `workflow_education_tracker` 
                LEFT JOIN 
                    `address_book_connection` ON `address_book_connection`.`address_book_id` = `workflow_education_tracker`.`address_book_id`
                WHERE `workflow_education_tracker`.`status` NOT IN ('finish','cancel')";
                //filter by period
                $where='';
                if (isset($data['period']) && !empty($data['period'])) {
                    $where .=' AND ';
                    if($data['period']=='today') {
                        $where .= "DATE_FORMAT(`workflow_education_tracker`.`created_on`,'%Y-%m-%d') = CURDATE()";
                    } else if($data['period']=='this_month') {
                        $where .= "DATE_FORMAT(`workflow_education_tracker`.`created_on`,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')";
                    }
                }

                if (isset($data['start_date']) && !empty($data['start_date'])) {
                    
                    $start_date = date('Y-m-d 00:00:00', strtotime($data['start_date']));
                    $where .= ' AND ';
                    $where .= ' `workflow_education_tracker`.`created_on` >= "'.$start_date.'" ';
                }
        
                if (isset($data['end_date']) && !empty($data['end_date'])) {
                    $end_date = date('Y-m-d 00:00:00', strtotime($data['end_date']));
                    $where .= ' AND ';
                    $where .= ' `workflow_education_tracker`.`created_on` <= "'.$end_date.'" ';
                }

                if ($ent != false) {
                    $where .= "  `address_book_connection`.`connection_id` = '{$ent}' ";
                }
		$stmt = $this->db->prepare($sql.$where);
		$stmt->bind_result($all_level, $normal, $soft_warning, $hard_warning, $deadline);
		$stmt->execute();
		if ($stmt->fetch()) {
			$out = [
				'all_level' => $all_level,
				'normal' => $normal,
				'soft_warning' => $soft_warning,
				'hard_warning' => $hard_warning,
				'deadline' => $deadline
			];
		}

		$stmt->close();

		return $out;
    }
    
    public function updateEducationTrackerLevel($workflow) {
        $tracker_db = new db();
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`course_request_id`,
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`course_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`accepted_on`,
                    `workflow_".$workflow."_tracker`.`enrolled_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status`
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('finish','cancel')
				";
        $data = $this->db->query_array($sql);
        
        $now = time();
        foreach ($data as $index => $item){
            //calculate warning level
            if($item['reference_milestone'] == ''){
                $data[$index]['level'] = 'normal';
                continue;
            }

            $reference_date = $item[$item['reference_milestone']];
            
            if($item['reference_direction'] == 'after')
            {
                if( $now > strtotime($reference_date.' + '.$item['deadline'].' days') )
                {
                    $level = '4';
                } else if( $now > strtotime($reference_date.' + '.$item['hard_warning'].' days') ) {
                    $level = '3';
                } else if( $now > strtotime($reference_date.' + '.$item['soft_warning'].' days') ) {
                    $level = '2';
                } else {
                    $level = '1';
                }

            } else {

                if( $now > strtotime($reference_date.' - '.$item['deadline'].' days') )
                {
                    $level = '4';
                } else if( $now > strtotime($reference_date.' - '.$item['hard_warning'].' days') ) {
                    $level = '3';
                } else if( $now > strtotime($reference_date.' - '.$item['soft_warning'].' days') ) {
                    $level = '2';
                } else {
                    $level = '1';
                }
            }

            $this->updateWorkflowTrackerLevel($workflow,$item['course_request_id'], $level);

        }

        return $out;
    }

    public function updateWorkflowTrackerLevel($workflow,$course_request_id, $level){
        $out = null;
        $sql =  "UPDATE
                    `workflow_".$workflow."_tracker`
                SET
                    `level` = {$level}
                WHERE
                    `course_request_id` = {$course_request_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
	}
    
    public function getTotalEducationTrackerByLevelPartner(){
        $sql =  "SELECT 
                    COUNT(`workflow_education_tracker`.`course_request_id`) as total,
                    `workflow_education_tracker`.`level`,`address_book_connection`.`connection_id`
                FROM 
                    `workflow_education_tracker`
                LEFT JOIN
                `address_book_connection` ON `workflow_education_tracker`.`address_book_id`=`address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`='lp'
                    
                WHERE `workflow_education_tracker`.`status` NOT IN ('finish','cancel')
       			GROUP BY `workflow_education_tracker`.`level`,`address_book_connection`.`connection_id`";
        $data = $this->db->query_array($sql);
        return $data;
    }
}