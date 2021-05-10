<?php
namespace core\modules\workflow\models\common;
/*
 * Final interview/db class.
 *
 * @final
 * 
 */
final class security_checker_db extends \core\app\classes\module_base\module_db_report  {

	public function __construct()
	{

        parent::__construct('local', 'interview'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
    /*
     * get interview security list for datatable
     */
    function getInterviewSecurityDatatable(){
        $request = $_POST;
        $this->validateRequest($request);
        $table = 'workflow_security_checker';

        $primaryKey = 'workflow_security_checker.principal_code';

        $columns = array(
            array( 'db' => '`workflow_security_checker`.`principal_code`', 'dt' => 'principal_code' ),
            array( 'db' => 'workflow_security_checker.countryCode_id', 'dt' => 'countryCode_id' ),
            array( 'db' => 'workflow_security_checker.checker_id', 'dt' => 'checker_id' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'checker', 'dt' => 'checker' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` on `address_book`.`address_book_id` = workflow_security_checker.checker_id ';
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

    public function insertInterviewSecurity($data){
        $out = null;
        $sql =  "INSERT INTO
                    `workflow_security_checker`
                SET
                    `principal_code` = ?,
                    `countryCode_id` = ?,
                    `checker_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['principal_code'],
            $data['countryCode_id'],
            $data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurity($data){
        $out = null;
        $sql =  "UPDATE
                    `workflow_security_checker`
                SET
                    `principal_code` = ?,
                    `countryCode_id` = ?,
                    `checker_id` = ?
                WHERE
                    `principal_code` = ? AND
                    `countryCode_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssiss',
            $data['principal_code'],
            $data['countryCode_id'],
            $data['address_book_id'],
            $data['principal_code'],
            $data['countryCode_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteInterviewSecurity($data){
        $out = null;
        $sql =  "DELETE
                    FROM `workflow_security_checker`
                WHERE
                    `principal_code` = ? AND
                    `countryCode_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $data['principal_code'],
            $data['countryCode_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function checkNeedSecurityCheck($principal_code, $country_code){
        $out = null;
        $sql =  "SELECT
                    count(`workflow_security_checker`.`principal_code`) as total
                FROM
                    `workflow_security_checker`
                WHERE
                    `principal_code` = ? AND
                    `countryCode_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $principal_code,
            $country_code);
        $stmt->bind_result(
            $total
        );
        $stmt->execute();
        $stmt->fetch();
        $out = $total > 0 ? true : false;
        $stmt->close();
        return $out;
    }


    public function insertInterviewSecurityCheck($job_application_id){
        $out = null;
        $sql =  "INSERT INTO
                    `workflow_security_tracker`
                SET
                    `job_application_id` = ?,
                    `status` = 'request_file',
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `level`= 1
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $job_application_id);
        $stmt->execute();
        echo $stmt->error;
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

}
?>