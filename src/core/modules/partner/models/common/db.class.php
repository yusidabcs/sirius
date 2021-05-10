<?php

namespace core\modules\partner\models\common;

/**
 * Final pages_db class.
 *
 * @final
 */
final class db extends \core\app\classes\module_base\module_db
{


    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    public function getPrincipalArray()
    {
        $this->generic = \core\app\classes\generic\generic::getInstance();
        $out = array();

        $sql = 'SELECT
						`principal`.`address_book_id`,
						`principal`.`code`,
                        `address_book`.`number_given_name`,
                        `address_book`.`entity_family_name`
					FROM
						`principal`
					LEFT JOIN
					    `address_book` on `address_book`.`address_book_id` = `principal`.`address_book_id`
				';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($address_book_id, $code, $number_given_name, $entity_family_name);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $out[] = array(
                'address_book_id' => $address_book_id,
                'code' => $code,
                'principal_fullname' => $this->generic->getName('ent', $entity_family_name, $number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME)
            );

        }

        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function getPartnerArray()
    {
        $out = array();

        $sql = 'SELECT
						`partner`.`address_book_id`,
						`partner`.`partner_code`,
                        `partner`.`status`,
                        `partner`.`created_on`,
                        `partner`.`created_by`,
                        `partner`.`modified_on`,
						`partner`.`modified_by`,
						`address_book`.`main_email`,
						`address_book`.`entity_family_name`
						
					FROM
						`partner`
					LEFT JOIN
					    `address_book` on `address_book`.`address_book_id` = `partner`.`address_book_id`
				';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($address_book_id, $partner_code, $status, $created_on, $created_by, $modified_on, $modified_by, $main_email, $entity_family_name);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $sub_data = $this->getPartnerSubCountryDetail($address_book_id);
            $out[] = array(
                'address_book_id' => $address_book_id,
                'partner_code' => $partner_code,
                'countryCode_id' => json_encode($sub_data['countries']),
                'countrySubCode_id' => json_encode($sub_data['subcountries']),
                'status' => $status,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'main_email' => $main_email,
                'entity_family_name' => $entity_family_name,
            );

        }

        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function getPartnerDetail($id)
    {
        $out = array();

        $sql = 'SELECT
						`partner`.`address_book_id`,
						`partner`.`partner_code`,
                        `partner`.`status`,
                        `partner`.`created_on`,
                        `partner`.`created_by`,
                        `partner`.`modified_on`,
						`partner`.`modified_by`,
						`address_book`.`main_email`,
						`address_book`.`entity_family_name`
						
					FROM
						`partner`
					LEFT JOIN
					    `address_book` on `address_book`.`address_book_id` = `partner`.`address_book_id`
					WHERE
						`partner`.`address_book_id` = ? 
				';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->bind_result($address_book_id, $partner_code, $status, $created_on, $created_by, $modified_on, $modified_by, $main_email, $entity_family_name);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $sub_data = $this->getPartnerSubCountryDetail($address_book_id);
            $out = array(
                'address_book_id' => $address_book_id,
                'partner_code' => $partner_code,
                'countryCode_id' => json_encode($sub_data['countries']),
                'countrySubCode_id' => json_encode($sub_data['subcountries']),
                'status' => $status,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'main_email' => $main_email,
                'entity_family_name' => $entity_family_name,
            );
            
        }

        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function getPartnerSubCountryDetail($address_book_id)
    {
        $out = array();
        $subcountries = array();
        $countries = array();

        $sql = 'SELECT
                    `partner_subcountry`.`countryCode_id`,
                    `partner_subcountry`.`countrySubCode_id`
                FROM
                    `partner_subcountry`
                WHERE
                    `partner_subcountry`.`address_book_id` = ? 
            ';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $address_book_id);
        $stmt->bind_result($countryCode_id, $countrySubCode_id);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) 
        {
            $subcountries[$countryCode_id][] = $countrySubCode_id;

            if(!in_array($countryCode_id, $countries, true))
            {
                array_push($countries, $countryCode_id);
            }
        }
        $out['countries'] = $countries;
        $out['subcountries'] = $subcountries;
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    //get partner file data
    public function getPartnerFile($ab_id)
    {
        $out = array();
        $sql = "SELECT
                  `address_book_file`.`filename`
					FROM
						`address_book_file`
					WHERE
						`address_book_file`.`address_book_id` = ?
					AND  `address_book_file`.`model_code` = 'banner'
					AND  `address_book_file`.`model_sub_code` = 'register'
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $ab_id);
        $stmt->bind_result($filename);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $out = array(
                'filename' => $filename
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }


    public function updatePartner($data)
    {

        $sql = "UPDATE
					`partner`
				SET 
					`partner_code` = ?,
                    `modified_on`= CURRENT_TIMESTAMP,
					`modified_by`= ?
			    
          WHERE
			      address_book_id = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sii', $data['partner_code'], $data['modified_by'], $data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        if ($out == 1)
        {
            $this->updatePartnerSubCountry($data['address_book_id'],$data['countrySubCode_id']);
        }
        $stmt->close();
        return $out;
    }

    public function updatePartnerAB($new_ab,$old_ab)
    {

        $sql = "UPDATE
					`partner`
				SET 
					`address_book_id` = ?,
                    `modified_on`= CURRENT_TIMESTAMP,
					`modified_by`= {$this->user_id}
                WHERE
                    address_book_id = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $new_ab, $old_ab);
        $stmt->execute();
        $out = $stmt->affected_rows;
        if ($out == 1)
        {
            $this->updatePartnerSubCountryAB($new_ab,$old_ab);
        }
        $stmt->close();
        return $out;
    }

    public function updatePartnerSubCountry($address_book_id,$countries)
    {
        
        $good = true;
        $this->deletePartnerSubCountry($address_book_id);
        $sql =  "INSERT INTO
                    `partner_subcountry`
                SET
                    `address_book_id` = ?,
                    `countryCode_id` = ?,
                    `countrySubCode_id` = ?,
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                ON DUPLICATE KEY UPDATE
                    `countryCode_id` = ?,
                    `countrySubCode_id` = ?,
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                ";

        $stmt = $this->db->prepare($sql);
        foreach($countries as $key => $subcountries)
        {
            foreach ($subcountries as $subcountry)
            {
                $stmt->bind_param('issss',$address_book_id, $key, $subcountry,$key, $subcountry);
                if (!$stmt->execute()) { 
                $good = false;
                }
            }
        }

        $stmt->close();
        return $good;
    }

    public function updatePartnerSubCountryAB($new_ab,$old_ab)
    {

        $sql = "UPDATE
					`partner_subcountry`
				SET 
					`address_book_id` = ?,
                    `modified_on`= CURRENT_TIMESTAMP,
					`modified_by`= {$this->user_id}
                WHERE
                    address_book_id = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $new_ab,$old_ab);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function insertPartner($data)
    {
        $sql = "INSERT INTO
					`partner`
				SET 
					`address_book_id` = ?,
					`partner_code` = ?,
					`status` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by` = ?,
                    `modified_on` = CURRENT_TIMESTAMP,
					`modified_by` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isiii', $data['address_book_id'], $data['partner_code'], $data['status'], $data['created_by'], $data['created_by']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        if ($out == 1 )
        {    
           $out = $this->insertPartnerSubCountry($data['address_book_id'],$data['subcountry_data']);
        }
        $stmt->close();
        return $out;
    }

    public function insertPartnerType($address_book_id,$partner_type)
    {
        $sql = "INSERT INTO
					`partner_type`
				SET 
					`address_book_id` = ?,
					`partner_type` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $address_book_id,$partner_type);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deletePartnerType($address_book_id)
    {

        $sql = "DELETE
              from
                `partner_type`
              WHERE
                address_book_id = ?
              ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $address_book_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getPartnerType($ab_id)
    {
        $out = array();
        $sql = "SELECT
                  `partner_type`.`partner_type`
					FROM
						`partner_type`
					WHERE
						`partner_type`.`address_book_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $ab_id);
        $stmt->bind_result($partner_type);
        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $out[] = $partner_type;
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function insertPartnerSubCountry($address_book_id,$countries)
    {
        $good = true;
        $sql = "INSERT INTO
					`partner_subcountry`
				SET 
					`address_book_id` = ?,
					`countryCode_id` = ?,
					`countrySubCode_id` = ?, 
					`created_on` = CURRENT_TIMESTAMP,
					`created_by` = {$this->user_id},
                    `modified_on` = CURRENT_TIMESTAMP,
					`modified_by` = {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        foreach($countries as $key => $subcountries)
        {
            foreach ($subcountries as $subcountry)
            {
                $stmt->bind_param('iss', $address_book_id, $key, $subcountry);
                if (!$stmt->execute()) { 
                   $good = false;
                }
            }
        }
        
        $stmt->close();
        return $good;
    }

    public function deletePartner($id)
    {

        $sql = "DELETE
              from
                `partner`
              WHERE
                address_book_id = ?
              ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        if ($out == 1)
        {
            $this->deletePartnerSubCountry($id);
            $this->deletePartnerType($id);
        }
        $stmt->close();
        return $out;
    }

    public function deletePartnerSubCountry($id)
    {

        $sql = "DELETE
              from
                `partner_subcountry`
              WHERE
                address_book_id = ?
              ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function editPartnerStatus($data)
    {

        $sql = "UPDATE
					`partner`
				SET 
					`status` = ?,
					`modified_on`= CURRENT_TIMESTAMP
			    WHERE
          address_book_id = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $data['status'], $data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    // get all list partner from table
    public function getListPartner()
    {
        $request = $_POST;
        $table = 'partner';

        $primaryKey = 'partner.address_book_id';

        $columns = array(
            array( 'db' => 'partner.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'partner.partner_code', 'dt' => 'partner_code' ),
            array( 'db' => 'country.countryCode_id', 'dt' => 'countryCode_id' ),
            array( 'db' => 'partner.status', 'dt' => 'status' ),
            array( 'db' => 'partner_type.partner_type', 'dt' => 'partner_type' )
        );
        $filename = ',(SELECT `address_book_file`.`filename` FROM `address_book_file` WHERE `address_book_file`.`address_book_id`=`partner`.`address_book_id` AND `address_book_file`.`model_code`="banner" AND `address_book_file`.`model_sub_code`="register" ORDER BY `address_book_file`.`created_on` DESC LIMIT 1) as filename';

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = 'LEFT JOIN address_book on partner.address_book_id = address_book.address_book_id ';
        $join .= 'LEFT JOIN (
                        SELECT 
                        address_book_id,
                        CONCAT(
                            "[",
                            GROUP_CONCAT(DISTINCT "\"",countryCode_id,"\"")
                            ,"]") as countryCode_id
                        FROM partner_subcountry
                        GROUP BY address_book_id
                    ) as country ON partner.address_book_id = country.address_book_id  ';
        $join .= 'LEFT JOIN (
                        SELECT 
                        address_book_id,
                        CONCAT(
                            "[",
                            GROUP_CONCAT(DISTINCT "\"",partner_type,"\"")
                            ,"]") as partner_type
                        FROM partner_type
                        GROUP BY address_book_id
                    ) as partner_type ON partner.address_book_id = partner_type.address_book_id  ';

        $where = $this->filter( $request, $columns,$bindings  );

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db')).$filename."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $limit";
        //echo $qry1;
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
        // register column filename
        $columns[]=array( 'db' => 'filename', 'dt' => 'filename' );
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }


    public function checkPartnerCodeExist($code, $ab = false)
    {
        $out = false;

        $sql = "SELECT
					`address_book_id`
				FROM 
					`partner`
        WHERE
        `partner`.`partner_code` = ?
			";
        if($ab != false){
            $sql .= " AND `partner`.`address_book_id` != '{$ab}'";
        }

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('s', $code);
        $stmt->bind_result($id);
        $stmt->execute();
        $stmt->store_result();
        if( $stmt->num_rows == 1 )
        {
            $out = true;
        }
        $stmt->close();
        return $out;
    }


}

?>