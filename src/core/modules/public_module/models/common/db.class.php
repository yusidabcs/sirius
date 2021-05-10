<?php
namespace core\modules\public_module\models\common;
/**
 * Final cv/db class.
 *
 * @final
 * 
 */
final class db extends \core\app\classes\module_base\module_db {


    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }
    
    public function getAllJobSpeedyByDemand($demand='',$data,$start=false,$limit=false,$except_job_code=false)
    {
        $out = array(
        );

        $where = "";
        if($except_job_code!='' && $except_job_code!=false) {
            $where .= " AND `job_speedy`.`job_speedy_code` <> '".$except_job_code."'";
        }
        if(isset($data['job_speedy_code'])) {
            if($data['job_speedy_code']!='') {
                $where .= " AND `job_speedy`.`job_speedy_code`='".$data['job_speedy_code']."'";
            }
        }

        if(isset($data['country'])) {
            if($data['country']!='') {
                $where .= " AND (FIND_IN_SET('{$data['country']}', `job_speedy`.`country`) OR `job_speedy`.`country`='' OR `job_speedy`.`country`='ALL')";
            }
        }

        if(isset($data['category'])) {
            if($data['category']!='') {
                $where .= " AND `job_speedy`.`job_speedy_category_id`=".$data['category'];
            }
        }

        if(isset($data['min_experience'])) {
            if($data['min_experience']!='') {
                $where .= " AND `job_speedy`.`min_experience`>=".$data['min_experience'];
            }
        }
        if(isset($data['min_education'])) {
            if($data['min_education']!='') {
                $education = ['school','certificate','diploma','degree','honours','masters','doctorate'];
                $pos = array_search($data['min_education'], $education);
                $where .= " AND `job_speedy`.`min_education`>=".($pos+1);
            }
        }
        $sql = "SELECT 
                        `job_speedy`.`job_speedy_code`,
                        `job_speedy`.`job_title`,
                        `job_speedy`.`short_description`,
                        `job_speedy`.`min_requirement`,
                        `job_speedy`.`min_experience`,
                        `job_speedy`.`min_education`,
                        `job_speedy`.`stcw_req`,
                        `job_speedy`.`min_english_experience`,
                        `job_speedy`.`min_salary`,
                        `job_speedy`.`max_salary`,
                        `job_speedy`.`job_speedy_category_id`,
                        `job_speedy`.`sequence`,
                        `job_speedy`.`created_on`,
                        `job_speedy`.`created_by`,
                        `job_speedy`.`country`,
                        `job_speedy`.`cover_image`,
                        `job_speedy_category`.`name` as `job_category_name`
                    FROM
                        `job_speedy`
                    LEFT JOIN
                        `job_speedy_category` ON `job_speedy`.`job_speedy_category_id`=`job_speedy_category`.`job_speedy_category_id`

                    WHERE
                        `job_speedy`.`job_speedy_code` IN ({$demand})
                    {$where}
                    
                    ";
        
        $sql .= " ORDER BY `sequence` ";
        if($start!==false && $limit!==false) {
            $sql .= " LIMIT ".$limit." OFFSET ".$start;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req, $min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $sequence, $created_on, $created_by, $country,$cover_image, $job_category_name );

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_speedy_code' => $job_speedy_code,
                'job_title' => $job_title,
                'short_description' => $short_description,
                'min_requirement' => $min_requirement,
                'min_experience' => $min_experience,
                'min_education' => $min_education,
                'stcw_req' => $stcw_req,
                'min_english_experience' => $min_english_experience,
                'min_salary' => $min_salary,
                'max_salary' => $max_salary,
                'job_speedy_category_id' => $job_speedy_category_id,
                'sequence' => $sequence ,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'country' => $country,
                'cover_image' => $cover_image,
                'job_category_name' => $job_category_name
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;


    }

}
?>