<?php
namespace core\modules\workflow\models\common;

/**
 * Final interview_security_tracker db class.
 *
 * @final
 * @package		interview_security_tracker
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 Jun 2020
 */
final class recruitment_db extends \core\app\classes\module_base\module_db {

    public $table = 'workflow_recruitment_tracker';
    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    /*
    * get interview security list for datatable
    */
    function getRecruitmentTrackerDatatable($ent = false){
        $request = $_POST;
        $this->validateRequest($request, ['status', 'level']);

        $primaryKey = $this->table.'.address_book_id';

        $columns = array(
            array( 'db' => $this->table.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $this->table.'.level', 'dt' => 'level' ),
            array( 'db' => $this->table.'.status', 'dt' => 'status' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'workflow_recruitment_workflow.short_description', 'dt' => 'short_description' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN `address_book` on `address_book`.`address_book_id` = '.$this->table.'.address_book_id ';
        $join .= 'LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';
        $join .= 'LEFT JOIN `workflow_recruitment_workflow` ON '.$this->table.'.`status` = `workflow_recruitment_workflow`.`milestone` ';
        $where = $this->filter( $request, $columns, $bindings  );

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$this->table.`status` not in ('accepted','rejected')";

        if (isset($request['status']) && !empty($request['status'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "$this->table.`status` = '".$request['status']."'";
        }

        if (isset($request['level'])) {
            if ($request['level'] > 0) {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .= "$this->table.`level` = ".$request['level'];
            }
        }
        if ($ent != false) {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }
        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$this->table."`
			 $join
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$this->table."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$this->table."`  $join $where";
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

    public function getTrackerDatatable()
	{
        $tablename ='workflow_recruitment_tracker';
		$request = $_POST;
        $this->validateRequest($request, ['status', 'level','startDate','endDate']);
		$primaryKey = $tablename.'.address_book_id';

        $columns = array(
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.request_verification_on', 'dt' => 'request_verification_on'),
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
            $where .= "DATE(".$tablename.".`request_verification_on`) >= '".date('Y-m-d',strtotime($request['startDate']))."'";
		}
		if (isset($request['endDate']) && !empty($request['endDate'])) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`request_verification_on`) <= '".date('Y-m-d',strtotime($request['endDate']))."'";
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


    public function getTotalTrackerByLevel(){
        $sql =  "SELECT 
                    COUNT(`workflow_recruitment_tracker`.`address_book_id`) as total,
                    `workflow_recruitment_tracker`.`level`
                FROM 
                    `workflow_recruitment_tracker`
                JOIN `workflow_recruitment_workflow` on `workflow_recruitment_workflow`.`milestone` = `workflow_recruitment_tracker`.`status`
                
                WHERE `workflow_recruitment_tracker`.`status` NOT IN ('accepted', 'rejected')
                	
       			GROUP BY `workflow_recruitment_tracker`.`level`";
        $data = $this->db->query_array($sql);
        return $data;
    }

    public function updateAllTrackerLevel(){
        $out = [];
        $sql =  "SELECT 
                    `workflow_recruitment_tracker`.`address_book_id`,
                    `workflow_recruitment_tracker`.`request_verification_on`,
                    `workflow_recruitment_tracker`.`status`,  
                    `workflow_recruitment_workflow`.`milestone`, 
                    `workflow_recruitment_workflow`.`soft_warning`, 
                    `workflow_recruitment_workflow`.`hard_warning`, 
                    `workflow_recruitment_workflow`.`deadline`, 
                    `workflow_recruitment_workflow`.`reference_direction`, 
                    `workflow_recruitment_workflow`.`reference_milestone`
                FROM 
                    `workflow_recruitment_tracker`
                
                
                JOIN `workflow_recruitment_workflow` on `workflow_recruitment_workflow`.`milestone` = `workflow_recruitment_tracker`.`status`";

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

            $this->updateTrackerLevel($item['address_book_id'], $level);

        }
        return $out;
    }

    public function updateTrackerLevel($address_book_id, $level){
        $out = null;
        $sql =  "UPDATE
                    `workflow_recruitment_tracker`
                SET
                    `level` = {$level}
                WHERE
                    `address_book_id` = {$address_book_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function insertTracker($address_book_id) {
        $sql =  "INSERT INTO
                    $this->table
                SET 
                    `status`= 'request_verification', 
                    `request_verification_on`= CURRENT_TIMESTAMP,
                    `request_verification_by`= {$this->user_id},
                    `address_book_id` = ?
                    ";

        $statement = $this->db->prepare($sql);
        $statement->bind_param('i', $address_book_id);

        $statement->execute();

        if ($statement->error) {
            return $statement->error;
        }
        $out = $statement->affected_rows;

        $statement->close();

        return ($out === 1) ? true:false;
    }

    public function updateTrackerStatus($address_book_id, $status){
        $out = null;
        $sql =  "UPDATE
                    $this->table
                SET
                    `status` = ?,
                    `level` =1
                WHERE
                    `address_book_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status,$address_book_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteTracker($address_book_id){
        $out = null;
        $sql =  "DELETE FROM
                    $this->table
                WHERE
                    `address_book_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }




}
