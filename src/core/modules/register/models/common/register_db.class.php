<?php
namespace core\modules\register\models\common;

/**
 * Final register_db class.
 * 
 * @final
 */
final class register_db extends \core\app\classes\module_base\module_db {
	
	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
	
	public function getInfoArray()
	{
		$out = array();
		
		//insert this one in
		$qry = "SELECT
					`code`,
					`type`,
					`heading`,
					`short_description`
				FROM  
					`register_country_info` 
				";			
		$stmt = $this->db->prepare($qry);
		$stmt->bind_result($code,$type,$heading,$short_description);
		
		$qry2 = "SELECT
					`country`
				FROM  
					`register_country_info_country`
				WHERE
					`code` = ?
				";
		$stmt2 = $this->db->prepare($qry2);
		$stmt2->bind_param('s',$code);
		$stmt2->bind_result($country);		
		
		$stmt->execute();
		$stmt->store_result();
		while($stmt->fetch())
		{
			$out[$code] = array('type' => $type, 'heading' => $heading, 'short_description' => $short_description);
			
			$stmt2->execute();
			while($stmt2->fetch())
			{
				$out[$code]['countries'][] = $country;
			}
			
		}
		$stmt->free_result();
		$stmt2->close();
		$stmt->close();
		
		return $out;
	}
	
	public function getCountriesInfoCode()
	{
		$out = array();
		
		//insert this one in
		$qry = "SELECT
					`country`,
					`code`
				FROM
					`register_country_info_country`
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_result($country,$code);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$country] = $code;
		}
		$stmt->close();
		
		return $out;

	}
	
	//register_country_info
	
	public function insertInfo($code,$type,$heading,$short_description)
	{
		//insert this one in
		$qry = "INSERT INTO  
					`register_country_info`  
				SET  
					`code` =  ?,
					`type` = ?,
					`heading` = ?,
					`short_description` = ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ssss",$code,$type,$heading,$short_description);
		$stmt->execute();
		$stmt->close();
		
		return;
	}

	public function updateInfo($code,$type,$heading,$short_description)
	{
		$qry = "UPDATE  
					`register_country_info`  
				SET  
					`type` = ?,
					`heading` = ?,
					`short_description` = ?
				WHERE
					`code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ssss",$type,$heading,$short_description,$code);
		$stmt->execute();
		$stmt->close();
		return;
	}
	
	public function updateInfoType($code,$type)
	{
		$qry = "UPDATE  
					`register_country_info`  
				SET  
					`type` = ?
				WHERE
					`code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$type,$code);
		$stmt->execute();
		$stmt->close();
		return;
	}

	public function updateInfoHeading($code,$heading)
	{
		$qry = "UPDATE  
					`register_country_info`  
				SET  
					`heading` = ?
				WHERE
					`code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$heading,$code);
		$stmt->execute();
		$stmt->close();
		return;
	}

	public function updateInfoShortDescription($code,$short_description)
	{
		$qry = "UPDATE  
					`register_country_info`  
				SET  
					`short_description` = ?
				WHERE
					`code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$short_description,$code);
		$stmt->execute();
		$stmt->close();
		return;
	}
	
	public function deleteInfo($code)
	{
		$qry = "DELETE FROM 
					`register_country_info` 
				WHERE
					`code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$code);
		$stmt->execute();
		$stmt->close();
		
		$this->deleteCountryCode($code); //cascasde
		
		return;
	}
	
	//register_country_info_country
	
	public function insertCountryCode($country,$code)
	{
		$qry = "INSERT INTO  
					`register_country_info_country`  
				SET  
					`country` =  ?,
					`code` = ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$country,$code);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	public function deleteCountryCode($code)
	{
		$qry = "DELETE FROM  
					`register_country_info_country`  
				WHERE 
					`code` = ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$code);
		$stmt->execute();
		$stmt->close();
		
		return;
	}
	
	//register
	
	public function insertRegister($hash,$title,$family_name,$given_name,$middle_names,$dob,$sex,$main_email,$country,$local_partner)
	{
		//insert this one in
		$qry = "INSERT INTO  
					`register`  
				SET  
					`hash` =  ?,
					`title` = ?,
					`family_name` = ?,
					`given_name` = ?,
					`middle_names` =  ?,
					`dob` =  ?,
					`sex` =  ?,
					`main_email` =  ?,
					`country` =  ?,
					`partner_id` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("sssssssssi",$hash,$title,$family_name,$given_name,$middle_names,$dob,$sex,$main_email,$country,$local_partner);
		$stmt->execute();
		$affected_rows = $stmt->affected_rows;
		$stmt->close();
		
		return $affected_rows;
	}
	
	public function getRegistrationInfo($hash)
	{
		$out = array();
		
		//insert this one in
		$qry = "SELECT
					`title`,
					`family_name`,
					`given_name`,
					`middle_names`,
					`dob`,
					`sex`,
					`main_email`,
					`country`,
					`partner_id`,
					(SELECT entity_family_name FROM address_book where address_book_id=`partner_id`)
				FROM  
					`register`  
				WHERE  
					`hash` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$hash);
		$stmt->bind_result($title,$family_name,$given_name,$middle_names,$dob,$sex,$main_email,$country,$partner_id,$partner_name);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = array('title' => $title, 'family_name' => $family_name, 'given_name' => $given_name, 'middle_names' => $middle_names, 'dob' => $dob, 'sex' => $sex, 'main_email' => $main_email, 'country' => $country, 'partner_id' => $partner_id, 'partner_name' => $partner_name);
		}
		$stmt->close();

		return $out;
	}
	
	public function cleanRegistrationInfo()
	{
		$one_days_ago = date('Y-m-d h:i:s',strtotime("-1 day"));
		
		$qry = "DELETE FROM  
					`register`  
				WHERE 
					`created_on` < ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$one_days_ago);
		$stmt->execute();
		$stmt->close();
		return;
	}
	
	public function hasHash($hash)
	{
		$out = false;
		
		//insert this one in
		$qry = "SELECT
					`hash`
				FROM  
					`register`  
				WHERE  
					`hash` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$hash);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function deleteHash($hash)
	{
		$qry = "DELETE FROM  
					`register`  
				WHERE 
					`hash` = ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$hash);
		$stmt->execute();
		$stmt->close();
		return;
	}

	 // get all list partner from table
	 public function getPartnerListByCountry($country_code)
	 {
	   $out = array();
	   
	   $this->db->set_charset('utf8');

	   $sql = "SELECT
			 `id`,`partner_code`,`partner_name`,`created_at`
		   FROM 
			 `partners`
			WHERE
			 `country_code` LIKE CONCAT('%',?, '%')
			AND
			 `status` = 1
		 ";

	   $stmt = $this->db->prepare($sql);
	   
	   $stmt->bind_param('s',$country_code);
	   $stmt->bind_result($id,$partner_code,$partner_name,$created_at);

	   $stmt->execute();

	   while($stmt->fetch())
	   {
		 $out[$id] = array(
		   'id' => $id,
		   'partner_code' => $partner_code,
		   'partner_name' => $partner_name,
		   'created_at' => $created_at
	   );
		 
	   } 
	   $stmt->close();
	   return $out;
	 }

	 public function getPartnerByPartnerCode($partner_code)
	 {
	   $out = array();

	   $sql = "SELECT
			 `partner`.`address_book_id`,
			 `partner`.`partner_code`,
			 `partner_subcountry`.`countryCode_id`,
			 `partner_subcountry`.`countrySubCode_id`
		   FROM 
			 `partner`
            LEFT JOIN `address_book` ON `partner`.`address_book_id` = `address_book`.`address_book_id`
            LEFT JOIN `address_book_file` ON `partner`.`address_book_id` = `address_book_file`.`address_book_id`
			LEFT JOIN `partner_subcountry` ON `partner`.`address_book_id` = `partner_subcountry`.`address_book_id`
			WHERE
			 `partner`.`partner_code` = ?
			AND
			 `status` = 1
		 ";

	   $stmt = $this->db->prepare($sql);
	   
	   $stmt->bind_param('s',$partner_code);
	   $stmt->bind_result($address_book_id,$partner_code,$countryCode_id,$countrySubCode_id);

	   $stmt->execute();

	   $stmt->store_result();
	   while($stmt->fetch())
	   {
			$sub_data = $this->getPartnerSubCountryDetail($address_book_id);
			$out = array(
				'address_book_id' => $address_book_id,
				'partner_code' => $partner_code,
				'countryCode_id' => json_encode($sub_data['countries']),
				'countrySubCode_id' => json_encode($sub_data['subcountries'])
	   );
		 
	   } 
	   $stmt->close();
	   return $out;
	 }

	 public function getPartnerFile($partner_id)
	 {
	   $out = array();
	   $sql = "SELECT
			 `filename`,`type`
		   FROM 
			 `partners_file`
			WHERE
			 `partner_id` = ?
		 ";

	   $stmt = $this->db->prepare($sql);
	   
	   $stmt->bind_param('s',$partner_id);
	   $stmt->bind_result($filename,$type);

	   $stmt->execute();

	   while($stmt->fetch())
	   {
		 $out[$type] = array(
		   'filename' => $filename
	   );
		 
	   } 
	   $stmt->close();
	   return $out;
	 }

	 public function getPartnerCoveredCountry()
	 {
	   $out = array();
	   
	   $this->db->set_charset('utf8');

	   $sql = "SELECT DISTINCT
			 `countryCode_id`
		   FROM 
			 `partner_subcountry`
		 ";

	   $stmt = $this->db->prepare($sql);
	   
	   $stmt->bind_result($countryCode_id);

	   $stmt->execute();

	   while($stmt->fetch())
	   {
		 $out[$id] = array(
		   'id' => $id,
		   'partner_code' => $partner_code,
		   'partner_name' => $partner_name,
		   'created_at' => $created_at
	   );
		 
	   } 
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

}

?>