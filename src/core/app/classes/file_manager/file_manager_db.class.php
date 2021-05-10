<?php
namespace core\app\classes\file_manager;

/**
 * Final file_manager_db class.
 *
 * All database interaction for file_manager is here
 *
 * @final
 * @package 	file manger
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class file_manager_db {
	
	public function __construct($db_location)
	{
		//just check you have a database! It should always be on the local database
		$db_ns = NS_APP_DRIVERS.'\\db\\db_mysql';
		$this->db = $db_ns::getInstance($db_location);
		return;
	}
    	
	public function setNewFileManagerId()
	{
		$generic_ns = NS_APP_CLASSES.'\\generic\\generic';
		$generic = $generic_ns::getInstance();
		
		$qry = "SELECT `file_manager_id` FROM `file_manager` WHERE `file_manager_id` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$file_manager_id);

		//check if the file_manager_id is exist in table
        //continue until maximum 30x trial to get unique file manager id
		$i = 0;
		$exists = true;
		do
		{
			$file_manager_id = $generic->generateRandomString(8);
			//checking random name
			$stmt->execute();

			//no exist? quit the loop constinue to insert
			if( $stmt->affected_rows == 0 )
			{
				$exists = false;
			}
			$i++;
		} while ( $exists && $i < 30);

		$stmt->close();
		
		//reserve the name just in case
		$sql = "INSERT INTO `file_manager` SET `file_manager_id` = '{$file_manager_id}'";
		$this->db->query($sql);

		//check if no insert in database, something must be wrong
		if($this->db->affected_rows() != 1)
		{
			$msg = 'System error - Filename did not get set in the database!';
			throw new \RuntimeException($msg);
			exit();
		}
		return $file_manager_id;
    }
    /*
     * Check if the file_manager_id is exist in table
     */
    public function fileManagerIdExists($file_manager_id)
	{
		$out = false;
		
		$qry = "SELECT `file_manager_id` FROM `file_manager` WHERE `file_manager_id` = ?";

		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$file_manager_id);
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
	
	//!getters
    /*
     * get a file manager obj
     * by file_manager_id
     * return file_obj object
     *
     */
    public function getFileManagerObj($file_manager_id)
    {
	    $sql = "SELECT 
					`link_id`,
					`model`,
					`model_id`,
					`title`,
					`sdesc`,
					`security_level_id`,
					`group_id`,
					`dir`,
					`mime_type`,
					`size`,
					`original_name`,
					`original_ext`,
					`sequence`,
					`image`,
					`status`
  			 FROM `file_manager` 
  			 WHERE `file_manager_id` = ?";
  			 
	    $stmt = $this->db->prepare($sql);
	    $stmt->bind_param('s',$file_manager_id);
	    $stmt->bind_result(
		    				$link_id,
	    					$model,
						  	$model_id,
							$title,
							$sdesc,
							$security_level_id,
							$group_id,
							$dir,
							$mime_type,
							$size,
							$original_name,
							$original_ext,
							$sequence,
							$image,
							$status
						);
	    $stmt->execute();
	    if(!$stmt->fetch())//there should be only one
	    {
		    $stmt->close();
		    $msg = "Tried to get {$file_manager_id} from file manager and could not find it!";
	    	throw new \RuntimeException($msg); 
	    }
	    $stmt->close();

	    $file_obj = new file_obj();
	    
	    $file_obj->setFileManagerID($file_manager_id);
		$file_obj->setLinkId($link_id);
		$file_obj->setModel($model);
		$file_obj->setModelId($model_id);
		$file_obj->setTitle($title);
		$file_obj->setSDesc($sdesc);
		$file_obj->setSecurityLevelId($security_level_id);
		$file_obj->setGroupId($group_id);
		$file_obj->setDir($dir);
		$file_obj->setMimeType($mime_type); //sets image!
		$file_obj->setSize($size);
		$file_obj->setOriginalName($original_name);
		$file_obj->setOriginalExt($original_ext);
		$file_obj->setSequence($sequence);
		$file_obj->setStatus($status);
		
		return $file_obj;
    }

    /**
     * get files info
     * @param $link_id
     * @param $model
     * @param $model_id
     * @return array of file_manager
     */
    public function getFilesInfoArray($link_id, $model, $model_id)
    {
	    $out = array();

	    $sql = "SELECT
	    			`file_manager_id`,
	    			`link_id`,
	    			`model`,
	    			`model_id`,
					`title`,
					`sdesc`,
					`security_level_id`,
					`group_id`,
					`dir`,
					`mime_type`,
					`size`,
					`original_name`,
					`original_ext`,
					`sequence`,
					`image`,
					`status`
  			 FROM `file_manager` ";

	    //where	
	    if( empty($model) && empty($model_id) )
	    {
		    $sql .= "WHERE `link_id` = ? ";
	    } else if(empty($model_id)) {
		    $sql .= "WHERE `link_id` = ? AND `model` = ? ";
	    } else {
		    $sql .= "WHERE `link_id` = ? AND `model` = ? AND `model_id` = ? ";
	    }
	    
	    $sql .= "ORDER BY `sequence`,`title`";
	    
	    $stmt = $this->db->prepare($sql);
	    
	    //bind param
	    if( empty($model) && empty($model_id) )
	    {
		    $stmt->bind_param('s',$link_id);
	    } else if(empty($model_id)) {
		    $stmt->bind_param('ss',$link_id,$model);
	    } else {
		    $stmt->bind_param('sss',$link_id,$model,$model_id);
	    }
	    
	    $stmt->bind_result(
		    				$file_manager_id,
		    				$link_id,
		    				$model,
		    				$model_id,
							$title,
							$sdesc,
							$security_level_id,
							$group_id,
							$dir,
							$mime_type,
							$size,
							$original_name,
							$original_ext,
							$sequence,
							$image,
							$status
						);

	    $stmt->execute();
	    while( $stmt->fetch() )
	    {
		    $out[$file_manager_id] = array(
			    'link_id' => $link_id,
    			'model' => $model,
    			'model_id' => $model_id,
		    	'title' => $title,
				'sdesc' => $sdesc,
				'security_level_id' => $security_level_id,
				'group_id' => $group_id,
				'dir' => $dir,
				'mime_type' => $mime_type,
				'size' => $size,
				'original_name' => $original_name,
				'original_ext' => $original_ext,
				'sequence' => $sequence,
				'image' => $image,
				'status' => $status
		    );
	    }
	    $stmt->close();
		
		return $out;
	    
    }

    /**
     * get single file info
     *
     * @param $file_manager_id
     * @return array
     */
    public function getFileInfoArray($file_manager_id)
    {
	    $out = array();

	    $sql = "SELECT
                    `file_manager_id`,
	    			`link_id`,
	    			`model`,
	    			`model_id`,
					`title`,
					`sdesc`,
					`security_level_id`,
					`group_id`,
					`dir`,
					`mime_type`,
					`size`,
					`original_name`,
					`original_ext`,
					`sequence`,
					`image`,
					`status`
			FROM 
					`file_manager`
			WHERE
					`file_manager_id` = ?
  			 ";
  			 
	    $stmt = $this->db->prepare($sql);
	    $stmt->bind_param('s',$file_manager_id);
	    
	    $stmt->bind_result(
	                        $file_manager_id,
		    				$link_id,
		    				$model,
		    				$model_id,
							$title,
							$sdesc,
							$security_level_id,
							$group_id,
							$dir,
							$mime_type,
							$size,
							$original_name,
							$original_ext,
							$sequence,
							$image,
							$status
						);

	    $stmt->execute();
	    if( $stmt->fetch() )
	    {
		    $out = array(
                'file_manager_id' => $file_manager_id,
			    'link_id' => $link_id,
    			'model' => $model,
    			'model_id' => $model_id,
		    	'title' => $title,
				'sdesc' => $sdesc,
				'security_level_id' => $security_level_id,
				'group_id' => $group_id,
				'dir' => $dir,
				'mime_type' => $mime_type,
				'size' => $size,
				'original_name' => $original_name,
				'original_ext' => $original_ext,
				'sequence' => $sequence,
				'image' => $image,
				'status' => $status
		    );
	    }
	    $stmt->close();	
		return $out;
    }

    
    //!update functions
	
	public function updateFileManagerAllDetails($file_obj)
	{
		$out = false;
		
		$link_id = $file_obj->getLinkId();
		$model = $file_obj->getModel();
		$model_id = $file_obj->getModelId();
		$title = $file_obj->getTitle();
		$sdesc = $file_obj->getSDesc();
		$security_level_id = $file_obj->getSecurityLevelId();
		$group_id = $file_obj->getGroupId();
		$dir = $file_obj->getDir();
		$mime_type = $file_obj->getMimeType();
		$size = $file_obj->getSize();
		$original_name = $file_obj->getOriginalName();
		$original_ext = strtolower($file_obj->getOriginalExt());
		$sequence = $file_obj->getSequence();
		$image = $file_obj->getImage();
		$status = $file_obj->getStatus();
		
		$file_manager_id = $file_obj->getFileManagerId();
			
			
		$sql = "UPDATE `file_manager` 
					SET
						`link_id` = ?,  
						`model` = ?,
						`model_id` = ?,
						`title` = ?,
						`sdesc` = ?,
						`security_level_id` = ?,
						`group_id` = ?,
						`dir` = ?,
						`mime_type` = ?,
						`size` = ?,
						`original_name` = ?,
						`original_ext` = ?,
						`sequence` = ?,
						`image` = ?,
						`status` = ?
					WHERE
						`file_manager_id`= ?
				";
		
		$stmt = $this->db->prepare($sql);
		
		$stmt->bind_param("sssssssssissiiis",
			$link_id,
			$model,
			$model_id,
			$title,
			$sdesc,
			$security_level_id,
			$group_id,
			$dir,
			$mime_type,
			$size,
			$original_name,
			$original_ext,
			$sequence,
			$image,
			$status,
			$file_manager_id
		);
		
		$stmt->execute();
		if( $stmt->affected_rows == 1 )
		{
			$out = true;
		}
		$stmt->close();
		
		return $out;	
	}
	
	public function updateFileManagerFileDetails($file_obj)
	{
		$dir = $file_obj->getDir();
		$mime_type = $file_obj->getMimeType();
		$size = $file_obj->getSize();
		$original_name = $file_obj->getOriginalName();
		$original_ext = strtolower($file_obj->getOriginalExt());
		$image = $file_obj->getImage();
		
		$file_manager_id = $file_obj->getFileManagerId();
			
			
		$sql = "UPDATE `file_manager` 
					SET
						`dir` = ?,
						`mime_type` = ?,
						`size` = ?,
						`original_name` = ?,
						`original_ext` = ?,
						`image` = ?
					WHERE
						`file_manager_id`= ?
					LIMIT 1
				";
		
		$stmt = $this->db->prepare($sql);
		
		$stmt->bind_param("ssissis",
			$dir,
			$mime_type,
			$size,
			$original_name,
			$original_ext,
			$image,
			$file_manager_id
		);
		
		$stmt->execute();
		if(  $stmt->affected_rows == 0 )
		{
			$msg = "Unable to update file manager {$$this->_file_manager_id}!";
			$e = new \RuntimeException($msg);
			$htmlOutput = new \iow\app\classes\html\htmlmsg($e,DEBUG);
			header("HTTP/1.0 500 Internal Error");
			echo $htmlOutput->getHtmlOutput();
			exit();
		}
		$stmt->close();
		
		return;	
	}

    /**
     *
     * Update single field in file manager table to new value
     *
     * @param $file_manager_id
     * @param $field
     * @param $value
     * @return bool
     */
    public function updateFileManagerItemDetails($file_manager_id, $field, $value)
    {
	    $out = false;
	    
	    $sql = "UPDATE `file_manager` SET `{$field}` = '{$value}' WHERE `file_manager_id` = '{$file_manager_id}'";
	    $this->db->query($sql);
	    
	    if( $this->db->affected_rows() == 1 )
		{
			$out = true;
		}
	    return $out;
	}

    /**
     * Update link_id to new link_id
     *
     * @param $link_id_new
     * @param $link_id_orig
     * @return string
     */
    public function updateLinkIds($link_id_new, $link_id_orig)
	{
		$out = '';
		
		$sql = "UPDATE `file_manager` 
					SET`link_id` = ? 
					WHERE `link_id` = ?";
		
		$stmt = $this->db->prepare($sql);
		
		//bind param
		$stmt->bind_param('ss',$link_id_new,$link_id_orig);
		$stmt->execute();
		
		$out = $stmt->affected_rows;
		
		$stmt->close();
		
		return $out;
	}

    /**
     * Delete file manager by it file_manager_id
     * update the sequence after delete an item
     *
     * @param $file_manager_id
     * @return bool
     */
    public function deleteFileManagerId($file_manager_id)
    {
        //select the file obj
        $obj = $this->getFileManagerObj($file_manager_id);

	    $out = false;

	    $sql = "DELETE FROM `file_manager` WHERE `file_manager_id` = '{$file_manager_id}' LIMIT 1";
	    $this->db->query($sql);

	    if( $this->db->affected_rows() == 1 )
		{
			$out = true;

			//update sequence order
            $sql = "UPDATE `file_manager` SET `sequence` = (`sequence`  - 1)  WHERE `sequence` > 0 and `link_id` = '{$obj->getLinkId()}' 
                and `model` = '{$obj->getModel()}'
                and `model_id` = '{$obj->getModelId()}'
            ";
            $this->db->query($sql);
		}


	    return $out;
	}

    /**
     * get latest sequence of file
     */
    public function getLatestSequence($link_id,
                                      $model,
                                      $model_id){
        $sequence = 0;

        $qry = "SELECT `sequence` FROM `file_manager` 
                  WHERE `link_id` = ? and 
                  `model` = ? and
                  `model_id` = ? order by `sequence` desc limit 1";

        $stmt = $this->db->prepare($qry);
        $stmt->bind_param("sss",$link_id, $model, $model_id);
        $stmt->bind_result(
            $sequence
        );
        $stmt->execute();
        if($stmt->fetch()){
            $sequence = $sequence + 1;
        };
        $stmt->close();

        return $sequence;
    }

    public function updateSequence($file_manager_id, $new_sequence, $old_sequence, $link_id, $model, $model_id){

        if($new_sequence > $old_sequence){
            $sql = "UPDATE
					`file_manager`
				SET 
					`sequence` = `sequence` - 1 
				WHERE
					`link_id` = ? and
					`model` = ? and
					`model_id` = ? and
					`sequence` > ? and
					`sequence` <= ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sssii',$link_id,$model,$model_id,$old_sequence,$new_sequence);
            $stmt->execute();
        }if($new_sequence < $old_sequence){
            $sql = "UPDATE
					`file_manager`
				SET 
					`sequence` = `sequence` + 1 
				WHERE
					`link_id` = ? and
					`model` = ? and
					`model_id` = ? and
					`sequence` >= ? and
					`sequence` < ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sssii',$link_id,$model,$model_id,$new_sequence,$old_sequence);
            $stmt->execute();
        }
        //update pages itself
        $sql = "UPDATE
					`file_manager`
				SET 
					`sequence` = ?
				WHERE
					`file_manager_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is',$new_sequence,$file_manager_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
	
	//!autocommit function
	
	public function commitOff()
	{
		$this->db->commitOff();
		return;
	}
	
	public function commit()
	{
		$this->db->commit();
		return;
	}
	
	public function rollback()
	{
		$this->db->rollback();
		return;
	}
	
	public function commitOn()
	{
		$this->db->commitOn();
		return;
	}
	
}
?>