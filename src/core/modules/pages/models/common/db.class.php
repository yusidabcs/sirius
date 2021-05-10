<?php
namespace core\modules\pages\models\common;

/**
 * Final pages_db class.
 * 
 * @final
 */
final class db extends \core\app\classes\module_base\module_db {
	
	private $_page_core; //singleton page core
	
	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		
		$pages_core_ns = NS_APP_CLASSES.'\\page_core\\page_core';
		$this->_page_core = $pages_core_ns::getInstance();
		
		return;
	}
	
	/**
	 * getPageInfo function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 */
	public function getPageInfo($link_id)
	{
		$out = $this->_page_core->getPageCoreInfo($link_id);
		
		//get the contents of the page
		if(!empty($out['page_info']['link_id']))
		{
			$out['page_contents'] = $this->getPageContent($link_id,0); // '0' means get all of the contents
		}
		
		return $out;
			
	}
	
	/**
	 * getPageContent function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $content_id
	 * @return void
	 */
	public function getPageContent($link_id, $content_id)
	{
		$out = array(); //the page might not have any contents :-)
		
		if($content_id > 0)
		{
			$sql = "SELECT
						`pages_content`.`content_type`,
						`pages_content`.`show_heading`,
						`pages_content`.`heading`,
						`pages_content`.`sdesc`,
						`pages_content`.`content`,
						`pages_content`.`sequence`,
						`pages_content`.`created_on`,
						`pages_content`.`created_by`,
						`pages_content`.`modified_on`,
						`pages_content`.`modified_by`,
						`pages_content`.`image_position`,
						`pages_content_contact_form`.`to_name`,
						`pages_content_contact_form`.`to_email`,
						`pages_content_contact_form`.`to_subject`,
						`pages_content_contact_form`.`submitted_heading`,
						`pages_content_contact_form`.`submitted_sdesc`,
						`pages_content_contact_form`.`submitted_content`
					FROM
						`pages_content`
					LEFT JOIN
						`pages_content_contact_form`
					ON
						`pages_content_contact_form`.`link_id` = `pages_content`.`link_id`
					AND
						`pages_content_contact_form`.`content_id` = `pages_content`.`content_id`
					WHERE
						`pages_content`.`link_id` = ? 
					AND
						`pages_content`.`content_id` = ?
				";
				
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('si',$link_id,$content_id);
			$stmt->bind_result($content_type, $show_heading, $heading, $sdesc, $content, $sequence, $created_on, $created_by, $modified_on, $modified_by,$image_position, $to_name, $to_email, $to_subject, $submitted_heading, $submitted_sdesc, $submitted_content);
		
		} else {
			$sql = "SELECT
						`pages_content`.`content_id`,
						`pages_content`.`content_type`,
						`pages_content`.`show_heading`,
						`pages_content`.`heading`,
						`pages_content`.`sdesc`,
						`pages_content`.`content`,
						`pages_content`.`sequence`,
						`pages_content`.`created_on`,
						`pages_content`.`created_by`,
						`pages_content`.`modified_on`,
						`pages_content`.`modified_by`,
						`pages_content`.`image_position`,
						`pages_content_contact_form`.`to_name`,
						`pages_content_contact_form`.`to_email`,
						`pages_content_contact_form`.`to_subject`,
						`pages_content_contact_form`.`submitted_heading`,
						`pages_content_contact_form`.`submitted_sdesc`,
						`pages_content_contact_form`.`submitted_content`
					FROM
						`pages_content`
					LEFT JOIN
						`pages_content_contact_form`
					ON
						`pages_content_contact_form`.`link_id` = `pages_content`.`link_id`
					AND
						`pages_content_contact_form`.`content_id` = `pages_content`.`content_id`
					WHERE
						`pages_content`.`link_id` = ?
					ORDER BY 
						`pages_content`.`sequence`,`pages_content`.`content_id`
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('s',$link_id);
			$stmt->bind_result($content_id,$content_type, $show_heading, $heading, $sdesc, $content, $sequence, $created_on, $created_by, $modified_on, $modified_by,$image_position, $to_name, $to_email, $to_subject, $submitted_heading, $submitted_sdesc, $submitted_content);
		}
		
		$stmt->execute();
		$stmt->store_result();
		while($stmt->fetch())
		{
			$out[$content_id] = array(
				'content_type' => $content_type,
				'show_heading' => $show_heading,
				'heading' => $heading,
				'sdesc' => $sdesc,
				'content' => $content,
				'sequence' => $sequence,
				'created_on' => $created_on,
				'created_by' => $created_by,
				'modified_on' => $modified_on,
				'modified_by' => $modified_by,
				'image_position' => $image_position,
				'to_name' => $to_name,
				'to_email' => $to_email, 
				'to_subject' => $to_subject, 
				'submitted_heading' => $submitted_heading, 
				'submitted_sdesc' => $submitted_sdesc, 
				'submitted_content' => $submitted_content
			);
		}
		$stmt->free_result();
		$stmt->close();
		
		return $out;
	}

	/**
	 * getPageContentAjax function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $content_id
	 * @return void
	 */
	public function getPageContentAjax($link_id, $content_id)
	{
		$out = array(); //the page might not have any contents :-)
		
		if($content_id > 0)
		{
			$sql = "SELECT
						`pages_content`.`content_type`,
						`pages_content`.`show_heading`,
						`pages_content`.`heading`,
						`pages_content`.`sdesc`,
						`pages_content`.`content`,
						`pages_content`.`sequence`,
						`pages_content`.`created_on`,
						`pages_content`.`created_by`,
						`pages_content`.`modified_on`,
						`pages_content`.`modified_by`,
						`pages_content`.`image_position`,
						`pages_content_contact_form`.`to_name`,
						`pages_content_contact_form`.`to_email`,
						`pages_content_contact_form`.`to_subject`,
						`pages_content_contact_form`.`submitted_heading`,
						`pages_content_contact_form`.`submitted_sdesc`,
						`pages_content_contact_form`.`submitted_content`
					FROM
						`pages_content`
					LEFT JOIN
						`pages_content_contact_form`
					ON
						`pages_content_contact_form`.`link_id` = `pages_content`.`link_id`
					AND
						`pages_content_contact_form`.`content_id` = `pages_content`.`content_id`
					WHERE
						`pages_content`.`link_id` = ? 
					AND
						`pages_content`.`content_id` = ?
				";
				
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('si',$link_id,$content_id);
			$stmt->bind_result($content_type, $show_heading, $heading, $sdesc, $content, $sequence, $created_on, $created_by, $modified_on, $modified_by,$image_position, $to_name, $to_email, $to_subject, $submitted_heading, $submitted_sdesc, $submitted_content);
		
		} else {
			$sql = "SELECT
						`pages_content`.`content_id`,
						`pages_content`.`content_type`,
						`pages_content`.`show_heading`,
						`pages_content`.`heading`,
						`pages_content`.`sdesc`,
						`pages_content`.`content`,
						`pages_content`.`sequence`,
						`pages_content`.`created_on`,
						`pages_content`.`created_by`,
						`pages_content`.`modified_on`,
						`pages_content`.`modified_by`,
						`pages_content`.`image_position`,
						`pages_content_contact_form`.`to_name`,
						`pages_content_contact_form`.`to_email`,
						`pages_content_contact_form`.`to_subject`,
						`pages_content_contact_form`.`submitted_heading`,
						`pages_content_contact_form`.`submitted_sdesc`,
						`pages_content_contact_form`.`submitted_content`
					FROM
						`pages_content`
					LEFT JOIN
						`pages_content_contact_form`
					ON
						`pages_content_contact_form`.`link_id` = `pages_content`.`link_id`
					AND
						`pages_content_contact_form`.`content_id` = `pages_content`.`content_id`
					WHERE
						`pages_content`.`link_id` = ?
					ORDER BY 
						`pages_content`.`sequence`,`pages_content`.`content_id`
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('s',$link_id);
			$stmt->bind_result($content_id,$content_type, $show_heading, $heading, $sdesc, $content, $sequence, $created_on, $created_by, $modified_on, $modified_by,$image_position, $to_name, $to_email, $to_subject, $submitted_heading, $submitted_sdesc, $submitted_content);
		}
		
		$stmt->execute();
		$stmt->store_result();
		while($stmt->fetch())
		{
			$out[] = array(
				'content_type' => $content_type,
				'show_heading' => $show_heading,
				'heading' => $heading,
				'sdesc' => $sdesc,
				'content' => $content,
				'sequence' => $sequence,
				'created_on' => $created_on,
				'created_by' => $created_by,
				'modified_on' => $modified_on,
				'modified_by' => $modified_by,
				'image_position' => $image_position,
				'to_name' => $to_name,
				'to_email' => $to_email, 
				'to_subject' => $to_subject, 
				'submitted_heading' => $submitted_heading, 
				'submitted_sdesc' => $submitted_sdesc, 
				'submitted_content' => $submitted_content,
				'files' => $this->getFilePageContent($link_id, 'entry-'.$content_id)
			);
		}
		$stmt->free_result();
		$stmt->close();
		
		return $out;
	}

	/**
	 * Get file or image for each page content
	 */
	public function getFilePageContent($link_id, $model_id)
	{
		$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		$file_manager = $file_manager_ns::getInstance();

		$files = $file_manager->getFilesArray($link_id, $model_id);
		$out = array();
        $x = 1;
        //run over the images and files and put them in the right array
        foreach( $files as $fileName => $value )
        {
            $content_id = ltrim($value['model'],"entry-");
			$type = $value['model_id'];
			
			$out[] = [
				'file_manager_id' => $fileName,
				'model' => $value['model'],
				'model_id' => $value['model_id'],
				'status' => $value['status'],
				'title' => $value['title'],
				'sdesc' => $value['sdesc'],
				'image_prefix' => $value['image_prefix'],
				'file_prefix' => $value['file_prefix'],
				'file_name' => $value['file_name'],
				'sequence' => $value['sequence']
			];
            $x++;
        }

        return $out;
	}
	
	/**
	 * getPageContentSummary function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 */
	public function getPageContentSummary($link_id)
	{
		$out = array(); //the page might not have any contents :-)
	
		$sql = "SELECT
					`content_id`,
					`heading`,
					`sdesc`,
					`sequence`
				FROM
					`pages_content`
				WHERE
					`link_id` = ?
				ORDER BY 
					`sequence`,`content_id`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($content_id,$heading,$sdesc,$sequence);

		$stmt->execute();
		$stmt->store_result();
		while($stmt->fetch())
		{
			$out[$content_id] = array(
				'heading' => $heading,
				'sdesc' => $sdesc,
				'sequence' => $sequence
			);
		}
		$stmt->free_result();
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * getContactFormInfo function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $content_id
	 * @return void
	 */
	public function getContactFormInfo($link_id,$content_id)
	{
		//update pages itself
		$sql = "SELECT
					`to_name`,
					`to_email`,
					`to_subject`,
					`submitted_heading`,
					`submitted_sdesc`,
					`submitted_content`
				FROM
					`pages_content_contact_form`
				WHERE
					`link_id` = ?
				AND
					`content_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$link_id,$content_id);
		$stmt->bind_result($to_name, $to_email, $to_subject, $submitted_heading, $submitted_sdesc, $submitted_content);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		$out = array('to_name' => $to_name, 'to_email' => $to_email, 'to_subject' => $to_subject, 'submitted_heading' => $submitted_heading, 'submitted_sdesc' => $submitted_sdesc, 'submitted_content' => $submitted_content);
		
		return $out;	
	}
	
	/**
	 * updateLinkIds function.
	 * 
	 * @access public
	 * @param mixed $to_link_id
	 * @param mixed $from_link_id
	 * @return void
	 */
	public function updateLinkIds($to_link_id,$from_link_id)
	{
		//update the core links
		$out = $this->_page_core->updateCoreLinkIds($to_link_id,$from_link_id);	
		
		//update all the content for this page
		$sql = "UPDATE
					`pages_content`
				SET 
					`link_id` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ss',$to_link_id,$from_link_id);
		$stmt->execute();
		$stmt->close();
		
		//update all the content for that page
		$sql = "UPDATE
					`pages_content_contact_form`
				SET 
					`link_id` = ?
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ss',$to_link_id,$from_link_id);
		$stmt->execute();
		$stmt->close();
		
		return $out;	
	}
	
	/**
	 * updatePageContent function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	public function updatePageContent($data)
	{
		//required
		$link_id = $data['link_id'];
		$content_id = $data['content_id'];
		$content_type = $data['content_type'];
		
		//standard
		$show_heading = isset($data['show_heading'])? $data['show_heading'] : '';
		$heading = isset($data['heading'])? $data['heading'] : '';
		$sdesc = isset($data['sdesc'])? $data['sdesc'] : '';
		$content = isset($data['content'])? $data['content'] : '';
		$sequence = isset($data['sequence'])? $data['sequence'] : '';

		//contact_form
		$to_name = isset($data['to_name'])? $data['to_name'] : '';
		$to_email = isset($data['to_email'])? $data['to_email'] : '';
		$to_subject = isset($data['to_subject'])? $data['to_subject'] : '';
		$submitted_heading = isset($data['submitted_heading'])? $data['submitted_heading'] : '';
		$submitted_sdesc = isset($data['submitted_sdesc'])? $data['submitted_sdesc'] : '';
		$submitted_content = isset($data['submitted_content'])? $data['submitted_content'] : '';
		$image_position = isset($data['image_position'])? $data['image_position'] : '';

		//update pages itself
		$sql = "INSERT INTO
					`pages_content`
				SET 
					`link_id` = ?,
					`content_id` = ?,
					`content_type` = ?,
					`show_heading` = ?,
					`heading` = ?,
					`sdesc` = ?,
					`content` = ?,
					`sequence` = ?,
					`image_position` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`content_type` = ?,
					`show_heading` = ?,
					`heading` = ?,
					`sdesc` = ?,
					`content` = ?,
					`sequence` = ?,
					`image_position` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sisisssississsis',$link_id,$content_id,$content_type,$show_heading,$heading,$sdesc,$content,$sequence,$image_position,$content_type,$show_heading,$heading,$sdesc,$content,$sequence,$image_position);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		//if type is contact_form
		if($content_type == 'contact_form')
		{
			$sql = "INSERT INTO
						`pages_content_contact_form`
					SET 
						`link_id` = ?,
						`content_id` = ?,
						`to_name` = ?,
						`to_email` = ?,
						`to_subject` = ?,
						`submitted_heading` = ?,
						`submitted_sdesc` = ?,
						`submitted_content` = ?
					ON DUPLICATE KEY UPDATE
						`to_name` = ?,
						`to_email` = ?,
						`to_subject` = ?,
						`submitted_heading` = ?,
						`submitted_sdesc` = ?,
						`submitted_content` = ?
					";
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param('sissssssssssss',$link_id,$content_id,$to_name,$to_email,$to_subject,$submitted_heading,$submitted_sdesc,$submitted_content,$to_name,$to_email,$to_subject,$submitted_heading,$submitted_sdesc,$submitted_content);
			$stmt->execute();
			$stmt->close();
		}

		return $out;
	}
	
	/**
	 * updateSort function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $sequence
	 * @param mixed $content_id
	 * @return void
	 */
	public function updateSort($link_id,$sequence,$content_id)
	{
		//update pages itself
		$sql = "UPDATE
					`pages_content`
				SET 
					`sequence` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				WHERE
					`link_id` = ?
				AND
					`content_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isi',$sequence,$link_id,$content_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		return $out;
	}
	
	/**
	 * deletePage function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 */
	public function deletePage($link_id)
	{
		//delete the page contents first
		$sql = "DELETE FROM
					`pages_content`
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->execute();
		$stmt->close();
		
		//delete the core page
		$this->_page_core->deletePageCore($link_id);
				
		return;
	}
	
	/**
	 * deletePageContent function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $content_id
	 * @return void
	 */
	public function deletePageContent($link_id,$content_id)
	{
		//delete the general content
		$sql = "DELETE FROM
					`pages_content`
				WHERE
					`link_id` = ?
				AND
					`content_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$link_id,$content_id);
		$stmt->execute();
		$stmt->close();
		
		//delete the contact form (in case it is there)
		$sql = "DELETE FROM
					`pages_content_contact_form`
				WHERE
					`link_id` = ?
				AND
					`content_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si',$link_id,$content_id);
		$stmt->execute();
		$stmt->close();

        //update sequence order
        $sql = "UPDATE `pages_content` SET `sequence` = (`sequence`  - 1)  WHERE `sequence` > 0 and `link_id` = '{$link_id}'";
        $this->db->query($sql);
		
		return;
	}
	
}
?>