<?php
namespace core\modules\finance\models\common;

/**
 * Final finance db class.
 *
 * @final
 * @package		finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
 */
final class db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}

	/*
	 * Finance PSF Tracker
	 */
	public function getFinancePSFDatatable(){
        $request = $_POST;
        $this->validateRequest($request, ['status','level','start_date', 'end_date']);
        $table = 'finance_psf_tracker';

        $primaryKey = 'finance_psf_tracker.job_application_id';

        $columns = array(
            array( 'db' => 'finance_psf_tracker.`job_application_id`', 'dt' => 'job_application_id' ),
            array( 'db' => 'finance_psf_tracker.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => 'finance_psf_tracker.`invoice_on`', 'dt' => 'invoice_on' ),
            array( 'db' => 'finance_psf_tracker.`invoice_expected_on`', 'dt' => 'invoice_expected_on', 'formatter' => function( $d, $row ) {return date( 'd M Y', strtotime($d));} ),
            array( 'db' => 'finance_psf_tracker.`invoice_paid_on`', 'dt' => 'invoice_paid_on' ),
            array( 'db' => 'finance_psf_tracker.`notes`', 'dt' => 'notes' ),
            array( 'db' => 'finance_psf_tracker.`status`', 'dt' => 'status' ),
            array( 'db' => 'finance_psf_tracker.`level`', 'dt' => 'level' ),

            array( 'db' => 'finance_psf_workflow.`code`', 'dt' => 'code' ),
            array( 'db' => 'finance_psf_workflow.`short_description`', 'dt' => 'short_description' ),
            array( 'db' => 'finance_psf_workflow.`soft_warning`', 'dt' => 'soft_warning' ),
            array( 'db' => 'finance_psf_workflow.`hard_warning`', 'dt' => 'hard_warning' ),
            array( 'db' => 'finance_psf_workflow.`deadline`', 'dt' => 'deadline' ),
            array( 'db' => 'finance_psf_workflow.`reference_milestone`', 'dt' => 'reference_milestone' ),
            array( 'db' => 'finance_psf_workflow.`reference_direction`', 'dt' => 'reference_direction' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => 'address_book.`entity_family_name`', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.`number_given_name`', 'dt' => 'number_given_name' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN `job_application` on `job_application`.`job_application_id` = `finance_psf_tracker`.`job_application_id` ';
        $join .= ' JOIN `address_book` on `address_book`.`address_book_id` = `job_application`.`job_application_id` ';
        $join .= ' JOIN `finance_psf_workflow` on `finance_psf_workflow`.`milestone` = `finance_psf_tracker`.`status` ';

        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `finance_psf_tracker`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `finance_psf_tracker`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['level']) && $request['level'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_psf_tracker`.`level` = "'.$request['level'].'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_psf_tracker`.`status` = "'.$request['status'].'" ';
        }else{
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_psf_tracker`.`status` NOT IN (\'paid\', \'cancelled\') ';
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

    public function insertFinancePSFTracker($job_application_id){
        $out = null;
        $sql =  "INSERT INTO
                    `finance_psf_tracker`
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

    public function updateFinancePsfGenerateInvoice($data){
        $out = null;
        $now = date('Y-m-d H:i:s');
        $sql =  "UPDATE
                    `finance_psf_tracker`
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

    public function updateFinanceTravelpackGenerateInvoice($data){
        $out = null;
        $now = date('Y-m-d H:i:s');
        $sql =  "UPDATE
                    `finance_travelpack_tracker`
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

    public function updateFinancePsfPayInvoice($data){
        $out = null;
        $sql =  "UPDATE
                    `finance_psf_tracker`
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

    public function updateFinanceTravelpackPayInvoice($data){
        $out = null;
        $sql =  "UPDATE
                    `finance_travelpack_tracker`
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

    public function updateFinancePrincipalGenerateInvoice($data){
        $out = null;
        $now = date('Y-m-d H:i:s');
        $sql =  "UPDATE
                    `finance_principal_tracker`
                SET
                    `invoice_expected_on`= ?, 
                    `invoice_number`=?,
                    `status`= 'pay_invoice', 
                    `invoice_on`= CURRENT_TIMESTAMP,
                    `invoice_by`= {$this->user_id},
                    `created_on`= '$now',
                    `level` = 1
                WHERE
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sii',
            $data['invoice_expected_on'],
            $data['invoice_number'],
            $data['job_application_id']);

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateFinancePrincipalPayInvoice($data){
        $out = null;
        $sql =  "UPDATE
                    `finance_principal_tracker`
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

    public function insertFinancePrincipalTracker($job_application_id){
        $out = null;
        $sql =  "INSERT INTO
                    `finance_principal_tracker`
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

    public function insertFinanceTravelpackTracker($job_application_id){
        $out = null;
        $sql =  "INSERT INTO
                    `finance_travelpack_tracker`
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

    public function getFinancePrincipalDatatable(){
        $request = $_POST;
        $this->validateRequest($request, ['status', 'level', 'start_date', 'end_date']);
        $table = 'finance_principal_tracker';

        $primaryKey = 'finance_principal_tracker.job_application_id';

        $columns = array(
            array( 'db' => 'finance_principal_tracker.`job_application_id`', 'dt' => 'job_application_id' ),
            array( 'db' => 'finance_principal_tracker.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => 'finance_principal_tracker.`invoice_on`', 'dt' => 'invoice_on' ),
            array( 'db' => 'finance_principal_tracker.`invoice_expected_on`', 'dt' => 'invoice_expected_on', 'formatter' => function( $d, $row ) {return date( 'd M Y', strtotime($d));} ),
            array( 'db' => 'finance_principal_tracker.`invoice_paid_on`', 'dt' => 'invoice_paid_on' ),
            array( 'db' => 'finance_principal_tracker.`notes`', 'dt' => 'notes' ),
            array( 'db' => 'finance_principal_tracker.`status`', 'dt' => 'status' ),
            array( 'db' => 'finance_principal_tracker.`level`', 'dt' => 'level' ),

            array( 'db' => 'finance_principal_workflow.`code`', 'dt' => 'code' ),
            array( 'db' => 'finance_principal_workflow.`short_description`', 'dt' => 'short_description' ),
            array( 'db' => 'finance_principal_workflow.`soft_warning`', 'dt' => 'soft_warning' ),
            array( 'db' => 'finance_principal_workflow.`hard_warning`', 'dt' => 'hard_warning' ),
            array( 'db' => 'finance_principal_workflow.`deadline`', 'dt' => 'deadline' ),
            array( 'db' => 'finance_principal_workflow.`reference_milestone`', 'dt' => 'reference_milestone' ),
            array( 'db' => 'finance_principal_workflow.`reference_direction`', 'dt' => 'reference_direction' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => 'address_book.`entity_family_name`', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.`number_given_name`', 'dt' => 'number_given_name' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN `job_application` on `job_application`.`job_application_id` = `finance_principal_tracker`.`job_application_id` ';
        $join .= ' JOIN `address_book` on `address_book`.`address_book_id` = `job_application`.`job_application_id` ';
        $join .= ' JOIN `finance_principal_workflow` on `finance_principal_workflow`.`milestone` = `finance_principal_tracker`.`status` ';

        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `finance_principal_tracker`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `finance_principal_tracker`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['level']) && $request['level'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_principal_tracker`.`level` = "'.$request['level'].'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_principal_tracker`.`status` = "'.$request['status'].'" ';
        }else{
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_principal_tracker`.`status` NOT IN (\'paid\', \'cancelled\') ';
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

    public function getFinanceTravelpackDatatable(){
        $request = $_POST;
        $this->validateRequest($request, ['status','level','start_date', 'end_date']);
        $table = 'finance_travelpack_tracker';

        $primaryKey = 'finance_travelpack_tracker.job_application_id';

        $columns = array(
            array( 'db' => 'finance_travelpack_tracker.`job_application_id`', 'dt' => 'job_application_id' ),
            array( 'db' => 'finance_travelpack_tracker.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => 'finance_travelpack_tracker.`invoice_on`', 'dt' => 'invoice_on' ),
            array( 'db' => 'finance_travelpack_tracker.`invoice_expected_on`', 'dt' => 'invoice_expected_on', 'formatter' => function( $d, $row ) {return date( 'd M Y', strtotime($d));} ),
            array( 'db' => 'finance_travelpack_tracker.`invoice_paid_on`', 'dt' => 'invoice_paid_on' ),
            array( 'db' => 'finance_travelpack_tracker.`notes`', 'dt' => 'notes' ),
            array( 'db' => 'finance_travelpack_tracker.`status`', 'dt' => 'status' ),
            array( 'db' => 'finance_travelpack_tracker.`level`', 'dt' => 'level' ),

            array( 'db' => 'finance_travelpack_workflow.`code`', 'dt' => 'code' ),
            array( 'db' => 'finance_travelpack_workflow.`short_description`', 'dt' => 'short_description' ),
            array( 'db' => 'finance_travelpack_workflow.`soft_warning`', 'dt' => 'soft_warning' ),
            array( 'db' => 'finance_travelpack_workflow.`hard_warning`', 'dt' => 'hard_warning' ),
            array( 'db' => 'finance_travelpack_workflow.`deadline`', 'dt' => 'deadline' ),
            array( 'db' => 'finance_travelpack_workflow.`reference_milestone`', 'dt' => 'reference_milestone' ),
            array( 'db' => 'finance_travelpack_workflow.`reference_direction`', 'dt' => 'reference_direction' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'candidate', 'dt' => 'candidate' ),
            array( 'db' => 'address_book.`entity_family_name`', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.`number_given_name`', 'dt' => 'number_given_name' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN `job_application` on `job_application`.`job_application_id` = `finance_travelpack_tracker`.`job_application_id` ';
        $join .= ' JOIN `address_book` on `address_book`.`address_book_id` = `job_application`.`job_application_id` ';
        $join .= ' JOIN `finance_travelpack_workflow` on `finance_travelpack_workflow`.`milestone` = `finance_travelpack_tracker`.`status` ';

        $where = $this->filter( $request, $columns, $bindings  );

        if (isset($request['start_date']) && !empty($request['start_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request['start_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `finance_travelpack_tracker`.`created_on` >= "'.$start_date.'" ';
        }

        if (isset($request['end_date']) && !empty($request['end_date'])) {
            $end_date = date('Y-m-d 00:00:00', strtotime($request['end_date']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= ' `finance_travelpack_tracker`.`created_on` <= "'.$end_date.'" ';
        }

        if(isset($request['level']) && $request['level'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_travelpack_tracker`.`level` = "'.$request['level'].'" ';
        }

        if(isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_travelpack_tracker`.`status` = "'.$request['status'].'" ';
        }else{
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `finance_travelpack_tracker`.`status` NOT IN (\'paid\', \'cancelled\') ';
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


    public function selectTrackerLevel($table){
	    $workflow_tbl = str_replace('_tracker','_workflow',$table);
        $out = [];
        $sql =  "SELECT 
                    $table.`job_application_id`,
                    $table.`created_on`,
                    $table.`invoice_on`,
                    $table.`invoice_expected_on`,
                    $table.`invoice_paid_on`,
                    $table.`status`, 
                    $workflow_tbl.`milestone`, 
                    $workflow_tbl.`soft_warning`, 
                    $workflow_tbl.`hard_warning`, 
                    $workflow_tbl.`deadline`, 
                    $workflow_tbl.`reference_direction`, 
                    $workflow_tbl.`reference_milestone`
                FROM 
                    $table
                
                JOIN $workflow_tbl on $workflow_tbl.`milestone` = $table.`status`
                
                WHERE $table.`status` NOT IN ('accepted', 'denied')
                	";

        return $this->db->query_array($sql);

    }

    public function updateTrackerLevel($table, $job_application_id, $level){
        $out = null;
        $sql =  "UPDATE
                    $table
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

    /*
     * Reports
     */
    function getReportsDatatable(){
        $request = $_POST;
        $this->validateRequest($request);
        $table = 'finance_reports';

        $primaryKey = 'finance_reports.address_book_id';

        $columns = array(
            array( 'db' => 'finance_reports.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => 'finance_reports.`level`', 'dt' => 'level' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'reporter', 'dt' => 'reporter' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` on `address_book`.`address_book_id` = finance_reports.address_book_id ';

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

    function getReportsArray(){
        $table = '`finance_reports`';
        $sql =  "SELECT 
                    $table.`address_book_id`,
                    $table.`level`,
                    `address_book`.`number_given_name`,
                    `address_book`.`entity_family_name`,
                    `address_book`.`main_email`
                FROM 
                    $table
                JOIN `address_book` on `address_book`.`address_book_id` = $table.`address_book_id`
                
                ORDER BY $table.`level`
                	";

        return $this->db->query_array($sql);
    }

    public function insertReports($data){
        $out = null;
        $sql =  "INSERT INTO
                    `finance_reports`
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
                    FROM `finance_reports`
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

    public function getTotalPsfTrackerByLevel(){
        $sql =  "SELECT 
                    COUNT(`finance_psf_tracker`.`job_application_id`) as total,
                    `finance_psf_tracker`.`level`
                FROM 
                    `finance_psf_tracker`
                    
                WHERE `finance_psf_tracker`.`status` NOT IN ('paid', 'canceled')
                	
       			GROUP BY `finance_psf_tracker`.`level`";
        $data = $this->db->query_array($sql);
        return $data;
    }
}