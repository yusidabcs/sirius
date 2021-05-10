<?php
namespace core\modules\principal\models\common;

/**
 * Final pages_db class.
 *
 * @final
 */
final class db extends \core\app\classes\module_base\module_db {


    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    public function getPrincipalArray()
    {
        $out = [];

        $sql = "SELECT
						`principal`.`address_book_id`,
                        `principal`.`code`,
                        `address_book`.`main_email`,
						`address_book`.`number_given_name`,
                        `address_book`.`entity_family_name`
					  
					FROM
						`principal`
                    LEFT JOIN  `address_book` 
                    ON `principal`.`address_book_id` = `address_book`.`address_book_id` 
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($address_book_id, $code, $email, $number_given_name, $entity_family_name);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'address_book_id' => $address_book_id,
                'code' => $code,
                'email' => $email,
                'number_given_name' => $number_given_name,
                'entity_family_name' => $entity_family_name,
                'brands' => $this->getBrandByPrincipal($address_book_id)
            );
        }
        $stmt->free_result();
        $stmt->close();


        return $out;


    }

    public function getAllPrincipalDatatable()
    {
        $request = $_POST;
        $table = 'principal';

        $primaryKey = 'principal.address_book_id';

        $columns = array(
            array( 'db' => 'principal.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'principal.code', 'dt' => 'code' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'name' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = 'join address_book on principal.address_book_id = address_book.address_book_id ';

        $where = $this->filter( $request, $columns,$bindings  );


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
    public function insertPrincipal($data){

        $sql = "INSERT INTO
					`principal`
				SET 
                    `address_book_id` = ?,
					`code` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',$data['address_book_id'],$data['code']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        foreach ($data['brands'] as $brand) {
            $sql = "INSERT INTO
					`principal_brand`
				SET 
					`address_book_id` = ?,
					`name` = ?,
					`code` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('iss',$data['address_book_id'],$brand['name'],$brand['code']);
            $stmt->execute();
            $stmt->close();
        }

        return $out;
    }

    public function updatePrincipal($data){

        $sql = "UPDATE
					`principal`
				SET 
					`code` = ?
				WHERE
				    `address_book_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$data['code'], $data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function updatePrincipalAB($data){

        $sql = "UPDATE
					`principal`
				SET 
					`address_book_id` = ?
				WHERE
				    `address_book_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$data['new_ab'], $data['old_ab']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        if ( $out == 1 )
            $out = $this->updatePrincipalBrandAB($data);    
            
        $stmt->close();

        return $out;
    }

    public function updatePrincipalBrandAB($data){

        $sql = "UPDATE
					`principal_brand`
				SET 
					`address_book_id` = ?
				WHERE
				    `address_book_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$data['new_ab'], $data['old_ab']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function getPrincipalDetail($id)
    {
        $out = null;

        $sql = "SELECT
						`principal`.`address_book_id`,
                        `principal`.`code`,
                        `address_book`.`main_email`,
						`address_book`.`number_given_name`
					  
					FROM
						`principal`
                    LEFT JOIN  `address_book` 
                    ON `principal`.`address_book_id` = `address_book`.`address_book_id`
					WHERE
						`principal`.`address_book_id` = ? 
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->bind_result($address_book_id, $code, $email, $number_given_name);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out['data'] = array(
                'address_book_id' => $address_book_id,
                'code' => $code,
                'email' => $email,
                'number_given_name' => $number_given_name,
            );
        }
        $stmt->free_result();
        $stmt->close();
        $out['data']['brands'] = [];
        $sql = "SELECT
					`principal_brand`.`address_book_id`,
					`principal_brand`.`name`,
					`principal_brand`.`code`
				  
				FROM
					`principal_brand`
				WHERE
					`principal_brand`.`address_book_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->bind_result($address_book_id, $name, $code);

        $stmt->execute();
        while($stmt->fetch())
        {
            $out['data']['brands'][] = array(
                'address_book_id' => $address_book_id,
                'name' => $name,
                'code' => $code,
            );
        }
        $stmt->close();

        return $out;


    }

    public function getPrincipalByCode($code)
    {
        $out = null;

        $sql = "SELECT
						`principal`.`address_book_id`,
                        `principal`.`code`,
                        `address_book`.`main_email`,
						`address_book`.`entity_family_name`,
						`address_book`.`number_given_name`
					  
					FROM
						`principal`
                    JOIN  `address_book` 
                    ON `principal`.`address_book_id` = `address_book`.`address_book_id`
					WHERE
						`principal`.`code` = ? 
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$code);
        $stmt->bind_result($address_book_id, $code, $email, $entity_family_name, $number_given_name);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out = array(
                'address_book_id' => $address_book_id,
                'code' => $code,
                'email' => $email,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
            );
        }
        $stmt->free_result();
        $stmt->close();


        return $out;


    }

    public function insertPrincipalBrand($data){
        $out = [];
        $sql = "INSERT INTO
					`principal_brand`
				SET 
					`address_book_id` = ?,
					`name` = ?,
					`code` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iss',$data['address_book_id'],$data['name'],$data['code']);
        $out['result'] = $stmt->execute();
        $out['id'] = $stmt->insert_id;
        $stmt->close();
        return $out;
    }

    public function updatePrincipalBrand($data){
        $out = [];

        $sql = "UPDATE
					`principal_brand`
				SET 
					`name` = ?,
					`code` = ?,
                    `modified_on` = CURRENT_TIMESTAMP, 
                    `modified_by` = {$this->user_id}
                WHERE
					`code` = ?
					
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss',$data['name'],$data['code'],$data['code']);
        $out['result'] = $stmt->execute();
        $stmt->close();
        return $out;
    }

    public function deletePrincipalBrand($id){
        $out = [];

        $sql = "DELETE
					`principal_brand`
                FROM
					`principal_brand`
                WHERE
					`code` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$id);
        $out['result'] = $stmt->execute();
        $stmt->close();
        return $out;
    }

    public function deletePrincipal($id){
        $out = [];

        $sql = "DELETE
					`principal`, `principal_brand`
                FROM
					`principal`
				LEFT JOIN
					`principal_brand`
				ON
					`principal`.`address_book_id` = `principal_brand`.`address_book_id`
                WHERE
					`principal`.`address_book_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $out['result'] = $stmt->execute();
        $stmt->close();
        return $out;
    }

    public function checkPrincipalCode($code, $address_book_id = false){

        $qry = "SELECT 
                    `address_book_id`
                FROM
                    `principal`
                WHERE
                    `code` = '{$code}'";
        if($address_book_id !== false){
            $qry .= " AND `address_book_id` != '{$address_book_id}'";
        }
                    
        $result = $this->db->query($qry);
        $row = $result->fetch_row();
        if(!empty($row))
        {
            $out = $row[0];
        } else {
            $out = false;
        }
        $result->close();
        return $out;
    }


    public function checkBrandCode($code, $address_book_id = false){

        $qry = "SELECT 
                    `address_book_id`
                FROM
                    `principal_brand`
                WHERE
                    `code` = '{$code}'";
        if($address_book_id !== false){
            $qry .= " AND `address_book_id` = '{$address_book_id}'";
        }
                    
        $result = $this->db->query($qry);
        $row = $result->fetch_row();
        if(!empty($row))
        {
            $out = $row[0];
        } else {
            $out = false;
        }
        $result->close();
        return $out;
    }


    public function getBrandCodeByName($name){

        $qry = "SELECT 
                    `principal_brand`.`code` as brand_code,
                    `principal`.`code` as principal_code
                FROM
                    `principal_brand`
                JOIN
                    `principal` on `principal`.`address_book_id` = `principal_brand`.`address_book_id`
                WHERE
                    `name` = '{$name}'";

        $result = $this->db->query($qry);
        $row = $result->fetch_row();
        if(!empty($row))
        {
            $out = [
                'brand_code' => $row[0],
                'principal_code' => $row[1],
            ];
        } else {
            $out = false;
        }
        $result->close();
        return $out;
    }

    public function getBrandByPrincipal($address_book_id){

        $out = [];
        $qry = "SELECT 
                    `principal_brand`.`code` as brand_code,
                    `principal_brand`.`name` as name
                FROM
                    `principal_brand`
                WHERE
                    `principal_brand`.`address_book_id` = '{$address_book_id}'";

        $stmt = $this->db->prepare($qry);
        $stmt->bind_result($brand_code, $name);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'principal_brand_code' => $brand_code,
                'name' => $name,
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function getBrandByPrincipalCode($principal_code){

        $out = [];
        $qry = "SELECT 
                    `principal_brand`.`code` as brand_code,
                    `principal_brand`.`name` as name,
                    `principal`.`code`
                FROM
                    `principal_brand`
                LEFT JOIN
                    `principal` ON `principal`.`address_book_id` = `principal_brand`.`address_book_id`
                WHERE
                    `principal`.`code` = ?";

        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('s', $principal_code);
        $stmt->bind_result($brand_code, $name, $principal_cde);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'principal_brand_code' => $brand_code,
                'name' => $name,
                'principal_code' => $principal_code
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    


}
?>