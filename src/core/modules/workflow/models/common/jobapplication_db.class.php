<?php
namespace core\modules\workflow\models\common;
/**
 * Final jobapplication_tracker db class.
 *
 * @final
 * @package		jobapplication_tracker
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 Jun 2020
 */

final class jobapplication_db extends \core\app\classes\module_base\module_db {
    public $table = 'workflow_jobapplication_tracker';

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    public function getWorkflowDatatable($tablename, $ent = false,$table_workflow='')
    {
        $request = $_POST;
        $this->validateRequest($request, ['status', 'level']);

        $primaryKey = $tablename.'.address_book_id';

        $columns = array(
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
            array( 'db' => $tablename.'.status', 'dt' => 'status' ),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' )
        );

        if ($tablename === 'workflow_stcw_tracker') {
            $columns[] = array( 'db' => $tablename.'.stcw_type', 'dt' => 'stcw_type' );
        }

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);;

        $join = " JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
        $join .= ' LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';
        if($table_workflow!='') {
			$columns[]=array( 'db' => $table_workflow.'.short_description', 'dt' => 'short_description');
			$join .= ' LEFT JOIN `'.$table_workflow.'` ON '.$tablename.'.`status` = `'.$table_workflow.'`.`milestone` ';
		}

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`status` not in ('accepted','rejected')";

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
        if ($ent != false) {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
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
			 FROM  `".$tablename."`  $join $where";
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

    public function getTotalTrackerByLevel($tablename,$ent = false)
    {
        $sql = "
            SELECT level, COUNT(level) as total
            FROM $tablename
        ";
        $join = ' JOIN `address_book_connection` ON '.$tablename.'.`address_book_id` = `address_book_connection`.`address_book_id` ';
        $where = '';
        
        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`status` not in ('accepted','rejected')";

        if ($ent != false) {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }
        
        $group = ' GROUP BY level';
        $sql .= $join;
        $sql .= $where;
        $sql .= $group;

        $stmt = $this->db->query($sql);

        $out = null;

        while ($data = $stmt->fetch_assoc()) {
            $out[] = $data;
        }

        $stmt->close();

        return $out;
    }

    /**
     * Insert Jobapplication tracker
     */
    public function insertJobApplicationTracker($data) {
        if (!isset($data['status'])) {
            $data['status'] = 'reference_check';
        }

        if (!isset($data['created_on'])) {
            $data['created_on'] = '0000-00-00 H:i:s';
        }

        if (!isset($data['checklist_on'])) {
            $data['checklist_on'] = '0000-00-00 H:i:s';
        }

        if (!isset($data['level'])) {
            $data['level'] = 1;
        }

        $sql = "INSERT INTO $this->table VALUES('', ?, ?, ?, ?, ?, ?, ?)";

        $statement = $this->db->prepare($sql);
        $statement->bind_param('issisii', $data['application_id'], $data['status'], $data['created_on'], $_SESSION['user_id'], $data['checklist_on'], $_SESSION['user_id'], $data['level']);

        $statement->execute();

        if ($statement->error) {
            return $statement->error;
        }

        $out = $statement->affected_rows;

        $statement->close();

        return ($out === 1) ? true:false;
    }

    public function updateJobApplicationTracker($application_id, $checklist_on) {

        $sql = "UPDATE $this->table SET checklist_on = ? WHERE job_application_id = ? ORDER BY `id` DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('si', $checklist_on, $application_id);
        $stmt->execute();

        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function getCandidateActiveJobApplication($address_book_id)
    {
        $sql = "SELECT 
        `job_application`.`job_application_id`,
        `job_application`.`address_book_id`,
        `job_application`.`status`,
        `job_application`.`created_on`,
        `address_book`.`address_book_id`,
        `address_book`.`main_email`
        FROM `job_application`
        LEFT JOIN address_book on `job_application`.`address_book_id` = `address_book`.`address_book_id` 
        WHERE `address_book`.`address_book_id` = $address_book_id AND `job_application`.`status` = 'applied' 
        limit 1";

        $stmt = $this->db->query($sql);

        $data = $stmt->fetch_array();
        $stmt->close();

        return $data;
    }
}