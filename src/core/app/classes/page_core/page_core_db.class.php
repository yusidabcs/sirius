<?php
namespace core\app\classes\page_core;

/**
 * Final page_core_db class.
 *
 * All database interaction for page_core is here
 *
 * @final
 * @package 	page_core
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 19 August 2019
 */
final class page_core_db extends \core\app\classes\module_base\module_db {
	
	public function __construct()
	{
		//always local
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables		
		return;
	}

    /**
     * get page core info by link_id
     * @param $link_id
     * @return array
     */
    public function getAllPageCore()
    {
        $data = array();

        //get the main information
        $sql = "SELECT
					`link_id`,
					`show_heading`,
					`page_heading`,
					`page_sdesc`,
					`page_keywords`,
					`page_text`,
					`show_anchors`,
					`created_on`,
					`created_by`,
					`modified_on`,
					`modified_by`
				FROM
					`page_core`
		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $link_id,
            $show_heading,
            $page_heading,
            $page_sdesc,
            $page_keywords,
            $page_text,
            $show_anchors,
            $created_on,
            $created_by,
            $modified_on,
            $modified_by);
        $stmt->execute();
        while($stmt->fetch())
        {
            $data[] = array(
                'link_id' => $link_id,
                'show_heading' => $show_heading,
                'page_heading' => $page_heading,
                'page_sdesc' => $page_sdesc,
                'page_keywords' => $page_keywords,
                'page_text' => $page_text,
                'show_anchors' => $show_anchors,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by
            );
        }
        $stmt->close();

        return $data;
    }


    /**
     * get page core info by link_id
     * @param $link_id
     * @return array
     */
    public function getPageCoreInfo($link_id)
	{		
		$out = array();

		//get the main information
		$sql = "SELECT
					`show_heading`,
					`page_heading`,
					`page_sdesc`,
					`page_keywords`,
					`page_text`,
					`show_anchors`,
					`created_on`,
					`created_by`,
					`modified_on`,
					`modified_by`
				FROM
					`page_core`
				WHERE
					`link_id` = ?
		";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($show_heading, $page_heading, $page_sdesc, $page_keywords, $page_text, $show_anchors, $created_on, $created_by, $modified_on, $modified_by);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out['page_info'] = array(
				'link_id' => $link_id,
				'show_heading' => $show_heading,
				'page_heading' => $page_heading,
				'page_sdesc' => $page_sdesc,
				'page_keywords' => $page_keywords,
				'page_text' => $page_text,
				'show_anchors' => $show_anchors,
				'created_on' => $created_on, 
				'created_by' => $created_by,
				'modified_on' => $modified_on,
				'modified_by' => $modified_by
			);
		}
		$stmt->close();
		
		return $out;			
	}


    /**
     * update core link id from old_link_id to new_link_id
     * @param $to_link_id
     * @param $from_link_id
     * @return mixed
     */
    public function updateCoreLinkIds($to_link_id, $from_link_id)
	{
		$sql = "UPDATE
					`page_core`
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
		$out = $stmt->affected_rows;
		$stmt->close();
		return $out;
	}


    /**
     * Delete page core by link_id
     * @param $link_id
     */
    public function deletePageCore($link_id)
	{
		//delete the core page by link_id
		$sql = "DELETE FROM
					`page_core`
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->execute();
		$stmt->close();
		
		return;
	}

    /**
     * updatePageCoreInfo function.
     *
     * update page core info
     * @access public
     * @param mixed $link_id
     * @param mixed $show_heading
     * @param mixed $page_heading
     * @param mixed $page_sdesc
     * @param mixed $page_keywords
     * @param mixed $page_text
     * @param mixed $show_anchors
     * @return void
     */
    public function updatePageCoreInfo($link_id,$show_heading,$page_heading,$page_sdesc,$page_keywords,$page_text,$show_anchors)
	{
		$sql = "INSERT INTO
					`page_core`
				SET 
					`link_id` = ?,
					`show_heading` = ?,
					`page_heading` = ?,
					`page_sdesc` = ?,
					`page_keywords` = ?,
					`page_text` = ?,
					`show_anchors` = ?,
					`created_on`= CURRENT_TIMESTAMP, 
					`created_by`= {$this->user_id},
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				ON DUPLICATE KEY UPDATE
					`show_heading` = ?,
					`page_heading` = ?,
					`page_sdesc` = ?,
					`page_keywords` = ?,
					`page_text` = ?,
					`show_anchors` = ?,
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id}
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sissssiissssi',$link_id,$show_heading,$page_heading,$page_sdesc,$page_keywords,$page_text,$show_anchors,$show_heading,$page_heading,$page_sdesc,$page_keywords,$page_text,$show_anchors);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		return $out;
	}
	
}
?>