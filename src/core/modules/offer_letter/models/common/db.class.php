<?php
namespace core\modules\offer_letter\models\common;

/**
 * Final offer_letter db class.
 *
 * @final
 * @package		offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
 */
final class db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
	
	public function getWorkflowArray()
    {
        $workflow_array = array();
        
        $now = time();

        $sql = "SELECT
					`interview_security_check`.`job_application_id`,
					`interview_security_check`.`created_on`,
					`interview_security_check`.`request_file_on`,
					`interview_security_check`.`passport_file_on`,
					`interview_security_check`.`request_clearance_on`,
					`interview_security_check`.`clearance_file_on`,
					`interview_security_workflow`.`soft_warning`,
					`interview_security_workflow`.`hard_warning`,
					`interview_security_workflow`.`deadline`,
					`interview_security_workflow`.`reference_milestone`,
					`interview_security_workflow`.`reference_direction`
				FROM
					`interview_security_check`
				LEFT JOIN
					`interview_security_workflow`
				ON
					`interview_security_check`.`status` = `interview_security_workflow`.`milestone`
				WHERE
					`interview_security_check`.`status` NOT IN ('accepted', 'denied')
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
        					$job_application_id,
        					$created_on,
        					$request_file_on,
        					$passport_file_on,
        					$request_clearance_on,
        					$clearance_file_on,
        					$soft_warning,
        					$hard_warning,
        					$deadline,
        					$reference_milestone,
        					$reference_direction
        				);
        $stmt->execute();
        while($stmt->fetch())
        {
	        //calculate warning level
	        $reference_date = $$reference_milestone;
	       
	        if($reference_direction == 'after')
	        {
		        if( $now > strtotime($reference_date.' + '.$deadline.' days') )
		        {
			        $level = 'deadline';
		        } else if( $now > strtotime($reference_date.' + '.$hard_warning.' days') ) {
			        $level = 'hard';
		        } else if( $now > strtotime($reference_date.' + '.$soft_warning.' days') ) {
			        $level = 'soft';
		        } else {
			        $level = 'normal';
		        }
		        
	        } else {
		        
		        if( $now > strtotime($reference_date.' - '.$deadline.' days') )
		        {
			        $level = 'deadline';
		        } else if( $now > strtotime($reference_date.' - '.$hard_warning.' days') ) {
			        $level = 'hard';
		        } else if( $now > strtotime($reference_date.' - '.$soft_warning.' days') ) {
			        $level = 'soft';
		        } else {
			        $level = 'normal';
		        }
	        }
	        
	       $workflow_array[$job_application_id] = $level;
	       
        };
        $stmt->close();

        return $workflow_array;
    }

    /*
     * Endorser
     */
    function getEndorserDatatable(){
        $request = $_POST;
        $this->validateRequest($request);
        $table = 'offer_letter_endorser';

        $primaryKey = 'offer_letter_endorser.job_master_id';

        $columns = array(
            array( 'db' => 'offer_letter_endorser.`job_master_id`', 'dt' => 'job_master_id' ),
            array( 'db' => 'job_master.`job_title`', 'dt' => 'job_title' ),
            array( 'db' => 'job_master.`job_code`', 'dt' => 'job_code' ),
            array( 'db' => 'job_master.`principal_code`', 'dt' => 'principal_code' ),
            array( 'db' => 'job_master.`brand_code`', 'dt' => 'brand_code' ),
            array( 'db' => 'offer_letter_endorser.endorser_id', 'dt' => 'endorser_id' ),
            array( 'db' => 'offer_letter_endorser.allowance_days', 'dt' => 'allowance_days' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'endorser', 'dt' => 'endorser' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` on `address_book`.`address_book_id` = offer_letter_endorser.endorser_id ';
        $join .= ' JOIN `job_master` on `job_master`.`job_master_id` = offer_letter_endorser.job_master_id ';

        $where = $this->filter( $request, $columns, $bindings  );

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

    public function insertEndorser($data){
        $out = null;
        $sql =  "INSERT INTO
                    `offer_letter_endorser`
                SET
                    `job_master_id` = ?,
                    `endorser_id` = ?,
                    `allowance_days` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['job_master_id'],
            $data['endorser_id'],
            $data['allowance_days']);
        $stmt->execute();
        echo $stmt->error;
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateTrackerRequestEndorsement($job_application_id){
        $out = null;
        $sql =  "UPDATE 
                    `workflow_offer_letter_tracker`
                SET
                    `workflow_offer_letter_tracker`.`endorsement_requested_on` = CURRENT_TIMESTAMP,
                    `workflow_offer_letter_tracker`.`endorsement_requested_by` = {$this->user_id}
                WHERE
                    `workflow_offer_letter_tracker`.`job_application_id` = ?
                
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateEndorsementFileStatus($job_application_id,$filename,$status){
        $out = null;
        $sql =  "UPDATE 
                    `workflow_offer_letter_tracker`
                SET
                    `workflow_offer_letter_tracker`.`endorsement_complete_on` = CURRENT_TIMESTAMP,
                    `workflow_offer_letter_tracker`.`endorsement_complete_by` = {$this->user_id},
                    `workflow_offer_letter_tracker`.`endorsement_file` = ?,
                    `workflow_offer_letter_tracker`.`status` = ?
                WHERE
                    `workflow_offer_letter_tracker`.`job_application_id` = ?
                
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',$filename,$status,$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }




    public function deleteEndorser($job_master_id){
        $out = null;
        $sql =  "DELETE
                    FROM `offer_letter_endorser`
                WHERE
                    `job_master_id` = ? 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $job_master_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getEndorser($job_master_id){
        $out = null;
        $sql =  "SELECT
                    `job_master_id`,
                    `endorser_id`,
                    `allowance_days`
                FROM
                    `offer_letter_endorser`
                WHERE
                    `offer_letter_endorser`.`job_master_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_master_id);
        $stmt->bind_result($job_master_id,$endorser_id, $allowance_days);
        $stmt->execute();
        while($stmt->fetch()) {
            $out = [
                'job_master_id' => $job_master_id,
                'endorser_id' => $endorser_id,
                'allowance_days' => $allowance_days,
            ];
        };
        $stmt->close();
        return $out;
    }

    /*
     * Offer Letter Security
     */
    /*
     * Endorser
     */
    function getReportsDatatable(){
        $request = $_POST;
        $this->validateRequest($request);
        $table = 'offer_letter_reports';

        $primaryKey = 'offer_letter_reports.address_book_id';

        $columns = array(
            array( 'db' => 'offer_letter_reports.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => 'offer_letter_reports.`level`', 'dt' => 'level' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'reporter', 'dt' => 'reporter' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` on `address_book`.`address_book_id` = offer_letter_reports.address_book_id ';

        $where = $this->filter( $request, $columns, $bindings  );

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

    public function insertReports($data){
        $out = null;
        $sql =  "INSERT INTO
                    `offer_letter_reports`
                SET
                    `address_book_id` = ?,
                    `level` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is',
            $data['address_book_id'],
            $data['level']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteReports($address_book_id){
        $out = null;
        $sql =  "DELETE
                    FROM `offer_letter_reports`
                WHERE
                    `address_book_id` = ? 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $address_book_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * Offer Letter Tracker
     */

    public function getOfferLetterTrackerDatatable(){
        $request = $_POST;
        $this->validateRequest($request, ['status']);
        $table = 'workflow_offer_letter_tracker';

        $primaryKey = 'workflow_offer_letter_tracker.job_application_id';

        $columns = array(
            array( 'db' => '`workflow_offer_letter_tracker`.`job_application_id`', 'dt' => 'job_application_id' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`endorsement_expected_on`', 'dt' => 'endorsement_expected_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`endorsement_requested_on`', 'dt' => 'endorsement_requested_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`endorsement_requested_by`', 'dt' => 'endorsement_requested_by' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`endorsement_complete_on`', 'dt' => 'endorsement_complete_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`endorsement_complete_by`', 'dt' => 'endorsement_complete_by' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`request_offer_letter_on`', 'dt' => 'request_offer_letter_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`offer_letter_file_on`', 'dt' => 'offer_letter_file_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`candidate_accepted_on`', 'dt' => 'candidate_accepted_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`personal_data_uploaded_on`', 'dt' => 'personal_data_uploaded_on' ),
            array( 'db' => '`workflow_offer_letter_tracker`.`status`', 'dt' => 'status' ),
            array( 'db' => 'CONCAT(`candidate`.`entity_family_name`,\' \', `candidate`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => '`candidate`.`main_email`', 'dt' => 'main_email' ),
            array( 'db' => '`job_master`.`job_title`', 'dt' => 'job_title' ),
            array( 'db' => '`job_master`.`job_code`', 'dt' => 'job_code' ),
            array( 'db' => '`job_master`.`principal_code`', 'dt' => 'principal_code' ),
            array( 'db' => '`job_demand_master`.`job_demand_master_id`', 'dt' => 'job_demand_master_id' ),
            array( 'db' => '`job_demand_allocation`.`allocated_on`', 'as' => 'allocated_on', 'dt' => 'allocated_on' ),
            array( 'db' => '`workflow_offer_letter`.`soft_warning`', 'dt' => 'soft_warning' ),
            array( 'db' => '`workflow_offer_letter`.`hard_warning`', 'dt' => 'hard_warning' ),
            array( 'db' => '`workflow_offer_letter`.`deadline`', 'dt' => 'deadline' ),
            array( 'db' => '`workflow_offer_letter`.`reference_milestone`', 'dt' => 'reference_milestone' ),
            array( 'db' => '`workflow_offer_letter`.`reference_direction`', 'dt' => 'reference_direction' ),
            array( 'db' => '`endorsement_file`.`hash`', 'as' => 'endorsement_file_hash', 'dt' => 'endorsement_file_hash' ),
            array( 'db' => '`offer_letter_file`.`hash`', 'as' => 'offer_letter_file_hash', 'dt' => 'offer_letter_file_hash' ),
            array( 'db' => '`personal_data_file`.`hash`', 'as' => 'personal_data_file_hash', 'dt' => 'personal_data_file_hash' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' 
            LEFT JOIN `address_book` `created` on `created`.`address_book_id` = workflow_offer_letter_tracker.created_by 
            JOIN `job_application` on `job_application`.`job_application_id` = `workflow_offer_letter_tracker`.`job_application_id` 
            JOIN `address_book` `candidate` on `candidate`.`address_book_id` = job_application.address_book_id 
            JOIN `job_demand_allocation` on `job_demand_allocation`.`address_book_id` = `job_application`.`address_book_id` 
            JOIN `job_demand_master` on `job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id` 
            JOIN `job_master` on `job_master`.`job_master_id` = `job_demand_master`.`job_master_id`
            LEFT JOIN `workflow_offer_letter` on `workflow_offer_letter`.`milestone` = `workflow_offer_letter_tracker`.`status` 
            LEFT JOIN `secure_file` `endorsement_file` on `endorsement_file`.`file_id` = `workflow_offer_letter_tracker`.`endorsement_file` 
            LEFT JOIN `secure_file` `offer_letter_file` on `offer_letter_file`.`file_id` = `workflow_offer_letter_tracker`.`offer_letter_file` 
            LEFT JOIN `secure_file` `personal_data_file` on `personal_data_file`.`file_id` = `workflow_offer_letter_tracker`.`personal_data_file` 
        ';

        $group = '';
        $where = $this->filter( $request, $columns, $bindings  );


        //$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        //$where .=' `interview_security_tracker`.`status` NOT IN (\'accepted\', \'denied\') ';

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_offer_letter_tracker`.`status` = "'.$request['status'].'" ';
        }

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $group
			 $limit";


        $data = $this->db->query_array($qry1);
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
                    $level = 'deadline';
                } else if( $now > strtotime($reference_date.' + '.$item['hard_warning'].' days') ) {
                    $level = 'hard';
                } else if( $now > strtotime($reference_date.' + '.$item['soft_warning'].' days') ) {
                    $level = 'soft';
                } else {
                    $level = 'normal';
                }

            } else {

                if( $now > strtotime($reference_date.' - '.$item['deadline'].' days') )
                {
                    $level = 'deadline';
                } else if( $now > strtotime($reference_date.' - '.$item['hard_warning'].' days') ) {
                    $level = 'hard';
                } else if( $now > strtotime($reference_date.' - '.$item['soft_warning'].' days') ) {
                    $level = 'soft';
                } else {
                    $level = 'normal';
                }
            }
            $data[$index]['level'] = $level;
        }
        $columns[] = array( 'db' => 'level', 'dt' => 'level' );

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

    public function getOfferLetterTrackerArray($job_application_id_array){
        $out = [];
        $sql =  "SELECT 
                    `workflow_offer_letter_tracker`.`job_application_id`,
                    `job_application`.`address_book_id`,
                    `workflow_offer_letter_tracker`.`created_on`, 
                    `workflow_offer_letter_tracker`.`created_by`,
                    `workflow_offer_letter_tracker`.`endorsement_expected_on`,
                    `workflow_offer_letter_tracker`.`offer_letter_file`,
                    `workflow_offer_letter_tracker`.`personal_data_file`,
                    `workflow_offer_letter_tracker`.`status`,
                    `job_demand_master`.`job_master_id`,
                    `endorser`.`main_email`,
                    `endorser`.`entity_family_name`,
                    `endorser`.`number_given_name`,
                    CONCAT(`candidate`.`entity_family_name`,' ', `candidate`.number_given_name) as candidate,
                    `job_master`.`job_title`,
                    `job_master`.`job_code`
                FROM    
                    `workflow_offer_letter_tracker`
                
                LEFT JOIN `address_book` `created` on `created`.`address_book_id` = workflow_offer_letter_tracker.created_by 
                JOIN `job_application` on `job_application`.`job_application_id` = `workflow_offer_letter_tracker`.`job_application_id` 
                JOIN `address_book` `candidate` on `candidate`.`address_book_id` = job_application.address_book_id 
                JOIN `job_demand_allocation` on `job_demand_allocation`.`address_book_id` = `job_application`.`address_book_id` 
                JOIN `job_demand_master` on `job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id` 
                JOIN `job_master` on `job_master`.`job_master_id` = `job_demand_master`.`job_master_id` 
                JOIN `offer_letter_endorser` on `offer_letter_endorser`.`job_master_id` = `job_demand_master`.`job_master_id` 
                JOIN `address_book` `endorser` on `endorser`.`address_book_id` = offer_letter_endorser.endorser_id 
                LEFT JOIN `workflow_offer_letter` on `workflow_offer_letter`.`milestone` = `workflow_offer_letter_tracker`.`status` 
                LEFT JOIN `secure_file` `endorsement_file` on `endorsement_file`.`file_id` = `workflow_offer_letter_tracker`.`endorsement_file` 
                LEFT JOIN `secure_file` `offer_letter_file` on `offer_letter_file`.`file_id` = `workflow_offer_letter_tracker`.`offer_letter_file` 
                LEFT JOIN `secure_file` `personal_data_file` on `personal_data_file`.`file_id` = `workflow_offer_letter_tracker`.`personal_data_file`
                
                WHERE
                    `workflow_offer_letter_tracker`.`job_application_id` in (".implode(',',$job_application_id_array).")
                    
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $job_application_id,
            $address_book_id,
            $created_on,
            $created_by,
            $endorsement_expected_on,
            $offer_letter_file,
            $personal_data_file,
            $status,
            $job_master_id,
            $main_email,
            $entity_family_name,
            $number_given_name,
            $candidate,
            $job_title,
            $job_code
        );
        $stmt->execute();
        while($stmt->fetch()){
            $out[] = [
                'job_application_id' => $job_application_id,
                'address_book_id' => $address_book_id,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'endorsement_expected_on' => $endorsement_expected_on,
                'offer_letter_file' => $offer_letter_file,
                'personal_data_file' => $personal_data_file,
                'status' => $status,
                'job_master_id' => $job_master_id,
                'main_email' => $main_email,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
                'candidate' => $candidate,
                'job_title' => $job_title,
                'job_code' => $job_code
            ];
        }
        $stmt->close();
        return $out;
    }

    public function getOfferLetterTracker($job_application_id){
        $out = null;
        $sql =  "SELECT 
                    `job_application_id`,
                    `created_on`, 
                    `created_by`,
                    `endorsement_expected_on`,
                    `offer_letter_file`,
                    `personal_data_file`,
                    `status`
                    FROM
                    `workflow_offer_letter_tracker`
                WHERE
                    `job_application_id` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $job_application_id);
        $stmt->bind_result(
            $job_application_id,
            $created_on,
            $created_by,
            $endorsement_expected_on,
            $offer_letter_file,
            $personal_data_file,
            $status
        );
        $stmt->execute();
        while($stmt->fetch()){
            $out = [
                'job_application_id' => $job_application_id,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'endorsement_expected_on' => $endorsement_expected_on,
                'offer_letter_file' => $offer_letter_file,
                'personal_data_file' => $personal_data_file,
                'status' => $status
            ];
        }
        $stmt->close();
        return $out;
    }
    
    public function getOfferLetterTrackerStatus($job_application_id)
    {
        $out = null;
        $sql =  "SELECT 
                    `workflow_offer_letter_tracker`.`job_application_id`,
                    `workflow_offer_letter_tracker`.`created_on`, 
                    `workflow_offer_letter_tracker`.`created_by`,
                    `workflow_offer_letter_tracker`.`endorsement_expected_on`,
                    `workflow_offer_letter_tracker`.`endorsement_complete_on`,
                    `workflow_offer_letter_tracker`.`offer_letter_file`,
                    `workflow_offer_letter_tracker`.`personal_data_file`,
                    `workflow_offer_letter_tracker`.`status`,
                    `workflow_offer_letter`.`soft_warning`,
                    `workflow_offer_letter`.`hard_warning`,
                    `workflow_offer_letter`.`deadline`,
                    `workflow_offer_letter`.`reference_milestone`,
                    `workflow_offer_letter`.`reference_direction`
                FROM
                    `workflow_offer_letter_tracker`
                LEFT JOIN `workflow_offer_letter` on `workflow_offer_letter_tracker`.`status` = `workflow_offer_letter`.`milestone`
                WHERE
                `workflow_offer_letter_tracker`.`job_application_id` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $job_application_id);
        $stmt->bind_result(
            $job_application_id,
            $created_on,
            $created_by,
            $endorsement_expected_on,
            $endorsement_complete_on,
            $offer_letter_file,
            $personal_data_file,
            $status,
            $soft_warning,
            $hard_warning,
            $deadline,
            $reference_milestone,
            $reference_direction
        );
        $stmt->execute();
        if($stmt->fetch()) {
            $out = [
                'job_application_id' => $job_application_id,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'endorsement_expected_on' => $endorsement_expected_on,
                'endorsement_complete_on' => $endorsement_complete_on,
                'offer_letter_file' => $offer_letter_file,
                'personal_data_file' => $personal_data_file,
                'status' => $status,
                'soft_warning' => $soft_warning,
                'hard_warning' => $hard_warning,
                'deadline' => $deadline,
                'reference_milestone' => $reference_milestone,
                'reference_direction' => $reference_direction
            ];
        }

        $stmt->close();
        $now = time();

        //calculate warning level
        if($out['reference_milestone'] == ''){
            $out['level'] = 'normal';

            return $out;
        }

        $reference_date = $out[$out['reference_milestone']];

        if($out['reference_direction'] == 'after')
        {
            if( $now > strtotime($reference_date.' + '.$out['deadline'].' days') )
            {
                $level = 'deadline';
            } else if( $now > strtotime($reference_date.' + '.$out['hard_warning'].' days') ) {
                $level = 'hard';
            } else if( $now > strtotime($reference_date.' + '.$out['soft_warning'].' days') ) {
                $level = 'soft';
            } else {
                $level = 'normal';
            }

        } else {

            if( $now > strtotime($reference_date.' - '.$out['deadline'].' days') )
            {
                $level = 'deadline';
            } else if( $now > strtotime($reference_date.' - '.$out['hard_warning'].' days') ) {
                $level = 'hard';
            } else if( $now > strtotime($reference_date.' - '.$out['soft_warning'].' days') ) {
                $level = 'soft';
            } else {
                $level = 'normal';
            }
        }
        $out['level'] = $level;

        
        return $out;
    }

    public function insertOfferLetterTrackerWithEndorsment($data){
        $out = null;
        $sql =  "INSERT INTO
                    `workflow_offer_letter_tracker`
                SET
                    `job_application_id` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `endorsement_expected_on` = ?,
                    `status` = 'endorsement'
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is',
            $data['job_application_id'],
            $data['endorsement_expected_on']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    public function insertOfferLetterTrackerWithoutEndorsment($data){
        $out = null;
        $sql =  "INSERT INTO
                    `workflow_offer_letter_tracker`
                SET
                    `job_application_id` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `endorsement_complete_on` = CURRENT_TIMESTAMP,
                    `endorsement_complete_by` = {$this->user_id},
                    `status` = 'offer_letter'
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateTrackerRequestOfferLetter($job_application_id, $date){
        $out = null;
        $sql =  "UPDATE
                    `workflow_offer_letter_tracker`
                SET
                    `request_offer_letter_on`= ?, 
                    `request_offer_letter_by`= {$this->user_id}
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $date,
            $job_application_id);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateTrackerOfferLetterFile($data){
        $out = null;
        $sql =  "UPDATE
                    `workflow_offer_letter_tracker`
                SET
                    `offer_letter_file_on`= CURRENT_TIMESTAMP, 
                    `offer_letter_file_by`= {$this->user_id},
                    `offer_letter_file`= ?,
                    `status`= 'candidate_acceptance'
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $data['offer_letter_file'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateTrackerAcceptanceOfferLetter($data){
        $out = null;
        $sql =  "UPDATE
                    `workflow_offer_letter_tracker`
                SET
                    `candidate_accepted_on`= ?, 
                    `offer_letter_file_by`= {$this->user_id},
                    `status` = ?
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['candidate_accepted_on'],
            $data['status'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }


    public function updateTrackerPersonalData($data){
        $out = null;
        $sql =  "UPDATE
                    `workflow_offer_letter_tracker`
                SET
                    `personal_data_uploaded_on`= CURRENT_TIMESTAMP, 
                    `personal_data_uploaded_by`= {$this->user_id},
                    `personal_data_file`= ?,
                    `status`= 'accepted'
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $data['personal_data_file'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateTrackerLoeFile($data) {
        $out = null;
        $sql =  "UPDATE
                    `workflow_offer_letter_tracker`
                SET
                    `loe_file_on`= CURRENT_TIMESTAMP, 
                    `loe_file_by`= {$this->user_id},
                    `loe_file`= ?
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $data['loe_file'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateTrackerToDeployment($job_application_id, $status)
    {
        $out = false;

        $sql = "UPDATE
                    `workflow_offer_letter_tracker`
                SET
                    `status` = ?
                WHERE
                    `job_application_id` = ?
                AND
                    `status` = 'accepted'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $job_application_id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }
	
}