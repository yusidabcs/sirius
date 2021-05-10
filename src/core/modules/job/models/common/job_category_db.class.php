<?php
namespace core\modules\job\models\common;
use Exception;
/**
 * Final job/db class.
 *
 * @final
 * 
 */
final class job_category_db extends \core\app\classes\module_base\module_db {


    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    public function getAll()
    {
        $out = array(
        );

        $sql = "SELECT
                        `job_speedy_category`.`job_speedy_category_id`,
                        `job_speedy_category`.`name`,
                        `job_speedy_category`.`parent_id`,
                        `job_speedy_category`.`short_description`,
                        `job_speedy_category`.`sequence`,
                        `job_speedy_category`.`created_on`,
                        `job_speedy_category`.`created_by`,
                        `job_speedy_category`.`modified_on`,
                        `job_speedy_category`.`modified_by`
                    FROM
                        `job_speedy_category`
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $parent_id,$short_description, $sequence, $created_on,$created_by,$modified_on, $modified_by);

        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_speedy_category_id' => $id,
                'name' => $name,
                'parent_id' => $parent_id,
                'short_description' => $short_description,
                'sequence' => $sequence,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;


    }


    public function insert($data){
        $data['sequence'] = $this->getLatestSequance($data['parent_id']);
        $sql = "INSERT INTO
					`job_speedy_category`
				SET   
                    `job_speedy_category`.`name` = ?,
                    `job_speedy_category`.`parent_id` = ?,
                    `job_speedy_category`.`short_description` = ?,
                    `job_speedy_category`.`sequence` = ? ,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}

				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sisi',$data['name'], $data['parent_id'],$data['short_description'], $data['sequence']);
        $stmt->execute();
        $out = $stmt->insert_id;
        $stmt->close();
        return $out;
    }

    public function update($id,$data){

        $old_data = $this->get($id);
        if($old_data['parent_id'] == $data['parent_id']){
            $sql = "UPDATE
					`job_speedy_category`
				SET 
                    `job_speedy_category`.`name` = ?,
                    `job_speedy_category`.`short_description` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
				WHERE
				    `job_speedy_category_id` = ?
				";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssi',$data['name'],$data['short_description'],$id);
            $stmt->execute();
            $out = $stmt->affected_rows;
            $stmt->close();
        }else{
            $new_sequance = $this->getLatestSequance($data['parent_id']);
            $sql = "UPDATE
					`job_speedy_category`
				SET 
                    `job_speedy_category`.`name` = ?,
                    `job_speedy_category`.`parent_id` = ?,
                    `job_speedy_category`.`sequence` = ?,
                    `job_speedy_category`.`short_description` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
				WHERE
				    `job_speedy_category_id` = ?
				";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('siisi',$data['name'],$data['parent_id'],$new_sequance,$data['short_description'],$id);
            $stmt->execute();
            $out = $stmt->affected_rows;
            $stmt->close();

            if($out){
                $sql = "UPDATE
					`job_speedy_category`
				SET 
                    `job_speedy_category`.`sequence` = `job_speedy_category`.`sequence` - 1,
					`modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
				WHERE
				    `job_speedy_category`.`sequence` > {$data['sequence']} and `job_speedy_category`.`parent_id` = {$old_data['parent_id']}
				";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $rs = $stmt->affected_rows;
                $stmt->close();
            }
        }



        return $out;
    }


    public function updateSequence($id,$index,$parent){

        $old_data = $this->get($id);

        $sql = "UPDATE
					`job_speedy_category`
				SET 
                    `job_speedy_category`.`sequence` = ?,
                    `job_speedy_category`.`parent_id` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
				WHERE
				    `job_speedy_category_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii',$index,$parent,$id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        if($out){

            if($old_data['parent_id'] == $parent){
                if($old_data['sequence'] < $index){
                    $sql = "UPDATE
                        `job_speedy_category`
                    SET 
                        `job_speedy_category`.`sequence` = `job_speedy_category`.`sequence` - 1,
                        `modified_on`= CURRENT_TIMESTAMP,
                        `modified_by`= {$this->user_id}
                    WHERE
                        `job_speedy_category`.`sequence` <= {$index} 
                        and `job_speedy_category`.`parent_id` = {$old_data['parent_id']}
                        and `job_speedy_category`.`job_speedy_category_id` != {$id}
                    ";
                }else{
                    $sql = "UPDATE
                        `job_speedy_category`
                    SET 
                        `job_speedy_category`.`sequence` = `job_speedy_category`.`sequence` + 1,
                        `modified_on`= CURRENT_TIMESTAMP,
                        `modified_by`= {$this->user_id}
                    WHERE
                        `job_speedy_category`.`sequence` >= {$index} 
                        and `job_speedy_category`.`parent_id` = {$old_data['parent_id']}
                        and `job_speedy_category`.`job_speedy_category_id` != {$id}
                    ";
                }

                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $rs = $stmt->affected_rows;
                $stmt->close();
            }else{

                //parent lama

                $sql = "UPDATE
                        `job_speedy_category`
                    SET 
                        `job_speedy_category`.`sequence` = `job_speedy_category`.`sequence` - 1,
                        `modified_on`= CURRENT_TIMESTAMP, 
                        `modified_by`= {$this->user_id}
                    WHERE
                        `job_speedy_category`.`sequence` > {$old_data['sequence']} and `job_speedy_category`.`parent_id` = {$old_data['parent_id']}
                    ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    $rs = $stmt->affected_rows;
                    $stmt->close();

                $sql = "UPDATE
					`job_speedy_category`
                    SET 
                        `job_speedy_category`.`sequence` = `job_speedy_category`.`sequence` + 1,
                        `modified_on`= CURRENT_TIMESTAMP,
                        `modified_by`= {$this->user_id}
                    WHERE
                        `job_speedy_category`.`sequence` >= {$index} and `job_speedy_category`.`parent_id` = {$parent}
                        and `job_speedy_category`.`job_speedy_category_id` != {$id}
                    ";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    $rs = $stmt->affected_rows;
                    $stmt->close();
                }


        }




        return $out;
    }

    public function get($id)
    {
        $out = array(
        );

        $sql = "SELECT
                        `job_speedy_category`.`job_speedy_category_id`,
                        `job_speedy_category`.`name`,
                        `job_speedy_category`.`short_description`,
                        `job_speedy_category`.`parent_id`,
                        `job_speedy_category`.`sequence`,
                        `job_speedy_category`.`created_on`,
                        `job_speedy_category`.`created_by`,
                        `job_speedy_category`.`modified_on`,
                        `job_speedy_category`.`modified_by`
					FROM
						`job_speedy_category`
					WHERE
						`job_speedy_category`.`job_speedy_category_id` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->bind_result($job_speedy_category_id, $name, $short_description, $parent_id, $sequence, $created_on, $created_by, $modified_on, $modified_by);

        $stmt->execute();
        $stmt->store_result();

        while($stmt->fetch())
        {
            $out = array(
                'job_speedy_category_id' => $job_speedy_category_id,
                'name' => $name,
                'short_description' => $short_description,
                'parent_id' => $parent_id,
                'sequence' => $sequence,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
            );
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function checkName($name)
    {
        $out = 0;

        $sql = "SELECT
                        (`job_speedy_category`.`job_speedy_category_id`)
					FROM
						`job_speedy_category`
					WHERE
						`job_speedy_category`.`name` = ?
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$name);
        $stmt->bind_result($id);

        $stmt->execute();
        $stmt->store_result();

        while($stmt->fetch())
        {
            $out = $id;
        }
        $stmt->free_result();
        $stmt->close();

        return $out;

    }

    public function delete($id){

        $old_data = $this->get($id);

        $out = [];

        $sql = "DELETE
					FROM `job_speedy_category`
                WHERE
					`job_speedy_category_id` = ? OR `parent_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$id,$id);
        $out = $stmt->execute();
        $stmt->close();

        if($out){
            $sql = "UPDATE
					`job_speedy_category`
				SET 
                    `job_speedy_category`.`sequence` = `job_speedy_category`.`sequence` - 1,
					`modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
				WHERE
				    `job_speedy_category`.`sequence` > {$old_data['sequence']} 
				    AND `job_speedy_category`.`parent_id` = {$old_data['parent_id']}
				";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $stmt->affected_rows;
            $stmt->close();
        }
        return $out;
    }

    public function getLatestSequance($parent_id){

        $sql = "SELECT
                        `job_speedy_category`.`sequence`
                    
					FROM
						`job_speedy_category`
					WHERE
						`job_speedy_category`.`parent_id` = '{$parent_id}'
                    ORDER BY
                        `job_speedy_category`.`sequence` DESC 
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

    public function getFiles($category_id){
        $out = array();
        $sql = "SELECT
                  `address_book_file`.`filename`,
                  `address_book_file`.`model_code`
					FROM
						`address_book_file`
					WHERE
						`address_book_file`.`address_book_id` = ?
					AND  `address_book_file`.`model_sub_code` = 'job_category'
				";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $category_id);
        $stmt->bind_result($filename, $model_code);

        $stmt->execute();
        $stmt->store_result();
        while ($stmt->fetch()) {
            $out[] = array(
                'filename' => $filename,
                'model_code' => $model_code,
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }



}
?>