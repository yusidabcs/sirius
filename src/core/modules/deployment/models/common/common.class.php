<?php
namespace core\modules\deployment\models\common;

final class common extends \core\app\classes\module_base\module_db {

    public $table = 'deployment_master';
    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    public function getTrackerDatatableDeployment() {
        $this->generic = \core\app\classes\generic\generic::getInstance();

        $tablename ='deployment_master';
		$request = $_POST;
        $this->validateRequest($request, ['status','startDate','endDate']);
		$primaryKey = $tablename.'.address_book_id';

        $columns = array(
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
            array( 'db' => $tablename.'.deploy_date', 'dt' => 'deployment_date'),
            array( 'db' => $tablename.'.loe_file', 'dt' => 'loe_file'),
            array( 'db' => $tablename.'.loe_date', 'dt' => 'loe_date'),
            array( 'db' => '`job_master`.`job_title`', 'dt' => 'job_title' ),
            array( 'db' => '`job_master`.`job_code`', 'dt' => 'job_code' ),
            array( 'db' => '`job_master`.`principal_code`', 'dt' => 'principal_code' ),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
			array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'user.user_id', 'dt' => 'user_id'),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'fullname', 'formatter' => function ($d, $row) {
                return $this->generic->getName('per', $row['entity_family_name'], $row['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            }),
            
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "LEFT JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
        $join .= " LEFT JOIN `user` on address_book.main_email = user.email
                LEFT JOIN `job_demand_master` on $tablename.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id` 
                LEFT JOIN `job_master` on `job_master`.`job_master_id` = `job_demand_master`.`job_master_id`
        ";


        if (isset($request['status']) && !empty($request['status'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "$tablename.`status` = '".$request['status']."'";
        }

		
		//filter by date
		if ((isset($request['startDate']) && !empty($request['startDate']))) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`deploy_date`) >= '".date('Y-m-d',strtotime($request['startDate']))."'";
		}
		if (isset($request['endDate']) && !empty($request['endDate'])) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`deploy_date`) <= '".date('Y-m-d',strtotime($request['endDate']))."'";
        }
        
        $order .= (strpos(strtolower($order),'order by') === false)? ' ORDER BY ' :  ', ';
        $order .= "`deployment_master`.`deploy_date` desc";

        $select = "
        ,(
            SELECT `job_application`.`job_application_id` FROM `job_application` where `job_application`.`address_book_id`= ".$tablename.".`address_book_id` AND `job_application`.`status`='allocated' ORDER BY `job_application`.`job_application_id` DESC LIMIT 1
            ) AS job_application_id 
        ";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db')).$select."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

            //echo $qry1;
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
        array_push($columns, array('dt' => 'job_application_id','as' => 'job_application_id'));
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function getStatusTracker($table,$primary_value,$primary_field='address_book_id') {
        $out = '';
	    $sql = "SELECT `status` from $table
				WHERE $primary_field = ?
                ORDER BY $primary_field DESC
                LIMIT 1
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $primary_value);
        $stmt->bind_result($status);
        $stmt->execute();
        while($stmt->fetch()){
            $out = $status;
        }
        return $out;
    }
}