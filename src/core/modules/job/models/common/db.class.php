<?php
namespace core\modules\job\models\common;
/**
 * Final job/db class.
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


    public function getCustomizedAllJobSpeedy($data,$demand='')
    {
        $out = array(
        );

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
                        `job_speedy`.`modified_on`,
                        `job_speedy`.`modified_by`,
                        `job_speedy`.`deleted_on`,
                        `job_speedy`.`deleted_by`
                    FROM
                        `job_speedy`
                    WHERE
                        `min_education` <= ?
                    AND 
                        `min_english_experience` <= ?
                    AND
                        `stcw_req` <= ?
                    AND  ( ";

        $employ_sql = '`min_experience` = 0';
        //check if have employment data
        if (!empty ($data['employment']))
        {
            //iterate each employment data and query based on experience and relevance category
            foreach ($data['employment'] as $key => $employment)
            {
                //add OR for each employment query
                if ($employ_sql != '')
                    $employ_sql .= ' OR ';

                $employ_sql .= "  (
                                        min_experience <= ".$employment['experience']."
                                    AND
                                        `job_speedy_category_id` IN 
                                        (
                                            SELECT 
                                                `job_speedy_category_id`
                                            FROM 
                                                `job_speedy_category`  
                                            WHERE 
                                                `job_speedy_category_id` IN  ($key) 
                                            OR 
                                                parent_id IN ($key)
                                        )
                                    ) ";
                
            }

        }/*else{
            //if doesn't have employment data, query job with all job_speedy_category_id which have min_experience = 0 
            $employ_sql .= ' `min_experience` = 0';
        }*/
        $employ_sql .= ' )';
        $employ_sql .= " AND `job_speedy`.`job_speedy_code` IN ({$demand})";
        
        $sql .= $employ_sql. " ORDER BY `sequence` ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii',
            $data['max_education'],
            $data['english_exp'],
            $data['stcw_count']    
        );
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req, $min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $sequence, $created_on, $created_by, $modified_on, $modified_by, $deleted_on, $deleted_by);

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
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'deleted_on' => $deleted_on,
                'deleted_by' => $deleted_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;


    }

    public function getAllJobSpeedy()
    {
        $out = array(
        );

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
                        `job_speedy`.`modified_on`,
                        `job_speedy`.`modified_by`,
                        `job_speedy`.`deleted_on`,
                        `job_speedy`.`deleted_by`
                    FROM
                        `job_speedy`
                    ORDER BY `sequence`
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req, $min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $sequence, $created_on, $created_by, $modified_on, $modified_by, $deleted_on, $deleted_by);

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
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'deleted_on' => $deleted_on,
                'deleted_by' => $deleted_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;


    }

    public function getAllJobSpeedyDatatable()
    {
        $request = $_POST;
        $table = 'job_speedy';

        $primaryKey = 'job_speedy.job_speedy_code';

        $columns = array(
            array( 'db' => 'job_speedy.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'job_speedy.short_description', 'dt' => 'short_description' ),
            array( 'db' => 'job_speedy.min_requirement', 'dt' => 'min_requirement' ),
            array( 'db' => 'job_speedy.min_experience', 'dt' => 'min_experience' ),
            array( 'db' => 'job_speedy.min_education', 'dt' => 'min_education' ),
            array( 'db' => 'job_speedy.stcw_req', 'dt' => 'stcw_req' ),
            array( 'db' => 'job_speedy.min_english_experience', 'dt' => 'min_english_experience' ),
            array( 'db' => 'job_speedy.min_salary', 'dt' => 'min_salary' ),
            array( 'db' => 'job_speedy.max_salary', 'dt' => 'max_salary' ),
            array( 'db' => 'job_speedy.job_speedy_category_id', 'dt' => 'job_speedy_category_id' ),
            array( 'db' => 'job_speedy_category.name', 'dt' => 'category_name' ),
            array( 'db' => 'job_speedy.created_on', 'dt' => 'created_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'job_speedy.modified_on', 'dt' => 'modified_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'job_speedy.sequence', 'dt' => 'sequence'),
            array( 'db' => 'job_speedy.country', 'dt' => 'country'),
            array( 'db' => 'job_speedy.cover_image', 'dt' => 'cover_image')
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = 'LEFT JOIN job_speedy_category on job_speedy_category.job_speedy_category_id = job_speedy.job_speedy_category_id';
        
        $where = $this->filter( $request, $columns, $bindings  );
        if (strpos(strtolower($where),'where') === false){
			$where .=' WHERE ';
		}else{
            $where .=' AND ';
            
        }
        $where .=' `job_speedy`.`deleted_on` = 0 ';

        if(isset($request['job_speedy_category_id']) && $request['job_speedy_category_id'] != ''){
            if (strpos(strtolower($where),'where') === false){
                $where .=' WHERE ';
            }else{
                $where .=' AND ';
            }
            $where .=' `job_speedy`.`job_speedy_category_id` = '.$request['job_speedy_category_id'];
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

    public function insertJobSpeedy($data){

        $sequence = $this->getLatestSequance($data['job_speedy_category_id']);

        $sql = "INSERT INTO
					`job_speedy`
				SET 
                    `job_speedy_code` = ?,
                    `job_title` = ?,
                    `short_description` = ?,
                    `min_requirement` = ?,
                    `min_experience` = ?,
                    `min_education` = ?,
                    `stcw_req` = ?,
                    `min_english_experience` = ?,
                    `min_salary` = ?,
                    `max_salary` = ?,
                    `job_speedy_category_id` = ?,
                    `sequence` = ?,
                    `country` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}

				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssiiiiiis',
            $data['job_speedy_code'],
            $data['job_title'],
            $data['short_description'],
            $data['min_requirement'], 
            $data['min_experience'],
            $data['min_education'], 
            $data['stcw_req'], 
            $data['min_english_experience'], 
            $data['min_salary'], 
            $data['max_salary'], 
            $data['job_speedy_category_id'],
            $sequence,
            $data['country']
        );
        $stmt->execute();
        // $out = $stmt->affected_rows;
        $out = $stmt->insert_id;
        $stmt->close();
        return $out;
    }

    public function getLatestSequance($job_speedy_category_id){

        $sql = "SELECT
                        `job_speedy`.`sequence`
                    
					FROM
						`job_speedy`
					WHERE
						`job_speedy`.`job_speedy_category_id` = {$job_speedy_category_id}
                    ORDER BY
                        `job_speedy`.`sequence` DESC 
                    LIMIT 1
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($sequence);
        $stmt->execute();
        if($stmt->fetch())
        {
            $sequence = $sequence + 1;
        }
        $stmt->close();
        if($sequence == null){
            $sequence = 1;
        }
        return $sequence;

    }

    public function updateJobSpeedy($data){

        $sql = "UPDATE
					`job_speedy`
				SET 
                    `job_speedy_code` = ?,
                    `job_title` = ?,
                    `short_description` = ?,
                    `min_requirement` = ?,
                    `min_experience` = ?,
                    `min_education` = ?,
                    `stcw_req` = ?,
                    `min_english_experience` = ?,
                    `min_salary` = ?,
                    `max_salary` = ?,
                    `job_speedy_category_id` = ?,
                    `country` = ?,
                    `cover_image` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
				WHERE
				    `job_speedy_code` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssisiiiiisss',
            $data['e_job_speedy_code'],
            $data['e_job_title'],
            $data['e_short_description'],
            $data['min_requirement'],
            $data['e_min_experience'],
            $data['e_min_education'],
            $data['e_stcw_req'],
            $data['e_min_english_experience'],
            $data['e_min_salary'],
            $data['e_max_salary'],
            $data['e_job_speedy_category_id'],
            $data['country'],
            $data['cover_image'],
            $data['old_job_speedy_code']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function updateCoverImageJobSpeedy($job_speedy_code,$cover_image){

        $sql = "UPDATE
					`job_speedy`
				SET 
                    `cover_image` = ?
				WHERE
				    `job_speedy_code` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $cover_image,
            $job_speedy_code
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function getJobSpeedy($job_speedy_code)
    {
        $out = array(
        );

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
                        `job_speedy`.`created_on`,
                        `job_speedy`.`created_by`,
                        `job_speedy`.`modified_on`,
                        `job_speedy`.`modified_by`,
                        `job_speedy`.`deleted_on`,
                        `job_speedy`.`deleted_by`
					FROM
						`job_speedy`
					WHERE
						`job_speedy`.`job_speedy_code` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$job_speedy_code);
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req,$min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $created_on, $created_by, $modified_on, $modified_by, $deleted_on, $deleted_by);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out = array(
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
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'deleted_on' => $deleted_on,
                'deleted_by' => $deleted_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;


    }

    public function getJobSpeedyInSameCategory($job_speedy_category_id)
    {
        $out = array(
        );

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
                        `job_speedy`.`created_on`,
                        `job_speedy`.`created_by`,
                        `job_speedy`.`modified_on`,
                        `job_speedy`.`modified_by`,
                        `job_speedy`.`deleted_on`,
                        `job_speedy`.`deleted_by`
					FROM
						`job_speedy`
					WHERE
						`job_speedy`.`job_speedy_category_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$job_speedy_category_id);
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req,$min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $created_on, $created_by, $modified_on, $modified_by, $deleted_on, $deleted_by);

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
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'deleted_on' => $deleted_on,
                'deleted_by' => $deleted_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;


    }

    public function deleteJobSpeedy($job_speedy_code){
        $out = [];

        $sql = "DELETE
					`job_speedy`
                WHERE
					`job_speedy_code` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$job_speedy_code);
        $out['result'] = $stmt->execute();
        $stmt->close();
        return $out;
    }

    public function softDeleteJobSpeedy($job_speedy_code){

        $sql = "UPDATE
					`job_speedy`
				SET 
					`deleted_on` = CURRENT_TIMESTAMP,
                    `deleted_by` = {$this->user_id}
				WHERE
				    `job_speedy_code` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $job_speedy_code);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    // Job demand master
    public function insertJobDemand($data)
    {

        $sql = "UPDATE 
                `job_demand_master`
            SET
                `reason` = 'expired by system because re-upload new demand from excel',
                `expiry_on` = subdate(CURDATE(), 1),
                `modified_on` = CURRENT_TIMESTAMP,
                `modified_by` = {$this->user_id}
            WHERE 
                `job_demand_master`.`job_master_id` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $data['job_master_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();


        $sql = "INSERT INTO
                `job_demand_master`
            SET 
                `job_master_id` = ?,
                `demand` = ?,
                `sex` = ?,
                `month` = ?,
                `year` = ?,
                `reason` = ?,
                `expiry_on` = ?,
                `created_on` = CURRENT_TIMESTAMP,
                `created_by` = {$this->user_id},
                `modified_on` = CURRENT_TIMESTAMP,
                `modified_by` = {$this->user_id}
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iisiiss',
                $data['job_master_id'],
                $data['demand'],
                $data['sex'], 
                $data['month'], 
                $data['year'], 
                $data['reason'],
                $data['expiry_on']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function insertJobMaster($data)
    {
        $sql = "INSERT INTO
                    `job_master`
                SET 
                    `principal_code` = ?,
                    `brand_code` = ?,
                    `cost_center` = ?,
                    `job_code` = ?,
                    `job_title` = ?,
                    `minimum_salary` = ?,
                    `medium_salary` = ?,
                    `maximum_salary` = ?,
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by` = {$this->user_id},
                    `modified_on` = CURRENT_TIMESTAMP,
                    `modified_by` = {$this->user_id}
                ON DUPLICATE KEY UPDATE
                    `principal_code` = ?,
                    `brand_code` = ?,
                    `cost_center` = ?,
                    `job_code` = ?,
                    `job_title` = ?,
                    `minimum_salary` = ?,
                    `medium_salary` = ?,
                    `maximum_salary` = ?,
                    `modified_on` = CURRENT_TIMESTAMP,
                    `modified_by` = {$this->user_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssssssssssss',
            $data['principal_code'],
            $data['brand_code'],
            $data['cost_center'],
            $data['job_code'], 
            $data['job_title'], 
            $data['minimum_salary'], 
            $data['mid_salary'],  
            $data['max_salary'],
            $data['principal_code'],
            $data['brand_code'],
            $data['cost_center'], 
            $data['job_code'],
            $data['job_title'], 
            $data['minimum_salary'], 
            $data['mid_salary'],  
            $data['max_salary']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getAllJobMaster()
    {
        $request = $_POST;
        $this->validateRequest($request, ['job_speedy','principal_code','brand_code']);
        $table = 'job_master';
        $primaryKey = 'job_master.job_master_id';

        $columns = array(
            array( 'db' => 'job_master.job_master_id', 'dt' => 'job_master_id' ),
            array( 'db' => 'job_master.principal_code', 'dt' => 'principal_code' ),
            array( 'db' => 'job_master.brand_code', 'dt' => 'brand_code' ),
            array( 'db' => 'job_master.cost_center', 'dt' => 'cost_center' ),
            array( 'db' => 'job_master.job_code', 'dt' => 'job_code' ),
            array( 'db' => 'job_master.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_speedy.job_title','as' => 'job_speedy_title', 'dt' => 'job_speedy_title' ),
            array( 'db' => 'job_master.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'job_master.minimum_salary', 'dt' => 'minimum_salary' ),
            array( 'db' => 'job_master.medium_salary', 'dt' => 'mid_salary' ),
            array( 'db' => 'job_master.maximum_salary', 'dt' => 'max_salary' ),
            array( 'db' => 'job_master.created_on', 'dt' => 'created_on' ),
            array( 'db' => 'job_master.modified_on', 'dt' => 'modified_on' ),
            array( 'db' => 'job_demand_master.demand', 'dt' => 'demand' ),
        );

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' left JOIN job_speedy on job_speedy.job_speedy_code = job_master.job_speedy_code ';
        $join .= ' left JOIN job_demand_master on job_master.job_master_id = job_demand_master.job_master_id AND  job_demand_master.expiry_on >= CURDATE()';

        $where = $this->filter( $request, $columns,$bindings  );

        if (isset($request['job_speedy']) && !empty($request['job_speedy'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `job_master`.`job_speedy_code` = '{$request['job_speedy']}' ";
        }

        if (isset($request['principal_code']) && !empty($request['principal_code'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `job_master`.`principal_code` = '{$request['principal_code']}' ";
        }

        if (isset($request['brand_code']) && !empty($request['brand_code'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `job_master`.`brand_code` = '{$request['brand_code']}' ";
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

    public function updateJobMasterfield($job_code,$field,$value)
    {
	    //make absolutly certain the person is logged in
	    if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] < 0)
	    {
		    $msg = 'Security error: you can not update job master information.';
		    throw new \RuntimeException($msg);
	    }

	    $out = false;

	    $qry = "UPDATE `job_master` SET `{$field}` = '{$value}', `modified_on` = CURRENT_TIMESTAMP WHERE `job_code` = '{$job_code}'";
	    $this->db->query($qry);
	    if( $this->db->affected_rows() >= 0 )
		{
			$out = true;
		}

	    return $out;
    }

    public function updateJobMaster($data)
    {
	    //make absolutly certain the person is logged in
	    if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] < 0)
	    {
		    $msg = 'Security error: you can not update job master information.';
		    throw new \RuntimeException($msg);
	    }

	    $out = false;

	    $qry = "UPDATE `job_master`
                            SET 
                                `job_title` = ?,
                                `principal_code` = ?,
                                `brand_code` = ?,
                                `cost_center` = ?,
                                `job_speedy_code` = ?,
                                `minimum_salary` = ?,
                                `medium_salary` = ?,
                                `maximum_salary` = ?,
                                `modified_on` = CURRENT_TIMESTAMP 
                WHERE `job_code` = ?
                ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('sssssiiis', $data['job_title'],$data['principal_code'],$data['brand_code'],$data['cost_center'],$data['job_speedy_code'],$data['minimum_salary'],$data['mid_salary'],$data['max_salary'],$data['job_code']);
        $stmt->execute();
        
	    if( $stmt->affected_rows >= 0 )
		{
			$out = true;
        }
        
        $stmt->close();

	    return $out;
    }

    public function deleteJobMaster($id){

        $sql = "DELETE FROM
					`job_master`
			    WHERE
			      id = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getJobDemandByCode($job_code)
    {
        $out = [];
        $sql = "SELECT
                    `job_demand_master`.`job_code`,
                    `job_demand_master`.`demand`,
                    `job_demand_master`.`sex`,
                    `job_demand_master`.`month`,
                    `job_demand_master`.`year`
            
                FROM
                    `job_demand_master`
                WHERE
                    `job_demand_master`.`job_code` = ?
                ORDER BY 
                     `job_demand_master`.`month` desc,`job_demand_master`.`year` desc
                     
                LIMIT 5
            ";
            //,`job_master_demand`.`status` desc
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$job_code);
        $stmt->bind_result(
            $job_code,
            $demand,
            $sex,
            $month,
            $year
        );

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_code' => $job_code,
                'demand' => $demand,
                'sex' => $sex,
                'month' => $month,
                'year' => $year,
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function checkJobCodeSpeedyExist($code){
        $out = [];

        $sql = "SELECT
                `job_speedy_code`
            FROM 
                `job_speedy`
            WHERE
            `job_speedy`.`job_speedy_code` = ?";
    
        $stmt = $this->db->prepare($sql);
    
        $stmt->bind_param('s',$code);

        
        $stmt->bind_result($id);
        $stmt->execute();

        $stmt->store_result();
        if($stmt->num_rows > 0) {
            $out['duplicate'] = true;
        }else{
            $out['duplicate'] = false;
        }
        $stmt->close();
        return $out;
    }

    public function checkJobTitleSpeedyExist($title){
        $out = [];

        $sql = "SELECT
                `job_speedy_code`
            FROM 
                `job_speedy`
            WHERE
            `job_speedy`.`job_title` = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('s',$title);


        $stmt->bind_result($id);
        $stmt->execute();

        $stmt->store_result();
        if($stmt->num_rows > 0) {
            $out = true;
        }else{
            $out = false;
        }
        $stmt->close();
        return $out;
    }

    public function getJobMasterByJobSpeedy($code)
    {
        $out = array();

        $sql = "SELECT
                        `job_master`.`job_master_id`,
                        `job_master`.`principal_code`,
                        `job_master`.`brand_code`,
                        `job_master`.`job_code`,
                        `job_master`.`cost_center`,
                        `job_master`.`job_speedy_code`,
                        `job_master`.`job_title`,
                        `job_master`.`minimum_salary`,
                        `job_master`.`medium_salary`,
                        `job_master`.`maximum_salary`,
                        `job_master`.`created_on`,
                        `job_master`.`created_by`,
                        `job_master`.`modified_on`,
                        `job_master`.`modified_by`
                    FROM
                        `job_master`
                    WHERE
                        `job_master`.`job_speedy_code` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $code);
        $stmt->bind_result(
            $job_master_id,
            $principal_code,
            $brand_code,
            $job_code,
            $cost_center,
            $job_speedy_code,
            $job_title,
            $minimum_salary,
            $medium_salary,
            $maximum_salary,
            $created_on,
            $created_by,
            $modified_on,
            $modified_by);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_master_id' => $job_master_id,
                'principal_code' =>$principal_code,
                'brand_code' =>$brand_code,
                'job_code' =>$job_code,
                'cost_center' =>$cost_center,
                'job_speedy_code' =>$job_speedy_code,
                'job_title' =>$job_title,
                'minimum_salary' =>$minimum_salary,
                'medium_salary' =>$medium_salary,
                'maximum_salary' =>$maximum_salary,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function getJobMasterByJobSpeedyDatatable($code)
    {
        $request = $_POST;
        $table = 'job_master';
        $primaryKey = 'job_master.job_master_id';

        $columns = array(
            array( 'db' => 'job_master.job_master_id', 'dt' => 'job_master_id' ),
            array( 'db' => 'job_master.brand_code', 'dt' => 'brand_code' ),
            array( 'db' => 'job_master.cost_center', 'dt' => 'cost_center' ),
            array( 'db' => 'job_master.job_code', 'dt' => 'job_code' ),
            array( 'db' => 'job_master.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_master.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'job_master.minimum_salary', 'dt' => 'minimum_salary' ),
            array( 'db' => 'job_master.medium_salary', 'dt' => 'mid_salary' ),
            array( 'db' => 'job_master.maximum_salary', 'dt' => 'max_salary' ),
            array( 'db' => 'job_master.created_on', 'dt' => 'created_on' ),
            array( 'db' => 'job_master.modified_on', 'dt' => 'modified_on' ),
            array( 'db' => 'job_demand_master.demand', 'dt' => 'demand', 'formatter' => function($d,$r){ return $d == null ?  0 :  $d;}),
            array( 'db' => 'job_demand_master.job_demand_master_id', 'dt' => 'job_demand_master_id' ),
            array( 'db' => 'COUNT(`job_demand_allocation`.`job_demand_master_id`)', 'as' => 'allocation', 'dt' => 'allocation' ),
        );

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN job_demand_master ON job_demand_master.job_master_id = job_master.job_master_id AND job_demand_master.expiry_on >= CURDATE() ';
        $join .= ' LEFT JOIN
        				`job_demand_allocation`
        		ON
        				`job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id`  ';
        $group = ' GROUP BY
                            `job_demand_master`.`job_demand_master_id`';

        $where = $this->filter( $request, $columns,$bindings  );
        //add our conditional parameter
        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .=" `job_master`.`job_speedy_code` = '{$code}'";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
             FROM `$table`
             $join
             $where
             $group
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

        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );

    }

    public function getJobMasterByCode($job_code,$principal_code,$brand_code)
    {
        $out = array();

        $sql = "SELECT
                        `job_master`.`job_master_id`,
                        `job_master`.`principal_code`,
                        `job_master`.`brand_code`,
                        `job_master`.`job_code`,
                        `job_master`.`cost_center`,
                        `job_master`.`job_speedy_code`,
                        `job_master`.`job_title`,
                        `job_master`.`minimum_salary`,
                        `job_master`.`medium_salary`,
                        `job_master`.`maximum_salary`,
                        `job_master`.`created_on`,
                        `job_master`.`created_by`,
                        `job_master`.`modified_on`,
                        `job_master`.`modified_by`
                    FROM
                        `job_master`
                    WHERE
                        `job_master`.`principal_code` = ? AND
                        `job_master`.`brand_code` = ? AND
                        `job_master`.`job_code` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $principal_code,$brand_code,$job_code);
        $stmt->bind_result(
            $job_master_id,
            $principal_code,
            $brand_code,
            $job_code,
            $cost_center,
            $job_speedy_code,
            $job_title,
            $minimum_salary,
            $medium_salary,
            $maximum_salary,
            $created_on,
            $created_by,
            $modified_on,
            $modified_by);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out = array(
                'job_master_id' => $job_master_id,
                'principal_code' =>$principal_code,
                'brand_code' =>$brand_code,
                'job_code' =>$job_code,
                'cost_center' =>$cost_center,
                'job_speedy_code' =>$job_speedy_code,
                'job_title' =>$job_title,
                'minimum_salary' =>$minimum_salary,
                'medium_salary' =>$medium_salary,
                'maximum_salary' =>$maximum_salary,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function searchJobMaster($query)
    {
        $out = array();

        $sql = "SELECT
                        `job_master`.`job_master_id`,
                        `job_master`.`principal_code`,
                        `job_master`.`brand_code`,
                        `job_master`.`job_code`,
                        `job_master`.`cost_center`,
                        `job_master`.`job_speedy_code`,
                        `job_master`.`job_title`
                    FROM
                        `job_master`
                    WHERE
                        `job_master`.`job_title` like '%".$query."%'";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $job_master_id,
            $principal_code,
            $brand_code,
            $job_code,
            $cost_center,
            $job_speedy_code,
            $job_title);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_master_id' => $job_master_id,
                'principal_code' =>$principal_code,
                'brand_code' =>$brand_code,
                'job_code' =>$job_code,
                'cost_center' =>$cost_center,
                'job_speedy_code' =>$job_speedy_code,
                'job_title' =>$job_title
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function getJobMasterArray()
    {
        $out = array();

        $sql = "SELECT
                        `job_master`.`job_master_id`,
                        `job_master`.`principal_code`,
                        `job_master`.`brand_code`,
                        `job_master`.`job_code`,
                        `job_master`.`cost_center`,
                        `job_master`.`job_speedy_code`,
                        `job_master`.`job_title`
                    FROM
                        `job_master`";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $job_master_id,
            $principal_code,
            $brand_code,
            $job_code,
            $cost_center,
            $job_speedy_code,
            $job_title);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_master_id' => $job_master_id,
                'principal_code' =>$principal_code,
                'brand_code' =>$brand_code,
                'job_code' =>$job_code,
                'cost_center' =>$cost_center,
                'job_speedy_code' =>$job_speedy_code,
                'job_title' =>$job_title
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function addingDemand($data){
        $sql = "INSERT INTO
					`job_adding_demand`
				SET 
                    `job_code` = ?,
                    `demand` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id}

				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $data['job_code'],
            $data['demand']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function calculateJobCurrentDemand($job_code)
    {
        //calculate demand
        $sql = 
        "INSERT INTO
            job_current_demand
                (
                job_code,
                demand,
                created_by,
                created_on,
                modified_by,
                modified_on
                )
            SELECT
                job_master.job_speedy_code,
                COALESCE((sum(job_master_demand.demand)+adding_demand.demand-allocation_demand.demand),0),
                {$this->user_id},
                CURRENT_TIMESTAMP,
                {$this->user_id},
                CURRENT_TIMESTAMP
            FROM
                job_speedy
            JOIN 
                job_master ON job_speedy.job_speedy_code = job_master.job_speedy_code
            LEFT JOIN
                job_master_demand ON job_master.job_code = job_master_demand.job_code
            LEFT JOIN 
                (
                    (
                    SELECT
                        sum(job_adding_demand.demand) as demand,
                        job_master.* 
                    FROM
                        job_adding_demand
                    LEFT JOIN
                        job_master ON job_adding_demand.job_code = job_master.job_code
                    GROUP BY 
                        job_master.job_speedy_code
                    )
                ) adding_demand on adding_demand.job_speedy_code = job_master.job_speedy_code
                
            LEFT JOIN
                (
                    (
                        SELECT
                            sum(job_demand_allocation.demand) as demand,
                            job_master.* 
                        FROM
                            job_demand_allocation
                        LEFT JOIN
                            job_master ON job_demand_allocation.job_code = job_master.job_code
                        GROUP BY 
                            job_master.job_speedy_code
                    )
                ) allocation_demand ON allocation_demand.job_speedy_code = job_master.job_speedy_code	
            WHERE 
                job_master.job_speedy_code = ?
            GROUP BY
                job_master.job_speedy_code
        ON DUPLICATE KEY UPDATE 
            modified_by = {$this->user_id},
            modified_on = CURRENT_TIMESTAMP,
            demand = (SELECT
                COALESCE((sum(job_master_demand.job)+adding_demand.demand-allocation_demand.demand),0)
            FROM
                job_speedy
            JOIN 
                job_master ON job_speedy.job_speedy_code = job_master.job_speedy_code
            LEFT JOIN
                job_master_demand ON job_master.job_code = job_master_demand.job_code
            LEFT JOIN 
                (
                    (
                    SELECT
                        sum(job_adding_demand.demand) as demand,
                        job_master.* 
                    FROM
                        job_adding_demand
                    LEFT JOIN
                        job_master ON job_adding_demand.job_code = job_master.job_code
                    GROUP BY 
                        job_master.job_speedy_code
                    )
                ) adding_demand on adding_demand.job_speedy_code = job_master.job_speedy_code
                
            LEFT JOIN
                (
                    (
                        SELECT
                            sum(job_demand_allocation.demand) as demand,
                            job_master.* 
                        FROM
                            job_demand_allocation
                        LEFT JOIN
                            job_master ON job_demand_allocation.job_code = job_master.job_code
                        GROUP BY 
                            job_master.job_speedy_code
                    )
                ) allocation_demand ON allocation_demand.job_speedy_code = job_master.job_speedy_code	
            WHERE 
                job_master.job_speedy_code = ?
            GROUP BY
                job_master.job_speedy_code)
            ";
            
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',$job_code,$job_code);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function JobSpeedyDemand(){
        $out = [];
        $sql = "SELECT 
                    SUM(job_demand_master.demand) as demand,
                    adding_demand.demand as add_demand,
                    allocation_demand.demand as allocated_demand,
                    job_speedy.job_title, 
                    job_master.job_speedy_code 
                FROM 
                    job_speedy
                JOIN 
                    job_master on job_speedy.job_speedy_code = job_master.job_speedy_code
                LEFT JOIN 
                    job_demand_master on job_master.job_master_id = job_demand_master.job_master_id
                LEFT JOIN 
                    (
                        (
                        SELECT 
                            SUM(job_demand_modified.demand) as demand,
                            job_master.* 
                        FROM 
                            job_demand_modified
                        LEFT JOIN 
                            job_master on job_demand_modified.job_code = job_master.job_code
                        GROUP BY 
                            job_master.job_speedy_code
                        )
                    ) adding_demand on adding_demand.job_speedy_code = job_master.job_speedy_code
                LEFT JOIN 
                    (
                        (
                        SELECT 
                            SUM(job_demand_allocation.job_master_id) as demand, 
                            job_master.* 
                        FROM 
                            job_demand_allocation
                        LEFT JOIN 
                            job_master on job_demand_allocation.job_master_id = job_master.job_master_id
                        GROUP BY 
                            job_master.job_speedy_code
                        )
                    ) allocation_demand on allocation_demand.job_speedy_code = job_master.job_speedy_code        
                GROUP BY 
                    job_master.job_speedy_code";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($demand, $add_demand, $allocation_demand, $job_title, $job_speedy_code);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch()){
            $out[] = [
                'demand' => $demand,
                'add_demand' => $add_demand,
                'allocation_demand' => $allocation_demand,
                'job_title' => $job_title,
                'job_speedy_code' => $job_speedy_code,
            ];
        }
        return $out;
    }

    public function getJobSpeedyWithDemand($limit = false){

        $out = array(
        );

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
                        `job_speedy`.`created_on`,
                        `job_speedy`.`created_by`,
                        `job_speedy`.`modified_on`,
                        `job_speedy`.`modified_by`,
                        `job_speedy`.`deleted_on`,
                        `job_speedy`.`deleted_by`
                    FROM
                        `job_speedy`
                ";
        if($limit != false){
            $sql .= "limit ".$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req ,$min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $created_on, $created_by, $modified_on, $modified_by, $deleted_on, $deleted_by);

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
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'deleted_on' => $deleted_on,
                'deleted_by' => $deleted_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function getCustomizedJobSpeedyWithDemand($data, $limit = false, $demand){

        $out = array();

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
                        `job_speedy`.`created_on`,
                        `job_speedy`.`created_by`,
                        `job_speedy`.`modified_on`,
                        `job_speedy`.`modified_by`,
                        `job_speedy`.`deleted_on`,
                        `job_speedy`.`deleted_by`
                    FROM
                        `job_speedy`
                    WHERE
                        `min_education` <= ?
                    AND 
                        `min_english_experience` <= ?
                    AND
                        `stcw_req` <= ?
                    AND (
                ";

            $employ_sql = '`min_experience` = 0';
            //check if have employment data
            if (!empty ($data['employment']))
            {
                //iterate each employment data and query based on experience and relevance category
                foreach ($data['employment'] as $key => $employment)
                {
                    //add OR for each employment query
                    if ($employ_sql != '')
                        $employ_sql .= ' OR ';

                    $employ_sql .= "  (
                                            min_experience <= ".$employment['experience']."
                                        AND
                                            `job_speedy_category_id` IN 
                                            (
                                                SELECT 
                                                    `job_speedy_category_id`
                                                FROM 
                                                    `job_speedy_category`  
                                                WHERE 
                                                    `job_speedy_category_id` IN  ($key) 
                                                OR 
                                                    parent_id IN ($key)
                                            )
                                        ) ";
                    
                }

            }/*else{
                //if doesn't have employment data, query job with all job_speedy_category_id which have min_experience = 0 
                $employ_sql .= ' `min_experience` = 0';
            }*/
            $employ_sql .= ' )';
            $employ_sql .= " AND `job_speedy`.`job_speedy_code` IN ({$demand})";       
            $sql .= $employ_sql;

        if($limit != false){
            $sql .= "limit ".$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii',
            //$data['work_exp'],
            $data['max_education'],
            $data['english_exp'],
            $data['stcw_count']    
        );
        $stmt->bind_result($job_speedy_code, $job_title, $short_description, $min_requirement, $min_experience, $min_education, $stcw_req ,$min_english_experience,$min_salary, $max_salary, $job_speedy_category_id, $created_on, $created_by, $modified_on, $modified_by, $deleted_on, $deleted_by);

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
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'deleted_on' => $deleted_on,
                'deleted_by' => $deleted_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function getJobSpeedyWithDemandDatatable()
    {
        $request = $_POST;
        $table = 'job_speedy';
        $primaryKey = 'job_speedy.job_speedy_code';

        $columns = array(
            array( 'db' => 'job_speedy.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'job_speedy.short_description', 'dt' => 'short_description' ),
            array( 'db' => 'job_speedy.min_requirement', 'dt' => 'min_requirement' ),
            array( 'db' => 'job_speedy.min_experience', 'dt' => 'min_experience' ),
            array( 'db' => 'job_speedy.min_education', 'dt' => 'min_education' ),
            array( 'db' => 'job_speedy.stcw_req', 'dt' => 'stcw_req' ),
            array( 'db' => 'job_speedy.min_english_experience', 'dt' => 'min_english_experience' ),
            array( 'db' => 'job_speedy.min_salary', 'dt' => 'min_salary' ),
            array( 'db' => 'job_speedy.max_salary', 'dt' => 'max_salary' ),
            array( 'db' => 'job_speedy.job_speedy_category_id', 'dt' => 'job_speedy_category_id' ),
            array( 'db' => 'job_speedy_category.name', 'dt' => 'category_name' ),
            array( 'db' => 'job_speedy.created_on', 'dt' => 'created_on' ),
            array( 'db' => 'job_speedy.modified_on', 'dt' => 'modified_on' ),
            array( 'db' => 'job_speedy.created_by', 'dt' => 'created_by' ),
            array( 'db' => 'job_speedy.modified_by', 'dt' => 'modified_by' ),
            array( 'db' => 'job_speedy.deleted_on', 'dt' => 'deleted_on' ),
            array( 'db' => 'job_speedy.deleted_by', 'dt' => 'deleted_by' )
        );

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' left join job_speedy_category on job_speedy_category.id = job_speedy.job_speedy_category_id ';

        $where = $this->filter( $request, $columns,$bindings  );

        if(isset($_POST['job_speedy_category_id']) && $_POST['job_speedy_category_id'] != ''){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `job_speedy`.`job_speedy_category_id` = '{$_POST['job_speedy_category_id']}' ";
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

    public function getJobWithDemand() {
        $out = [];
        $sql = "SELECT
             `job_speedy`.`job_speedy_code`, `job_speedy`.`job_title`, SUM(`job_demand_master`.`demand`) as demand,SUM(`allocation`.`total_allocation`) as total_allocation
              FROM `job_speedy`
              LEFT JOIN
                        `job_master`
                ON
                        `job_master`.`job_speedy_code` = `job_speedy`.`job_speedy_code`
                LEFT JOIN
                        `job_demand_master`
                ON
                        `job_demand_master`.`job_master_id` = `job_master`. `job_master_id` AND (`job_demand_master`.`expiry_on` >= NOW() OR `job_demand_master`.`expiry_on` is null) 
                LEFT JOIN
                        (
                        SELECT `job_demand_allocation`.`job_demand_master_id`,COUNT(`job_demand_allocation`.`job_demand_master_id`) as `total_allocation` FROM `job_demand_allocation` GROUP BY `job_demand_allocation`.`job_demand_master_id`) as `allocation`
                ON
                        `job_demand_master`.`job_demand_master_id` = `allocation`.`job_demand_master_id` 
             
             GROUP BY
                        `job_speedy`.`job_speedy_code`
             ORDER BY `job_speedy`.`job_speedy_code` ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_result($job_speedy_code, $job_title, $demand, $total_allocation);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            if(($demand-$total_allocation)>0) {
                $out[]=$job_speedy_code;
            }
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
        }


    /*
     * get list job application array by address book
     */

    public function getJobApplications($address_book_id){
        $out = [];
        $qry = "SELECT 
                    `job_application`.`job_application_id`,
                    `job_application`.`address_book_id`,
                    `job_application`.`job_speedy_code`,
                    `job_application`.`employment_id`,
                    `job_application`.`personal_reference_id`,
                    `job_application`.`work_reference_id`,
                    `job_application`.`status`,
                    `job_application`.`created_on`, 
                    `job_application`.`created_by`,
                    `job_application`.`modified_on`, 
                    `job_application`.`modified_by`,
                    `job_application`.`relevance`,
                    `job_speedy`.`job_title`
                  
                FROM
                    `job_application`
                JOIN `job_speedy` on `job_speedy`.`job_speedy_code` = `job_application`.`job_speedy_code`
                WHERE
                    `job_application`.`address_book_id` = ?
                ORDER BY  `job_application`.`created_on` DESC
        ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($job_application_id, $address_book_id, $job_speedy_code, $employment_id, $personal_reference_id,$work_reference_id,$status,$created_on, $created_by, $modified_on, $modified_by, $relevance, $job_title);
        $stmt->execute();

        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_application_id' => $job_application_id,
                'address_book_id' => $address_book_id,
                'job_speedy_code' => $job_speedy_code,
                'employment_id' => $employment_id,
                'personal_reference_id' => $personal_reference_id,
                'work_reference_id' => $work_reference_id,
                'status' => $status,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'relevance' => $relevance,
                'job_title' => $job_title
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    /*
     * get one job application array by id
     */
    public function getJobApplication($job_application_id){
        $out = [];
        $qry = "SELECT 
                    `job_application`.`job_application_id`,
                    `job_application`.`address_book_id`,
                    `job_application`.`job_speedy_code`,
                    `job_application`.`employment_id`,
                    `job_application`.`personal_reference_id`,
                    `job_application`.`work_reference_id`,
                    `job_application`.`status`,
                    `job_application`.`created_on`, 
                    `job_application`.`created_by`,
                    `job_application`.`modified_on`, 
                    `job_application`.`modified_by`,
                    `job_application`.`relevance`,
                    `job_speedy`.`job_title`
                  
                FROM
                    `job_application`
                JOIN `job_speedy` on `job_speedy`.`job_speedy_code` = `job_application`.`job_speedy_code`
                WHERE
                    `job_application`.`job_application_id` = ?
                ORDER BY  `job_application`.`created_on` DESC
        ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('i',$job_application_id);
        $stmt->bind_result($job_application_id, $address_book_id, $job_speedy_code, $employment_id, $personal_reference_id,$work_reference_id,$status,$created_on, $created_by, $modified_on, $modified_by, $relevance, $job_title);
        $stmt->execute();

        $stmt->store_result();
        while($stmt->fetch())
        {
            $out = array(
                'job_application_id' => $job_application_id,
                'address_book_id' => $address_book_id,
                'job_speedy_code' => $job_speedy_code,
                'employment_id' => $employment_id,
                'personal_reference_id' => $personal_reference_id,
                'work_reference_id' => $work_reference_id,
                'status' => $status,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'relevance' => $relevance,
                'job_title' => $job_title
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function getJobApplicationCTrack($job_application_id)
    {
        $out = [];
        $qry = "SELECT 
                    `job_application_id`,
                    `send_ctrac_on`,
                    `send_ctrac_by`,
                    `ctrac_accessed_on`,
                    `ctrac_accessed_by`,
                    `ctrac_completed_on`,
                    `ctrac_completed_by`
                FROM
                    `job_application_ctrac`
                WHERE
                    `job_application_ctrac`.`job_application_id` = ?
        ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('i',$job_application_id);
        $stmt->bind_result($job_application_id, $send_ctrac_on, $send_ctrac_by, $ctrac_accessed_on, $ctrac_accessed_by, $ctrac_completed_on, $ctrac_completed_by);
        $stmt->execute();

        $stmt->store_result();
        while($stmt->fetch())
        {
            $out = array(
                'job_application_id' => $job_application_id,
                'send_ctrac_on' => strtotime($send_ctrac_on) == strtotime('0000-00-00 00:00:00') ? null : $send_ctrac_on,
                'send_ctrac_by' => $send_ctrac_by ,
                'ctrac_accessed_on' => strtotime($ctrac_accessed_on) == strtotime('0000-00-00 00:00:00') ? null : $ctrac_accessed_on,
                'ctrac_accessed_by' => $ctrac_accessed_by,
                'ctrac_completed_on' => strtotime($ctrac_completed_on) == strtotime('0000-00-00 00:00:00') ? null : $ctrac_completed_on,
                'ctrac_completed_by' => $ctrac_completed_by
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function updateCtrac($data){
        $sql = "INSERT INTO
					`job_application_ctrac`
				SET
					`job_application_id` = ?,
					`send_ctrac_on` = ?,
					`send_ctrac_by` = {$this->user_id},
					`ctrac_accessed_on`= ?,
					`ctrac_accessed_by`= {$this->user_id}, 
					`ctrac_completed_on`= ?,
					`ctrac_completed_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`send_ctrac_on` = ?,
					`send_ctrac_by` = {$this->user_id},
					`ctrac_accessed_on`= ?,
					`ctrac_accessed_by`= {$this->user_id}, 
					`ctrac_completed_on`= ?,
					`ctrac_completed_by`= {$this->user_id}
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('issssss',$data['job_application_id'],$data['send_ctrac_on'],$data['ctrac_accessed_on'],$data['ctrac_completed_on'],$data['send_ctrac_on'],$data['ctrac_accessed_on'],$data['ctrac_completed_on']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getTotalJobApplication($address_book_id){
        $out = [];
        $qry = "SELECT 
                    count(`job_application`. `job_application_id`)
                FROM
                    `job_application`
                WHERE
                    `job_application`.`address_book_id` = ? AND  `job_application`.`status` != 'canceled'
        ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($count);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = $count;
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }
    
    public function getJobPremiumServiceByABId($address_book_id){
        $out = [];
        $sql = 
            "SELECT
                `job_premium_service`.`verified`,
                `job_premium_service`.`type`,
                `job_premium_service`.`contract_type`,
                `job_premium_service`.`full_amount`,
                `job_premium_service`.`amount`,
                `job_premium_service`.`status`,
                `job_premium_service`.`verified_on`,
                `job_premium_service`.`confirmed_on`,
                `job_premium_service`.`sending_on`,
                `job_premium_service`.`filename`,
                `address_book`.`entity_family_name`,
                `address_book`.`number_given_name`,
                `personal_idcard`.`idcard_safe`,
                `personal_idcard`.`from_date`,
                `personal_idcard`.`authority`,
                `personal_passport`.`passport_id`,
                `personal_passport`.`from_date`,
                `personal_passport`.`place_issued`,
                `address_book_address`.`line_1`,
                `address_book_address`.`line_2`,
                `address_book_address`.`suburb`,
                `address_book_address`.`state`,
                `address_book_address`.`postcode`,
                `address_book_address`.`country`,
                `address_book_pots`.`number`,
                `address_book_pots`.`country`
            FROM
                `job_premium_service` 
            LEFT JOIN
                `address_book` on `address_book`.`address_book_id` = `job_premium_service`.`address_book_id`                
            LEFT JOIN
                (
                    SELECT 
                        `passport_id`,
                        `address_book_id`,
                        `from_date`,
                        `place_issued`
                    FROM
                        `personal_passport`
                ) `personal_passport` on `personal_passport`.`address_book_id` = `job_premium_service`.`address_book_id`                
            LEFT JOIN
                (
                    SELECT 
                        `idcard_safe`,
                        `from_date`,
                        `authority`,
                        `address_book_id`
                    FROM
                        `personal_idcard`
                )
                 `personal_idcard` on `personal_idcard`.`address_book_id` = `job_premium_service`.`address_book_id`            
            LEFT JOIN
                (
                    SELECT 
                        `address_book_id`,
                        `line_1`,
                        `line_2`,
                        `suburb`,
                        `state`,
                        `postcode`,
                        `country`
                    FROM
                        `address_book_address`
                    WHERE
                        `type` = 'main'
                )
                 `address_book_address` on `address_book_address`.`address_book_id` = `job_premium_service`.`address_book_id`                
            LEFT JOIN
                (
                    SELECT 
                        `address_book_id`,
                        `number`,
                        `country`
                    FROM
                        `address_book_pots`
                    ORDER BY
                        `created_on` DESC
                )
                 `address_book_pots` on `address_book_pots`.`address_book_id` = `job_premium_service`.`address_book_id` 

            WHERE
                `job_premium_service`.`address_book_id` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($verified, $type, $contract_type, $full_amount, $amount, $status, $verified_on,$confirmed_on,$sending_on,$filename, $entity_family_name, $number_given_name, $idcard_safe, $idcard_doi, $idcard_poi, $passport_id, $passport_doi, $passport_poi, $line_1, $line_2, $suburb, $state, $postcode, $country, $number, $number_country);
        $stmt->execute();
        while ($stmt->fetch())
        {
            $core_db = new \core\app\classes\core_db\core_db;
			$countryCodes = $core_db->getAllCountryCodes();
			$subCountries =$core_db->getSubCountryCodes($country);
            $countryDialCodes = $core_db->getAllDialCodes();
            
            $address = '';
			if(!empty($line_1)) $address .= $line_1;
			if(!empty($line_2)) 
			{
				$address .= !empty($line_1) ? ', '.$line_2 : $line_2;
			}
			if(!empty($address) && !empty($country))
			{
				$address .= ', '.((!empty($postcode))? $postcode: ''). ' '.$countryCodes[$country];
			}
            $out = array(
                'verified' => $verified,
                'type' => $type,
                'contract_type' => $contract_type,
                'full_amount' => $full_amount,
                'amount' => $amount,
                'status' => $status,
                'verified_on' => $verified_on,
                'confirmed_on' => $confirmed_on,
                'sending_on' => $sending_on,
                'filename' => $filename,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
                'idcard_safe' => $idcard_safe,
                'idcard_doi' => $idcard_doi,
                'idcard_poi' => $idcard_poi,
                'address' => $address,
                'passport_id' => $passport_id,
                'passport_doi' => $passport_doi,
                'passport_poi' => $passport_poi,
                'phone_number' => (isset($number_country)? '+'.$countryDialCodes[$number_country]['dialCode'] : '').$number
            );
        }
        $stmt->close();

        return $out;
    }

    public function insertJobPremiumService($data)
    {
        $sql = "INSERT INTO
                    `job_premium_service`
                SET 
                    `address_book_id` = ?,
                    `hash` = ?,
                    `type` = ?,
                    `status` = ?,
                    `full_amount` = ?,
                    `amount` = ?,
                    `verified` = 'unknown',
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `sending_on`= CURRENT_TIMESTAMP
                ON DUPLICATE KEY UPDATE    
                    `hash` = ?,
                    `type` = ?,
                    `status` = ?,
                    `full_amount` = ?,
                    `amount` = ?,
                    `verified` = 'unknown',
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `sending_on`= CURRENT_TIMESTAMP
            
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isssiisssii',
            $data['address_book_id'],
            $data['hash'],
            $data['type'],
            $data['status'],
            ($data['full_amount']),
            ($data['amount']),
            $data['hash'],
            $data['type'],
            $data['status'],
            ($data['full_amount']),
            ($data['amount'])
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        echo $stmt->error;
        $stmt->close();
        return $out;
    }

    public function updateJobPremiumService($data)
    {
        $sql = "UPDATE 
                    `job_premium_service`
                SET 
                    `hash` = ?,
                    `type` = ?,
                    `status` = ?,
                    `full_amount` = ?,
                    `amount` = ?,
                    `verified` = 'unknown',
                    `sending_on`= CURRENT_TIMESTAMP
                WHERE
                    `address_book_id` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssiii',
            $data['hash'],
            $data['type'],
            $data['status'],
            ($data['full_amount']),
            ($data['amount']),
            ($data['address_book_id'])
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    
    public function getJobPremiumServiceByHash($hash)
    {
        $out = [];
        $sql = 
            "SELECT
                `job_premium_service`.`address_book_id`,
                `job_premium_service`.`type`,
                `job_premium_service`.`status`,
                `job_premium_service`.`verified`,
                `job_premium_service`.`created_on`,
                `job_premium_service`.`created_by`
            FROM
                `job_premium_service` 
            WHERE
                `hash` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$hash);
        $stmt->bind_result($adress_book_id, $type, $status, $verified, $created_on, $created_by);
        $stmt->execute();
        if ($stmt->fetch())
        {
            $out = array(
               
                'address_book_id' => $adress_book_id,
                'type' => $type,
                'status' => $status,
                'verified' => $verified,
                'created_on' => $created_on,
                'created_by' => $created_by
            );
        }
        $stmt->close();

        return $out;
    }

    /*
     * Update premium status to confirmed
     */
    public function confirmPremiumService($data)
    {
        $sql = "UPDATE
                `job_premium_service`
            SET 
                `status` = 'confirmed',
                `confirmed_on`= CURRENT_TIMESTAMP
            WHERE
                `address_book_id` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $data['address_book_id']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    
    public function updateJobPremiumServiceStatus($data)
    {
        $sql = "UPDATE
                `job_premium_service`
            SET 
                `status` = ?,
                `filename` = ?,
                `verified_on`= CURRENT_TIMESTAMP, 
                `verified_by`= {$this->user_id}
            WHERE
                `address_book_id` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['status'],
            $data['filename'],
            $data['address_book_id']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateJobPremiumServiceFile($data)
    {
        $sql = "UPDATE
                `job_premium_service`
            SET 
                `filename` = ?
            WHERE
                `address_book_id` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',
            $data['filename'],
            $data['address_book_id']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function userConfirmJobPremiumService($data)
    {
        $sql = "UPDATE
                `job_premium_service`
            SET 
                `verified` = ?,
                `verified_on`= CURRENT_TIMESTAMP, 
                `verified_by`= {$this->user_id}
            WHERE
                `hash` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $data['verified'],
            $data['hash']
        );
        $stmt->execute();
        echo $stmt->error;
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateJobSequence($data)
    {
        $sql = "UPDATE
                `job_speedy`
            SET 
                `sequence` = ?
            WHERE
                `job_speedy_code` = ?
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $data['sequence'],
            $data['job_speedy_code']
        );

        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function insertJobApplication($data)
    {
        $sql = "INSERT INTO
					`job_application`
				SET 
                    `address_book_id` = ?,
                    `job_speedy_code` = ?,
                    `employment_id` = ?,
                    `personal_reference_id` = ?,
                    `work_reference_id` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id},
                    `relevance` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isiiis',
            $data['address_book_id'],
            $data['job_speedy_code'],
            $data['employment_id'],
            $data['personal_reference_id'],
            $data['work_reference_id'],
            $data['relevance']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function lastJobApplication()
    {
        $sql = "SELECT job_application.*,
        address_book_connection.*,
        address_book_per.*,
        address_book.*,
        `partner`.`main_email` as partner_main_email,
        `partner`.`entity_family_name` as partner_name
        FROM `job_application`
        LEFT JOIN `address_book_per` on `job_application`.`address_book_id` = `address_book_per`.`address_book_id` 
        LEFT JOIN `address_book` on `job_application`.`address_book_id` = `address_book`.`address_book_id`
        LEFT JOIN `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id`
        LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id`
        WHERE `job_application`.`status` = 'applied'
        ORDER BY job_application_id DESC limit 1;
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetch_assoc();
    }

    public function sendNotificationEmailToLP($application_data, $to_name, $to_email, $from_name, $from_email) {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;

        //need a reset code
		$random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
        $resetCode = md5($random_string);
        
        $template =  $mailing_common->renderEmailTemplate('new_applicant', [
            'number_given_name' => $application_data['number_given_name'],
            'middle_names' => $application_data['middle_names'],
            'entity_family_name' => $application_data['entity_family_name'],
            'main_email' => $application_data['main_email'],
            'dob' => $application_data['dob']
        ]);
		
		//insert the site
		$security_db_ns = NS_MODULES.'\security\models\common\security_db';
		$security_db = new $security_db_ns;
		$security_db->setResetCode($resetCode,$to_email);
		
        //subject
        $subject = $template['subject'];
	
        //message
        $message = $template['html'];
		
		//cc
		$cc = '';
		
		//bcc
		if(SYSADMIN_BCC_NEW_USERS)
		{
			$bcc = SYSADMIN_EMAIL;
		} else {
			$bcc = '';
		}
		
		//html
		$html = true;
		$fullhtml = false;
		
		//unsubscribe link
		$unsubscribelink = false;
	
		//generic for the sendmail
		$generic = \core\app\classes\generic\generic::getInstance();
		$generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
		
		return;
    }

    public function getAllJobApplicationsDatatable($entity_id = false){
        $request = $_POST;
        $table = 'job_application';

        $primaryKey = 'job_application.job_application_id';

        $columns = array(
            array( 'db' => 'job_application.job_application_id', 'dt' => 'job_application_id' ),
            array( 'db' => 'job_application.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_application.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'job_application.status', 'dt' => 'status' ),
            array( 'db' => 'job_application.created_on','as'=>'applied_on', 'dt' => 'applied_on' ),
            array( 'db' => 'address_book_address.country', 'dt' => 'country' ),
            array( 'db' => 'address_book_per.title', 'dt' => 'title' ),
            array( 'db' => 'address_book_per.middle_names', 'dt' => 'middle_names' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'address_book.created_by', 'dt' => 'created_by' ),

            array( 'db' => 'partner.entity_family_name', 'as' => 'partner_name',  'dt' => 'partner_name' ),
            array( 'db' => 'partner.address_book_id', 'as' => 'partner_id',  'dt' => 'partner_id' ),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'job_premium_service.type','as' => 'premium_type', 'dt' => 'premium_type' ),
            array( 'db' => 'job_premium_service.verified', 'dt' => 'premium_verified' ),
            array( 'db' => 'job_premium_service.contract_type', 'dt' => 'premium_contract_type' ),
            array( 'db' => 'job_premium_service.pstatus', 'dt' => 'premium_status' ),
            array( 'db' => 'job_premium_service.filename', 'dt' => 'premium_file' ),
            array( 'db' => 'job_premium_service.sending_on', 'dt' => 'sending_on' ,'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'job_premium_service.confirmed_on', 'dt' => 'confirmed_on' ),
            array( 'db' => 'job_premium_service.verified_on', 'dt' => 'verified_on' ,'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),

            array( 'db' => 'job_speedy.created_on', 'dt' => 'created_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'job_speedy.modified_on', 'dt' => 'modified_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'interview_result.schedule_id','as' => 'interview_result_id', 'dt' => 'interview_result_id'),
            array( 'db' => 'interview_schedule.type','as' => 'type', 'dt' => 'type'),
            array( 'db' => 'interview_online.schedule_on','as' => 'schedule_on', 'dt' => 'schedule_on'),
            array( 'db' => 'interview_location.start_on','as' => 'start_on', 'dt' => 'start_on')
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN job_speedy on job_speedy.job_speedy_code = job_application.job_speedy_code';
        $join .= ' LEFT JOIN address_book_connection on address_book_connection.address_book_id = job_application.address_book_id';
        $join .= ' JOIN `address_book_per` ON `job_application`.`address_book_id` = `address_book_per`.`address_book_id` ';
        $join .= ' JOIN `address_book` ON `job_application`.`address_book_id` = `address_book`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';
        $join .= 'LEFT JOIN `interview_schedule` as `interview_schedule` ON `interview_schedule`.`job_application_id` = `job_application`.`job_application_id` ';
        $join .= 'LEFT JOIN `interview_physical` as `interview_physical` ON `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id` ';
        $join .= 'LEFT JOIN `interview_location` as `interview_location` ON `interview_location`.`interview_location_id` = `interview_physical`.`interview_location_id` ';
        $join .= 'LEFT JOIN `interview_online` as `interview_online` ON `interview_online`.`schedule_id` = `interview_schedule`.`schedule_id` ';

        $join .= 'LEFT JOIN `job_prescreen` as `job_prescreen` ON `job_prescreen`.`job_application_id` = `job_application`.`job_application_id` ';

        $join .= 'LEFT JOIN `interview_result` as `interview_result` ON `interview_result`.`schedule_id` = `interview_schedule`.`schedule_id` ';
        $join .=
        'LEFT JOIN 
            (
                SELECT 
                    `job_premium_service`.`address_book_id`,
                    `job_premium_service`.`type`,
                    `job_premium_service`.`contract_type`,
                    `job_premium_service`.`status` as `pstatus`,
                    `job_premium_service`.`verified`,
                    `job_premium_service`.`filename`,
                    `job_premium_service`.`sending_on`,
                    `job_premium_service`.`verified_on`,
                    `job_premium_service`.`confirmed_on`,
                    CONCAT(`address_book`.`entity_family_name`," ",`address_book`.`number_given_name`) as `verified_by`
                FROM 
                    `job_premium_service`
                LEFT JOIN    
                (
                    SELECT 
                        `entity_family_name`,
                        `number_given_name`,
                        `user_id`
                    FROM 
                        `address_book`
                    JOIN
                        `user` on `user`.email = `address_book`.`main_email`
                    WHERE `address_book`.type = "per"
                ) 
                `address_book` on `address_book`.user_id = `job_premium_service`.verified_by
            ) 
            `job_premium_service` on `job_premium_service`.`address_book_id` = `job_application`.`address_book_id` ';
        

        $where = $this->filter( $request, $columns, $bindings  );

        if(isset($request['schedule']) ){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " (select count(*) from interview_schedule where interview_schedule.job_application_id = `job_application`.`job_application_id`) = ".$request['schedule'];
        }

        if($entity_id != false){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$entity_id}' ";
        }

        if(isset($_POST['partner_id']) && $_POST['partner_id'] != ''){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$_POST['partner_id']}' ";
        }

        if(isset($_POST['country']) && $_POST['country'] != ''){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_address`.`country` = '{$_POST['country']}' ";
        }


        if (isset($_POST['status']) && $_POST['status'] != '' )
        {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            if (isset($_POST['status']) && $_POST['status'] != '') {
                if (strpos($_POST['status'], ',') > 0) {
                    # code...
                    
                    $where .= " `job_application`.`status` IN('";
                    $where .= implode("','", explode(',', $_POST['status']));
                    $where .= "') ";
                } else {
                    $where .= " `job_application`.`status`='{$_POST['status']}'  ";
                }
            }
        }

        if (isset($_POST['register_method']) && $_POST['register_method'] != '') {

            
            if ($_POST['register_method'] == -1) {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` > 0 ";
            } else {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` = 0 ";
            }

        }

        $order.= (strpos(strtolower($order),'order by') === false)? ' ORDER BY ' :  ' , ';
        $order .= " `job_prescreen`.`created_on` ASC";

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
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function getAllJobApplicationsCount($status, $entity_id = false){
        $out = 0;
        $qry = "SELECT 
                    count(`job_application`. `job_application_id`)
                FROM
                    `job_application`
                    
                LEFT JOIN address_book_connection on address_book_connection.address_book_id = job_application.address_book_id
                
                WHERE
                  `job_application`.`status` = '{$status}'
        ";

        if($entity_id != false){
            $qry .= " AND address_book_connection.connection_id = {$entity_id} AND address_book_connection.connection_type = 'lp'";
        }
        $stmt = $this->db->prepare($qry);
        $stmt->bind_result($count);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = $count;
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function CalculateMinMaxSalary($job_speedy_code)
    {
	    $out = false;

        $qry = 
        'UPDATE  
            job_speedy
        INNER JOIN
            (
                SELECT 
                    job_speedy_code,
                    MIN(minimum_salary)*100 min_salary, 
                    MAX(maximum_salary)*100 max_salary
                FROM 
                    job_master 
                WHERE 
                    job_speedy_code = ?
            ) min_max 
        ON  
            min_max.job_speedy_code = job_speedy.job_speedy_code
        SET     
            job_speedy.min_salary = min_max.min_salary,
            job_speedy.max_salary = min_max.max_salary';

        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('s',$job_speedy_code);
        $stmt->execute();
	    if( $stmt->affected_rows == 1 )
		{
			$out = true;
		}
        $stmt->close();        
	    return $out;
    }

    public function acceptJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'accepted',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function cancelJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'canceled',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function acceptInterviewJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'interview',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function rejectJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'rejected',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * change job application job speedy
     */
    public function changeJobApplicationJobSpeedy($job_application_id,$job_speedy_code){
        $sql = "UPDATE
					`job_application`
				SET 
                    `job_speedy_code` = ?,
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si',$job_speedy_code,$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * update status of job application to selected one
     */
    public function updateJobApplicationStatus($job_application_id, $status){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = ?,
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $job_application_id);
        $stmt->execute();
        echo $stmt->error;
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * update status of job application to pending
     */
    public function pendingJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'pending',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * update status of job application to hire
     */
    public function hireJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'hired',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
    * update status of job application to hire
    */
    public function allocatedJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'allocated',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * update status of job application to not_hire
     */
    public function notHireJobApplication($job_application_id){
        $sql = "UPDATE
					`job_application`
				SET 
                    `status` = 'not_hired',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                WHERE
                  `job_application_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_application_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * Add new allocation to job_demand_allocation
     */
    public function addDemandAllocation($data){
        $sql = "INSERT INTO
					`job_demand_allocation`
				SET 
                    `job_demand_master_id` = ?,
                    `address_book_id` = ?,
                    `allocated_on`= CURRENT_TIMESTAMP, 
                    `allocated_by`= CURRENT_TIMESTAMP, 
                    `status`= 'allocated'

				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',
            $data['job_demand_master_id'],
            $data['address_book_id']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        echo $stmt->error;
        $stmt->close();
        return $out;
    }

    public function initJobApplicationTrackers()
    {

    }

    public function getAllJobCategory() {
        $out = [];
        $sql = 
            "SELECT
                `job_speedy_category`.`job_speedy_category_id`,`job_speedy_category`.`name`
            FROM
                `job_speedy_category`
            ORDER BY
                `job_speedy_category`.`name` ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_speedy_category_id, $name);
        $stmt->execute();
        while ($stmt->fetch())
        {
            $out[] = array(
                'job_speedy_category_id' => $job_speedy_category_id,
                'name' => $name
            );
        }
        $stmt->close();

        return $out;
    }

    public function getActiveJobApplication($address_book_id)
    {
        $out = '';
        $qry = "SELECT 
                    `job_application`.`job_application_id`,
                    `job_application`.`address_book_id`,
                    `job_application`.`job_speedy_code`,
                    `job_application`.`employment_id`,
                    `job_application`.`personal_reference_id`,
                    `job_application`.`work_reference_id`,
                    `job_application`.`status`,
                    `job_application`.`created_on`, 
                    `job_application`.`created_by`,
                    `job_application`.`modified_on`, 
                    `job_application`.`modified_by`,
                    `job_application`.`relevance`,
                    `job_speedy`.`job_title`
                  
                FROM
                    `job_application`
                JOIN `job_speedy` on `job_speedy`.`job_speedy_code` = `job_application`.`job_speedy_code`
                WHERE
                    `job_application`.`address_book_id` = ?
                AND
                    `job_application`.`status` NOT IN('canceled','not_hired')
                ORDER BY  `job_application`.`created_on` DESC LIMIT 1
        ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_param('i',$address_book_id);
        $stmt->bind_result($job_application_id, $address_book_id, $job_speedy_code, $employment_id, $personal_reference_id,$work_reference_id,$status,$created_on, $created_by, $modified_on, $modified_by, $relevance, $job_title);
        $stmt->execute();

        $stmt->store_result();
        while($stmt->fetch())
        {
            $out = array(
                'job_application_id' => $job_application_id,
                'address_book_id' => $address_book_id,
                'job_speedy_code' => $job_speedy_code,
                'employment_id' => $employment_id,
                'personal_reference_id' => $personal_reference_id,
                'work_reference_id' => $work_reference_id,
                'status' => $status,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'relevance' => $relevance,
                'job_title' => $job_title
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }

    public function getListJobMaster($principal,$brand){
        $out = [];
        $where = "";
        if($brand!='') {
            $where .= " AND `job_master`.`brand_code` = '".$brand."'";
        }
        $qry = "SELECT 
                    `job_master`.`job_master_id`,
                    `job_master`.`principal_code`,
                    `job_master`.`brand_code`,
                    `job_master`.`job_code`,
                    `job_master`.`job_title`
                FROM
                    `job_master`
                WHERE
                `job_master`.`principal_code`='".$principal."'
                AND 
                (`job_master`.`job_speedy_code`='' OR `job_master`.`job_speedy_code` IS NULL)
                ".$where."
                ORDER BY  `job_master`.`job_code` ASC
        ";
        $stmt = $this->db->prepare($qry);
        $stmt->bind_result($job_master_id, $principal_code, $brand_code, $job_code, $job_title);
        $stmt->execute();

        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_master_id' => $job_master_id,
                'principal_code' => $principal_code,
                'brand_code' => $brand_code,
                'job_code' => $job_code,
                'job_title' => $job_title
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;
    }
}
?>