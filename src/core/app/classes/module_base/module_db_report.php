<?php


namespace core\app\classes\module_base;


abstract class module_db_report extends module_db
{
    private $table;
    public function __construct($db,$table_prefix)
    {
        $this->table = $table_prefix.'_security_reports';

        parent::__construct($db);

        return;
    }

    /*
    * get interview security list for datatable
    */
    function getInterviewSecurityReportDatatable(){
        $request = $_POST;
        $this->validateRequest($request);
        $table = $this->table;

        $primaryKey = $this->table.'.address_book_id';

        $columns = array(
            array( 'db' => $this->table.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $this->table.'.level', 'dt' => 'level' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = 'JOIN `address_book` on `address_book`.`address_book_id` = '.$this->table.'.address_book_id';
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


    public function insertInterviewSecurityReport($data){
        $out = null;
        $sql =  "INSERT INTO
                    $this->table
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

}