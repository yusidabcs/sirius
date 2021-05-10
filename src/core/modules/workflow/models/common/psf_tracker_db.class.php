<?php
namespace core\modules\workflow\models\common;

/**
 * Final finance db class.
 *
 * @final
 * @package		finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
 */
final class psf_tracker_db extends \core\app\classes\module_base\module_db {

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    /*
     * Finance PSF Tracker
     */
    public function getPSFDatatable(){
        $request = $_POST;
        $this->validateRequest($request, ['status','level','start_date', 'end_date']);
        $table = 'workflow_psf_tracker';

        $primaryKey = 'workflow_psf_tracker.job_application_id';

        $columns = array(
            array( 'db' => 'workflow_psf_tracker.`job_application_id`', 'dt' => 'job_application_id' ),
            array( 'db' => 'workflow_psf_tracker.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => 'workflow_psf_tracker.`invoice_on`', 'dt' => 'invoice_on' ),
            array( 'db' => 'workflow_psf_tracker.`invoice_expected_on`', 'dt' => 'invoice_expected_on', 'formatter' => function( $d, $row ) {return date( 'd M Y', strtotime($d));} ),
            array( 'db' => 'workflow_psf_tracker.`invoice_paid_on`', 'dt' => 'invoice_paid_on' ),
            array( 'db' => 'workflow_psf_tracker.`notes`', 'dt' => 'notes' ),
            array( 'db' => 'workflow_psf_tracker.`status`', 'dt' => 'status' ),
            array( 'db' => 'workflow_psf_tracker.`level`', 'dt' => 'level' ),

            array( 'db' => 'workflow_psf_workflow.`code`', 'dt' => 'code' ),
            array( 'db' => 'workflow_psf_workflow.`short_description`', 'dt' => 'short_description' ),
            array( 'db' => 'workflow_psf_workflow.`soft_warning`', 'dt' => 'soft_warning' ),
            array( 'db' => 'workflow_psf_workflow.`hard_warning`', 'dt' => 'hard_warning' ),
            array( 'db' => 'workflow_psf_workflow.`deadline`', 'dt' => 'deadline' ),
            array( 'db' => 'workflow_psf_workflow.`reference_milestone`', 'dt' => 'reference_milestone' ),
            array( 'db' => 'workflow_psf_workflow.`reference_direction`', 'dt' => 'reference_direction' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => 'address_book.`entity_family_name`', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.`number_given_name`', 'dt' => 'number_given_name' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN `job_application` on `job_application`.`job_application_id` = `workflow_psf_tracker`.`job_application_id` ';
        $join .= ' JOIN `address_book` on `address_book`.`address_book_id` = `job_application`.`job_application_id` ';
        $join .= ' LEFT JOIN `workflow_psf_workflow` on `workflow_psf_workflow`.`milestone` = `workflow_psf_tracker`.`status` ';

        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `workflow_psf_tracker`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `workflow_psf_tracker`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['level']) && $request['level'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_psf_tracker`.`level` = "'.$request['level'].'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_psf_tracker`.`status` = "'.$request['status'].'" ';
        }else{
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `workflow_psf_tracker`.`status` NOT IN (\'paid\', \'cancelled\') ';
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

    public function insertPSFTracker($job_application_id){
        $out = null;
        $sql =  "INSERT INTO
                    `workflow_psf_tracker`
                SET 
                    `status`= 'generate_invoice', 
                    `created_on`= CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $job_application_id);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updatePsfGenerateInvoice($data){
        $out = null;
        $now = date('Y-m-d H:i:s');
        $sql =  "UPDATE
                    `workflow_psf_tracker`
                SET
                    `invoice_number`=?,
                    `invoice_expected_on`= ?, 
                    `status`= 'pay_invoice', 
                    `invoice_on`= CURRENT_TIMESTAMP,
                    `invoice_by`= {$this->user_id},
                    `created_on`= '{$now}',
                    `level` = 1
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['invoice_number'],
            $data['invoice_expected_on'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updatePsfPayInvoice($data){
        $out = null;
        $sql =  "UPDATE
                    `workflow_psf_tracker`
                SET
                    `status`= ?, 
                    `notes`= ?, 
                    `invoice_paid_on`= CURRENT_TIMESTAMP,
                    `invoice_paid_by`= {$this->user_id},
                    `level` = 1
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['status'],
            $data['notes'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getTotalPsfTrackerByLevel(){
        $sql =  "SELECT 
                    COUNT(`workflow_psf_tracker`.`job_application_id`) as total,
                    `workflow_psf_tracker`.`level`
                FROM 
                    `workflow_psf_tracker`
                    
                WHERE `workflow_psf_tracker`.`status` NOT IN ('paid', 'canceled')
                	
       			GROUP BY `workflow_psf_tracker`.`level`";
        $data = $this->db->query_array($sql);
        return $data;
    }

    public function getTotalPsfTrackerByLevelPartner(){
        $sql =  "SELECT 
                    COUNT(`workflow_psf_tracker`.`job_application_id`) as total,
                    `workflow_psf_tracker`.`level`,`address_book_connection`.`connection_id`
                FROM 
                    `workflow_psf_tracker`
                JOIN
                `job_application` ON `workflow_psf_tracker`.`job_application_id`=`job_application`.`job_application_id`
                JOIN
                `address_book_connection` ON `job_application`.`address_book_id`=`address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`='lp'
                    
                WHERE `workflow_psf_tracker`.`status` NOT IN ('paid')
                	
       			GROUP BY `workflow_psf_tracker`.`level`,`address_book_connection`.`connection_id`";
        $data = $this->db->query_array($sql);
        return $data;
    }

    public function updateTrackerLevel(){
        $out = [];
        $sql =  "SELECT 
                    `workflow_psf_tracker`.`job_application_id`,
                    `workflow_psf_tracker`.`created_on`,
                    `workflow_psf_tracker`.`invoice_on`,
                    `workflow_psf_tracker`.`invoice_expected_on`,
                    `workflow_psf_tracker`.`invoice_paid_on`,
                    `workflow_psf_tracker`.`status`, 
                    `workflow_psf_workflow`.`principal_code`, 
                    `workflow_psf_workflow`.`milestone`, 
                    `workflow_psf_workflow`.`soft_warning`, 
                    `workflow_psf_workflow`.`hard_warning`, 
                    `workflow_psf_workflow`.`deadline`, 
                    `workflow_psf_workflow`.`reference_direction`, 
                    `workflow_psf_workflow`.`reference_milestone`
                FROM 
                    `workflow_psf_tracker`
                
                JOIN `interview_result_principal` on `workflow_psf_tracker`.`job_application_id` = `interview_result_principal`.`job_application_id`
                
                JOIN `workflow_psf_workflow` on `workflow_psf_workflow`.`milestone` = `workflow_psf_tracker`.`status` AND `workflow_psf_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                
                WHERE `workflow_psf_tracker`.`status` NOT IN ('paid')
                	
       			GROUP BY `workflow_psf_tracker`.`job_application_id`";

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

            $this->updatePsfTrackerLevel($item['job_application_id'], $level);

        }
        return $out;
    }

    public function updatePsfTrackerLevel($job_application_id, $level){
        $out = null;
        $sql =  "UPDATE
                    `workflow_psf_tracker`
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
}