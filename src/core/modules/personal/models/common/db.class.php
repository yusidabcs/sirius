<?php
namespace core\modules\personal\models\common;

/**
 * Final personal db class.
 *
 * @final
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
	
	//General
	private function _updatePersonal($address_book_id)
	{			
		//set answer insert
		$sql = "INSERT INTO
					`personal`
				SET
					`address_book_id` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->execute();
		$stmt->close();
		return;
	}

	public function getProfileData($address_book_id)
	{
		$out = array();
		
		$sql = 'SELECT
		
					`personal`.`created_on` as "created_on", 
					`personal`.`modified_on`as "modified_on",
					`personal_general`.`tattoo` as "has_tattoo",
				 
				    count(DISTINCT `personal_checklist`.`type`) as "checklist",
					count(DISTINCT `personal_education`.`education_id`) as "education",
					count(DISTINCT `personal_stcw`.`education_id`) as "stcw",
					count(DISTINCT `personal_employment`.`employment_id`) as "employment",
					count(DISTINCT `personal_english`.`english_id`) as "english",
					count(DISTINCT `personal_general`.`address_book_id`) as "general",
					count(DISTINCT `personal_idcard`.`idcard_id`) as "idcard",
				    count(DISTINCT `personal_idcheck`.`idcheck_id`) as "idcheck",
					count(DISTINCT `personal_language`.`languageCode_id`) as "language",
					count(DISTINCT `personal_medical`.`medical_id`) as "medical",
					count(DISTINCT `personal_passport`.`passport_id`) as "passport",
					count(DISTINCT `personal_reference`.`type`) as "reference",
					count(DISTINCT `personal_tattoo`.`tattoo_id`) as "tattoo",
					count(DISTINCT `personal_vaccination`.`vaccination_id`) as "vaccination",
					count(DISTINCT `personal_visa`.`visa_id`) as "visa"
				
				FROM
					`personal`
				
				LEFT JOIN
					`personal_checklist`
				ON
					`personal`.`address_book_id` = `personal_checklist`.`address_book_id`
				
				LEFT JOIN
					`personal_education`
				ON
					`personal`.`address_book_id` = `personal_education`.`address_book_id`

				LEFT JOIN
					`personal_education` as `personal_stcw`
				ON
					`personal`.`address_book_id` = `personal_stcw`.`address_book_id` AND `personal_stcw`.`level` = "stcw"
				
				LEFT JOIN
					`personal_employment`
				ON
					`personal`.`address_book_id` = `personal_employment`.`address_book_id`
				
				LEFT JOIN
					`personal_english`
				ON
					`personal`.`address_book_id` = `personal_english`.`address_book_id`
				
				LEFT JOIN
					`personal_general`
				ON
					`personal`.`address_book_id` = `personal_general`.`address_book_id`
				                      
				LEFT JOIN
					`personal_idcard`
				ON
					`personal`.`address_book_id` = `personal_idcard`.`address_book_id`
				                    
				LEFT JOIN
					`personal_idcheck`
				ON
					`personal`.`address_book_id` = `personal_idcheck`.`address_book_id`
				
				LEFT JOIN
					`personal_language`
				ON
					`personal`.`address_book_id` = `personal_language`.`address_book_id`
				
				LEFT JOIN
					`personal_medical`
				ON
					`personal`.`address_book_id` = `personal_medical`.`address_book_id`
				
				LEFT JOIN
					`personal_passport`
				ON
					`personal`.`address_book_id` = `personal_passport`.`address_book_id`
				
				LEFT JOIN
					`personal_reference`
				ON
					`personal`.`address_book_id` = `personal_reference`.`address_book_id`
				
				LEFT JOIN
					`personal_tattoo`
				ON
					`personal`.`address_book_id` = `personal_tattoo`.`address_book_id`
				
				LEFT JOIN
					`personal_vaccination`
				ON
					`personal`.`address_book_id` = `personal_vaccination`.`address_book_id`
				
				LEFT JOIN
					`personal_visa`
				ON
					`personal`.`address_book_id` = `personal_visa`.`address_book_id`
					
				WHERE
				
					`personal`.`address_book_id` = ?
				            
				GROUP BY
					`personal_checklist`.`address_book_id`,
					`personal_education`.`address_book_id`,
					`personal_stcw`.`address_book_id`,
					`personal_employment`.`address_book_id`,
					`personal_english`.`address_book_id`,
					`personal_idcard`.`address_book_id`,
					`personal_idcheck`.`address_book_id`,
					`personal_language`.`address_book_id`,
					`personal_medical`.`address_book_id`,
					`personal_passport`.`address_book_id`,
					`personal_reference`.`address_book_id`,
					`personal_tattoo`.`address_book_id`,
					`personal_vaccination`.`address_book_id`,
					`personal_visa`.`address_book_id`				
				';

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($created_on,$modified_on,$has_tattoo,$checklist,$education,$stcw,$employment,$english,$general,$idcard,$idcheck,$language,$medical,$passport,$reference,$tattoo,$vaccination,$visa);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out = array( 
							'created_on' => $created_on,
							'modified_on' => $modified_on,
							'has_tattoo' => $has_tattoo,
							'checklist' => $checklist,
							'education' => $education,
							'stcw' => $stcw,
							'employment' => $employment,
							'english_test' => $english,
							'general' => $general,
							'idcard' => $idcard,
							'idcheck' => $idcheck,
							'language' => $language,
							'medical' => $medical,
							'passport' => $passport,
							'reference' => $reference,
							'tattoo' => $tattoo,
							'vaccination' => $vaccination,
							'visa' => $visa
						);
		}
		$stmt->close();
		
		return $out;
	}
	//Checklist
	
	public function getChecklist($personal_id,$type)
	{
		$out = array();
		
		$sql = "SELECT
					`personal_checklist`.`question_id`,
					`personal_checklist`.`answer`,
					`personal_checklist_text`.`text`,
					`personal_checklist`.`created_on`, 
					`personal_checklist`.`created_by`, 
					`personal_checklist`.`modified_on`, 
					`personal_checklist`.`modified_by`
				FROM
					`personal_checklist`
				LEFT JOIN
					`personal_checklist_text`
				ON
					`personal_checklist_text`.`address_book_id` = `personal_checklist`.`address_book_id`
				AND
					`personal_checklist_text`.`type` = `personal_checklist`.`type`
				AND
					`personal_checklist_text`.`question_id` = `personal_checklist`.`question_id`
				WHERE
					`personal_checklist`.`address_book_id` = ?
				AND
					`personal_checklist`.`type` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is',$personal_id,$type);
		$stmt->bind_result($question_id,$answer,$text,$created_on,$created_by,$modified_on,$modified_by);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$question_id] = array( 
							'answer' => $answer,
							'text' => $text,
							'created_on' => $created_on,
							'created_by' => $created_by,
							'modified_on' => $modified_on,
							'modified_by' => $modified_by
						);
		}
		$stmt->close();

		return $out;
	}
	
	public function putChecklist($personal_id,$type,$answer_array)
	{
		//stop the commit so they all execute before we write them
		$this->commitOff();
		
		//set answer insert
		$sql = "INSERT INTO
					`personal_checklist`
				SET
					`address_book_id` = ?,
					`type` = ?,
					`question_id` = ?,
					`answer` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`answer` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isiss',$personal_id,$type,$question_id,$answer,$answer);
		
		//set text insert	
		$sql2 = "INSERT INTO
					`personal_checklist_text`
				SET
					`address_book_id` = ?,
					`type` = ?,
					`question_id` = ?,
					`text` = ?
				ON DUPLICATE KEY UPDATE
					`text` = ?
				";
		$stmt2 = $this->db->prepare($sql2);
		$stmt2->bind_param('isiss',$personal_id,$type,$question_id,$text,$text);
		
		//delete empty text answers
		$sql3 = "DELETE FROM 
					`personal_checklist_text`
				WHERE
					`address_book_id` = ?
				AND
					`type` = ?
				AND
					`question_id` = ?
				";
		$stmt3 = $this->db->prepare($sql3);
		$stmt3->bind_param('isi',$personal_id,$type,$question_id);
			
		foreach($answer_array as $question_id => $value)
		{
			$answer = $value['answer'];
			$text = $value['text'];
			
			$stmt->execute();
			
			if($text)
			{
				$stmt2->execute();
			} else {
				$stmt3->execute();
			}
			
		}
		
		$stmt3->close();
		$stmt2->close();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($personal_id);
		$this->verify($personal_id);
		//commit the changes and turn commit back on
		$this->commit();
		$this->commitOn();
		
		return;
	}
	
	//Passport
	
	public function checkPassportExists($passport_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`passport_id`
				FROM
					`personal_passport`
				WHERE
					`passport_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$passport_id,$address_book_id);
		$stmt->bind_result($passport_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}

    public function getLatestPassport($address_book_id)
    {
        $out = array();

        $sql = "SELECT
					`passport_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`nationality`,
					`sex`,
					`place_issued`,
					`dob`,
					`pob`,
					`type`,
					`code`,
					`authority`,
					`active`,
					`filename`
				FROM
					`personal_passport`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`to_date`
				DESC
				LIMIT 1
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($passport_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
                'countryCode_id' => $countryCode_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'family_name' => $family_name,
                'given_names' => $given_names,
                'full_name' => $full_name,
                'nationality' => $nationality,
                'sex' => $sex,
                'place_issued' => $place_issued,
                'dob' => $dob,
                'pob' => $pob,
                'type' => $type,
                'code' => $code,
                'authority' => $authority,
                'active' => $active,
                'filename' => $filename
            );
        }
        $stmt->close();

        return $out;
    }
	
	public function getPassportList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`passport_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`nationality`,
					`sex`,
					`place_issued`,
					`dob`,
					`pob`,
					`type`,
					`code`,
					`authority`,
					`active`,
					`filename`
				FROM
					`personal_passport`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($passport_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$passport_id] = array(
					'countryCode_id' => $countryCode_id,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'full_name' => $full_name,
					'nationality' => $nationality,
					'sex' => $sex,
					'place_issued' => $place_issued,
					'dob' => $dob,
					'pob' => $pob,
					'type' => $type,
					'code' => $code,
					'authority' => $authority,
					'active' => $active,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getPassport($passport_id)
	{
		$out = array();
		
		$sql = "SELECT
					`passport_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`nationality`,
					`sex`,
					`place_issued`,
					`dob`,
					`pob`,
					`type`,
					`code`,
					`authority`,
					`active`,
					`filename`
				FROM
					`personal_passport`
				WHERE
					`passport_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$passport_id);
		$stmt->bind_result($passport_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
			$view_from = date('d M Y', strtotime($from_date));
			$view_to = date('d M Y', strtotime($to_date));
			$view_dob = date('d M Y', strtotime($dob));
			
			$out = array( 
					'passport_id' => $passport_id,
					'countryCode_id' => $countryCode_id,
					'from_date' => $view_from,
					'to_date' => $view_to,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'full_name' => $full_name,
					'nationality' => $nationality,
					'sex' => $sex,
					'place_issued' => $place_issued,
					'dob' => $view_dob,
					'pob' => $pob,
					'type' => $type,
					'code' => $code,
					'authority' => $authority,
					'active' => $active,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}

	public function putPassport($passport_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_passport`
				SET
					`passport_id` = ?,
					`address_book_id` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`nationality` = ?,
					`sex` = ?,
					`place_issued` = ?,
					`dob` = ?,
					`pob` = ?,
					`type` = ?,
					`code` = ?,
					`authority` = ?,
					`active` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`nationality` = ?,
					`sex` = ?,
					`place_issued` = ?,
					`dob` = ?,
					`pob` = ?,
					`type` = ?,
					`code` = ?,
					`authority` = ?,
					`active` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sissssssssssssssssssssssssssssssss',$passport_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deletePassport($passport_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_passport`
				WHERE
					`passport_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$passport_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	public function deactiveatePassport()
	{
		$qry = "UPDATE
					`personal_passport`
				SET
					`active` = 'not_active'
				WHERE 
					`to_date` <= CURDATE()
		";
		$this->db->query($qry);
		
		return;
	}
	
	//General
	
	public function getGeneral($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`height_cm`,
					`weight_kg`,
					`height_in`,
					`weight_lb`,
					`bmi`,
					`tattoo`,
					`relationship`,
					`children`,
					`employment`,
					`job_hunting`,
					`seafarer`,
					`migration`,
					`country_born`,
					`country_residence`,
					`passport`,
					`travelled_overseas`,
					`nok_family_name`,
					`nok_given_names`,
					`nok_relationship`,
					`nok_line_1`,
					`nok_line_2`,
					`nok_line_3`,
					`nok_country`,
					`nok_number_type`,
					`nok_number`,
					`nok_email`,
					`nok_skype`,
					`filename`,
					`signature_filename`
				FROM
					`personal_general`
				WHERE
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($height_cm,$weight_kg,$height_in,$weight_lb,$bmi,$tattoo,$relationship,$children,$employment,$job_hunting,$seafarer,$migration,$country_born,$country_resident,$passport,$travelled_overseas,$nok_family_name,$nok_given_names,$nok_relationship,$nok_line_1,$nok_line_2,$nok_line_3,$nok_country,$nok_number_type,$nok_number,$nok_email,$nok_skype,$filename,$signature_filename);
		$stmt->execute();
		if($stmt->fetch())
		{	
			//heights, weights and bmi are stored to 2 decimal places as an integer
			$height_cm = $height_cm / 100;
			$weight_kg = $weight_kg / 100;
			$height_in = $height_in / 100;
			$weight_lb = $weight_lb / 100;
			$bmi = $bmi / 100;
			
			if(!empty($height_in))
			{
				$height_weight_current = 'im';
			} else {
				$height_weight_current = 'me';
			}
			
			$out = array(
					'height_weight' => $height_weight_current,
					'height_cm' => $height_cm,
					'weight_kg' => $weight_kg,
					'height_in' => $height_in,
					'weight_lb' => $weight_lb,
					'bmi' => $bmi,
					'tattoo' => $tattoo,
					'relationship' => $relationship,
					'children' => $children,
					'employment' => $employment,
					'job_hunting' => $job_hunting,
					'seafarer' => $seafarer,
					'migration' => $migration,
					'country_born' => $country_born,
					'country_residence' => $country_resident,
					'passport' => $passport,
					'travelled_overseas' => $travelled_overseas,
					'nok_family_name' => $nok_family_name,
					'nok_given_names' => $nok_given_names,
					'nok_relationship' => $nok_relationship,
					'nok_line_1' => $nok_line_1,
					'nok_line_2' => $nok_line_2,
					'nok_line_3' => $nok_line_3,
					'nok_country' => $nok_country,
					'nok_number_type' => $nok_number_type,
					'nok_number' => $nok_number,
					'nok_email' => $nok_email,
					'nok_skype' => $nok_skype,
					'filename' => $filename,
					'signature_filename' => $signature_filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function putGeneral($address_book_id,$height_cm,$weight_kg,$height_in,$weight_lb,$bmi,$tattoo,$relationship,$children,$employment,$job_hunting,$seafarer,$migration,$country_born,$country_residence,$passport,$travelled_overseas,$nok_family_name,$nok_given_names,$nok_relationship,$nok_line_1,$nok_line_2,$nok_line_3,$nok_country,$nok_number_type,$nok_number,$nok_email,$nok_skype,$filename,$signature_filename)
	{	
		
		//heights, weights and bmi are stored to 2 decimal places as an integer
		$height_cm = round($height_cm,2) * 100;
		$weight_kg = round($weight_kg,2) * 100;
		$height_in = round($height_in,2) * 100;
		$weight_lb = round($weight_lb,2) * 100;
		$bmi = $bmi * 100;
		
		//set answer insert
		$sql = "INSERT INTO
					`personal_general`
				SET
					`address_book_id` = ?,
					`height_cm` = ?,
					`weight_kg` = ?,
					`height_in` = ?,
					`weight_lb` = ?,
					`bmi` = ?,
					`tattoo` = ?,
					`relationship` = ?,
					`children` = ?,
					`employment` = ?,
					`job_hunting` = ?,
					`seafarer` = ?,
					`migration` = ?,
					`country_born` = ?,
					`country_residence` = ?,
					`passport` = ?,
					`travelled_overseas` = ?,
					`nok_family_name` = ?,
					`nok_given_names` = ?,
					`nok_relationship` = ?,
					`nok_line_1` = ?,
					`nok_line_2` = ?,
					`nok_line_3` = ?,
					`nok_country` = ?,
					`nok_number_type` = ?,
					`nok_number` = ?,
					`nok_email` = ?,
					`nok_skype` = ?,
					`filename` = ?,
					`signature_filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`height_cm` = ?,
					`weight_kg` = ?,
					`height_in` = ?,
					`weight_lb` = ?,
					`bmi` = ?,
					`tattoo` = ?,
					`relationship` = ?,
					`children` = ?,
					`employment` = ?,
					`job_hunting` = ?,
					`seafarer` = ?,
					`migration` = ?,
					`country_born` = ?,
					`country_residence` = ?,
					`passport` = ?,
					`travelled_overseas` = ?,
					`nok_family_name` = ?,
					`nok_given_names` = ?,
					`nok_relationship` = ?,
					`nok_line_1` = ?,
					`nok_line_2` = ?,
					`nok_line_3` = ?,
					`nok_country` = ?,
					`nok_number_type` = ?,
					`nok_number` = ?,
					`nok_email` = ?,
					`nok_skype` = ?,
					`filename` = ?,
					`signature_filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iiiiiisssssssssssssssssssssssiiiiisssssssssssssssssssssssss',$address_book_id,$height_cm,$weight_kg,$height_in,$weight_lb,$bmi,$tattoo,$relationship,$children,$employment,$job_hunting,$seafarer,$migration,$country_born,$country_residence,$passport,$travelled_overseas,$nok_family_name,$nok_given_names,$nok_relationship,$nok_line_1,$nok_line_2,$nok_line_3,$nok_country,$nok_number_type,$nok_number,$nok_email,$nok_skype,$filename,$signature_filename,$height_cm,$weight_kg,$height_in,$weight_lb,$bmi,$tattoo,$relationship,$children,$employment,$job_hunting,$seafarer,$migration,$country_born,$country_residence,$passport,$travelled_overseas,$nok_family_name,$nok_given_names,$nok_relationship,$nok_line_1,$nok_line_2,$nok_line_3,$nok_country,$nok_number_type,$nok_number,$nok_email,$nok_skype,$filename,$signature_filename);
		$stmt->execute();
		$affected_rows = $stmt->affected_rows;
		
		$stmt->close();
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return $affected_rows;
	}
	
	//Visa
	
	public function checkVisaExists($visa_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`visa_id`
				FROM
					`personal_visa`
				WHERE
					`visa_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$visa_id,$address_book_id);
		$stmt->bind_result($visa_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}

	public function checkFlightExists($flight_number,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`flight_number`
				FROM
					`personal_flight`
				WHERE
					`flight_number` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$flight_number,$address_book_id);
		$stmt->bind_result($flight_number);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getVisaList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`visa_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`place_issued`,
					`entry`,
					`type`,
					`class`,
					`authority`,
					`active`,
					`passport_id`,
					`filename`
				FROM
					`personal_visa`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($visa_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$visa_id] = array(
					'countryCode_id' => $countryCode_id,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'full_name' => $full_name,
					'place_issued' => $place_issued,
					'entry' => $entry,
					'type' => $type,
					'class' => $class,
					'authority' => $authority,
					'active' => $active,
					'passport_id' => $passport_id,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getVisa($visa_id)
	{
		$out = array();
		
		$sql = "SELECT
					`visa_id`,
					`address_book_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`place_issued`,
					`entry`,
					`type`,
					`class`,
					`authority`,
					`active`,
					`passport_id`,
					`filename`
				FROM
					`personal_visa`
				WHERE
					`visa_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$visa_id);
		$stmt->bind_result($visa_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
			$view_from = date('d M Y', strtotime($from_date));
			$view_to = date('d M Y', strtotime($to_date));
			
			$out = array( 
					'visa_id' => $visa_id,
					'address_book_id' => $address_book_id,
					'countryCode_id' => $countryCode_id,
					'from_date' => $view_from,
					'to_date' => $view_to,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'full_name' => $full_name,
					'place_issued' => $place_issued,
					'entry' => $entry,
					'type' => $type,
					'class' => $class,
					'authority' => $authority,
					'active' => $active,
					'passport_id' => $passport_id,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}

	public function putVisa($visa_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_visa`
				SET
					`visa_id` = ?,
					`address_book_id` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`place_issued` = ?,
					`entry` = ?,
					`type` = ?,
					`class` = ?,
					`authority` = ?,
					`active` = ?,
					`passport_id` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`place_issued` = ?,
					`entry` = ?,
					`type` = ?,
					`class` = ?,
					`authority` = ?,
					`active` = ?,
					`passport_id` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sissssssssssssssssssssssssssss',$visa_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteVisa($visa_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_visa`
				WHERE
					`visa_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$visa_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	public function deactiveateVisa()
	{
		$qry = "UPDATE
					`personal_visa`
				SET
					`active` = 'not_active'
				WHERE 
					`to_date` <= CURDATE()
		";
		$this->db->query($qry);
		
		return;
	}

	//IDCard
	
	public function checkIDCardExists($idcard_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`idcard_id`
				FROM
					`personal_idcard`
				WHERE
					`idcard_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$idcard_id,$address_book_id);
		$stmt->bind_result($idcard_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getIDCardList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`idcard_id`,
					`idcard_safe`,
					`idcard_orig`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`type`,
					`authority`,
					`active`,
					`filename`,
					`filename_back`
				FROM
					`personal_idcard`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($idcard_id,$idcard_safe,$idcard_orig,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$idcard_id] = array(
					'idcard_safe' => $idcard_safe,
					'idcard_orig' => $idcard_orig,
					'countryCode_id' => $countryCode_id,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'full_name' => $full_name,
					'type' => $type,
					'authority' => $authority,
					'active' => $active,
					'filename' => $filename,
					'filename_back' => $filename_back
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getIDCard($idcard_id)
	{
		$out = array();
		
		$sql = "SELECT
					`idcard_id`,
					`idcard_safe`,
					`idcard_orig`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`type`,
					`authority`,
					`active`,
					`filename`,
					`filename_back`
				FROM
					`personal_idcard`
				WHERE
					`idcard_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$idcard_id);
		$stmt->bind_result($idcard_id,$idcard_safe,$idcard_orig,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back);
		$stmt->execute();
		if($stmt->fetch())
		{
			$view_from = date('d M Y', strtotime($from_date));
			$view_to = ($to_date == '0000-00-00')? '' : date('d M Y', strtotime($to_date));
			
			$out = array( 
					'idcard_id' => $idcard_id,
					'idcard_safe' => $idcard_safe,
					'idcard_orig' => $idcard_orig,
					'countryCode_id' => $countryCode_id,
					'from_date' => $view_from,
					'to_date' => $view_to,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'full_name' => $full_name,
					'type' => $type,
					'authority' => $authority,
					'active' => $active,
					'filename' => $filename,
					'filename_back' => $filename_back
				);
		}
		$stmt->close();

		return $out;
	}

	public function putIDCard($idcard_id,$idcard_safe,$idcard_orig,$countryCode_id,$address_book_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_idcard`
				SET
					`idcard_id` = ?,
					`address_book_id` = ?,
					`idcard_safe` = ?,
					`idcard_orig` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`type` = ?,
					`authority` = ?,
					`active` = ?,
					`filename` = ?,
					`filename_back` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`idcard_safe` = ?,
					`idcard_orig` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`type` = ?,
					`authority` = ?,
					`active` = ?,
					`filename` = ?,
					`filename_back` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sissssssssssssssssssssssssss',$idcard_id,$address_book_id,$idcard_safe,$idcard_orig,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back,$idcard_safe,$idcard_orig,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteIDCard($idcard_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_idcard`
				WHERE
					`idcard_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$idcard_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	public function deactiveateIDCard()
	{
		$qry = "UPDATE
					`personal_idcard`
				SET
					`active` = 'not_active'
				WHERE 
					`to_date` <= CURDATE()
		";
		$this->db->query($qry);
		
		return;
	}

	//English
	
	public function checkEnglishExists($english_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`english_id`
				FROM
					`personal_english`
				WHERE
					`english_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$english_id,$address_book_id);
		$stmt->bind_result($english_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getEnglishList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`english_id`,
					`type`,
					`overall`,
					`breakdown`,
					`when`,
					`where`,
					`filename`,
					`status`
				FROM
					`personal_english`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`when`
				DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($english_id,$type,$overall,$breakdown,$when,$where,$filename,$status);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$english_id] = array(
					'type' => $type,
					'overall' => $overall,
					'breakdown' => $breakdown,
					'when' => $when,
					'where' => $where,
					'filename' => $filename,
					'status' => $status,
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getEnglish($english_id)
	{
		$out = array();
		
		$sql = "SELECT
					`address_book_id`,
					`type`,
					`overall`,
					`when`,
					`where`,
					`filename`,
					`status`
				FROM
					`personal_english`
				WHERE
					`english_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$english_id);
		$stmt->bind_result($address_book_id,$type,$overall,$when,$where,$filename,$status);
		$stmt->execute();

		$sql2 = "SELECT
						`breakdown_name`,
						`score`
					FROM
						`personal_english_breakdown`
					WHERE
						`english_id` = ?
					";
									
		
		
		if($stmt->fetch())
		{
			$when = date('d M Y', strtotime($when));
			
			$out = array(
					'address_book_id' => $address_book_id,
					'english_id' => $english_id,
					'type' => $type,
					'overall' => $overall,
					'when' => $when,
					'where' => $where,
					'filename' => $filename,
					'status' => $status
				);
		}
		$stmt->close();

		$stmt2 = $this->db->prepare($sql2);
		$stmt2->bind_param('i',$english_id);
		$stmt2->bind_result($breakdown_name,$score);
		$stmt2->execute();
		$breakdown = [];
		while($stmt2->fetch())
		{	
			$breakdown[$breakdown_name] = $score;
		}
		$out['breakdown'] = $breakdown;

		return $out;
	}

	public function insertEnglish($address_book_id)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_english`
				SET
					`address_book_id` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->execute();

		$out = $stmt->insert_id;
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return $out;
	}

	public function insertStcw($address_book_id)
	{
		$sql = "INSERT INTO
					`personal_stcw`
				SET
					`address_book_id` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->execute();

		$out = $stmt->insert_id;
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return $out;
	}

	public function validateEnglish($english_id)
	{
		$sql = "UPDATE `personal_english` SET status = 'accepted' where `english_id` = ? ";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $english_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}
	
	public function validateStcw($education_id)
	{
		$sql = "UPDATE `personal_education` SET status = 'accepted' where `education_id` = ? ";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $education_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function validateMedical($medical_id)
	{
		$sql = "UPDATE `personal_medical` SET status = 'accepted' where `medical_id` = ? ";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $medical_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function rejectMedical($medical_id)
	{
		$sql = "UPDATE `personal_medical` SET status = 'rejected' where `medical_id` = ? ";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $medical_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function validateVaccination($vaccination_id)
	{
		$sql = "UPDATE `personal_vaccination` SET status = 'accepted' where `vaccination_id` = ? ";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $vaccination_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function rejectVaccination($vaccination_id)
	{
		$sql = "UPDATE `personal_vaccination` SET status = 'rejected' where `vaccination_id` = ? ";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $vaccination_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}
		
	public function updateEnglish($type,$overall,$breakdown,$when,$where,$filename,$english_id)
	{		
		//set answer insert
		$sql = "UPDATE
					`personal_english`
				SET
					`type` = ?,
					`overall` = ?,
					`when` = ?,
					`where` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`english_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sssssi',$type,$overall,$when,$where,$filename,$english_id);
		$stmt->execute();
		$stmt->close();

		$sql = "DELETE FROM
					`personal_english_breakdown`
				WHERE
					`english_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$english_id);
		$stmt->execute();
		$stmt->close();
		foreach($breakdown as $item => $value){
			$sql = "INSERT INTO
					`personal_english_breakdown`
				SET
					`english_id` = ?,
					`breakdown_name` = ?,
					`score`= ?
				";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('isi',$english_id, $item, $value);
			$stmt->execute();
			echo $stmt->error;
			$stmt->close();
		}
		
		$this->verify($_SESSION['personal']['address_book_id']);
		return;
	}

	public function updateStcw($type, $serial_no, $certificate_no, $place_issued, $held_by, $held_at, $from_date, $to_date, $stcw_id)
	{
		$sql = "UPDATE
					`personal_stcw`
				SET
					`type` = ?,
					`serial_no` = ?,
					`certificate_no` = ?,
					`place_issued` = ?,
					`held_by` = ?,
					`held_at` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`stcw_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ssssssssi',$type,$serial_no,$certificate_no,$place_issued,$held_by, $held_at, $from_date, $to_date, $stcw_id);
		$stmt->execute();
		$stmt->close();
		$this->verify($_SESSION['personal']['address_book_id']);
		return;
	}
	
	public function deleteEnglish($english_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_english`
				WHERE
					`english_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$english_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();


		$sql = "DELETE FROM
					`personal_english_breakdown`
				WHERE
					`english_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$english_id);
		$stmt->execute();
		$stmt->close();
		
		$this->verify($address_book_id);
		return $out;
	}
	
	public function deactiveateEnglish()
	{
		$qry = "UPDATE
					`personal_english`
				SET
					`active` = 'not_active'
				WHERE 
					`to_date` <= CURDATE()
		";
		$this->db->query($qry);
		return;
	}
	
	//Language
	
	public function getLanguage($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`languageCode_id`,
					`level`,
					`experience`
				FROM
					`personal_language`
				WHERE
					`address_book_id` = ?
				ORDER BY
					`level` DESC,`languageCode_id` ASC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($languageCode_id,$level,$experience);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$languageCode_id] = array(
				'level' => $level,
				'experience' => $experience
			);
		}
		$stmt->close();

		return $out;
	}
	
	public function putLanguage($address_book_id,$languageCode_id,$level,$experience)
	{			
		//set answer insert
		$sql = "INSERT INTO
					`personal_language`
				SET
					`languageCode_id` = ?,
					`address_book_id` = ?,
					`level` = ?,
					`experience` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`level` = ?,
					`experience` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('siiiii',$languageCode_id,$address_book_id,$level,$experience,$level,$experience);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteLanguage($address_book_id,$keep)
	{
		$in = implode("','", $keep);
		
		$out = false;
		
		$sql = "DELETE FROM
					`personal_language`
				WHERE
					`languageCode_id` NOT IN ('{$in}')
				AND
					`address_book_id` = ?
				";
							
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}

	//Employment
	
	public function checkEmploymentExists($employment_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`employment_id`
				FROM
					`personal_employment`
				WHERE
					`employment_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$employment_id,$address_book_id);
		$stmt->bind_result($employment_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getEmploymentList($address_book_id,$job_category_id='')
	{
		$out = array();

		$where = " ";
		if($job_category_id!='') {
			$where = " AND job_speedy_category_id=".$job_category_id;
		}
		
		$sql = "SELECT
					`employment_id`,
					`from_date`,
					`to_date`,
					`employer`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`job_title`,
					`type`,
					`description`,
					`job_speedy_category_id`,
					`active`,
					`filename`
				FROM
					`personal_employment`
				WHERE
					`address_book_id` = ?
				".$where."
				ORDER BY 
					`active` ASC,
					`to_date` DESC,
					`from_date` DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($employment_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$job_speedy_category_id,$active,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$employment_id] = array(
					'from_date' => $from_date,
					'to_date' => $to_date,
					'employer' => $employer,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'job_title' => $job_title,
					'type' => $type,
					'description' => $description,
					'job_speedy_category_id' => $job_speedy_category_id,
					'active' => $active,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getEmployment($employment_id)
	{
		$out = array();
		
		$sql = "SELECT
					`job_speedy_category_id`,
					`from_date`,
					`to_date`,
					`employer`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`job_title`,
					`type`,
					`description`,
					`job_speedy_category_id`,
					`active`,
					`filename`
				FROM
					`personal_employment`
				WHERE
					`employment_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$employment_id);
		$stmt->bind_result($job_category_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$job_speedy_category_id,$active,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
			$view_from = date('d M Y', strtotime($from_date));
			
			if($to_date == '0000-00-00')
			{
				$view_to = '';
				$to_date = '';
				
			} else {
				$view_to = date('d M Y', strtotime($to_date));
			}
			
			$out = array( 
					'employment_id' => $employment_id,
					'job_category_id' => $job_category_id,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'employer' => $employer,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'job_title' => $job_title,
					'type' => $type,
					'description' => $description,
					'job_speedy_category_id' => $job_speedy_category_id,
					'active' => $active,
					'filename' => $filename,
					'view_from' => $view_from,
					'view_to' => $view_to
				);
		}
		$stmt->close();

		return $out;
	}

	public function putEmployment($employment_id,$address_book_id,$job_speedy_category_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$active,$filename)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_employment`
				SET
					`employment_id` = ?,
					`address_book_id` = ?,
					`job_speedy_category_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`employer` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`job_title` = ?,
					`type` = ?,
					`description` = ?,
					`active` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`job_speedy_category_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`employer` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`job_title` = ?,
					`type` = ?,
					`description` = ?,
					`active` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iiissssssssssssissssssssssss',$employment_id,$address_book_id,$job_speedy_category_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$active,$filename,$job_speedy_category_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$active,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteEmployment($employment_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_employment`
				WHERE
					`employment_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$employment_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	//Education
	
	public function checkEducationExists($education_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`education_id`
				FROM
					`personal_education`
				WHERE
					`education_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$education_id,$address_book_id);
		$stmt->bind_result($education_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getEducationList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`education_id`,
					`from_date`,
					`to_date`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`qualification`,
					`type`,
					`description`,
					`level`,
					`attended_countryCode_id`,
					`active`,
					`english`,
					`certificate_date`,
					`certificate_number`,
					`certificate_expiry`,
					`status`,
					`filename`
				FROM
					`personal_education`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`active` ASC,
					`to_date` DESC,
					`from_date` DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($education_id,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$status,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$education_id] = array(
					'from_date' => $from_date,
					'to_date' => $to_date,
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'qualification' => $qualification,
					'type' => $type,
					'description' => $description,
					'level' => $level,
					'attended_countryCode_id' => $attended_countryCode_id,
					'active' => $active,
					'english' => $english,
					'certificate_date' => $certificate_date,
					'certificate_number' => $certificate_number,
					'certificate_expiry' => $certificate_expiry,
					'status' => $status,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getInternetList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`type`,
					`id`
				FROM
					`address_book_internet`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`sequence` ASC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($type,$id);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$type] = $id;
		}
		$stmt->close();

		return $out;
	}
	
	public function getEducation($education_id)
	{
		$out = array();
		
		$sql = "SELECT
					`address_book_id`,
					`from_date`,
					`to_date`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`qualification`,
					`type`,
					`description`,
					`level`,
					`attended_countryCode_id`,
					`active`,
					`english`,
					`certificate_date`,
					`certificate_number`,
					`certificate_expiry`,
					`stcw_type`,
					`filename`
				FROM
					`personal_education`
				WHERE
					`education_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$education_id);
		$stmt->bind_result($address_book_id,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$stcw_type,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
			//from_to
			$view_from = date('d M Y', strtotime($from_date));
			
			if($to_date == '0000-00-00')
			{
				$view_to = '';
				$to_date = '';
				
			} else {
				$view_to = date('d M Y', strtotime($to_date));
			}
			
			//certificate dates
			
			if($certificate_date == '0000-00-00')
			{
				$certificate_date = '';
				$certificate_from = '';
				
			} else {
				$certificate_from = date('d M Y', strtotime($certificate_date));
			}
			
			if($certificate_expiry == '0000-00-00')
			{
				$certificate_expiry = '';
				$certificate_to = '';
				
			} else {
				$certificate_to = date('d M Y', strtotime($certificate_expiry));
			}
			
			$out = array( 
					'education_id' => $education_id,
					'address_book_id' => $address_book_id,
					'from_date' => $from_date,
					'to_date' => $to_date,
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'qualification' => $qualification,
					'type' => $type,
					'description' => $description,
					'level' => $level,
					'attended_countryCode_id' => $attended_countryCode_id,
					'active' => $active,
					'english' => $english,
					'certificate_date' => $certificate_date,
					'certificate_number' => $certificate_number,
					'certificate_expiry' => $certificate_expiry,
					'stcw_type' => $stcw_type,
					'filename' => $filename,
					'view_from' => $view_from,
					'view_to' => $view_to,
					'certificate_from' => $certificate_from,
					'certificate_to' => $certificate_to
				);
		}
		$stmt->close();

		return $out;
	}

	public function getEducationSTCW($address_book_id, $type='')
	{
		$out = array();
		$where = "";
		if($type!='') {
			$where .= " AND stcw_type='".$type."' "; 
		}
		$sql = "SELECT
					`education_id`,
					`address_book_id`,
					`institution`,
					`qualification`,
					`certificate_date`,
					`certificate_expiry`,
					`filename`
				FROM
					`personal_education`
				WHERE
					`address_book_id` = ?
				AND
					`level` = 'stcw'
				".$where."
				ORDER BY 
					`certificate_date` DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($education_id,$address_book_id,$institution, $qualification, $certificate_date, $certificate_expiry,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[] = array( 
					'education_id' => $education_id,
					'address_book_id' => $address_book_id,
					'institution' => $institution,
					'qualification' => $qualification,
					'certificate_date' => date('d M Y', strtotime($certificate_date)),
					'certificate_expiry' => ($certificate_expiry!=''&&$certificate_expiry!='0000-00-00')?date('d M Y', strtotime($certificate_expiry)):'-',
					'url' => '/ab/show/'.$filename
				);
		}
		$stmt->close();

		return $out;
	}

	public function getPreviewMedical($address_book_id, $type='')
	{
		$out = array();
		$where = "";
		if($type!='') {
			$where .= " AND type='".$type."' "; 
		}
		$sql = "SELECT
					`medical_id`,
					`address_book_id`,
					`certificate_number`,
					`doctor`,
					`institution`,
					`certificate_date`,
					`certificate_expiry`,
					`filename`
				FROM
					`personal_medical`
				WHERE
					`address_book_id` = ?
				AND
					`status` NOT IN ('accepted','rejected')
				".$where."
				ORDER BY 
					`certificate_date` DESC
				Limit 1
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($medical_id,$address_book_id,$certificate_number,$doctor,$institution, $certificate_date, $certificate_expiry,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[] = array( 
					'medical_id' => $medical_id,
					'address_book_id' => $address_book_id,
					'certificate_number' => $certificate_number,
					'doctor' => $doctor,
					'institution' => $institution,
					'certificate_date' => date('d M Y', strtotime($certificate_date)),
					'certificate_expiry' => ($certificate_expiry!=''&&$certificate_expiry!='0000-00-00')?date('d M Y', strtotime($certificate_expiry)):'-',
					'url' => '/ab/show/'.$filename
				);
		}
		$stmt->close();

		return $out;
	}

	public function getPreviewVaccine($address_book_id, $type='')
	{
		$out = array();
		$where = "";
		if($type!='') {
			$where .= " AND type='".$type."' "; 
		}
		$sql = "SELECT
					`vaccination_id`,
					`address_book_id`,
					`vaccination_number`,
					`doctor`,
					`institution`,
					`vaccination_date`,
					`vaccination_expiry`,
					`filename`
				FROM
					`personal_vaccination`
				WHERE
					`address_book_id` = ?
				AND
					`status` NOT IN ('accepted','rejected')
				".$where."
				ORDER BY 
					`vaccination_date` DESC
				Limit 1
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($vaccination_id,$address_book_id,$vaccination_number,$doctor,$institution, $vaccination_date, $vaccination_expiry,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[] = array( 
					'vaccination_id' => $vaccination_id,
					'address_book_id' => $address_book_id,
					'vaccination_number' => $vaccination_number,
					'doctor' => $doctor,
					'institution' => $institution,
					'vaccination_date' => date('d M Y', strtotime($vaccination_date)),
					'vaccination_expiry' => ($vaccination_expiry!=''&&$vaccination_expiry!='0000-00-00')?date('d M Y', strtotime($vaccination_expiry)):'-',
					'url' => '/ab/show/'.$filename
				);
		}
		$stmt->close();

		return $out;
	}

	public function putEducation($education_id,$address_book_id,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$filename,$stcw_type,$status)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_education`
				SET
					`education_id` = ?,
					`address_book_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`qualification` = ?,
					`type` = ?,
					`description` = ?,
					`level` = ?,
					`attended_countryCode_id` = ?,
					`active` = ?,
					`english` = ?,
					`certificate_date` = ?,
					`certificate_number` = ?,
					`certificate_expiry` = ?,
					`filename` = ?,
					`stcw_type` = ?,
					`status` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`from_date` = ?,
					`to_date` = ?,
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`qualification` = ?,
					`type` = ?,
					`description` = ?,
					`level` = ?,
					`stcw_type` = ?,
					`attended_countryCode_id` = ?,
					`active` = ?,
					`english` = ?,
					`certificate_date` = ?,
					`certificate_number` = ?,
					`certificate_expiry` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iisssssssssssssssssssssssssssssssssssssss',$education_id,$address_book_id,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$filename,$stcw_type,$status,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$stcw_type,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$filename);
		$stmt->execute();

		// print_r($stmt->error);
		// exit(0);
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteEducation($education_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_education`
				WHERE
					`education_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$education_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	//Tattoo
	
	public function checkTattooExists($tattoo_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`tattoo_id`
				FROM
					`personal_tattoo`
				WHERE
					`tattoo_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$tattoo_id,$address_book_id);
		$stmt->bind_result($tattoo_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getTattooList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`tattoo_id`,
					`location`,
					`short_description`,
					`concealable`,
					`filename`
				FROM
					`personal_tattoo`
				WHERE
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($tattoo_id,$location,$short_description,$concealable,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$tattoo_id] = array(
					'location' => $location,
					'short_description' => $short_description,
					'concealable' => $concealable,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getTattoo($tattoo_id)
	{
		$out = array();
		
		$sql = "SELECT
					`location`,
					`short_description`,
					`concealable`,
					`filename`
				FROM
					`personal_tattoo`
				WHERE
					`tattoo_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$tattoo_id);
		$stmt->bind_result($location,$short_description,$concealable,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = array( 
				'tattoo_id' => $tattoo_id,
				'location' => $location,
				'short_description' => $short_description,
				'concealable' => $concealable,
				'filename' => $filename
			);
		}
		$stmt->close();

		return $out;
	}

	public function putTattoo($tattoo_id,$address_book_id,$location,$short_description,$concealable,$filename)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_tattoo`
				SET
					`tattoo_id` = ?,
					`address_book_id` = ?,
					`location` = ?,
					`short_description` = ?,
					`concealable` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`location` = ?,
					`short_description` = ?,
					`concealable` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iissssssss',$tattoo_id,$address_book_id,$location,$short_description,$concealable,$filename,$location,$short_description,$concealable,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteTattoo($tattoo_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_tattoo`
				WHERE
					`tattoo_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$tattoo_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	//Reference
	
	public function checkReferenceExists($reference_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`reference_id`
				FROM
					`personal_reference`
				WHERE
					`reference_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$reference_id,$address_book_id);
		$stmt->bind_result($reference_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getReferenceList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`reference_id`,
					`type`,
					`entity_name`,
					`family_name`,
					`given_names`,
					`relationship`,
					`line_1`,
					`line_2`,
					`line_3`,
					`countryCode_id`,
					`number_type`,
					`number`,
					`email`,
					`skype`,
					`comment`,
					`filename`
				FROM
					`personal_reference`
				WHERE
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($reference_id,$type,$entity_name,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$reference_id] = array(
					'type' => $type,
					'entity_name' => $entity_name,
					'family_name' => $family_name,
					'given_names' => $given_names,
					'relationship' => $relationship,
					'line_1' => $line_1,
					'line_2' => $line_2,
					'line_3' => $line_3,
					'countryCode_id' => $countryCode_id,
					'number_type' => $number_type,
					'number' => $number,
					'email' => $email,
					'skype' => $skype,
					'comment' => $comment,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getReference($reference_id)
	{
		$out = array();
		
		$sql = "SELECT
					`type`,
					`address_book_id`,
					`entity_name`,
					`family_name`,
					`given_names`,
					`relationship`,
					`line_1`,
					`line_2`,
					`line_3`,
					`countryCode_id`,
					`number_type`,
					`number`,
					`email`,
					`skype`,
					`comment`,
					`filename`
				FROM
					`personal_reference`
				WHERE
					`reference_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$reference_id);
		$stmt->bind_result($type,$address_book_id,$entity_name,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = array( 
				'reference_id' => $reference_id,
				'address_book_id' => $address_book_id,
				'type' => $type,
				'entity_name' => $entity_name,
				'family_name' => $family_name,
				'given_names' => $given_names,
				'relationship' => $relationship,
				'line_1' => $line_1,
				'line_2' => $line_2,
				'line_3' => $line_3,
				'countryCode_id' => $countryCode_id,
				'number_type' => $number_type,
				'number' => $number,
				'email' => $email,
				'skype' => $skype,
				'comment' => $comment,
				'filename' => $filename
			);
		}
		$stmt->close();

		return $out;
	}



	public function putReference($reference_id,$address_book_id,$type,$entity_name,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename)
	{		
		//set answer insert
		$sql = "INSERT INTO
					`personal_reference`
				SET
					`reference_id` = ?,
					`address_book_id` = ?,
					`type` = ?,
					`entity_name` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`relationship` = ?,
					`line_1` = ?,
					`line_2` = ?,
					`line_3` = ?,
					`countryCode_id` = ?,
					`number_type` = ?,
					`number` = ?,
					`email` = ?,
					`skype` = ?,
					`comment` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`type` = ?,
					`entity_name` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`relationship` = ?,
					`line_1` = ?,
					`line_2` = ?,
					`line_3` = ?,
					`countryCode_id` = ?,
					`number_type` = ?,
					`number` = ?,
					`email` = ?,
					`skype` = ?,
					`comment` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iissssssssssssssssssssssssssssss',$reference_id,$address_book_id,$type,$entity_name,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename,$type,$entity_name,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteReference($reference_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_reference`
				WHERE
					`reference_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$reference_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}

	//Medical
	
	public function checkMedicalExists($medical_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`medical_id`
				FROM
					`personal_medical`
				WHERE
					`medical_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$medical_id,$address_book_id);
		$stmt->bind_result($medical_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getMedicalList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`medical_id`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`type`,
					`fit`,
					`certificate_date`,
					`certificate_number`,
					`doctor`,
					`certificate_expiry`,
					`filename`
				FROM
					`personal_medical`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`fit` ASC,
					`certificate_expiry` DESC,
					`certificate_date` DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($medical_id,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$medical_id] = array(
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'type' => $type,
					'fit' => $fit,
					'certificate_date' => $certificate_date,
					'doctor' => $doctor,
					'certificate_number' => $certificate_number,
					'certificate_expiry' => $certificate_expiry,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getMedical($medical_id)
	{
		$out = array();
		
		$sql = "SELECT
					`address_book_id`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`type`,
					`fit`,
					`certificate_date`,
					`certificate_number`,
					`doctor`,
					`certificate_expiry`,
					`filename`
				FROM
					`personal_medical`
				WHERE
					`medical_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$medical_id);
		$stmt->bind_result($address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
					
			//certificate dates
			
			if($certificate_date == '0000-00-00')
			{
				$certificate_date = '';
				$certificate_from = '';
				
			} else {
				$certificate_from = date('d M Y', strtotime($certificate_date));
			}
			
			if($certificate_expiry == '0000-00-00')
			{
				$certificate_expiry = '';
				$certificate_to = '';
				
			} else {
				$certificate_to = date('d M Y', strtotime($certificate_expiry));
			}
			
			$out = array( 
					'address_book_id' => $address_book_id,
					'medical_id' => $medical_id,
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'type' => $type,
					'fit' => $fit,
					'certificate_date' => $certificate_date,
					'certificate_number' => $certificate_number,
					'doctor' => $doctor,
					'certificate_expiry' => $certificate_expiry,
					'filename' => $filename,
					'certificate_from' => $certificate_from,
					'certificate_to' => $certificate_to
				);
		}
		$stmt->close();

		return $out;
	}

	public function putMedical($medical_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename)
	{	
		//set answer insert
		$sql = "INSERT INTO
					`personal_medical`
				SET
					`medical_id` = ?,
					`address_book_id` = ?,
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`type` = ?,
					`fit` = ?,
					`certificate_date` = ?,
					`certificate_number` = ?,
					`doctor` = ?,
					`certificate_expiry` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`type` = ?,
					`fit` = ?,
					`certificate_date` = ?,
					`certificate_number` = ?,
					`doctor` = ?,
					`certificate_expiry` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iissssssssssssssssssssssss',$medical_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteMedical($medical_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_medical`
				WHERE
					`medical_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$medical_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	//Vaccination
	
	public function checkVaccinationExists($vaccination_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`vaccination_id`
				FROM
					`personal_vaccination`
				WHERE
					`vaccination_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$vaccination_id,$address_book_id);
		$stmt->bind_result($vaccination_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getVaccinationList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`vaccination_id`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`type`,
					`vaccination_date`,
					`vaccination_number`,
					`doctor`,
					`vaccination_expiry`,
					`filename`
				FROM
					`personal_vaccination`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`vaccination_expiry` DESC,
					`vaccination_date` DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($vaccination_id,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$vaccination_id] = array(
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'type' => $type,
					'vaccination_date' => $vaccination_date,
					'doctor' => $doctor,
					'vaccination_number' => $vaccination_number,
					'vaccination_expiry' => $vaccination_expiry,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getVaccination($vaccination_id)
	{
		$out = array();
		
		$sql = "SELECT
					`address_book_id`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`type`,
					`vaccination_date`,
					`vaccination_number`,
					`doctor`,
					`vaccination_expiry`,
					`filename`
				FROM
					`personal_vaccination`
				WHERE
					`vaccination_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$vaccination_id);
		$stmt->bind_result($address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
					
			//vaccination dates
			
			if($vaccination_date == '0000-00-00')
			{
				$vaccination_date = '';
				$vaccination_from = '';
				
			} else {
				$vaccination_from = date('d M Y', strtotime($vaccination_date));
			}
			
			if($vaccination_expiry == '0000-00-00')
			{
				$vaccination_expiry = '';
				$vaccination_to = '';
				
			} else {
				$vaccination_to = date('d M Y', strtotime($vaccination_expiry));
			}
			
			$out = array( 
					'address_book_id' => $address_book_id,
					'vaccination_id' => $vaccination_id,
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'type' => $type,
					'vaccination_date' => $vaccination_date,
					'vaccination_number' => $vaccination_number,
					'doctor' => $doctor,
					'vaccination_expiry' => $vaccination_expiry,
					'filename' => $filename,
					'vaccination_from' => $vaccination_from,
					'vaccination_to' => $vaccination_to
				);
		}
		$stmt->close();

		return $out;
	}

	public function putVaccination($vaccination_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename)
	{	
		//set answer insert
		$sql = "INSERT INTO
					`personal_vaccination`
				SET
					`vaccination_id` = ?,
					`address_book_id` = ?,
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`type` = ?,
					`vaccination_date` = ?,
					`vaccination_number` = ?,
					`doctor` = ?,
					`vaccination_expiry` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`type` = ?,
					`vaccination_date` = ?,
					`vaccination_number` = ?,
					`doctor` = ?,
					`vaccination_expiry` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iissssssssssssssssssssss',$vaccination_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteVaccination($vaccination_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_vaccination`
				WHERE
					`vaccination_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$vaccination_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}

	//Idcheck
	
	public function checkIdcheckExists($idcheck_id,$address_book_id)
	{
		$out = false;
		
		$sql = "SELECT
					`idcheck_id`
				FROM
					`personal_idcheck`
				WHERE
					`idcheck_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$idcheck_id,$address_book_id);
		$stmt->bind_result($idcheck_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		}
		$stmt->close();

		return $out;
	}
	
	public function getIdcheckList($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`idcheck_id`,
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`type`,
					`idcheck_date`,
					`idcheck_number`,
					`idcheck_expiry`,
					`filename`
				FROM
					`personal_idcheck`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`idcheck_expiry` DESC,
					`idcheck_date` DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($idcheck_id,$institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$idcheck_id] = array(
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'type' => $type,
					'idcheck_date' => $idcheck_date,
					'idcheck_number' => $idcheck_number,
					'idcheck_expiry' => $idcheck_expiry,
					'filename' => $filename
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getIdcheck($idcheck_id)
	{
		$out = array();
		
		$sql = "SELECT
					`institution`,
					`countryCode_id`,
					`website`,
					`email`,
					`phone`,
					`type`,
					`idcheck_date`,
					`idcheck_number`,
					`idcheck_expiry`,
					`filename`
				FROM
					`personal_idcheck`
				WHERE
					`idcheck_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$idcheck_id);
		$stmt->bind_result($institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename);
		$stmt->execute();
		if($stmt->fetch())
		{
					
			//idcheck dates
			
			if($idcheck_date == '0000-00-00')
			{
				$idcheck_date = '';
				$idcheck_from = '';
				
			} else {
				$idcheck_from = date('d M Y', strtotime($idcheck_date));
			}
			
			if($idcheck_expiry == '0000-00-00')
			{
				$idcheck_expiry = '';
				$idcheck_to = '';
				
			} else {
				$idcheck_to = date('d M Y', strtotime($idcheck_expiry));
			}
			
			$out = array( 
					'idcheck_id' => $idcheck_id,
					'institution' => $institution,
					'countryCode_id' => $countryCode_id,
					'website' => $website,
					'email' => $email,
					'phone' => $phone,
					'type' => $type,
					'idcheck_date' => $idcheck_date,
					'idcheck_number' => $idcheck_number,
					'idcheck_expiry' => $idcheck_expiry,
					'filename' => $filename,
					'idcheck_from' => $idcheck_from,
					'idcheck_to' => $idcheck_to
				);
		}
		$stmt->close();

		return $out;
	}

	public function putIdcheck($idcheck_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename)
	{	
		//set answer insert
		$sql = "INSERT INTO
					`personal_idcheck`
				SET
					`idcheck_id` = ?,
					`address_book_id` = ?,
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`type` = ?,
					`idcheck_date` = ?,
					`idcheck_number` = ?,
					`idcheck_expiry` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`institution` = ?,
					`countryCode_id` = ?,
					`website` = ?,
					`email` = ?,
					`phone` = ?,
					`type` = ?,
					`idcheck_date` = ?,
					`idcheck_number` = ?,
					`idcheck_expiry` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iissssssssssssssssssss',$idcheck_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename,$institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename);
		$stmt->execute();
		$stmt->close();
		
		//update personal
		$this->_updatePersonal($address_book_id);
		$this->verify($address_book_id);
		return;
	}
	
	public function deleteIdcheck($idcheck_id,$address_book_id)
	{
		$out = false;
		
		$sql = "DELETE FROM
					`personal_idcheck`
				WHERE
					`idcheck_id` = ?
				AND
					`address_book_id` = ?
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii',$idcheck_id,$address_book_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		$this->verify($address_book_id);
		return $out;
	}
	
	//get latest verification status
	public function checkVerification($address_book_id)
	{
		$out = array();

		$sql = "SELECT
					`status`,
					`verification_info`,
					`verified_by`,
					`modified_on`
				FROM
					`personal_verification`
				WHERE
					`address_book_id` = ?
					order by modified_on desc LIMIT 1
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($status,$verification_info,$verified_by,$update_at);
		$stmt->execute();
		if($stmt->fetch())
		{	
			$out = array( 
					'status' => $status,
					'verification_info' => $verification_info,
					'verified_by' => $verified_by,
					'modified_on' => $update_at
			);
		}
		$stmt->close();

		return $out;
	}

	public function insertVerification($address_book_id, $status = 'request', $verification_info = '')
	{
		$out = array();

		$sql = "INSERT INTO
					`personal_verification`
				SET
					`address_book_id` = ?,
					`status` = ?,
					`verification_info` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id},
					`verified_on`= CURRENT_TIMESTAMP, 
					`verified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iss',$address_book_id,$status,$verification_info);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();

		return $out;
	}

	public function verify($address_book_id) {
	    return;
		$affected_rows = 0;
		$sql = "SELECT
					`status`
				FROM
					`personal_verification`
				WHERE
					`address_book_id` = ?
					order by created_on DESC LIMIT 1
				";
		$sqla = $sql;							
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($status);
		$stmt->execute();
		$sqlb = '';
		if($stmt->fetch()){}
		$stmt->close();
		if ($status == 'verified')
		{
			//insert status process to personal verification
			$sql2 = "INSERT INTO
						`personal_verification`
					SET
						`address_book_id` = ?,
						`status` = 'process',
						`verification_info` = 'verified data changed, verified status revoked.',
						`created_on`= CURRENT_TIMESTAMP,
						`modified_on`= CURRENT_TIMESTAMP,
						`verified_by`= 0
					";
			$sqlb = $sql2;							
			$stmt2 = $this->db->prepare($sql2);
			$stmt2->bind_param('i',$address_book_id);
			$stmt2->execute();
			$affected_rows = $stmt2->affected_rows;
			$stmt2->close();
		}
	}

	public function getVerificationList($status = 'all'){
		$request = $_POST;
        $table = 'personal_verification';

        $primaryKey = 'personal_verification.address_book_id';

        $columns = array(
            array( 'db' => 'personal_verification.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'personal_verification.status', 'dt' => 'status' ),
            array( 'db' => 'personal_verification.verification_info', 'dt' => 'verification_info' ),
            array( 'db' => 'personal_verification.created_on',  'dt' => 'created_on' ),
            array( 'db' => 'personal_verification.modified_on',  'dt' => 'modified_on' ),
            array( 'db' => 'personal_verification.verified_by', 'dt' => 'verified_by' )
        );

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
		
		//get only one latest record order by last modified_on and last id
		$join = 'LEFT JOIN personal_verification AS t2
					ON personal_verification.address_book_id = t2.address_book_id 
						AND (personal_verification.modified_on < t2.modified_on 
						OR (personal_verification.modified_on = t2.modified_on AND personal_verification.address_book_id < t2.address_book_id))
						
				';
		
		$where = $this->filter( $request, $columns,$bindings  );
		
		
		if (strpos(strtolower($where),'where') === false){
			$where .=' WHERE t2.address_book_id IS NULL ';
			if ( $status != 'all' ){
				$where .= 'AND personal_verification.status = "'.$status .'" ';
			}
		}else{
			$where .=' AND t2.address_book_id IS NULL ';
			if ( $status != 'all' ){
				$where .= 'AND personal_verification.status = "'.$status .'" ';
			}
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

        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

	public function getVerificationHistory($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`personal_verification`.`status`,
					`personal_verification`.`verification_info`,
					`personal_verification`.`created_on`,
					`personal_verification`.`verified_by`,
					`address_book`.`number_given_name`,
					`address_book`.`entity_family_name`
				FROM
					`personal_verification`
				LEFT JOIN 
					(
					SELECT 
						`user_id`,`number_given_name`,`entity_family_name` 
					FROM 
						`address_book` 
					JOIN 
						`user` on `user`.`email` = `address_book`.`main_email` 
					WHERE 
						`address_book`.`type` = 'per'
					) as `address_book` on `address_book`.`user_id` = `personal_verification`.`verified_by`
				WHERE
					`personal_verification`.`address_book_id` = ?
				ORDER BY 
					`created_on`
				DESC
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($status,$verification_info,$created_at,$verified_by, $given_name, $family_name);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[] = array(
					'status' => $status,
					'verification_info' => $verification_info,
					'created_on' => date('d M Y H:i', strtotime($created_at)),
					'verified_by' => $verified_by,
					'given_name' => $given_name,
					'family_name' => $family_name,
				);
		}
		$stmt->close();

		return $out;
	}

	public function getVerificationCount($type, $partner_id = false)
	{
		$out = 0;
		
		$sql = "SELECT
					count(t1.address_book_id), t1.id, t1.address_book_id ";
        if($partner_id !== false){
            $sql .= ', t3.connection_id ';
        }
        $sql .= " FROM
					`personal_verification` t1
				LEFT JOIN `personal_verification` AS `t2`
					ON t1.id < t2.id AND t1.address_book_id = t2.address_book_id";
        if($partner_id !== false){
            $sql .= ' left join address_book_connection t3 on t1.address_book_id = t3.address_book_id ';
        }
		$sql .= " WHERE
					`t2`.`id` IS NULL AND t1.`status` = '{$type}' 
				";
        if($partner_id !== false){
            $sql .= " and t3.connection_id = {$partner_id} ";
        }
		$stmt = $this->db->prepare($sql);
        if($partner_id !== false){
            $stmt->bind_result($count,$count2,$count3,$count4);
        }else{
            $stmt->bind_result($count,$count2,$count3);
        }
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $count;
		}
		$stmt->close();

		return $out;
	}
	
	public function getLatestVerification($address_book_id)
	{
		$out = [];
		
		$sql = "SELECT
					`personal_verification`.`status`,
					`personal_verification`.`verification_info`,
					`personal_verification`.`created_on`,
					`personal_verification`.`verified_by`
				FROM
					`personal_verification`
				WHERE
					`personal_verification`.`address_book_id` = ?
				ORDER BY 
					`created_on`
				DESC LIMIT 1
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($status,$verification_info,$created_at,$verified_by);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out = array(
					'status' => $status,
					'verification_info' => $verification_info,
					'created_on' => date('d M Y H:i', strtotime($created_at)),
					'verified_by' => $verified_by
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function getCurriculumVitae($address_book_id)
	{
		$out = array();
		$internet_list = $this->getInternetList($address_book_id);
		$education_list = $this->getEducationList($address_book_id);
		$employment_list = $this->getEmploymentList($address_book_id);

		$sql = "SELECT 
					address_book.entity_family_name,
					address_book_per.middle_names,
					address_book.number_given_name,
					address_book_per.dob,
					address_book_address.care_of,
					address_book_address.line_1,
					address_book_address.line_2,
					address_book_address.suburb,
					address_book_address.state,
					address_book_address.country,
					address_book_per.sex,
					personal_general.height_cm,
					personal_general.weight_kg,
					personal_general.height_in,
					personal_general.weight_lb,
					address_book_file.filename,
					address_book_pots.country,
					address_book_pots.number,
					address_book.main_email
				FROM address_book
				LEFT JOIN address_book_address USING (address_book_id)
				LEFT JOIN address_book_per USING (address_book_id)
				LEFT JOIN address_book_pots USING (address_book_id)
				LEFT JOIN personal_general USING (address_book_id)
				LEFT JOIN address_book_file ON address_book.address_book_id=address_book_file.address_book_id AND model_code='avatar'
				WHERE
				`address_book`.`address_book_id` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result(
			$entity_family_name,
			$middle_name,
			$number_given_name,
			$dob,
			$care_of,
			$line_1,
			$line_2,
			$suburb,
			$state,
			$country,
			$sex,
			$height_cm,
			$weight_kg,
			$height_in,
			$weight_lb,
			$filename,
			$number_country,
			$number,
			$main_email
		);

		$stmt->execute();
		$stmt->store_result();
		if( $stmt->fetch() )
		{	
			
			$core_db = new \core\app\classes\core_db\core_db;
			$countryCodes = $core_db->getAllCountryCodes();
			$subCountries =$core_db->getSubCountryCodes($country);
			$countryDialCodes = $core_db->getAllDialCodes();

			$hw = ( ($weight_kg == 0) && ($height_cm == 0))
				? ($height_in/100) . ' in / '. ($weight_lb/100).' lb'
				: ($height_cm/100) . ' cm / '. ($weight_kg/100).' kg';
			
			$address = '';
			if (!empty($line_1)) 
				$address .= $line_1;
			if (!empty($line_2)) 
				$address .= ', '.$line_2;
			if (!empty($suburb)) 
				$address .= ', '.$suburb;
			if (!empty($state)) 
				$address .= ' - '.$subCountries[$state];

			$numbers = '';

			if (isset($number_country))
			{
				$numbers = '+'.$countryDialCodes[$number_country]['dialCode'].$number;
			}

			if (isset($country))
			{
				$country = $countryCodes[$country];
			}

			foreach ($education_list as $key => $education)
			{
				$education_list[$key]['from_date'] = date('Y', strtotime($education_list[$key]['from_date']));

				($education_list[$key]['active'] == 'active')
					? $education_list[$key]['to_date'] = 'Present'
					: $education_list[$key]['to_date'] = date('Y', strtotime($education_list[$key]['to_date']));
			}

			foreach ($employment_list as $key => $employment)
			{
				$employment_list[$key]['from_date'] = date('F jS, Y', strtotime($employment_list[$key]['from_date']));

				($employment_list[$key]['to_date'] == '' || $employment_list[$key]['to_date'] == '0000-00-00')
					?$employment_list[$key]['to_date'] = 'now'
					:$employment_list[$key]['to_date'] = date('F jS, Y', strtotime($employment_list[$key]['to_date']));
			}

			$out = array(
					'name' => $number_given_name.(!empty($middle_name)? ' '.$middle_name : '').' '.$entity_family_name,
					'dob' => (empty($dob) || $dob == '0000-00-00') ? 'Not Set': date('F jS, Y', strtotime($dob)),
					'address' => $address,
					'country' => $country,
					'sex' => $sex,
					'hw' => $hw,
					'full_image' => $filename,
					'number' => $numbers,
					'main_email' => $main_email,
					'education_count' => count($education_list),
					'education_list' => $education_list,
					'employment_count' => count($employment_list),
					'employment_list' => $employment_list,
					'internet_list' => $internet_list,
					'address_book_id' => $address_book_id
				);
		}

		$stmt->close();
		return $out;
	}

	public function getReferenceQuestions($group_name){

        $out = null;
        $qry = "SELECT 
                  question_id, 
                  question_type, 
                  question, 
                  answer_type, 
                  sequence, 
                  status 
              FROM `personal_reference_question`
              WHERE
                question_type = ? 
              ORDER BY sequence";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('s',$group_name);
        $stmt->bind_result($question_id, $question_type, $question, $answer_type, $sequence, $status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = [
                'question_id' => $question_id,
                'question_type' => $question_type,
                'question' => $question,
                'answer_type' => $answer_type,
                'sequence' => $sequence,
                'status' => $status
            ];
        }
        $stmt->close();
        return $out;
    }

    public function insertReferenceCheck($data){

        $sql = "INSERT INTO
					`personal_reference_check`
				SET
					`reference_id` = ?,
					`contact_method` = ?,
					`status` = ?,
					`hash` = ?,
					`question_type` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`completed_on`= ?, 
					`completed_by`= ?,
					`requested_on` = ?,
					`requested_by` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isssssisi',$data['reference_id'],$data['contact_method'], $data['status'], $data['hash'], $data['question_type'],$data['completed_on'],$data['completed_by'],$data['requested_on'],$data['requested_by']);
		$stmt->execute();
        $stmt->close();
        return;
    }

	public function updateReferenceCheck($reference_check_id,$data){

        $sql = "UPDATE
					`personal_reference_check`
				SET
					`reference_id` = ?,
					`contact_method` = ?,
					`status` = ?,
					`hash` = ?,
					`question_type` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`completed_on`= ?, 
					`completed_by`= ?
				WHERE
					`reference_check_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isssssii',$data['reference_id'],$data['contact_method'], $data['status'], $data['hash'], $data['question_type'],$data['completed_on'],$data['completed_by'],$reference_check_id);
		$stmt->execute();
        $stmt->close();
        return;
    }

    public function insertReferenceCheckAnswer($type, $data){

	    if($type == 'text') {
            $table = '`personal_reference_check_answer_text`';
        }elseif($type == 'point'){
            $table = '`personal_reference_check_answer_point`';
        }

        $sql = "INSERT INTO
					".$table."
				SET
					`reference_check_id` = ?,
					`question_id` = ?,
					`answer` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis',$data['reference_check_id'],$data['question_id'],$data['answer']);
        $stmt->execute();
        $stmt->close();
        return;
	}
	
	public function deleteReferenceCheckAnswer($reference_check_id)
	{
		$out = false;

		$sql = "DELETE 
					FROM
						`personal_reference_check_answer_point`
					WHERE
						`reference_check_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $reference_check_id);
		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;

	}

	public function deleteReferenceTextAnswer($reference_check_id)
	{
		$out = false;

		$sql = "DELETE 
					FROM
						`personal_reference_check_answer_text`
					WHERE
						`reference_check_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $reference_check_id);
		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;

	}

	public function deleteReferenceCheck($reference_id)
	{
		$out = false;

		$sql = "DELETE 
					FROM
						`personal_reference_check`
					WHERE
						`reference_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $reference_id);
		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

    public function getReferenceCheck($reference_id){
        $out = array();

        $sql = "SELECT
					`personal_reference_check`.`reference_check_id`,
					`personal_reference_check`.`reference_id`,
					`personal_reference_check`.`contact_method`,
					`personal_reference_check`.`status`,
					`personal_reference_check`.`hash`,
					`personal_reference_check`.`question_type`,
					`personal_reference_check`.`completed_on`,
					`personal_reference_check`.`completed_by`,
					`personal_reference_check`.`confirmed_on`,
					`personal_reference_check`.`confirmed_by`,
					`personal_reference_check`.`created_on`,
					`personal_reference_check`.`created_by`,
					`personal_reference_check`.`requested_on`,
					`personal_reference_check`.`requested_by`,
					user_created.`username` as user_created, 
					user_completed.`username` as user_completed,
					user_confirmed.`username` as user_confirmed, 
					user_requested.`username` as user_requested 
				FROM
					`personal_reference_check`
                LEFT JOIN `user` user_created on `personal_reference_check`.`created_by` = user_created.`user_id` 
                LEFT JOIN `user` user_completed on `personal_reference_check`.`completed_by` = user_completed.`user_id`
                LEFT JOIN `user` user_confirmed on `personal_reference_check`.`confirmed_by` = user_confirmed.`user_id`
                LEFT JOIN `user` user_requested on `personal_reference_check`.`requested_by` = user_requested.`user_id`
					
				WHERE
					`id` = ?
				ORDER BY
					`personal_reference_check`.`created_on` DESC
				LIMIT 1
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reference_id);
        $stmt->bind_result($id,$reference_id,$contact_method,$status,$hash,$question_type,$completed_on,$completed_by, $confirmed_on, $confirmed_by, $created_on, $created_by, $requested_on, $requested_by, $user_created, $user_completed, $user_confirmed, $user_requested);
        $stmt->execute();
        if($stmt->fetch())
        {
            $out = array(
				'id' => $id,
                'reference_id' => $reference_id,
                'contact_method' => $contact_method,
                'status' => $status,
                'hash' => $hash,
                'question_type' => $question_type,
                'completed_on' => $completed_on,
                'completed_by' => $completed_by,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'confirmed_by' => $confirmed_by,
                'confirmed_on' => $confirmed_on,
                'requested_on' => $requested_on,
                'requested_by' => $requested_by,
                'user_created' => $user_created,
                'user_completed' => $user_completed,
                'user_confirmed' => $user_confirmed,
                'user_requested' => $user_requested,
            );
        }
        $stmt->close();

        return $out;
	}

	public function getLatestReferenceCheck($reference_id){
        $out = array();

        $sql = "SELECT
					`personal_reference_check`.`reference_check_id`,
					`personal_reference_check`.`reference_id`,
					`personal_reference_check`.`contact_method`,
					`personal_reference_check`.`status`,
					`personal_reference_check`.`hash`,
					`personal_reference_check`.`question_type`,
					`personal_reference_check`.`completed_on`,
					`personal_reference_check`.`completed_by`,
					`personal_reference_check`.`confirmed_on`,
					`personal_reference_check`.`confirmed_by`,
					`personal_reference_check`.`created_on`,
					`personal_reference_check`.`created_by`,
					`personal_reference_check`.`requested_on`,
					`personal_reference_check`.`requested_by`,
					user_created.`username` as user_created, 
					user_completed.`username` as user_completed,
					user_confirmed.`username` as user_confirmed, 
					user_requested.`username` as user_requested 
				FROM
					`personal_reference_check`
                LEFT JOIN `user` user_created on `personal_reference_check`.`created_by` = user_created.`user_id` 
                LEFT JOIN `user` user_completed on `personal_reference_check`.`completed_by` = user_completed.`user_id`
                LEFT JOIN `user` user_confirmed on `personal_reference_check`.`confirmed_by` = user_confirmed.`user_id`
                LEFT JOIN `user` user_requested on `personal_reference_check`.`requested_by` = user_requested.`user_id`

				WHERE
					`personal_reference_check`.`reference_id` = ?
					
				ORDER BY
					`personal_reference_check`.`created_on` DESC
				LIMIT 1
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reference_id);
        $stmt->bind_result($id,$reference_id,$contact_method,$status,$hash,$question_type,$completed_on,$completed_by, $confirmed_on, $confirmed_by, $created_on, $created_by, $requested_on, $requested_by, $user_created, $user_completed, $user_confirmed, $user_requested);
        $stmt->execute();
        if($stmt->fetch())
        {
            $out = array(
				'id' => $id,
                'reference_id' => $reference_id,
                'contact_method' => $contact_method,
                'status' => $status,
                'hash' => $hash,
                'question_type' => $question_type,
                'completed_on' => $completed_on,
                'completed_by' => $completed_by,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'confirmed_by' => $confirmed_by,
                'confirmed_on' => $confirmed_on,
                'requested_on' => $requested_on,
                'requested_by' => $requested_by,
                'user_created' => $user_created,
                'user_completed' => $user_completed,
                'user_confirmed' => $user_confirmed,
                'user_requested' => $user_requested,
            );
        }
        $stmt->close();

        return $out;
	}
	
	public function getReferenceCheckList($reference_id){
		$this->generic = \core\app\classes\generic\generic::getInstance();
        $out = array();

        $sql = "SELECT
					`personal_reference_check`.`reference_check_id`,
					`personal_reference_check`.`reference_id`,
					`personal_reference_check`.`contact_method`,
					`personal_reference_check`.`status`,
					`personal_reference_check`.`hash`,
					`personal_reference_check`.`question_type`,
					`personal_reference_check`.`completed_on`,
					`personal_reference_check`.`completed_by`,
					`personal_reference_check`.`confirmed_on`,
					`personal_reference_check`.`confirmed_by`,
					`personal_reference_check`.`created_on`,
					`personal_reference_check`.`created_by`,
					`personal_reference_check`.`requested_on`,
					`personal_reference_check`.`requested_by`,
					`personal_reference_check`.`rejected_on`,
					`personal_reference_check`.`rejected_by`,
					user_created.`username` as user_created, 
					user_completed.`username` as user_completed,
					user_confirmed.`username` as user_confirmed,
					user_requested.`username` as user_requested,  
					user_rejected.`username` as user_rejected,
					`ab_created`.`entity_family_name` as created_entity_family_name,
					`ab_created`.`number_given_name` as created_number_given_name,
					`ab_requested`.`entity_family_name` as requested_entity_family_name,
					`ab_requested`.`number_given_name` as requested_number_given_name,
					`ab_completed`.`entity_family_name` as completed_entity_family_name,
					`ab_completed`.`number_given_name` as completed_number_given_name,
					`ab_confirmed`.`entity_family_name` as confirmed_entity_family_name,
					`ab_confirmed`.`number_given_name` as confirmed_number_given_name,
					`ab_rejected`.`entity_family_name` as rejected_entity_family_name,
					`ab_rejected`.`number_given_name` as rejected_number_given_name     
				FROM
					`personal_reference_check`
                LEFT JOIN `user` user_created on `personal_reference_check`.`created_by` = user_created.`user_id` 
				LEFT JOIN `address_book` as ab_created on `user_created`.`email`=`ab_created`.`main_email` and `ab_created`.`type`='per'

                LEFT JOIN `user` user_completed on `personal_reference_check`.`completed_by` = user_completed.`user_id`
				LEFT JOIN `address_book` as ab_completed on `user_completed`.`email`=`ab_completed`.`main_email` and `ab_completed`.`type`='per'

                LEFT JOIN `user` user_confirmed on `personal_reference_check`.`confirmed_by` = user_confirmed.`user_id`
				LEFT JOIN `address_book` as ab_confirmed on `user_confirmed`.`email`=`ab_confirmed`.`main_email` and `ab_confirmed`.`type`='per'

				LEFT JOIN `user` user_requested on `personal_reference_check`.`requested_by` = user_requested.`user_id`
				LEFT JOIN `address_book` as ab_requested on `user_requested`.`email`=`ab_requested`.`main_email` and `ab_requested`.`type`='per'

				LEFT JOIN `user` user_rejected on `personal_reference_check`.`rejected_by` = user_rejected.`user_id`
				LEFT JOIN `address_book` as ab_rejected on `user_rejected`.`email`=`ab_rejected`.`main_email` and `ab_rejected`.`type`='per'
				
				WHERE
					`reference_id` = ?

				ORDER BY
					`personal_reference_check`.`created_on` DESC
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reference_id);
        $stmt->bind_result($id,$reference_id,$contact_method,$status,$hash,$question_type,$completed_on,$completed_by, $confirmed_on, $confirmed_by, $created_on, $created_by, $requested_on, $requested_by, $rejected_on, $rejected_by, $user_created, $user_completed, $user_confirmed, $user_requested, $user_rejected,$created_entity_family_name,$created_number_given_name,$requested_entity_family_name,$requested_number_given_name,$completed_entity_family_name,$completed_number_given_name,$confirmed_entity_family_name,$confirmed_number_given_name,$rejected_entity_family_name,$rejected_number_given_name);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
				'id' => $id,
                'reference_id' => $reference_id,
                'contact_method' => $contact_method,
                'status' => $status,
                'hash' => $hash,
                'question_type' => $question_type,
                'completed_on' => $completed_on,
                'completed_by' => $completed_by,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'confirmed_by' => $confirmed_by,
				'confirmed_on' => $confirmed_on,
				'requested_by' => $requested_by,
				'requested_on' => $requested_on,
				'rejected_by' => $rejected_by,
                'rejected_on' => $rejected_on,
                'user_created' => $user_created,
                'user_completed' => $user_completed,
                'user_confirmed' => $user_confirmed,
                'user_requested' => $user_requested,
				'user_rejected' => $user_rejected,
				'ab_created' => $this->generic->getName('per', $created_entity_family_name, $created_number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME),
				'ab_requested' => $this->generic->getName('per', $requested_entity_family_name, $requested_number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME),
				'ab_completed' => $this->generic->getName('per', $completed_entity_family_name, $completed_number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME),
				'ab_confirmed' => $this->generic->getName('per', $confirmed_entity_family_name, $confirmed_number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME),
				'ab_rejected' => $this->generic->getName('per', $rejected_entity_family_name, $rejected_number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME)
				
            );
        }
        $stmt->close();

        return $out;
    }

    public function getReferenceCheckByHash($hash){
        $out = array();

        $sql = "SELECT
					`personal_reference_check`.`reference_check_id`,
					`personal_reference_check`.`reference_id`,
					`personal_reference_check`.`contact_method`,
					`personal_reference_check`.`status`,
					`personal_reference_check`.`hash`,
					`personal_reference_check`.`question_type`,
					`personal_reference_check`.`completed_on`,
					`personal_reference_check`.`completed_by`,
					`personal_reference_check`.`confirmed_on`,
					`personal_reference_check`.`confirmed_by`,
					`personal_reference_check`.`created_on`,
					`personal_reference_check`.`created_by`,
					user_created.`username` as user_created, 
					user_completed.`username` as user_completed 
				FROM
					`personal_reference_check`
                LEFT JOIN `user` user_created on `personal_reference_check`.`created_by` = user_created.`user_id` 
                LEFT JOIN `user` user_completed on `personal_reference_check`.`completed_by` = user_completed.`user_id`
					
				WHERE
					`hash` = ?
                AND 
                    `personal_reference_check`.`status` IN ('pending','request')
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$hash);
        $stmt->bind_result($id,$reference_id,$contact_method,$status,$hash,$question_type,$completed_on,$completed_by, $confirmed_on, $confirmed_by, $created_on, $created_by, $user_created, $user_completed);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
				'id' => $id,
                'reference_id' => $reference_id,
                'contact_method' => $contact_method,
                'status' => $status,
                'hash' => $hash,
                'question_type' => $question_type,
                'completed_on' => $completed_on,
                'completed_by' => $completed_by,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'confirmed_by' => $confirmed_by,
                'confirmed_on' => $confirmed_on,
                'user_created' => $user_created,
                'user_completed' => $user_completed
            );
        }
        $stmt->close();

        return $out;
    }

    public function completedReferenceCheck($reference_id){
        $out = array();

        $sql = "UPDATE 
                    `personal_reference_check`
                SET
					`personal_reference_check`.`status` = 'completed',
					`personal_reference_check`.`completed_on` = CURRENT_TIMESTAMP,
					`personal_reference_check`.`completed_by` = {$this->user_id}
				WHERE
					`personal_reference_check`.`reference_check_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reference_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function confirmReferenceCheck($reference_id){
        $out = array();

        $sql = "UPDATE 
                    `personal_reference_check`
                SET
					`personal_reference_check`.`status` = 'confirmed',
					`personal_reference_check`.`confirmed_on` = CURRENT_TIMESTAMP,
					`personal_reference_check`.`confirmed_by` = {$this->user_id}
				WHERE
					`personal_reference_check`.`reference_check_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$reference_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
	}
	
	public function rejectReferenceCheck($reference_id){
        $out = array();

        $sql = "UPDATE 
                    `personal_reference_check`
                SET
					`personal_reference_check`.`status` = 'rejected',
					`personal_reference_check`.`rejected_on` = CURRENT_TIMESTAMP,
					`personal_reference_check`.`rejected_by` = {$this->user_id}
				WHERE
					`personal_reference_check`.`reference_check_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$reference_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function getReferenceCheckAnswer($reference_check_id){
        $text = $this->_getReferenceCheckAnswerText($reference_check_id);
        $point = $this->_getReferenceCheckAnswerPoint($reference_check_id);
        $out = array_replace($text,$point);
        return $out;
    }

    private function _getReferenceCheckAnswerText($reference_check_id){
        $out = array();

        $sql = "SELECT
					`personal_reference_check_answer_text`.`reference_check_id`,
					`personal_reference_check_answer_text`.`question_id`,
					`personal_reference_check_answer_text`.`answer`,
					`personal_reference_question`.`question`
				FROM
					`personal_reference_check_answer_text`
                JOIN
                    `personal_reference_question` on `personal_reference_question`.question_id = `personal_reference_check_answer_text`.`question_id`  
				WHERE
					`reference_check_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reference_check_id);
        $stmt->bind_result($reference_check_id,$question_id, $answer,$question);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[$question_id] = array(
                'reference_check_id' => $reference_check_id,
                'question_id' => $question_id,
                'answer' => $answer,
                'question' => $question
            );
        }
        $stmt->close();

        return $out;
    }

    private function _getReferenceCheckAnswerPoint($reference_check_id){
        $out = array();

        $sql = "SELECT
					`personal_reference_check_answer_point`.`reference_check_id`,
					`personal_reference_check_answer_point`.`question_id`,
					`personal_reference_check_answer_point`.`answer`,
					`personal_reference_question`.`question`
				FROM
					`personal_reference_check_answer_point`
                JOIN
                    `personal_reference_question` on `personal_reference_question`.question_id = `personal_reference_check_answer_point`.`question_id`  
				WHERE
					`reference_check_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reference_check_id);
        $stmt->bind_result($reference_check_id,$question_id, $answer,$question);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[$question_id] = array(
                'reference_check_id' => $reference_check_id,
                'question_id' => $question_id,
                'answer' => $answer,
                'question' => $question
            );
        }
        $stmt->close();

        return $out;
	}
	
	public function getReferenceCheckStatus($job_application_id)
	{
		$out = array();

        $sql = "SELECT 
					person_c.status,
					work_c.status
				FROM
					job_application 
				LEFT OUTER JOIN 
					personal_reference_check person_c ON job_application.personal_reference_id = person_c.reference_id
				LEFT OUTER JOIN 
					personal_reference_check work_c ON job_application.work_reference_id = work_c.reference_id
				WHERE 
					job_application_id =?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$job_application_id);
		$stmt->bind_result($person_status,$work_status);
		$stmt->execute();
		if ($stmt->fetch())
		{
			$out = array(
				'person' => $person_status,
				'work' => $work_status
			);
		}
		$stmt->close();
        return $out;
    }

	public function getLocalPartnerDataByReferenceId($reference_id)
	{
		$out = array();
		$sql =
			"SELECT
				`address_book`.`address_book_id`,
				`address_book`.`main_email`,
				`address_book`.`entity_family_name`
			FROM
				`address_book_connection`
			JOIN 
				`personal_reference` on `personal_reference`.`address_book_id` = `address_book_connection`.`address_book_id`
			JOIN 
				`address_book` on `address_book`.`address_book_id` = `address_book_connection`.`connection_id`
			WHERE 
				`personal_reference`.`reference_id`= ?
			";
			
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$reference_id);
		$stmt->bind_result($address_book_id,$main_email,$entity_family_name);
		$stmt->execute();
		if ($stmt->fetch())
		{
			$out = array (
				'address_book_id' => $address_book_id,
				'email' => $main_email,
				'entity_family_name' => $entity_family_name
			);
		}
		$stmt->close();
        return $out;
	}

	public function getLocalPartnerDataByAddressBookId($address_book_id)
	{
		$out = array();
		$sql =
			"SELECT
				`partner`.`main_email`,
				`partner`.`entity_family_name`,
				`address_book`.`entity_family_name`,
				`address_book`.`number_given_name`,
				`address_book`.`main_email`
			FROM
				`address_book_connection`
			JOIN 
				`address_book` as `partner` on `partner`.`address_book_id` = `address_book_connection`.`connection_id`
			JOIN 
				`address_book` on `address_book`.`address_book_id` = `address_book_connection`.`address_book_id`
			WHERE 
				`address_book_connection`.`address_book_id`= ?
			";
			
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($main_email,$entity_family_name,$user_family_name,$user_given_name,$user_email);
		$stmt->execute();
		if ($stmt->fetch())
		{
			$out = array (
				'email' => $main_email,
				'entity_family_name' => $entity_family_name,
				'user_family_name' => $user_family_name,
				'user_given_name' => $user_given_name,
				'user_email' => $user_email
			);
		}
		$stmt->close();
        return $out;
	}

    public function getAllCandidateCount($partner_id = false)
    {
        $out = 0;

        $sql = "SELECT
					count(address_book.address_book_id)";
        $sql .= " FROM
					`address_book`";
        if($partner_id !== false){
            $sql .= ' left join address_book_connection on address_book.address_book_id = address_book_connection.address_book_id ';
        }
        if($partner_id !== false){
            $sql .= " WHERE address_book_connection.connection_id = {$partner_id} ";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($count);
        $stmt->execute();
        if($stmt->fetch())
        {
            $out = $count;
        }
        $stmt->close();

        return $out;

    }

    public function putPoliceCheck($data)
    {
        //set answer insert
        $sql = "INSERT INTO
					`personal_police`
				SET
					`police_id` = ?,
					`address_book_id` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`full_name` = ?,
					`nationality` = ?,
					`sex` = ?,
					`place_issued` = ?,
					`dob` = ?,
					`pob` = ?,
					`active` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`address_book_id` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`full_name` = ?,
					`nationality` = ?,
					`sex` = ?,
					`place_issued` = ?,
					`dob` = ?,
					`pob` = ?,
					`active` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssssssssssssssssssssssss',
            $data['police_id'],
            $data['address_book_id'],
            $data['countryCode_id'],
            $data['from_date'],
            $data['to_date'],
            $data['full_name'],
            $data['nationality'],
            $data['sex'],
            $data['place_issued'],
            $data['dob'],
            $data['pob'],
            $data['active'],
            $data['filename'],
            $data['address_book_id'],
            $data['countryCode_id'],
            $data['from_date'],
            $data['to_date'],
            $data['full_name'],
            $data['nationality'],
            $data['sex'],
            $data['place_issued'],
            $data['dob'],
            $data['pob'],
            $data['active'],
            $data['filename']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        echo $stmt->error;
        $stmt->close();

        //update personal
        $this->_updatePersonal($data['address_book_id']);
        return $affected_rows;
    }

    public function getPoliceList($address_book_id)
    {
        $out = array();

        $sql = "SELECT
					`police_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`full_name`,
					`nationality`,
					`sex`,
					`place_issued`,
					`dob`,
					`pob`,
					`active`,
					`valid`,
					`filename`
				FROM
					`personal_police`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($police_id,$countryCode_id,$from_date,$to_date,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$active,$valid,$filename);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[$police_id] = array(
                'police_id' => $police_id,
                'countryCode_id' => $countryCode_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'full_name' => $full_name,
                'nationality' => $nationality,
                'sex' => $sex,
                'place_issued' => $place_issued,
                'dob' => $dob,
                'pob' => $pob,
                'active' => $active,
                'valid' => $valid,
                'filename' => $filename
            );
        }
        $stmt->close();

        return $out;
    }

    public function getPolice($police_id)
    {
        $out = array();

        $sql = "SELECT
					`police_id`,
					`address_book_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`full_name`,
					`nationality`,
					`sex`,
					`place_issued`,
					`dob`,
					`pob`,
					`active`,
					`valid`,
					`filename`
				FROM
					`personal_police`
				WHERE
					`police_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$police_id);
        $stmt->bind_result($police_id,$address_book_id,$countryCode_id,$from_date,$to_date,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$active,$valid,$filename);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
                'police_id' => $police_id,
                'address_book_id' => $address_book_id,
                'countryCode_id' => $countryCode_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'full_name' => $full_name,
                'nationality' => $nationality,
                'sex' => $sex,
                'place_issued' => $place_issued,
                'dob' => $dob,
                'pob' => $pob,
                'active' => $active,
                'valid' => $valid,
                'filename' => $filename
            );
        }
        $stmt->close();

        return $out;
    }
    public function checkPoliceExists($police_id,$address_book_id)
    {
        $out = false;

        $sql = "SELECT
					`police_id`
				FROM
					`personal_police`
				WHERE
					`police_id` = ?
				AND
					`address_book_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$police_id,$address_book_id);
        $stmt->bind_result($police_id);
        $stmt->execute();
        if($stmt->fetch())
        {
            $out = true;
        }
        $stmt->close();

        return $out;
	}
	public function checkSeamanExists($seaman_id,$address_book_id)
    {
        $out = false;

        $sql = "SELECT
					`sbk_id`
				FROM
					`personal_sbk`
				WHERE
					`sbk_id` = ?
				AND
					`address_book_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$seaman_id,$address_book_id);
        $stmt->bind_result($seaman_id);
        $stmt->execute();
        if($stmt->fetch())
        {
            $out = true;
        }
        $stmt->close();

        return $out;
    }

    public function deletePolice($police_id,$address_book_id)
    {
        $out = false;

        $sql = "DELETE FROM
					`personal_police`
				WHERE
					`police_id` = ?
				AND
					`address_book_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$police_id,$address_book_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    public function reviewPolice($police_id,$status)
    {
        $sql = "UPDATE
					`personal_police`
				SET
					`status` = ?
				WHERE 
					`police_id` = ?
		";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$status,$police_id);
        $stmt->execute();
        $out = $stmt->affected_rows;

        $stmt->close();

        return $out;
    }
    public function getAddressBook($address_book_id)
    {
        $out = null;

        $sql = "SELECT 
					`address_book_id`,
					`main_email`,
					`type`,
					`entity_family_name`,
					`number_given_name`
				FROM address_book
				WHERE address_book_id = $address_book_id
				LIMIT 1";

        $stmt = $this->db->query($sql);

        $out = $stmt->fetch_assoc();
        $stmt->close();

        return $out;

    }

    public function getUserBy($field, $value)
    {
        $out = null;

        $sql = "SELECT 
					`user_id`,
					`username`,
					`email`

				FROM user
				WHERE $field = '$value'
				LIMIT 1";

        $stmt = $this->db->query($sql);

        $out = $stmt->fetch_assoc();
        $stmt->close();

        return $out;
    }

    //Seaman
    public function putSeaman($seaman_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$dob,$pob,$authority,$active,$filename)
    {
        //set answer insert
        $sql = "INSERT INTO
					`personal_sbk`
				SET
					`sbk_id` = ?,
					`address_book_id` = ?,
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`nationality` = ?,
					`sex` = ?,
					`dob` = ?,
					`pob` = ?,
					`authority` = ?,
					`active` = ?,
					`filename` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`countryCode_id` = ?,
					`from_date` = ?,
					`to_date` = ?,
					`family_name` = ?,
					`given_names` = ?,
					`full_name` = ?,
					`nationality` = ?,
					`sex` = ?,
					`dob` = ?,
					`pob` = ?,
					`authority` = ?,
					`active` = ?,
					`filename` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sissssssssssssssssssssssssss',$seaman_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$dob,$pob,$authority,$active,$filename,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$dob,$pob,$authority,$active,$filename);
        $stmt->execute();
        $stmt->close();

        //update personal
        $this->_updatePersonal($address_book_id);
        //$this->verify($address_book_id);
        return;
    }

    public function getSeamanBookList($address_book_id)
    {
        $out = array();

        $sql = "SELECT
					`sbk_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`nationality`,
					`sex`,
					`dob`,
					`pob`,
					`authority`,
					`active`,
					`status`,
					`filename`
				FROM
					`personal_sbk`
				WHERE
					`address_book_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($seaman_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$dob,$pob,$authority,$active,$status,$filename);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[$seaman_id] = array(
                'countryCode_id' => $countryCode_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'family_name' => $family_name,
                'given_names' => $given_names,
                'full_name' => $full_name,
                'nationality' => $nationality,
                'sex' => $sex,
                'dob' => $dob,
                'pob' => $pob,
                'authority' => $authority,
				'active' => $active,
				'status' => $status,
                'filename' => $filename
            );
        }
        $stmt->close();

        return $out;
	}
	
	public function getPreviewSeaman($address_book_id)
	{
		$out = null;

        $sql = "SELECT
					`sbk_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`nationality`,
					`code`,
					`authority`,
					`status`,
					`filename`
				FROM
					`personal_sbk`
				WHERE
					`address_book_id` = ?
				AND
					`status` = 'pending'
				ORDER BY 
					`to_date`
				DESC
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($seaman_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$code,$authority,$status,$filename);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
				'seaman_id' => $seaman_id,
                'countryCode_id' => $countryCode_id,
                'from_date' => date('d M Y', strtotime($from_date)),
                'to_date' => date('d M Y', strtotime($to_date)),
                'family_name' => $family_name,
                'given_names' => $given_names,
                'full_name' => $full_name,
                'nationality' => $nationality,
                'code' => $code,
                'authority' => $authority,
				'status' => $status,
				'filename' => $filename,
				'url' => '/ab/show/' . $filename
            );
        }
        $stmt->close();

        return $out;
	}
	
	public function getSeaman($sbk_id)
    {
        $out = array();

        $sql = "SELECT
					`sbk_id`,
					`address_book_id`,
					`countryCode_id`,
					`from_date`,
					`to_date`,
					`family_name`,
					`given_names`,
					`full_name`,
					`nationality`,
					`sex`,
					`dob`,
					`pob`,
					`authority`,
					`active`,
					`filename`
				FROM
					`personal_sbk`
				WHERE
					`sbk_id` = ?
				ORDER BY 
					`to_date`
				DESC
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$sbk_id);
        $stmt->bind_result($sbk_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$dob,$pob,$authority,$active,$filename);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
				'sbk_id' => $sbk_id,
				'address_book_id' => $address_book_id,
                'countryCode_id' => $countryCode_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'family_name' => $family_name,
                'given_names' => $given_names,
                'full_name' => $full_name,
                'nationality' => $nationality,
                'sex' => $sex,
                'dob' => $dob,
                'pob' => $pob,
                'authority' => $authority,
                'active' => $active,
                'filename' => $filename
            );
        }
        $stmt->close();

        return $out;
	}
	
	public function updateSeamanStatus($seaman_id, $status)
	{
		$out = null;

		$sql = "UPDATE
					`personal_sbk`
				SET
					`status` = ?
				WHERE
					`sbk_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $seaman_id);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		} else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function deleteSeaman($seaman_id)
	{
		$out = null;

		$sql = "DELETE FROM
					`personal_sbk`
				WHERE
					`sbk_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $seaman_id);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		} else {
			$out = false;
		}

		$stmt->close();

		return $out;
	}
	
	public function updateStcwDocumentStatus($education_id, $status)
	{
		$sql = "UPDATE
					`personal_education`
				SET
					`status` = ?
				WHERE
					`education_id` = ?
				AND
					`level` = 'stcw'";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $education_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function updateMedicalStatus($medical_id, $status)
	{
		$sql = "UPDATE
					`personal_medical`
				SET
					`status` = ?
				WHERE
					`medical_id` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $medical_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function updateVaccinationStatus($vaccination_id, $status)
	{
		$sql = "UPDATE
					`personal_vaccination`
				SET
					`status` = ?
				WHERE
					`vaccination_id` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $vaccination_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function updateVisaStatus($visa_id, $status)
	{
		$sql = "UPDATE
					`personal_visa`
				SET
					`status` = ?
				WHERE
					`visa_id` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ss', $status, $visa_id);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function getPreviewVisa($address_book_id, $visa_type)
	{
		$out = false;
		$sql = "SELECT
					`address_book_id`,
					`visa_id`,
					`type`,
					`from_date`,
					`to_date`,
					`filename`
				FROM 
					personal_visa
				WHERE
					`address_book_id` = ?
				AND
					`type` = ?
				AND
					`status` NOT IN('accepted','rejected')
				LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ss', $address_book_id, $visa_type);
		$stmt->bind_result($_address_book_id, $visa_id, $type, $from_date, $to_date, $filename);

		$stmt->execute();
		if ($stmt->fetch()) {
			$out = [
				'address_book_id' => $_address_book_id,
				'visa_id' => $visa_id,
				'type' => $type,
				'from_date' => $from_date,
				'to_date' => $to_date,
				'filename' => $filename,
				'url' => '/ab/show/' . $filename
			];
		}

		return $out;
	}

	public function getAddressBookByUserId($user_id)
	{
		$sql = "SELECT
					`user`.`user_id`,
					`user`.`email`,
					`address_book`.`address_book_id`,
					`address_book`.`main_email`,
					`address_book`.`type`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`
				FROM
					`user`
				LEFT JOIN
					`address_book`
				ON
					`user`.`email` = `address_book`.`main_email`
				WHERE
					`user`.`user_id` = $user_id";
		$stmt = $this->db->query($sql);

		if ($data = $stmt->fetch_assoc()) {
			$stmt->close();

			return $data;
		}

		return false;
	}

	public function getFlightList($address_book_id)
	{
		$out = array();

		$sql = "SELECT 
					`address_book_id`,
					`flight_number`,
					`departure_date`,
					`status`,
					`filename`
				FROM
					`personal_flight`
				WHERE
					`address_book_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $address_book_id);
		$stmt->bind_result($_address_book_id, $flight_number, $departure_date, $status, $filename);

		$stmt->execute();

		while ($stmt->fetch()) {
			$out[] = array(
				'address_book_id' => $_address_book_id,
				'flight_number' => $flight_number,
				'departure_date' => $departure_date,
				'status' => $status,
				'filename' => $filename
			);
		}
		$stmt->close();

		return $out;
	}

	public function putFlight($address_book_id, $flight_number, $filename, $departure_date)
	{
		$sql = "INSERT INTO
					`personal_flight`
				SET
					`address_book_id` = ?,
					`flight_number` = ?,
					`filename` = ?,
					`departure_date` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`filename` = ?,
					`departure_date` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isssss', $address_book_id, $flight_number, $filename, $departure_date, $filename, $departure_date);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	public function getFlight($flight_number)
	{
		$out = false;

		$sql = "SELECT 
					`address_book_id`,
					`flight_number`,
					`filename`,
					`departure_date`
				FROM
					`personal_flight`
				WHERE
					`flight_number` = ?
				AND
					`status` NOT IN('rejected')
				LIMIT 1";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $flight_number);

		$stmt->execute();
		$stmt->bind_result($address_book_id, $_flight_number, $filename, $departure_date);

		if ($stmt->fetch()) {
			$out = array(
				'address_book_id' => $address_book_id,
				'flight_number' => $_flight_number,
				'filename' => $filename,
				'departure_date' => $departure_date,
			);
		}

		$stmt->close();

		return $out;
				
	}

	public function updateFlightStatus($flight_number, $status)
	{
		$out = false;

		$sql = "UPDATE
					`personal_flight`
				SET
					`status` = ?
				WHERE
					`flight_number` = ?
				AND
					`status` NOT IN('rejected','accepted')";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $flight_number);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function deleteFlight($flight_number, $address_book_id)
	{
		$out = false;
		$sql = "DELETE 
				FROM
					`personal_flight`
				WHERE
					`address_book_id` = ?
				AND
					`flight_number` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $flight_number);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		return $out;
	}

	public function putOktb($address_book_id, $oktb_number, $oktb_type, $oktb_date, $oktb_expired, $active, $filename)
	{
		$sql = "INSERT INTO
					`personal_oktb`
				SET
					`address_book_id` = ?,
					`oktb_number` = ?,
					`oktb_type` = ?,
					`filename` = ?,
					`date_of_issue` = ?,
					`valid_until` = ?,
					`active` = ?,
					`created_on` = CURRENT_TIMESTAMP,
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`oktb_type` = ?,
					`filename` = ?,
					`date_of_issue` = ?,
					`valid_until` = ?,
					`active` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isssssssssss', $address_book_id, $oktb_number, $oktb_type,$filename, $oktb_date, $oktb_expired, $active,$oktb_type,$filename, $oktb_date, $oktb_expired, $active);
		$stmt->execute();

		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	
	}

	public function deleteOktb($oktb_number, $personal_id)
    {
        $out = false;

        $sql = "DELETE FROM `personal_oktb`
                    WHERE `oktb_number` = ?
                    AND `address_book_id` = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('ii', $oktb_number,$personal_id);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }
        $stmt->close();

        return $out;
    }

    public function checkOktbExists($oktb_number, $personal_id)
    {
        $out = false;

        $sql = "SELECT
                    `oktb_number`,
                    `address_book_id`
                FROM `personal_oktb`
                WHERE `oktb_number` = ?
                AND `address_book_id` = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('is', $oktb_number,$personal_id);
        $stmt->bind_result($_oktb_number, $address_book_id);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = array(
                'oktb_number' => $_oktb_number,
                'address_book_id' => $address_book_id
            );
        }
        $stmt->close();

        return $out;
    }

    public function updateOktb($oktb_number, $oktb_type, $oktb_date, $oktb_expired, $active, $filename)
    {
        $out = false;
        $sql = "UPDATE
					`personal_oktb`
                    SET
                        `oktb_type` = ?,
                        `date_of_issue` = ?,
                        `valid_until` = ?,
                        `active` = ?,
                        `filename` = ?
                WHERE
                    `oktb_number` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssssi', $oktb_type, $oktb_date, $oktb_expired, $active, $filename, $oktb_number);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getOktbList($address_book_id)
    {
        $out = [];

        $sql = "SELECT 
                    `address_book_id`,
                    `oktb_number`,
                    `oktb_type`,
                    `date_of_issue`,
                    `valid_until`,
                    `filename`
                FROM
                    `personal_oktb`
                WHERE
                    `address_book_id` = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $address_book_id);
        $stmt->bind_result($addr_book_id, $oktb_number, $oktb_type, $date_of_issue, $valid_until, $filename);
        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'address_book_id' => $addr_book_id,
                'oktb_number' => $oktb_number,
                'oktb_type' => $oktb_type,
                'date_of_issue' => $date_of_issue,
                'valid_until' => $valid_until,
                'filename' => $filename
            );
        }

        return $out;
    }

    public function getOktb($oktb_number)
    {
        $out = false;

        $sql = "SELECT 
                    `address_book_id`,
                    `oktb_number`,
                    `oktb_type`,
                    `date_of_issue`,
                    `valid_until`,
                    `active`,
                    `filename`
                FROM
                    `personal_oktb`
                WHERE
                    `oktb_number` = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $oktb_number);
        $stmt->bind_result($address_book_id, $_oktb_number, $oktb_type, $date_of_issue, $valid_until, $active, $filename);
        $stmt->execute();

        if ($stmt->fetch()) {
			$view_from = date('d M Y', strtotime($date_of_issue));
			$view_to = date('d M Y', strtotime($valid_until));

            $out = array(
                'address_book_id' => $address_book_id,
                'oktb_number' => $_oktb_number,
                'oktb_type' => $oktb_type,
                'date_of_issue' => $view_from,
                'valid_until' => $view_to,
                'active' => $active,
                'filename' => $filename
            );
        }

        return $out;
    }

    public function getOktbByAddressBook($address_book_id,$type='')
    {
		$out = false;
		$where = "";
		if($type!='') {
			$where .= " AND oktb_type='".$type."' "; 
		}
        $sql = "SELECT 
                    `address_book_id`,
                    `oktb_number`,
                    `oktb_type`,
                    `date_of_issue`,
                    `valid_until`,
                    `active`,
                    `filename`,
                    `status`
                FROM
                    `personal_oktb`
                WHERE
                    `address_book_id` = ?
                AND
                    `status` NOT IN('accepted')
				".$where."
                ORDER BY
                    `created_on` DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $address_book_id);
        $stmt->bind_result($address_book_id, $oktb_number, $oktb_type, $date_of_issue, $valid_until, $active, $filename, $status);
        $stmt->execute();

        if ($stmt->fetch()) {
            $out = array(
                'address_book_id' => $address_book_id,
                'oktb_number' => $oktb_number,
                'oktb_type' => $oktb_type,
                'date_of_issue' => $date_of_issue,
                'valid_until' => $valid_until,
                'active' => $active,
                'filename' => $filename,
                'url' => SITE_WWW . '/ab/show/' . $filename
            );
        }

        return $out;
    }

    public function updateOktbStatus($oktb_number, $status)
    {
        $sql = "UPDATE
                    `personal_oktb`
                SET
                    `status` = ?
                WHERE
                    `oktb_number` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $oktb_number);
        $stmt->execute();

        $out = $stmt->affected_rows;

        $stmt->close();

        return $out;
    }

	public function getPreviewFlight($address_book_id)
	{
		$out = false;

		$sql = "SELECT 
					`address_book_id`,
					`flight_number`,
					`filename`,
					`departure_date`
				FROM
					`personal_flight`
				WHERE
					`address_book_id` = ?
				AND
					`status` = 'pending'
				ORDER BY
					`created_on` DESC
				LIMIT 1";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $address_book_id);

		$stmt->execute();
		$stmt->bind_result($_address_book_id, $flight_number, $filename, $departure_date);

		if ($stmt->fetch()) {
			$out = array(
				'address_book_id' => $_address_book_id,
				'flight_number' => $flight_number,
				'filename' => $filename,
				'departure_date' => date('M d, Y', strtotime($departure_date)),
				'url' => '/ab/show/' . $filename
			);
		}

		$stmt->close();

		return $out;
	}

	public function getPreviewPolice($address_book_id)
	{
		$out = array();
		
		$sql = "SELECT
					`police_id`,
					`address_book_id`,
					`place_issued`,
					`active`,
					`from_date`,
					`to_date`,
					`filename`
				FROM
					`personal_police`
				WHERE
					`address_book_id` = ?
				AND
					`status` NOT IN ('accepted','rejected')
				ORDER BY 
					`from_date` DESC
				Limit 1
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$address_book_id);
		$stmt->bind_result($police_id,$address_book_id,$place_issued,$active, $from_date, $to_date,$filename);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[] = array( 
					'police_id' => $police_id,
					'address_book_id' => $address_book_id,
					'place_issued' => $place_issued,
					'active' => $active,
					'from_date' => date('d M Y', strtotime($from_date)),
					'to_date' => ($to_date!=''&&$to_date!='0000-00-00')?date('d M Y', strtotime($to_date)):'-',
					'url' => '/ab/show/'.$filename
				);
		}
		$stmt->close();

		return $out;
	}

	public function updatePoliceStatus($police_id, $status)
	{
		$out = false;

		$sql = "UPDATE
					`personal_police`
				SET
					`status` = ?
				WHERE
					`police_id` = ?
				AND
					`status` NOT IN('rejected','accepted')";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $police_id);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function getPassportDatatable($address_book_id, $cols = array())
	{
		$this->generic = \core\app\classes\generic\generic::getInstance();
		$request = $_POST;
		$tablename = 'personal_passport';
		$primaryKey = $tablename.'.passport_id';
        $columns = array(
            array( 'db' => $tablename.'.`passport_id`', 'dt' => 'passport_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.active', 'dt' => 'active' ),
			array( 'db' => $tablename.'.from_date', 'dt' => 'from_date'),
			array( 'db' => $tablename.'.to_date', 'dt' => 'to_date'),
			array( 'db' => $tablename.'.nationality', 'dt' => 'nationality'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
			array( 'db' => $tablename.'.from_date', 'dt' => 'length', 'formatter' => function ($d, $row) {
				return $this->generic->tsDiffStr(strtotime($row['from_date']),strtotime($row['to_date']),2);
            })
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getReferenceDatatable($reference_id)
	{
		$request = $_POST;
		$tablename = 'personal_reference';
		$primaryKey = $tablename.'.reference_id';
        $columns = array(
            array( 'db' => $tablename.'.`reference_id`', 'dt' => 'reference_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.type', 'dt' => 'type' ),
			array( 'db' => $tablename.'.family_name', 'dt' => 'family_name'),
			array( 'db' => $tablename.'.given_names', 'dt' => 'given_names'),
			array( 'db' => $tablename.'.relationship', 'dt' => 'relationship'),
			array( 'db' => $tablename.'.number_type', 'dt' => 'number_type'),
			array( 'db' => $tablename.'.number', 'dt' => 'number'),
			array( 'db' => $tablename.'.email', 'dt' => 'email'),
			array( 'db' => $tablename.'.skype', 'dt' => 'skype'),
			array( 'db' => 'personal_reference_check.status', 'dt' => 'status'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename')
		);
		

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = " LEFT JOIN personal_reference_check ON $tablename.reference_id = personal_reference_check.reference_id";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`reference_id` = $reference_id";

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

	public function getVisaDatatable($address_book_id, $cols = array())
	{
		$tablename = "personal_visa";
		$primaryKey = "$tablename.visa_id";
		$request = $_POST;
        $columns = array(
            array( 'db' => $tablename.'.`visa_id`', 'dt' => 'visa_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.passport_id', 'dt' => 'passport_id' ),
			array( 'db' => $tablename.'.from_date', 'dt' => 'from_date'),
			array( 'db' => $tablename.'.to_date', 'dt' => 'to_date'),
			array( 'db' => $tablename.'.place_issued', 'dt' => 'place_issued'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename')
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getOktbDatatable($address_book_id, $cols = array())
	{
		$tablename = "personal_oktb";
		$primaryKey = "$tablename.oktb_number";
		$request = $_POST;
        $columns = array(
            array( 'db' => $tablename.'.`oktb_number`', 'dt' => 'oktb_number' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.date_of_issue', 'dt' => 'date_of_issue'),
			array( 'db' => $tablename.'.valid_until', 'dt' => 'valid_until'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename')
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getIdCardDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_idcard';
		$primaryKey = $tablename.'.idcard_id';
        $columns = array(
            array( 'db' => $tablename.'.`idcard_id`', 'dt' => 'idcard_id' ),
            array( 'db' => $tablename.'.`idcard_orig`', 'dt' => 'idcard_orig' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.active', 'dt' => 'active' ),
			array( 'db' => $tablename.'.authority', 'dt' => 'authority' ),
			array( 'db' => $tablename.'.from_date', 'dt' => 'from_date'),
			array( 'db' => $tablename.'.to_date', 'dt' => 'to_date'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
			array( 'db' => $tablename.'.filename_back', 'dt' => 'filename_back'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getIdCheckDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_idcheck';
		$primaryKey = $tablename.'.idcheck_id';
        $columns = array(
            array( 'db' => $tablename.'.`idcheck_id`', 'dt' => 'idcheck_id' ),
            array( 'db' => $tablename.'.`idcheck_number`', 'dt' => 'idcheck_number' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.countryCode_id', 'dt' => 'countryCode_id' ),
			array( 'db' => $tablename.'.idcheck_number', 'dt' => 'idcheck_number' ),
			array( 'db' => $tablename.'.institution', 'dt' => 'institution' ),
			array( 'db' => $tablename.'.idcheck_date', 'dt' => 'idcheck_date'),
			array( 'db' => $tablename.'.idcheck_expiry', 'dt' => 'idcheck_expiry'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getPoliceDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_police';
		$primaryKey = $tablename.'.police_id';
        $columns = array(
            array( 'db' => $tablename.'.`police_id`', 'dt' => 'police_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.`countryCode_id`', 'dt' => 'countryCode_id' ),
            array( 'db' => $tablename.'.`active`', 'dt' => 'active' ),
            array( 'db' => $tablename.'.from_date', 'dt' => 'from_date' ),
			array( 'db' => $tablename.'.to_date', 'dt' => 'to_date' ),
			array( 'db' => $tablename.'.nationality', 'dt' => 'nationality'),
			array( 'db' => $tablename.'.status', 'dt' => 'status'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getMedicalDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_medical';
		$primaryKey = $tablename.'.medical_id';
        $columns = array(
            array( 'db' => $tablename.'.`medical_id`', 'dt' => 'medical_id' ),
            array( 'db' => $tablename.'.`certificate_number`', 'dt' => 'certificate_number' ),
            array( 'db' => $tablename.'.`institution`', 'dt' => 'institution' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.`certificate_date`', 'dt' => 'certificate_date' ),
            array( 'db' => $tablename.'.`type`', 'dt' => 'type' ),
            array( 'db' => $tablename.'.fit', 'dt' => 'fit' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getVaccinationDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_vaccination';
		$primaryKey = $tablename.'.vaccination_id';
        $columns = array(
            array( 'db' => $tablename.'.`vaccination_id`', 'dt' => 'vaccination_id' ),
            array( 'db' => $tablename.'.`vaccination_number`', 'dt' => 'vaccination_number' ),
            array( 'db' => $tablename.'.`institution`', 'dt' => 'institution' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.`vaccination_date`', 'dt' => 'vaccination_date' ),
            array( 'db' => $tablename.'.`type`', 'dt' => 'type' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getSeamanDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_sbk';
		$primaryKey = $tablename.'.sbk_id';
        $columns = array(
            array( 'db' => $tablename.'.`sbk_id`', 'dt' => 'sbk_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.active', 'dt' => 'active' ),
			array( 'db' => $tablename.'.from_date', 'dt' => 'from_date'),
			array( 'db' => $tablename.'.to_date', 'dt' => 'to_date'),
			array( 'db' => $tablename.'.nationality', 'dt' => 'nationality'),
			array( 'db' => $tablename.'.status', 'dt' => 'status'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getStcwDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_education';
		$primaryKey = $tablename.'.education_id';
        $columns = array(
            array( 'db' => $tablename.'.`education_id`', 'dt' => 'education_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
			array( 'db' => $tablename.'.from_date', 'dt' => 'from_date'),
			array( 'db' => $tablename.'.to_date', 'dt' => 'to_date'),
			array( 'db' => $tablename.'.level', 'dt' => 'level'),
			array( 'db' => $tablename.'.institution', 'dt' => 'institution'),
			array( 'db' => $tablename.'.status', 'dt' => 'status'),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
		$where .= "$tablename.`address_book_id` = $address_book_id";
		
		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.`level` = 'STCW'";

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

	public function getFlightDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_flight';
		$primaryKey = $tablename.'.flight_number';
        $columns = array(
            array( 'db' => $tablename.'.`flight_number`', 'dt' => 'flight_number' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.departure_date', 'dt' => 'departure_date' ),
            array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
		$where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function getEnglishDatatable($address_book_id, $cols = array())
	{
		$request = $_POST;
		$tablename = 'personal_english';
		$primaryKey = $tablename.'.english_id';
        $columns = array(
            array( 'db' => $tablename.'.`english_id`', 'dt' => 'english_id' ),
            array( 'db' => $tablename.'.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.type', 'dt' => 'type' ),
            array( 'db' => $tablename.'.when', 'dt' => 'when' ),
            array( 'db' => $tablename.'.overall', 'dt' => 'overall' ),
            array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.filename', 'dt' => 'filename'),
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "";

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
		$where .= "$tablename.`address_book_id` = $address_book_id";

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

	public function checkUnixReference($address_book_id,$col,$val_col) {
		$out = array();
		
		$sql = "SELECT
					`reference_id`
				FROM
					`personal_reference`
				WHERE
					`address_book_id` = ? 
				AND  
					$col = ? 
				";
								
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is',$address_book_id,$val_col);
		$stmt->bind_result($reference_id);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[] = array( 
					'reference_id' => $reference_id,
				);
		}
		$stmt->close();

		return $out;
	}
	
	public function updateVerificationStatus($address_book_id, $status)
	{
		$out = false;

		$sql = "UPDATE `personal` SET `status` = ? WHERE `address_book_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $status, $address_book_id);

		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function checkPersonal($address_book_id)
	{
		$out = false;

		$sql = "SELECT `address_book_id` FROM `personal` WHERE `address_book_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $address_book_id);
		$stmt->bind_result($address_book_id);

		$stmt->execute();

		if ($stmt->fetch()) {
			$out = $address_book_id;
		}

		$stmt->close();

		return $out;
	}

	public function insertPersonal($address_book_id, $status = 'unverified')
	{
		$out = false;

		$sql = "INSERT INTO `personal`(`address_book_id`,`created_on`,`created_by`,`modified_on`,`modified_by`,`status`) VALUES(?,CURRENT_TIMESTAMP,?,CURRENT_TIMESTAMP,?,?)";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iiis', $address_book_id, $_SESSION['user_id'], $_SESSION['user_id'], $status);

		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}
}