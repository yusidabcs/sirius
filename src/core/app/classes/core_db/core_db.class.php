<?php
namespace core\app\classes\core_db;

/**
 * Final core_db class.
 *
 * A db connection that allows the system to get information from the core database.
 * Inserts and updates are handled in the control section of the system.
 * 
 * @final
 * @package 	core_db
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 7 January 2016
 * @update
 */
final class core_db extends \core\app\classes\module_base\module_db {
	
	public function __construct()
	{
		parent::__construct('core'); //sets up db connection to use core database 
		return;
	}
		
	public function getAllCountryCodes($country_codes = false)
	{
		$out = array();
		
		$this->db->set_charset('utf8');
		
		$sql = "SELECT
					`countryCode_id`,
					`country`
				FROM 
					`countrycode`
			";

		if($country_codes != false){
			$country_codes = implode("','", json_decode($country_codes));
			$country_codes = "'".$country_codes."'";
		    $sql .= 'WHERE `countryCode_id` in ('.$country_codes.')';
        }


		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($countryCode_id,$country);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$countryCode_id] = $country;
		} 
		$stmt->close();
		
		return $out;
	}
	public function searchCountryCodes($countryCode)
	{
		$out = array();

		$sql = "SELECT
					`countryCode_id`,
					`country`
				FROM 
					`countrycode`
                WHERE
                    `countryCode_id` like '%?%'
			";

		$stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$countryCode);
		$stmt->bind_result($countryCode_id,$country);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$countryCode_id] = $country;
		}
		$stmt->close();

		return $out;
	}

    public function getCountry($countryCode)
    {
        $out = array();

        $sql = "SELECT
					`countryCode_id`,
					`country`
				FROM 
					`countrycode`
                WHERE
                    `countryCode_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$countryCode);
        $stmt->bind_result($countryCode_id,$country);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[$countryCode_id] = $country;
        }
        $stmt->close();

        return $out;
    }

	public function getSubCountry($countrySubCode)
    {
        $out = array();

        $sql = "SELECT
					`countryCode_id`,
					`countrySubCode_id`,
					`commonName`
				FROM 
					`countrysubcode`
				WHERE
					`countrySubCode_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$countrySubCode);
        $stmt->bind_result($countryCode_id,$countrySubCode_id,$commonName);
        $stmt->execute();
        while($stmt->fetch())
        {
			// $out[$countrySubCode_id] = $commonName;
			$out[$countryCode_id] = $commonName;
        }
        $stmt->close();

        return $out;
	}
	
    public function getAllSubCountry()
    {
        $out = array();

        $sql = "SELECT
					`countryCode_id`,
					`countrySubCode_id`,
					`commonName`
				FROM 
					`countrysubcode`
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($countryCode_id,$countrySubCode_id,$commonName);
        $stmt->execute();
        while($stmt->fetch())
        {
			// $out[$countrySubCode_id] = $commonName;
			$out[$countryCode_id][$countrySubCode_id] = $commonName;
        }
        $stmt->close();

        return $out;
    }

	public function getAnzsicName($anzsicCode)
	{
		$anzsicTitle = '';
		
		$sql = "SELECT
					`title`
				FROM 
					`anzsic`
				WHERE
					`anzsic_id` = ?
			";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$anzsicCode);
		$stmt->bind_result($anzsicTitle);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();

		return $anzsicTitle;
	}
		
	public function getSubCountryCodes($countryCode_id)
	{
		$out = array();
		
		$this->db->set_charset('utf8');
		
		$sql = "SELECT
					`countrySubCode_id`,
					`commonName`
				FROM 
					`countrysubcode`
				WHERE
					`countryCode_id` = ?
			";
		// var_dump($sql);
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$countryCode_id);
		$stmt->bind_result($countrySubCode_id,$commonName);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$countrySubCode_id] = $commonName;
		} 
		$stmt->close();
		
		return $out;
	}
	
	public function getMultipleSubCountryCodes($countryCode_ids)
	{
		
		$out = array();

        $countries = json_decode($countryCode_ids);

        foreach ($countries as $country_code){
            $out[$country_code] = $this->getSubCountryCodes($country_code);
        }
		
		return $out;
	}
	
	public function getAllDialCodes()
	{
		$out = array();
		
		$this->db->set_charset('utf8');
		
		$sql = "SELECT
					`countryDial_id`,
					`country`,
					`dialCode`
				FROM 
					`countrydial`
				ORDER BY
					`country`
			";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($countryDial_id,$country,$dialCode);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$countryDial_id]['country'] = $country;
			$out[$countryDial_id]['dialCode'] = $dialCode;
		} 
		$stmt->close();
		
		return $out;
	}
	
	public function getChecklistCharacter()
	{
		$out = array();
		
		$sql = "SELECT
					`question_id`,
					`question`,
					`help`,
					`answer_heading`
				FROM 
					`checklistcharacter`
				WHERE
					`status` = 1
			";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($question_id,$question,$help,$answer_heading);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$question_id] = array(
				'relevance' => 'both',
				'question' => $question,
				'help' => $help,
				'answer_heading' => $answer_heading
			);
		} 
		$stmt->close();
		
		return $out;
	}
	
	public function getChecklistHealth()
	{
		$out = array();
		
		$sql = "SELECT
					`question_id`,
					`relevance`,
					`question`,
					`help`,
					`answer_heading`
				FROM 
					`checklisthealth`
				WHERE
					`status` = 1
			";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($question_id,$relevance,$question,$help,$answer_heading);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$question_id] = array(
				'relevance' => $relevance,
				'question' => $question,
				'help' => $help,
				'answer_heading' => $answer_heading
			);
		} 
		$stmt->close();
		
		return $out;
	}
	
	public function getChecklistAnswerHeadings($type,$question_id_array)
	{
		$out = array();
		
		$question_id_string = implode(',', $question_id_array);
		
		if($type == 'character')
		{
			$sql = "SELECT
						`question_id`,
						`answer_heading`
					FROM 
						`checklistcharacter`
					WHERE
						`question_id` IN ($question_id_string)
				";
		} else {
			$sql = "SELECT
						`question_id`,
						`answer_heading`
					FROM 
						`checklisthealth`
					WHERE
						`question_id` IN ($question_id_string)
				";
		}
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($question_id,$answer_heading);
		$stmt->execute();
		
		while($stmt->fetch())
		{
			$out[$question_id] = $answer_heading;
		}
		
		$stmt->close();
		
		return $out;
	}
	
	public function getAllLanguageCodes()
	{
		$out = array();
		
		$this->db->set_charset('utf8');
		
		$sql = "SELECT
					`languageCode_id`,
					`description`
				FROM 
					`languagecode`
			";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($languageCode_id,$description);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$languageCode_id] = $description;
		} 
		$stmt->close();
		
		return $out;
	}
	
	public function getMajorLanguageCodes()
	{
		$out = array();
		
		$this->db->set_charset('utf8');
		
		$sql = "SELECT
					`languageCode_id`,
					`description`
				FROM 
					`languagecode`
				WHERE
					CHAR_LENGTH(`languageCode_id`) = 2
				ORDER BY
					`description`
			";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($languageCode_id,$description);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[$languageCode_id] = $description;
		} 
		$stmt->close();
		
		return $out;
	}

    public function getPreIntreviewQuestion()
    {
        $out = array();

        $sql = "SELECT
					`question_id`,
					`parent_id`,
					`question`,
					`type`,
					`relevance`,
					`more`,
					`show_child`,
					`help`,
					`answer_heading`,
					`sequence`,
					`status`
				FROM 
					`prescreenquestion`
				ORDER BY
				    `sequence` ASC 
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($question_id,$parent_id,$question,$type,$relevance,$more,$show_child,$help,$answer_heading,$sequence,$status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'question_id' => $question_id,
                'parent_id' => $parent_id,
                'question' => $question,
                'type' => $type,
                'relevance' => $relevance,
                'more' => $more,
                'show_child' => $show_child,
                'help' => $help,
                'answer_heading' => $answer_heading,
                'sequence' => $sequence,
                'status' => $status
            );
        }
        $stmt->close();

        return $out;
    }
    public function checkPreIntreviewQuestionHasChilds($parent_id)
    {
        $out = 0;

        $sql = "SELECT
					count(*) as total
				FROM 
					`prescreenquestion`
				WHERE
				    `parent_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$parent_id);
        $stmt->bind_result($total);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = $total;
        }
        $stmt->close();

        return $out;
    }
    public function getOnePreIntreviewQuestion($id)
    {
        $out = array();

        $sql = "SELECT
					`question_id`,
					`parent_id`,
					`question`,
					`type`,
					`relevance`,
					`more`,
					`show_child`,
					`help`,
					`answer_heading`,
					`sequence`,
					`status`
				FROM 
					`prescreenquestion`
                WHERE
                  `question_id` = ?
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->bind_result($question_id,$parent_id,$question,$type,$relevance,$more,$show_child,$help,$answer_heading,$sequence,$status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
                'question_id' => $question_id,
                'parent_id' => $parent_id,
                'question' => $question,
                'type' => $type,
                'relevance' => $relevance,
                'more' => $more,
                'show_child' => $show_child,
                'help' => $help,
                'answer_heading' => $answer_heading,
                'sequence' => $sequence,
                'status' => $status
            );
        }
        $stmt->close();

        return $out;
    }

    public function updatePreIntreviewQuestion($data)
    {
        $sql = "UPDATE `prescreenquestion`
        
                SET
					`parent_id` = ?,
					`question` = ?,
					`type` = ?,
					`relevance` = ?,
					`more` = ?,
					`show_child` = ?,
					`help` = ?,
					`answer_heading` = ?,
					`status` = ?
                WHERE
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isssiissii",$data['parent_id'],$data['question'],$data['type'],$data['relevance'],$data['more'],$data['show_child'],$data['help'],$data['answer_heading'],$data['status'], $data['question_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function updateSequencePreIntreviewQuestion($data)
    {

        $old_data = $this->getOnePreIntreviewQuestion($data['id']);

        $sql = "UPDATE
					`prescreenquestion`
				SET 
                    `prescreenquestion`.`sequence` = ?,
                    `prescreenquestion`.`parent_id` = ? 
				WHERE
				    `question_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii',$data['index'],$data['parent_id'],$data['id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        if($out){

            if($old_data['parent_id'] == $data['parent_id']){
                if($old_data['sequence'] < $data['index']){
                    $sql = "UPDATE
                        `prescreenquestion`
                    SET 
                        `prescreenquestion`.`sequence` = `prescreenquestion`.`sequence` - 1
                    WHERE
                        `prescreenquestion`.`sequence` <= {$data['index']} 
                        and `prescreenquestion`.`parent_id` = {$old_data['parent_id']}
                        and `prescreenquestion`.`question_id` != {$data['id']}
                    ";
                }else{
                    $sql = "UPDATE
                        `prescreenquestion`
                    SET 
                        `prescreenquestion`.`sequence` = `prescreenquestion`.`sequence` + 1
                    WHERE
                        `prescreenquestion`.`sequence` >= {$data['index']} 
                        and `prescreenquestion`.`sequence` < {$old_data['sequence']} 
                        and `prescreenquestion`.`parent_id` = {$old_data['parent_id']} 
                        and `prescreenquestion`.`question_id` != {$data['id']}
                    ";
                }
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $rs = $stmt->affected_rows;
                $stmt->close();
            }else{

                //parent lama

                $sql = "UPDATE
                        `prescreenquestion`
                    SET 
                        `prescreenquestion`.`sequence` = `prescreenquestion`.`sequence` - 1
                    WHERE
                        `prescreenquestion`.`sequence` > {$old_data['sequence']} and `prescreenquestion`.`parent_id` = {$old_data['parent_id']}
                    ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $rs = $stmt->affected_rows;
                $stmt->close();

                $sql = "UPDATE
					`prescreenquestion`
                    SET 
                        `prescreenquestion`.`sequence` = `prescreenquestion`.`sequence` + 1
                    WHERE
                        `prescreenquestion`.`sequence` >= {$data['index']} and `prescreenquestion`.`parent_id` = {$data['parent_id']}
                        and `prescreenquestion`.`question_id` != {$data['id']}
                    ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $rs = $stmt->affected_rows;
                $stmt->close();
            }
        }

        return $out;
    }

    public function updateStatusPreIntreviewQuestion($question_id, $status)
    {
        $out = array();

        $sql = "UPDATE `prescreenquestion`
        
                SET
					`status` = ?
				FROM 
					`prescreenquestion`
                WHERE
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($status,$question_id);
        $stmt->execute();
        $stmt->close();

        return $out;
    }

    public function getLatestSequence($parent_id){

        $qry = "SELECT `sequence` FROM `prescreenquestion` 
                  WHERE `parent_id` = ? order by `sequence` desc limit 1";

        $stmt = $this->db->prepare($qry);
        $stmt->bind_param("i",$parent_id);
        $stmt->bind_result(
            $sequence
        );
        $stmt->execute();
        if($stmt->fetch()){
            $sequence = $sequence + 1;
        };
        $stmt->close();

        if($sequence == null){
            $sequence = 1;
        }

        return $sequence;
    }


    public function insertPreIntreviewQuestion($data)
    {
        $data['sequence'] = $this->getLatestSequence($data['parent_id']);
        $out = array();
        $sql = "INSERT INTO `prescreenquestion`
                SET
					`parent_id` = ?,
					`question` = ?,
					`type` = ?,
					`relevance` = ?,
					`more` = ?,
					`show_child` = ?,
					`help` = ?,
					`answer_heading` = ?,
					`sequence` = ?,
					`status` = ?
                    
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isssiissii",$data['parent_id'],$data['question'],$data['type'],$data['relevance'],$data['more'],$data['show_child'],$data['help'],$data['answer_heading'],$data['sequence'],$data['status']);
        $stmt->execute();
        $stmt->close();

        return $out;
    }

    public function deletePreIntreviewQuestion($id){

        $old_data = $this->getOnePreIntreviewQuestion($id);

        $out = [];

        $sql = "DELETE
					FROM `prescreenquestion`
                WHERE
					`question_id` = ? OR `parent_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$id,$id);
        $out = $stmt->execute();
        $stmt->close();
        if($out){
            $sql = "UPDATE
					`prescreenquestion`
				SET 
                    `prescreenquestion`.`sequence` = `prescreenquestion`.`sequence` - 1
				WHERE
				    `prescreenquestion`.`sequence` > {$old_data['sequence']} and `prescreenquestion`.`parent_id` = {$old_data['parent_id']}
				";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $stmt->affected_rows;
            $stmt->close();
        }
        return $out;
    }

}
?>