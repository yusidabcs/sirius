<?php
namespace core\modules\address_book\models\common;

/**
 * Final address_book_db_obj class.
 * 
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 5 January 2016
 */
final class address_book_db_obj extends \core\app\classes\module_base\module_db {
	
	private $_addressbook_join;
	private $_addressbook_where;
	private $_addressbook_orderby;
	private $_addressbook_offset;
	private $_addressbook_rowcount;
	
	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables

		//generic needed for getName
		$this->generic = \core\app\classes\generic\generic::getInstance();
		
		return;
	}
	
	/* Checkers */
		
	public function checkAddressID($address_book_id)
	{
		$out = false;
		
		$qry = "SELECT `address_book_id` FROM `address_book` WHERE `address_book_id` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("i",$address_book_id);
		$stmt->execute();
		$stmt->store_result();
		if( $stmt->num_rows == 1 )
		{
			$out = true;
		}
		$stmt->free_result();
		$stmt->close();

		return $out;
	}
	
	public function checkPersonEmail($main_email)
	{
		$out = false;
		
		$qry = "SELECT `address_book_id` FROM `address_book` WHERE `type` = 'per' AND `main_email` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$main_email);
		$stmt->bind_result($out);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();

		return $out;
	}

	public function checkPersonInEntity($ab_id){
        $out = false;

        $qry = "SELECT `address_book_ent_id` FROM `address_book_ent_link` WHERE `address_book_per_id` = '{$ab_id}'";

        $stmt = $this->db->prepare($qry);
        $stmt->bind_result($out);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();

        return $out;
    }
	
	public function checkPersonEmailDetails($main_email)
	{
		$out = array();
		
		$qry = "SELECT 
					`address_book_id`,
					`entity_family_name`,
					`number_given_name`
				FROM 
					`address_book` 
				WHERE 
					`type` = 'per' 
				AND 
					`main_email` = ?
				";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$main_email);
		$stmt->bind_result($address_book_id,$entity_family_name,$number_given_name);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out['address_book_id'] = $address_book_id;
			$out['entity_family_name'] = $entity_family_name;
			$out['number_given_name'] = $number_given_name;
		}
		$stmt->close();

		return $out;
	}
	
	/* Getters */
	
	public function getAddressBookArray()
	{
		if(!isset($this->_addressbook_offset))
		{
			$msg = "You must get client pagination information first!";
		    throw new \RuntimeException($msg); 
		}
		
		$out = array();
		
		$sql = "SELECT
					`address_book`.`address_book_id`,
					`address_book`.`main_email`,
					`address_book`.`type`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`,
					`address_book`.`created_on`,
					`address_book`.`modified_on`
				FROM
					`address_book`
				";
				
		$sql .= $this->_addressbook_join;
				
		$sql .= $this->_addressbook_where;
		
		$sql .= $this->_addressbook_orderby;	
				
		$sql .= "LIMIT
					{$this->_addressbook_offset},{$this->_addressbook_rowcount}
				";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($address_book_id,$main_email,$type,$entity_family_name,$number_given_name,$created_on,$modified_on);
		$stmt->execute();
		while($stmt->fetch())
		{
			$address_book_name = $this->generic->getName($type, $entity_family_name, $number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
						
			$out[$address_book_id] = array('address_book_name' => $address_book_name, 'main_email' => $main_email, 'created_on' => $created_on, 'modified_on' => $modified_on);
		}
		$stmt->close();
		
		return $out;
	}
	
	public function getClientPaginationInfo($link_id,$model_name,$page,$type,$text)
	{
		//we store the names in html with special characters so we have to convert them back to search
		$text = str_replace("'", "&#39;", $text);
		
		$paginate = new \core\app\classes\paginate\paginate('local','address_book',false);
		
		//setup pagination
		$paginate->setSessionInfo($link_id,$model_name,'main');
		
		//if page is set in options
		if($page > 0)
		{
			$paginate->setPageOn($page);
		}
		
		//join statement - none
		
		//set the where statement need to run getPaginationInfo to ensure we have the right one
		if(!empty($type))
		{
			if($text == 'RESET-THE-SEARCH')
			{
				$paginate->setWhere('');
				$paginate->setSearchType('');
				$paginate->setSearchText('');
			} else {
			
				switch ($type) 
				{
				    case 'starts':
				        $paginate->setWhere('WHERE (`entity_family_name` LIKE "'.$text.'%" OR `number_given_name` LIKE "'.$text.'%" ) ');
				        $paginate->setSearchType($type);
				        $paginate->setSearchText($text);
				        break;
				    case 'contains':
				        $paginate->setWhere('WHERE (`entity_family_name` LIKE "%'.$text.'%" OR `number_given_name` LIKE "%'.$text.'%" ) ');
				        $paginate->setSearchType($type);
				        $paginate->setSearchText($text);
				        break;
				    default:
				       $paginate->setWhere('');
				       $paginate->setSearchType('');
				       $paginate->setSearchText('');
				}
			}
		}
		
		//orderby fixed in this case
		$paginate->setOrderby('ORDER BY `created_on` DESC ');
		
		//update all the non-fixed information and get the pagination information for the view
		$paginateViewArray = $paginate->getPaginationInfo();
		
		//setInfo for $this->getAddressBookArray()
		$this->_addressbook_where = $paginateViewArray['where'];
		$this->_addressbook_orderby = $paginateViewArray['orderby'];
		$this->_addressbook_offset = $paginateViewArray['page_start_record'];
		$this->_addressbook_rowcount = $paginateViewArray['pagination_number'];

		return $paginateViewArray;
	}

	public function getEntityDetails($entity_family_name)
	{
		$out = array();
		
		$qry = "SELECT 
					`address_book_id`,
					`number_given_name`
				FROM 
					`address_book`
				WHERE 
					`entity_family_name` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$entity_family_name);
		$stmt->bind_result($address_book_id,$number_given_name);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array('address_book_id' => $address_book_id, 'number_given_name' => $number_given_name);
		}
		$stmt->close();
		
		return $out;
	}
	
	public function getPersonDetails($number_given_name)
	{
		$out = array();
		
		$qry = "SELECT 
					`address_book`.`address_book_id`,
					`address_book`.`entity_family_name`,
					`address_book_per`.`middle_names`,
					`address_book_per`.`dob`,
					`address_book_per`.`sex`
					
				FROM 
					`address_book`
				LEFT JOIN
					`address_book_per`
				ON
					`address_book_per`.`address_book_id` = `address_book`.`address_book_id`
				WHERE 
					`number_given_name` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$number_given_name);
		$stmt->bind_result($address_book_id,$entity_family_name,$middle_names,$dob,$sex);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(	'address_book_id' => $address_book_id,
							'entity_family_name' => $entity_family_name,
							'middle_names' => $middle_names,
							'dob' => $dob,
							'sex' => $sex
							);
		}
		$stmt->close();
		
		return $out;
	}
	
	public function getAddressBookMainDetails($address_book_id)
	{
		$out = array();
		
		$qry = "SELECT 
					`address_book`.`main_email`,
					`address_book`.`type`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`,
					`address_book`.`contact_allowed`,
					`address_book_per`.`title`,
					`address_book_per`.`middle_names`,
					`address_book_per`.`dob`,
					`address_book_per`.`sex`,
					`user`.`user_id`,
					`user`.`username`
				FROM 
					`address_book`
				LEFT JOIN
					`address_book_per`
				ON
					`address_book`.`address_book_id` = `address_book_per`.`address_book_id`
				LEFT JOIN
					`user`
				ON
					`address_book`.`main_email` = `user`.`email`
				WHERE 
					`address_book`.`address_book_id` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("i",$address_book_id);
		$stmt->bind_result($main_email,$type,$entity_family_name,$number_given_name,$contact_allowed,$title,$middle_names,$dob,$sex,$user_id,$username);
		$stmt->execute();
		if($stmt->fetch())
		{
			if($dob)
			{
				$age = date_diff(date_create($dob), date_create('now'))->y;
			} else {
				$age = '';
			}
			
			$out = array(
				'address_book_id' => $address_book_id,
				'main_email' => $main_email,
				'type' => $type,
				'entity_family_name' => $entity_family_name,
				'number_given_name' => $number_given_name,
				'contact_allowed' => $contact_allowed,
				'title' => $title,
				'middle_names' => $middle_names,
				'dob' => $dob,
				'sex' => $sex,
				'age' => $age,
				'user_id' => $user_id,
				'username' => $username
			);
		}
		$stmt->free_result();
		$stmt->close();

		return $out;
	}

	public function getAddressBookMainDetailsByEmail($email)
	{
		$out = array();
		
		$qry = "SELECT
					`address_book`.`address_book_id`, 
					`address_book`.`main_email`,
					`address_book`.`type`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`,
					`address_book`.`contact_allowed`,
					`address_book_per`.`title`,
					`address_book_per`.`middle_names`,
					`address_book_per`.`dob`,
					`address_book_per`.`sex`,
					`address_book_pots`.`number`,
					`user`.`user_id`,
					`user`.`username`
				FROM 
					`address_book`
				LEFT JOIN
					`address_book_per`
				ON
					`address_book`.`address_book_id` = `address_book_per`.`address_book_id`
				LEFT JOIN
					`address_book_pots`
				ON
					`address_book`.`address_book_id` = `address_book_pots`.`address_book_id`
				LEFT JOIN
					`user`
				ON
					`address_book`.`main_email` = `user`.`email`
				WHERE 
					`address_book`.`main_email` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$email);
		$stmt->bind_result($address_book_id,$main_email,$type,$entity_family_name,$number_given_name,$contact_allowed,$title,$middle_names,$dob,$sex,$number,$user_id,$username);
		$stmt->execute();
		if($stmt->fetch())
		{
			if($dob)
			{
				$age = date_diff(date_create($dob), date_create('now'))->y;
			} else {
				$age = '';
			}
			
			$out = array(
				'address_book_id' => $address_book_id,
				'main_email' => $main_email,
				'type' => $type,
				'entity_family_name' => $entity_family_name,
				'number_given_name' => $number_given_name,
				'contact_allowed' => $contact_allowed,
				'title' => $title,
				'middle_names' => $middle_names,
				'dob' => $dob,
				'sex' => $sex,
				'number' => $number,
				'age' => $age,
				'user_id' => $user_id,
				'username' => $username
			);
		}
		$stmt->free_result();
		$stmt->close();

		return $out;
	}
	
	public function getAddressBookAddressDetails($address_book_id)
	{
		//we want 'readable' country so need core country db
		$core_db = new \core\app\classes\core_db\core_db;
		$country_array = $core_db->getAllCountryCodes();
		
		$out = array();
		
		$qry = "SELECT 
					`address_book_address`.`type`,
					`address_book_address`.`physical_pobox`,
					`address_book_address`.`care_of`,
					`address_book_address`.`line_1`,
					`address_book_address`.`line_2`,
					`address_book_address`.`suburb`,
					`address_book_address`.`state`,
					`address_book_address`.`postcode`,
					`address_book_address`.`country`,
					`address_book_coordinates`.`latitude`,
					`address_book_coordinates`.`longitude`
				FROM 
					`address_book_address`
				LEFT JOIN
					`address_book_coordinates`
				ON
					`address_book_address`.`address_book_id` = `address_book_coordinates`.`address_book_id` AND `address_book_address`.`type` = `address_book_coordinates`.`type`
				WHERE 
					`address_book_address`.`address_book_id` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("i",$address_book_id);
		$stmt->bind_result($type,$physical_pobox,$care_of,$line_1,$line_2,$suburb,$state,$postcode,$country,$latitude,$longitude);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$type] = array(
					'physical_pobox' => $physical_pobox,
					'care_of' => $care_of,
					'line_1' => $line_1,
					'line_2' => $line_2,
					'suburb' => $suburb,
					'state' => $state,
					'postcode' => $postcode,
					'country' => $country,
					'country_full' => $country_array[$country],
					'latitude' => $latitude,
					'longitude' => $longitude
			);
		}
		$stmt->free_result();
		$stmt->close();
	
		return $out;
	}
	
	public function getAddressBookInternetDetails($address_book_id)
	{
		$out = array();
		
		$qry = "SELECT 
					`type`,
					`id`,
					`sequence`
				FROM 
					`address_book_internet`
				WHERE 
					`address_book_id` = ?
				ORDER BY
					`sequence`";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("i",$address_book_id);
		$stmt->bind_result($type,$id,$sequence);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
					'type' => $type,
					'id' => $id,
					'sequence' => $sequence
			);
		}
		$stmt->free_result();
		$stmt->close();

		return $out;
	}
	
	public function getAddressBookPotsDetails($address_book_id)
	{
		$out = array();
		
		$qry = "SELECT 
					`type`,
					`country`,
					`number`,
					`private`,
					`whatsapp`,
					`viber`,
					`sequence`
				FROM 
					`address_book_pots`
				WHERE 
					`address_book_id` = ?
				ORDER BY
					`sequence`";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("i",$address_book_id);
		$stmt->bind_result($type,$country,$number,$private,$whatsapp,$viber,$sequence);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
					'type' => $type,
					'country' => $country,
					'number' => $number,
					'private' => $private,
					'whatsapp' => $whatsapp,
					'viber' => $viber,
					'sequence' => $sequence
			);
		}
		$stmt->free_result();
		$stmt->close();
		
		return $out;
	}
	
	public function getAddressBookAdminLinks($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`address_book_ent_link`.`address_book_per_id`,
					`address_book_ent_link`.`person_type`,
					`address_book_ent_link`.`security_level_id`,
					`address_book`.`main_email`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`
					
				FROM
					`address_book_ent_link`
				LEFT JOIN
					`address_book`
				ON
					`address_book_ent_link`.`address_book_per_id` = `address_book`.`address_book_id`
				WHERE
					`address_book_ent_link`.`address_book_ent_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($address_book_id_admin,$key_person,$security_level_id,$main_email,$entity_family_name,$number_given_name);
		$stmt->execute();
		while($stmt->fetch())
		{
			$full_name = $this->generic->getName('per', $entity_family_name, $number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
			
			$out[$address_book_id_admin] = array(
			    'security_full_id' => $security_level_id,
			    'key_person' => $key_person,
			    'full_name' => $full_name,
                'email' => $main_email);
		}
		$stmt->close();
		
		return $out;
	}
	
	public function getPersonhAddressBookIdFromEmail($email)
	{
		$qry = "SELECT 
					`address_book_id`
				FROM 
					`address_book`
				WHERE 
					`main_email` = ?
				AND
					`type` = 'per'
				";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$email);
		$stmt->bind_result($address_book_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $address_book_id;
		} else {
			$out = false;
		}
		$stmt->close();
		
		return $out;
	}

	/* Setters for getAddressBookArray */
	
	public function setAddressbookJoin($join)
	{
		$this->_addressbook_join = $join;
		return;
	}
	
	public function setAddressbookWhere($where)
	{
		$this->_addressbook_where = $where;
		return;
	}
	
	public function setAddressbookOrderby($orderby)
	{
		$this->_addressbook_orderby = $orderby;
		return;
	}
	
	public function setAddressbookOffset($offset)
	{
		$this->_addressbook_offset = $offset;
		return;
	}
	
	public function setAddressbookRowcount($rowcount)
	{
		$this->_addressbook_rowcount = $rowcount;
		return;
	}
	
	/* Main */
	
	public function addMainAddressBookEntry($main_email,$type,$entity_family_name,$number_given_name,$contact_allowed)
	{
		$sql = "INSERT INTO
					`address_book`
				SET 
					`main_email` = ?,
					`type` = ?,
					`entity_family_name` = ?,
					`number_given_name` = ?,
					`contact_allowed` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ssssi',$main_email, $type, $entity_family_name, $number_given_name, $contact_allowed);
		$stmt->execute();
		$address_book_id = $stmt->insert_id;
		$stmt->close();
		
		return $address_book_id;
	}
	
	public function updateMainAddressBookEntry($address_book_id,$main_email,$type,$entity_family_name,$number_given_name,$contact_allowed)
	{
		$sql = "UPDATE
					`address_book`
				SET 
					`main_email` = ?,
					`type` = ?,
					`entity_family_name` = ?,
					`number_given_name` = ?,
					`contact_allowed` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ssssii',$main_email, $type, $entity_family_name, $number_given_name, $contact_allowed, $address_book_id);
		$stmt->execute();
		$affected_rows = $stmt->affected_rows;
		$stmt->close();
		
		return $affected_rows;
	}
	
	public function updateMainAddressBookPerEmail($old_email,$new_email)
	{
		//used for Secuirty to update persons that have changed their email address in user
		$sql = "UPDATE
					`address_book`
				SET 
					`main_email` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`main_email` = ?
				AND
					`type` = 'per'
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ss',$new_email,$old_email);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	

	/* ALL */
	
	public function deleteAddressBookEntry($address_book_id)
	{
		$tables_array = array(`address_book`, `address_book_address`, `address_book_coordinates`, `address_book_email`, `address_book_internet`, `address_book_per`, `address_book_pots`, `address_book_ent_link`);
		
		foreach ($tables_array as $table)
		{
			$qry = "DELETE FROM $table WHERE address_book_id = $address_book_id";
			$this->db->query($qry);
		}
		return;
	}
	
	/* address */
	
	public function insertAddressBookAddress($address_book_id,$type,$physical_pobox,$care_of,$line_1,$line_2,$suburb,$state,$postcode,$country)
	{
		$sql = "INSERT INTO
					`address_book_address`
				SET 
					`address_book_id` = ?,
					`type` = ?,
					`physical_pobox` = ?,
					`care_of` = ?,
					`line_1` = ?,
					`line_2` = ?,
					`suburb` = ?,
					`state` = ?,
					`postcode` = ?,
					`country` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isssssssss',$address_book_id,$type,$physical_pobox,$care_of,$line_1,$line_2,$suburb,$state,$postcode,$country);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookAddress($address_book_id,$type,$physical_pobox,$care_of,$line_1,$line_2,$suburb,$state,$postcode,$country)
	{
		$sql = "INSERT INTO
					`address_book_address`
				SET 
					`address_book_id` = ?,
					`type` = ?,
					`physical_pobox` = ?,
					`care_of` = ?,
					`line_1` = ?,
					`line_2` = ?,
					`suburb` = ?,
					`state` = ?,
					`postcode` = ?,
					`country` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`physical_pobox` = ?,
					`care_of` = ?,
					`line_1` = ?,
					`line_2` = ?,
					`suburb` = ?,
					`state` = ?,
					`postcode` = ?,
					`country` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isssssssssssssssss',$address_book_id,$type,$physical_pobox,$care_of,$line_1,$line_2,$suburb,$state,$postcode,$country,$physical_pobox,$care_of,$line_1,$line_2,$suburb,$state,$postcode,$country);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookAddress($address_book_id,$type)
	{
		$sql = "DELETE FROM
					`address_book_address`
				WHERE
					`address_book_id` = ?
				AND
					`type` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$address_book_id, $type);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookAddressAll($address_book_id)
	{
		$sql = "DELETE FROM
					`address_book_address`
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
		
	/* Coordinates */
	
	public function insertAddressBookCoordinates($address_book_id,$type,$latitude,$longitude)
	{
		$sql = "INSERT INTO
					`address_book_coordinates`
				SET 
					`address_book_id` = ?,
					`type` = ?,
					`latitude` = ?,
					`longitude` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isdd',$address_book_id,$type,$latitude,$longitude);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookCoordinates($address_book_id,$type,$latitude,$longitude)
	{
		$sql = "UPDATE
					`address_book_coordinates`
				SET
					`latitude` = ?,
					`longitude` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_id` = ?
				AND
					`type` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ddis',$latitude,$longitude,$address_book_id,$type);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookCoordinates($address_book_id,$type)
	{
		$sql = "DELETE FROM
					`address_book_coordinates`
				WHERE
					`address_book_id` = ?
				AND
					`type` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is',$address_book_id,$type);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookCoordinatesAll($address_book_id)
	{
		$sql = "DELETE FROM
					`address_book_coordinates`
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
	
	/* email */
	
	public function insertAddressBookEmail($address_book_id,$email,$name,$always_cc,$contact_allowed)
	{
		$sql = "INSERT INTO
					`address_book_email`
				SET 
					`address_book_id` = ?,
					`email` = ?,
					`name` = ?,
					`always_cc` = ?,
					`contact_allowed` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('issii',$address_book_id,$email,$name,$always_cc,$contact_allowed);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookEmail($address_book_id,$email,$name,$always_cc,$contact_allowed)
	{
		$sql = "UPDATE
					`address_book_email`
				SET
					`name` = ?,
					`always_cc` = ?,
					`contact_allowed` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_id` = ?
				AND 
					`email` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('siiis',$name,$always_cc,$contact_allowed,$address_book_id,$email);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookEmail($address_book_id,$email)
	{
		$sql = "DELETE FROM
					`address_book_email`
				WHERE
					`address_book_id` = ?
				AND
					`email` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$address_book_id, $email);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookEmailAll($address_book_id)
	{
		$sql = "DELETE FROM
					`address_book_email`
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
	
	/* internet */
	
	public function insertAddressBookInternet($address_book_id,$type,$id,$sequence)
	{
		$sql = "INSERT INTO
					`address_book_internet`
				SET 
					`address_book_id` = ?,
					`type` = ?,
					`id` = ?,
					`sequence` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('issi',$address_book_id,$type,$id,$sequence);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
		
	public function deleteAddressBookInternetAll($address_book_id)
	{
		$sql = "DELETE FROM
					`address_book_internet`
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

	/* Person */
	
	public function insertAddressBookPer($address_book_id,$title,$middle_names,$dob,$sex)
	{
		$sql = "INSERT INTO
					`address_book_per`
				SET 
					`address_book_id` = ?,
					`title` = ?,
					`middle_names` = ?,
					`dob` = ?,
					`sex` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('issss',$address_book_id,$title,$middle_names,$dob,$sex);
		$stmt->execute();
		echo $stmt->error;
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookPer($address_book_id,$title,$middle_names,$dob,$sex)
	{
		$sql = "INSERT INTO
					`address_book_per`
				SET 
					`address_book_id` = ?,
					`title` = ?,
					`middle_names` = ?,
					`dob` = ?,
					`sex` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`title` = ?,
					`middle_names` = ?,
					`dob` = ?,
					`sex` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('issssssss',$address_book_id,$title,$middle_names,$dob,$sex,$title,$middle_names,$dob,$sex);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookPer($address_book_id)
	{
		$sql = "DELETE FROM
					`address_book_per`
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
	
	public function updateAddressBookPerTitle($address_book_id,$title)
	{
		$sql = "UPDATE
					`address_book_per`
				SET
					`title` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$title,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookPerSex($address_book_id,$sex)
	{
		$sql = "UPDATE
					`address_book_per`
				SET
					`sex` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$sex,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookPerDob($address_book_id,$dob)
	{
		$sql = "UPDATE
					`address_book_per`
				SET
					`dob` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$dob,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	/* pots */
	
	public function insertAddressBookPots($address_book_id,$type,$country,$number,$private,$whatsapp,$viber,$sequence)
	{
		$sql = "INSERT INTO
					`address_book_pots`
				SET 
					`address_book_id` = ?,
					`type` = ?,
					`country` = ?,
					`number` = ?,
					`private` = ?,
					`whatsapp` = ?,
					`viber` = ?,
					`sequence` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isssiiii',$address_book_id,$type,$country,$number,$private,$whatsapp,$viber,$sequence);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
		
	public function deleteAddressBookPotsAll($address_book_id)
	{
		$sql = "DELETE FROM
					`address_book_pots`
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

	/* User Link */

	public function addAddressBookAdminLink($address_book_ent_id,$address_book_per_id, $person_type, $security_level_id)
	{
		$sql = "INSERT INTO
					`address_book_ent_link`
				SET 
					`address_book_per_id` = ?,
					`address_book_ent_id` = ?,
					`person_type` = ?,
					`security_level_id` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iiss', $address_book_per_id, $address_book_ent_id,$person_type, $security_level_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function updateAddressBookUserLink($address_book_id,$address_book_id_admin)
	{
		$sql = "UPDATE
					`address_book_ent_link`
				SET 
					`address_book_ent_id` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`address_book_per_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iii',$address_book_id_admin,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	public function deleteAddressBookAdminLink($address_book_ent_id,$address_book_per_id)
	{
		$sql = "DELETE FROM
					`address_book_ent_link`
				WHERE
					`address_book_ent_id` = ? 
                AND
                    `address_book_per_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$address_book_ent_id,$address_book_per_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	/* avatar specific - general in file */
	public function getAddressBookAvatarDetails($address_book_id)
	{
		return $this->getAddressBookFileArray($address_book_id,'avatar');
	}
	
	/* file */
	
	public function getAddressBookFileOwners($filename)
	{
		$out = array();
		
		//select the address book it for the image
		$sql = "SELECT
					`address_book_id`
				FROM
					`address_book_file`
				WHERE
					`filename` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$filename);
		$stmt->bind_result($address_book_id);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch())
		{
			$out[] = $address_book_id;
			
			$sql2 = "SELECT
						`address_book_ent_id`
					FROM
						`address_book_ent_link`
					WHERE
						`address_book_per_id` = ?
					";
			$stmt2 = $this->db->prepare($sql2);
			$stmt2->bind_param('i',$address_book_id);
			$stmt2->bind_result($address_book_id_admin);
			$stmt2->execute();
			while($stmt2->fetch())
			{
				$out[] = $address_book_id_admin;
			}
			$stmt2->close();
		}
		$stmt->free_result();
		$stmt->close();
		
		return $out;
	}
	
	public function getAddressBookFileArray($address_book_id,$model_code,$model_sub_code=false)
	{
		$out = array();
		
		if($model_sub_code)
		{
			$sql = "SELECT
						`filename`,
						`sequence`,
						`created_on`, 
						`created_by`,
						`modified_on`, 
						`modified_by`
					FROM
						`address_book_file`
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					AND
						`model_sub_code` = ?
					ORDER BY 
						`sequence`
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('iss',$address_book_id,$model_code,$model_sub_code);
			$stmt->bind_result($filename,$sequence,$created_on,$created_by,$modified_on,$modified_by);
			$stmt->execute();
			while($stmt->fetch())
			{
				$out[] = array('filename' => $filename, 'sequence' => $sequence,'created_on' => $created_on, 'created_by' => $created_by, 'modified_on' => $modified_on, 'modified_by' => $modified_by);
			}
			$stmt->close();
			
		} else {
			
			$sql = "SELECT
						`filename`,
						`sequence`,
						`created_on`, 
						`created_by`,
						`modified_on`, 
						`modified_by`
					FROM
						`address_book_file`
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					ORDER BY 
						`sequence`
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('is',$address_book_id,$model_code);
			$stmt->bind_result($filename,$sequence,$created_on,$created_by,$modified_on,$modified_by);
			$stmt->execute();
			while($stmt->fetch())
			{
				$out[] = array('filename' => $filename, 'sequence' => $sequence,'created_on' => $created_on, 'created_by' => $created_by, 'modified_on' => $modified_on, 'modified_by' => $modified_by);
			}
			$stmt->close();
			
		}
		
		return $out;
	}
	
	public function insertAddressBookFile($filename,$address_book_id,$model_code,$sequence,$model_sub_code=false, $public = 0)
	{	
		$this->checkAddressBookFileInfo($address_book_id,$model_code);
		
		if($model_sub_code)
		{
			//write the database record
			$sql = "INSERT INTO
						`address_book_file`
					SET 
						`filename` = ?,
						`address_book_id` = ?,
						`model_code` = ?,
						`model_sub_code` = ?,
						`sequence` = ?,
						`public` = ?,
						`created_on`= CURRENT_TIMESTAMP, 
						`created_by`= {$this->user_id},
						`modified_on`= CURRENT_TIMESTAMP, 
						`modified_by`= {$this->user_id}
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sissii',$filename,$address_book_id,$model_code,$model_sub_code,$sequence,$public);
			$stmt->execute();
			$out = $stmt->affected_rows;

			if ($out != 1) {
				echo $stmt->error;
				exit;
			}
			$stmt->close();

		} else {
			
			//write the database record
			$sql = "INSERT INTO
						`address_book_file`
					SET 
						`filename` = ?,
						`address_book_id` = ?,
						`model_code` = ?,
						`sequence` = ?,
						`public` = ?,
						`created_on`= CURRENT_TIMESTAMP, 
						`created_by`= {$this->user_id},
						`modified_on`= CURRENT_TIMESTAMP, 
						`modified_by`= {$this->user_id}
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sisii',$filename,$address_book_id,$model_code,$sequence,$public);
			$stmt->execute();
			
			$out = $stmt->affected_rows;

			if ($out != 1) {
				echo $stmt->error;
				exit;
			}
			$stmt->close();
		}
		
		if($out != 1)
		{
			$msg = "The address_book_id {$address_book_id} file did not insert properly!";
			throw new \RuntimeException($msg);
		}
		
		return $out;
	}
	
	public function updateAddressBookFile($filename,$address_book_id,$model_code,$sequence,$model_sub_code=false, $public=0)
	{
		$this->checkAddressBookFileInfo($address_book_id,$model_code);
		
		if($model_sub_code)
		{
			$sql = "UPDATE
						`address_book_file`
					SET
						`filename` = ?,
						`modified_on`= CURRENT_TIMESTAMP, 
						`modified_by`= {$this->user_id}
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					AND
						`model_sub_code` = ?
					AND
						`sequence` = ?
					AND
						`public` = ?
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sissii',$filename,$address_book_id,$model_code,$model_sub_code,$sequence,$public);
			$stmt->execute();
			$out = $stmt->affected_rows;
			$stmt->close();
			
		} else {
			
			$sql = "UPDATE
						`address_book_file`
					SET
						`filename` = ?,
						`modified_on`= CURRENT_TIMESTAMP, 
						`modified_by`= {$this->user_id}
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					AND
						`sequence` = ?
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sisi',$filename,$address_book_id,$model_code,$sequence);
			$stmt->execute();
			$out = $stmt->affected_rows;
			$stmt->close();
		}

		return $out;
	}
	//add filename on where clausa to update spesific file
	public function updateAddressBookFileRev($filename,$address_book_id,$model_code,$sequence,$current_file='',$model_sub_code=false, $public=0)
	{
		$this->checkAddressBookFileInfo($address_book_id,$model_code);
		
		if($model_sub_code)
		{
			$sql = "UPDATE
						`address_book_file`
					SET
						`filename` = ?,
						`modified_on`= CURRENT_TIMESTAMP, 
						`modified_by`= {$this->user_id}
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					AND
						`model_sub_code` = ?
					AND
						`sequence` = ?
					AND
						`public` = ?
					";
					if($current_file!='') {
						$sql .= " AND `filename`='".$current_file."'";
					}
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sissii',$filename,$address_book_id,$model_code,$model_sub_code,$sequence,$public);
			$stmt->execute();
			$out = $stmt->affected_rows;
			$stmt->close();
			
		} else {
			
			$sql = "UPDATE
						`address_book_file`
					SET
						`filename` = ?,
						`modified_on`= CURRENT_TIMESTAMP, 
						`modified_by`= {$this->user_id}
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					AND
						`sequence` = ?
					";
			if($current_file!='') {
			$sql .= " AND `filename`='".$current_file."'";
			}
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sisi',$filename,$address_book_id,$model_code,$sequence);
			$stmt->execute();
			$out = $stmt->affected_rows;
			$stmt->close();
		}
		
		return $out;
	}
	
	public function deleteAddressBookFile($address_book_id,$model_code,$model_sub_code=false)
	{
		if($model_sub_code)
		{
			$sql = "DELETE FROM
						`address_book_file`
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					AND
						`model_sub_code` = ?
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('iss',$address_book_id,$model_code,$model_sub_code);
			$stmt->execute();
			$out = $stmt->affected_rows;
			$stmt->close();
			
		} else {
			
			$sql = "DELETE FROM
						`address_book_file`
					WHERE
						`address_book_id` = ?
					AND
						`model_code` = ?
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('is',$address_book_id,$model_code);
			$stmt->execute();
			$out = $stmt->affected_rows;
			$stmt->close();
		}
		
		return $out;
	}

	public function checkAddressBookFileExists($address_book_id, $model_code, $model_sub_code)
	{
		$out = false;
		$sql = "SELECT
					`address_book_id`,
					`model_code`,
					`model_sub_code`,
					`filename`
				FROM
					`address_book_file`
				WHERE
					`address_book_id` = ?
				AND
					`model_code` = ?
				AND
					`model_sub_code` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isi', $address_book_id, $model_code, $model_sub_code);
		$stmt->bind_result($_address_book_id, $_model_code, $_model_sub_code, $filename);
		
		$stmt->execute();

		if ($stmt->fetch()) {
			$out = array(
				'address_book_id' => $_address_book_id,
				'model_code' => $_model_code,
				'model_sub_code' => $_model_sub_code,
				'filename' => $filename
			);
		}

		return $out;
	}
	
	public function uniqueAddressBookFileName()
	{
		$generic = \core\app\classes\generic\generic::getInstance();
		
		$qry = "SELECT `address_book_id` FROM `address_book_file` WHERE `filename` = ?";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$filename);

		$i = 0;
		$exists = true;
		
		do
		{
			$filename = $generic->generateRandomString(8);
			//checking random name
			$stmt->execute();
			if( $stmt->affected_rows == 0 )
			{
				$exists = false;
			}
			$i++;
		} while ( $exists && $i < 30);

		$stmt->close();
		
		return $filename;
	}
	
	private function checkAddressBookFileInfo($address_book_id,$model_code)
	{
		if( empty($address_book_id) || $address_book_id < 1 )
		{
			$msg = "You are trying to save a file for an empty address book id!";
			throw new \RuntimeException($msg);
		}
		
		//this is required because the field is ENUM ... so update here and in the DB!
		$acceptable_model_codes = array('avatar','passport','visa','idcard','general','tattoo','education','employment','employment_gallery','reference','english','medical','vaccination','idcheck','banner','offer_letter','personal_data','police','seaman','oktb','payment_receipt','flight', 'loe', 'signature');
		
		if(!in_array($model_code, $acceptable_model_codes))
		{
			$msg = "Come on! '{$model_code}' is not an acceptable model code in address book db!";
			throw new \RuntimeException($msg);
		}
				
		return;
	}

	public function searchAddressBookbyEmail($email,$type,$module = false,$partner = false){
		$this->generic = \core\app\classes\generic\generic::getInstance();
		$out = [];	
		$join = '';

		if ($module == 'workflow_reports') {
            $join = ' JOIN `user` on `user`.`email` = `address_book`.`main_email` ';
			if($partner!=='' && $partner!==false) {
				$join .= ' LEFT JOIN `address_book_ent_link` on `address_book`.`address_book_id` = `address_book_ent_link`.`address_book_per_id`';
			}
        }

		$where = " ";
		if($module != 'workflow_reports') {
			$where = " 
				AND `main_email` like CONCAT( '%','".$email."','%') ";
		}

		$qry = "SELECT 
					address_book.address_book_id,
					address_book.main_email,
					address_book.entity_family_name,
					address_book.number_given_name
				FROM 
					`address_book`

				$join
				WHERE 
					`type` = ?
				$where
				";

		if($module == 'partner'){
			$qry .= '
			AND NOT EXISTS (SELECT address_book_id
            FROM `partner` 
            WHERE `partner`.address_book_id = address_book.address_book_id)';
		}else if ($module == 'principal') {
            $qry .= '
			
			AND NOT EXISTS (SELECT address_book_id
            FROM `principal` 
            WHERE `principal`.address_book_id = address_book.address_book_id)';
        }
        else if ($module == 'interview') {
            $qry .= '
			
			AND NOT EXISTS (SELECT address_book_id
            FROM `interview_security_reports` 
            WHERE `interview_security_reports`.address_book_id = address_book.address_book_id)';

        }
        else if ($module == 'offer_letter_endorser') {
            $qry .= '
			
			AND NOT EXISTS (SELECT endorser_id
            FROM `offer_letter_endorser` 
            WHERE `offer_letter_endorser`.endorser_id = address_book.address_book_id)';

        }
        else if ($module == 'offer_letter_reports') {
            $qry .= '
			
			AND NOT EXISTS (SELECT address_book_id
            FROM `offer_letter_reports` 
            WHERE `offer_letter_reports`.address_book_id = address_book.address_book_id)';

        }
        else if ($module == 'workflow_reports') {
            $qry .= '
			
			AND NOT EXISTS (SELECT address_book_id
            FROM `workflow_reports` 
			WHERE `workflow_reports`.address_book_id = address_book.address_book_id)
			';
			if($partner!=='' && $partner!==false) {
				$qry .= '
					AND `address_book_ent_link`.`address_book_ent_id`='.$partner;
			} else {
				$qry .= '
					AND `user`.`security_level_id` != "USER"	
				';
			}
        }

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$type);
		$stmt->bind_result($address_book_id,$main_email,$entity_family_name,$number_given_name);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = [
				'address_book_id' => $address_book_id, 
				'main_email' => $main_email, 
				'entity_family_name' => $entity_family_name,
				'number_given_name' => $number_given_name,
				'fullname' => $this->generic->getName('per', $entity_family_name, $number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME) 
			];
		}
		$stmt->close();
		
		return $out;
	}

	public function getAddressBookPublicFileOwners($filename){
		$out = array();
		
		//select the address book it for the image
		$sql = "SELECT
					`address_book_id`
				FROM
					`address_book_file`
				WHERE
					`filename` = ? 
				AND 
					public = 1
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$filename);
		$stmt->bind_result($address_book_id);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch())
		{
			$out[] = $address_book_id;
		}
		$stmt->free_result();
		$stmt->close();
		
		return $out;

	}

	public function getListAddressBookDatatable($params = []){
        $request = $_POST;
        $table = 'address_book';

        $primaryKey = 'address_book.address_book_id';

        $columns = array(
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'address_book.type', 'dt' => 'type' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.created_on', 'dt' => 'created_on' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = 'LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';

        $where = $this->filter( $request, $columns,$bindings  );
        if(isset($params['ent']) != false){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$params['ent']}' ";
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
			 FROM   `$table`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        $data = $this->data_output($columns, $data);
        foreach ($data as $key => $item){
            $data[$key]['address_book_name'] = $this->generic->getName($item['type'], $item['entity_family_name'], $item['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        }
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $data,
        );
    }

    public function getListAdminAddressBook(){
        $out = array();

        //select the address book it for the image
        $sql = "SELECT
					`address_book`.`address_book_id`,
					`address_book`.`main_email`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`
				FROM
					`address_book`
                JOIN 
                    `user` on `user`.email = `address_book`.`main_email`
				WHERE
					(`user`.`security_level_id` = 'STAFF' or `user`.`security_level_id` = 'ADMIN') and `address_book`.`type` = 'per'
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($address_book_id, $email, $entity_family_name, $number_given_name);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = [
                'address_book_id' => $address_book_id,
                'email' => $email,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
            ];
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function insertAddressBookConnection($address_book_id, $entity_id){
        $sql = "INSERT INTO
					`address_book_connection`
				SET 
					`address_book_id` = ?,
					`connection_type` = 'lp',
					`connection_id` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$address_book_id, $entity_id);
        $out = $stmt->execute();
        $stmt->close();

        return $out;
    }

    public function getAddressBookConnection($address_book_id, $connection_type){
        $out = null;
	    $sql = "SELECT 
					`connection_type`,
					`connection_id` ,
					`created_on`, 
					`created_by`,
					`modified_on`, 
					`modified_by`
				FROM 
				    `address_book_connection`
				WHERE
				    `address_book_id` = ? AND
					`connection_type` = ? 
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is',$address_book_id, $connection_type);
        $stmt->bind_result(
            $connection_type,
            $connection_id ,
            $created_on,
            $created_by,
            $modified_on,
            $modified_by);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->fetch())
        {
            $out = [
                'address_book_id' => $address_book_id,
                'connection_type' => $connection_type,
                'connection_id' => $connection_id,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
            ];
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
	}
	
	public function deleteAllAddressBook($address_book_id,$table) {
		$row = 0;
		foreach ($table as $key => $value) {
			$qry = "DELETE FROM ".$value." WHERE `address_book_id`=".$address_book_id;
			$this->db->query($qry);
			$row = $row + $this->db->affected_rows();
		}
		return $row;

	}
	
		
}
?>