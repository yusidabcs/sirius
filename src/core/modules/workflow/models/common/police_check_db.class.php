<?php
namespace core\modules\workflow\models\common;
/**
 * Final police_check_db db class.
 *
 * @final
 * @package		jobapplication_tracker
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 Jun 2020
 */

final class police_check_db extends \core\app\classes\module_base\module_db {
    public $table = 'workflow_police_tracker';

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    public function getTrackersDatatable(){
        $this->generic = \core\app\classes\generic\generic::getInstance();
        
        $request = $_POST;
        $this->validateRequest($request, ['status', 'level','start_date','end_date','address_book']);
        $table = 'workflow_police_tracker';

        $primaryKey = 'workflow_police_tracker.address_book_id';

        $columns = array(
            array( 'db' => 'workflow_police_tracker.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => 'workflow_police_tracker.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => 'workflow_police_tracker.`request_file_on`', 'dt' => 'request_file_on', 'formatter' => function( $d, $row ) {return $d!='0000-00-00 00:00:00'?date( 'd M Y', strtotime($d)):$d;} ),
            array( 'db' => 'workflow_police_tracker.`uploaded_file_on`', 'dt' => 'uploaded_file_on' ),
            array( 'db' => 'workflow_police_tracker.`accepted_on`', 'dt' => 'accepted_on' ),
            array( 'db' => 'workflow_police_tracker.`rejected_on`', 'dt' => 'rejected_on' ),
            array( 'db' => 'workflow_police_tracker.`notes`', 'dt' => 'notes' ),
            array( 'db' => 'workflow_police_tracker.`status`', 'dt' => 'status' ),
            array( 'db' => 'workflow_police_tracker.`level`', 'dt' => 'level' ),

            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => 'address_book.`entity_family_name`', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.`number_given_name`', 'dt' => 'number_given_name' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'fullname', 'formatter' => function ($d, $row) {
                return $this->generic->getName('per', $row['entity_family_name'], $row['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            })
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN `address_book` on `address_book`.`address_book_id` = `workflow_police_tracker`.`address_book_id` ';
        //$join .= ' JOIN `workflow_police_workflow` on `workflow_police_workflow`.`milestone` = `workflow_police_tracker`.`status` ';

        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `workflow_police_tracker`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `workflow_police_tracker`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['level']) && $request['level'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_police_tracker`.`level` = "'.$request['level'].'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_police_tracker`.`status` = "'.$request['status'].'" ';
        }

        if(isset($request['address_book']) && $request['address_book'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_police_tracker`.`address_book_id` = "'.$request['address_book'].'" ';
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
			 FROM   `$table`  $join";
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

    /**
     * Insert  tracker
     */
    public function insertTracker($address_book_id) {
        $sql = "
              INSERT INTO 
              
              $this->table
              
              SET 
                `address_book_id` = ?,
                `status` = 'request_file',
                `created_on`= CURRENT_TIMESTAMP,
                `created_by`= {$this->user_id},
                `level` = 1
            
            ";

        $statement = $this->db->prepare($sql);
        $statement->bind_param('i', $address_book_id);

        $statement->execute();

        $out = $statement->affected_rows;

        $statement->close();

        return ($out === 1) ? true:false;
    }


    public function updateTrackers($address_book_id, $data)
    {
        $set = '';

        foreach ($data as $key => $value) {
            $set .= " $key = '$value',";
        }
        $set = rtrim($set, ',');

        $sql = "UPDATE $this->table
				SET $set WHERE address_book_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $address_book_id);
        $stmt->execute();

        $out = $stmt->affected_rows;
        echo $stmt->error;
        $stmt->close();

        return $out;
    }

    public function updatePoliceTrackerLevel($workflow) {
        $tracker_db = new db();
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`request_file_on`,
                    `workflow_".$workflow."_tracker`.`uploaded_file_on`,
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
                
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted', 'rejected')
                AND `job_application`.`status` NOT IN ('not_hired','canceled','reapply')";

        $data = $this->db->query_array($sql);
        $tracker_db->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

}