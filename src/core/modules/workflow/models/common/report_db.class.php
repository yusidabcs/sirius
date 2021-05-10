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
final class report_db extends \core\app\classes\module_base\module_db {

    public $table = 'workflow_reports';
    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

        return;
    }

    /*
    * get interview security list for datatable
    */
    function getInterviewSecurityReportDatatable(){
        $this->generic = \core\app\classes\generic\generic::getInstance();
        $request = $_POST;
        $this->validateRequest($request,['level','tracker','partner']);

        $primaryKey = $this->table.'.address_book_id';

        $columns = array(
            array( 'db' => $this->table.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $this->table.'.level', 'dt' => 'level' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'ab_number_given_name' , 'as' =>  'ab_number_given_name'),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'ab_entity_family_name' , 'as' =>  'ab_entity_family_name'),
            array( 'db' => 'partner.number_given_name', 'dt' => 'partner_number_given_name', 'as' =>  'partner_number_given_name'),
            array( 'db' => 'partner.entity_family_name', 'dt' => 'partner_entity_family_name' , 'as' =>  'partner_entity_family_name'),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'fullname', 'formatter' => function ($d, $row) {
                return $this->generic->getName('per', $row['ab_entity_family_name'], $row['ab_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            }),
            array( 'db' => 'partner.entity_family_name', 'dt' => 'partner_fullname', 'formatter' => function ($d, $row) {
                return $this->generic->getName('per', $row['partner_entity_family_name'], $row['partner_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            })
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = '
        LEFT JOIN `address_book` on `address_book`.`address_book_id` = '.$this->table.'.address_book_id
        LEFT JOIN `address_book` as partner on `partner`.`address_book_id` = '.$this->table.'.partner_id
        ';

        $where = $this->filter( $request, $columns, $bindings  );
        if (isset($request['level']) && $request['level'] != '') {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " $this->table.`level` = '".$request['level']."' ";
        }

        if (isset($request['partner']) && $request['partner'] != '') {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " $this->table.`partner_id` = '".$request['partner']."' ";
        }

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$this->table."`
			 $join
			 $where
			 $order
			 $limit";
        $data = $this->db->query_array($qry1);

        $ids = [];
        foreach ($data as $item) {
            $ids[] = (int) $item['address_book_id'];
        }
        if(count($ids) > 0)
            $author_ids = implode(',', $ids);
        else
            $author_ids = "''";

        $sql =  "SELECT
                    `workflow_reports_responsible`.`address_book_id`,
                    `workflow_reports_responsible`.`workflow`
                FROM 
                    `workflow_reports_responsible`
                WHERE  `address_book_id` IN ( {$author_ids})
                    ";
                    
        if (isset($request['tracker']) && $request['tracker'] != '') {
            $sql .= " AND `workflow_reports_responsible`.`workflow` = '".$request['tracker']."' ";
        }
            
        $grouped_workflow = [];
        $all_workflow = $this->db->query_array($sql);
        foreach($all_workflow as $workflow) {
            $grouped_workflow[$workflow['address_book_id']][] = $workflow;
        }
        $new_data = [];
        foreach ($data as $item) {
            $item['workflow'] = isset($grouped_workflow[$item['address_book_id']]) ? $grouped_workflow[$item['address_book_id']] : [];
            $new_data[] = $item;

        }
        $columns[] = array( 'db' => 'workflow', 'dt' => 'workflow' );
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$this->table."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$this->table."`  $join";
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
            "data"            => $this->data_output( $columns, $new_data ),
        );
    }

    function getReportArray(){
        $table = $this->table;
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

    function getReportByWorkflow($workflow){
        $table = $this->table;
        $sql =  "SELECT 
                    $table.`address_book_id`,
                    $table.`level`,
                    $table.`partner_id`,
                    `address_book`.`number_given_name`,
                    `address_book`.`entity_family_name`,
                    `address_book`.`main_email`
                FROM 
                    $table
                JOIN `address_book` on `address_book`.`address_book_id` = $table.`address_book_id`
                
                JOIN `workflow_reports_responsible` ON `workflow_reports_responsible`.`address_book_id` = `workflow_reports`.`address_book_id`
                
                WHERE `workflow_reports_responsible`.`workflow` = '".$workflow."'

                ORDER BY $table.`level`
                	";
        return $this->db->query_array($sql);
    }


    public function insertInterviewSecurityReport($data){
        $out = null;
        $sql =  "INSERT INTO
                    $this->table
                SET
                    `address_book_id` = ?,
                    `level` = ?,
                    `partner_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isi',
            $data['address_book_id'],
            $data['level'],
            $data['select_partner']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurityReport($data){
        $out = null;
        $sql =  "UPDATE 
                    $this->table
                SET
                    `level` = ?
                WHERE
                    `address_book_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $data['level'],
            $data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteInterviewSecurityReport($address_book_id){
        $out = null;
        $sql =  "DELETE
                    FROM $this->table
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

    public function insertSecurityReportResponsible($data){
        $out = null;
        $sql =  "INSERT INTO
                    workflow_reports_responsible
                SET
                    `address_book_id` = ?,
                    `workflow` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is',
            $data['address_book_id'],
            $data['workflow']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteSecurityReportResponsible($address_book_id){
        $out = null;
        $sql =  "DELETE
                    FROM `workflow_reports_responsible`
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
