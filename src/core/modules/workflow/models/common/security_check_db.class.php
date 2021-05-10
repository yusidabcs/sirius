<?php
namespace core\modules\workflow\models\common;

/**
 * Final workflow_security_tracker db class.
 *
 * @final
 * @package		workflow_security_tracker
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 Jun 2020
 */
final class security_check_db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}

    public function getInterviewSecurityCheckDatatable(){
        $request = $_POST;
        $this->validateRequest($request, ['status','level']);
        $table = 'workflow_security_tracker';

        $primaryKey = 'workflow_security_tracker.job_application_id';

        $columns = array(
            array( 'db' => '`workflow_security_tracker`.`job_application_id`', 'dt' => 'job_application_id' ),
            array( 'db' => '`workflow_security_tracker`.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => '`workflow_security_tracker`.`request_file_on`', 'dt' => 'request_file_on' ),
            array( 'db' => '`workflow_security_tracker`.`passport_file_on`', 'dt' => 'passport_file_on' ),
            array( 'db' => '`workflow_security_tracker`.`passport_file`', 'dt' => 'passport_file' ),
            array( 'db' => '`workflow_security_tracker`.`clearance_file`', 'dt' => 'clearance_file' ),
            array( 'db' => '`workflow_security_tracker`.`request_clearance_on`', 'dt' => 'request_clearance_on' ),
            array( 'db' => '`workflow_security_tracker`.`created_by`', 'dt' => 'created_by' ),
            array( 'db' => '`workflow_security_tracker`.`status`', 'dt' => 'status' ),
            array( 'db' => '`workflow_security_tracker`.`level`', 'dt' => 'level' ),
            array( 'db' => 'CONCAT(`candidate`.`entity_family_name`,\' \', `candidate`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => '`candidate`.main_email', 'dt' => 'main_email' ),
            array( 'db' => '`job_speedy`.`job_title`', 'dt' => 'job_title' ),
            array( 'db' => '`workflow_security_workflow`.`soft_warning`', 'dt' => 'soft_warning' ),
            array( 'db' => '`workflow_security_workflow`.`hard_warning`', 'dt' => 'hard_warning' ),
            array( 'db' => '`workflow_security_workflow`.`deadline`', 'dt' => 'deadline' ),
            array( 'db' => '`workflow_security_workflow`.`reference_milestone`', 'dt' => 'reference_milestone' ),
            array( 'db' => '`workflow_security_workflow`.`reference_direction`', 'dt' => 'reference_direction' ),
            array( 'db' => '`workflow_security_workflow`.`code`', 'dt' => 'code' ),
            array( 'db' => '`workflow_security_workflow`.`short_description`', 'dt' => 'short_description' ),
            array( 'db' => '`passport_file`.`hash`', 'as' => 'passport_file_hash', 'dt' => 'passport_file_hash' ),
            array( 'db' => '`clearance_file`.`hash`', 'as' => 'clearance_file_hash', 'dt' => 'clearance_file_hash' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` `created` on `created`.`address_book_id` = workflow_security_tracker.created_by
                  JOIN `job_application` on `job_application`.`job_application_id` = `workflow_security_tracker`.`job_application_id`
                  JOIN `address_book` `candidate` on `candidate`.`address_book_id` = job_application.address_book_id
                  JOIN `job_speedy` on `job_speedy`.`job_speedy_code` = `job_application`.`job_speedy_code`
                  LEFT JOIN `interview_result_principal` on `workflow_security_tracker`.`job_application_id` = `interview_result_principal`.`job_application_id`
                  LEFT JOIN `workflow_security_workflow` on `workflow_security_workflow`.`milestone` = `workflow_security_tracker`.`status` AND `workflow_security_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                  LEFT JOIN `secure_file` `passport_file` on `passport_file`.`file_id` = `workflow_security_tracker`.`passport_file`
                  LEFT JOIN `secure_file` `clearance_file` on `clearance_file`.`file_id` = `workflow_security_tracker`.`clearance_file`
        ';

        $group = ' GROUP BY `workflow_security_tracker`.`job_application_id` ';
        $where = $this->filter( $request, $columns, $bindings  );

        if(isset($request['level']) && $request['level'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_security_tracker`.`level` = "'.$request['level'].'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_security_tracker`.`status` = "'.$request['status'].'" ';
        }else{
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_security_tracker`.`status` NOT IN (\'accepted\', \'denied\') ';
        }

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $group
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

    public function getTotalTrackerByLevel(){
        $sql =  "SELECT 
                    COUNT(`workflow_security_tracker`.`job_application_id`) as total,
                    `workflow_security_tracker`.`level`
                FROM 
                    `workflow_security_tracker`
                
                JOIN `interview_result_principal` on `workflow_security_tracker`.`job_application_id` = `interview_result_principal`.`job_application_id`
                JOIN `workflow_security_workflow` on `workflow_security_workflow`.`milestone` = `workflow_security_tracker`.`status` AND `workflow_security_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                
                WHERE `workflow_security_tracker`.`status` NOT IN ('accepted', 'denied')
                	
       			GROUP BY `workflow_security_tracker`.`level`";
        $data = $this->db->query_array($sql);
        return $data;
    }

    public function getTotalTrackerByLevelPartner() {
        $sql =  "SELECT 
                    COUNT(`workflow_security_tracker`.`job_application_id`) as total,
                    `workflow_security_tracker`.`level`,`address_book_connection`.`connection_id`
                FROM 
                    `workflow_security_tracker`
                JOIN
                `job_application` ON `workflow_security_tracker`.`job_application_id`=`job_application`.`job_application_id`
                JOIN
                `address_book_connection` ON `job_application`.`address_book_id`=`address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`='lp'
                
                WHERE `workflow_security_tracker`.`status` NOT IN ('accepted')
                	
       			GROUP BY `workflow_security_tracker`.`level`,`address_book_connection`.`connection_id`";
        $data = $this->db->query_array($sql);
        return $data;
    }

	public function updateTrackerLevel(){
        $out = [];
        $sql =  "SELECT 
                    `workflow_security_tracker`.`job_application_id`,
                    `workflow_security_tracker`.`created_on`,
                    `workflow_security_tracker`.`request_file_on`,
                    `workflow_security_tracker`.`passport_file_on`,
                    `workflow_security_tracker`.`request_clearance_on`,
                    `workflow_security_tracker`.`status`, 
                    `workflow_security_workflow`.`principal_code`, 
                    `workflow_security_workflow`.`milestone`, 
                    `workflow_security_workflow`.`soft_warning`, 
                    `workflow_security_workflow`.`hard_warning`, 
                    `workflow_security_workflow`.`deadline`, 
                    `workflow_security_workflow`.`reference_direction`, 
                    `workflow_security_workflow`.`reference_milestone`
                FROM 
                    `workflow_security_tracker`
                
                JOIN `interview_result_principal` on `workflow_security_tracker`.`job_application_id` = `interview_result_principal`.`job_application_id`
                
                JOIN `workflow_security_workflow` on `workflow_security_workflow`.`milestone` = `workflow_security_tracker`.`status` AND `workflow_security_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                
                WHERE `workflow_security_tracker`.`status` NOT IN ('accepted')
                	
       			GROUP BY `workflow_security_tracker`.`job_application_id`";

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

            $this->updateInterviewSecurityTrackerLevel($item['job_application_id'], $level);

        }
        return $out;
    }

    public function updateInterviewSecurityTrackerLevel($job_application_id, $level){
        $out = null;
        $sql =  "UPDATE
                    `workflow_security_tracker`
                SET
                    `level` = {$level}
                WHERE
                    `job_application_id` = {$job_application_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getInterviewSecurityCheck($job_application){
        $out = null;
        $sql =  "SELECT 
                    `workflow_security_tracker`.`job_application_id`,
                    `workflow_security_tracker`.`status`,
                    `workflow_security_tracker`.`passport_file`,
                    `workflow_security_tracker`.`clearance_file`,
                    `workflow_security_tracker`.`notes`
                FROM
                    `workflow_security_tracker`
                WHERE
                    `workflow_security_tracker`.`job_application_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $job_application);
        $stmt->bind_result(
            $job_application_id,
            $status,
            $passport_file,
            $clearance_file,
            $notes
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = array(
                'job_application_id' => $job_application_id,
                'status' => $status,
                'passport_file' => $passport_file,
                'clearance_file' => $clearance_file,
                'notes' => $notes
            );

        }

        $stmt->close();
        return $out;
    }
    public function getInterviewSecurityCheckByArray($job_application_array){
        $out = [];
        $sql =  "SELECT 
                    `workflow_security_tracker`.`job_application_id`,
                    `job_application`.`address_book_id`,
                    concat(`address_book_per`.title,' ' ,`address_book`.entity_family_name,' ' ,`address_book`.number_given_name) as candidate,
                    `address_book`.`main_email` as candidate_email,
                    IF(`personal_passport`.`countryCode_id` IS NULL,`address_book_address`.`country`,`personal_passport`.`countryCode_id` ) as `countryCode_id`,
                    `workflow_security_checker`.`checker_id`,
                    `workflow_security_checker`.`principal_code`,
                    `checker`.`main_email`,
                    `checker`.`entity_family_name`,
                    `checker`.`number_given_name`,
                    `secure_file`.`hash`
                FROM 
                    `workflow_security_tracker`
                JOIN 
                	`interview_result_principal` on `workflow_security_tracker`.`job_application_id` = `interview_result_principal`.`job_application_id`
                JOIN 
                	`job_application` on `job_application`.`job_application_id` = `workflow_security_tracker`.`job_application_id`
                JOIN 
                    `address_book_per` ON `job_application`.`address_book_id` = `address_book_per`.`address_book_id`
                JOIN 
                    `address_book` ON `job_application`.`address_book_id` = `address_book`.`address_book_id` 
                LEFT JOIN 
                	(select `address_book_id`,`countryCode_id`,`from_date` from `personal_passport` where `personal_passport`.`active` = 'active' ORDER by from_date DESC) `personal_passport` on `personal_passport`.`address_book_id` = `job_application`.`address_book_id`
                LEFT JOIN 
                	`address_book_address` on `address_book_address`.`address_book_id` = `job_application`.`address_book_id`
                JOIN 
                	`workflow_security_checker` on `workflow_security_checker`.`principal_code` = `interview_result_principal`.`principal_code` AND `workflow_security_checker`.`countryCode_id` = IF(`personal_passport`.`countryCode_id` IS NULL,`address_book_address`.`country`,`personal_passport`.`countryCode_id` )
                JOIN 
                	`address_book` `checker` on `checker`.`address_book_id` = `workflow_security_checker`.`checker_id`
                JOIN 
                	`secure_file` on `secure_file`.`file_id` = `workflow_security_tracker`.`passport_file`
	
       			WHERE `workflow_security_tracker`.`job_application_id` in (".implode(',',$job_application_array).")";

        $stmt = $this->db->prepare($sql);
        //$stmt->bind_param('s', implode(',',$job_application_array));
        $stmt->bind_result(
            $job_application_id,
            $address_book_id,
            $candidate,
            $candidate_email,
            $countryCode_id,
            $checker_id,
            $principal_code,
            $main_email,
            $entity_family_name,
            $number_given_name,
            $hash
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'job_application_id' => $job_application_id,
                'address_book_id' => $address_book_id,
                'candidate' =>  $candidate,
                'candidate_email' => $candidate_email,
                'countryCode_id' => $countryCode_id,
                'checker_id' => $checker_id,
                'principal_code' => $principal_code,
                'main_email' => $main_email,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
                'hash' => $hash
            );

        }

        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurityCheckRequestFile($job_application_id){
        $out = null;
        $sql =  "UPDATE
                    `workflow_security_tracker`
                SET
                    `request_file_on` = CURRENT_TIMESTAMP,
                    `request_file_by`= {$this->user_id}
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurityCheckRequestedFile($job_application_id,$file){
        $out = null;
        $sql =  "UPDATE
                    `workflow_security_tracker`
                SET
                    `status` = 'request_clearance',
                    `passport_file` = ?,
                    `passport_file_on` = CURRENT_TIMESTAMP,
                    `passport_file_by`= {$this->user_id}
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$file,$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurityCheckRequestClearance($job_application_id){
        $out = null;
        $sql =  "UPDATE
                    `workflow_security_tracker`
                SET
                    `request_clearance_on` = CURRENT_TIMESTAMP,
                    `request_clearance_by`= {$this->user_id}
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurityCheckClearanceFileStatus($job_application_id,$file, $status){
        $out = null;
        $sql =  "UPDATE
                    `workflow_security_tracker`
                SET
                    `status` = ?,
                    `clearance_file` = ?,
                    `clearance_file_on` = CURRENT_TIMESTAMP,
                    `clearance_file_by`= {$this->user_id}
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',$status, $file,$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
}