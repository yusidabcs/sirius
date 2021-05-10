<?php
namespace core\app\classes\file_manager;

/**
 * Final file_obj class.
 *
 * Is a final file ready to process
 *
 * @todo: Put in better checkers for the setters
 *
 * @final
 * @package 	file manger
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class file_obj {
	
	//file manager fields
	private $_file_manager_id;
	private $_link_id; 
	private $_model = '';
	private $_model_id = '';
	private $_title = 'No Title Given';
	private $_sdesc = 'No Description Given';
	private $_security_level_id = 'NONE';
	private $_group_id = 'ALL';
	private $_dir; 
	private $_mime_type; 
	private $_size;
	private $_original_name = '';
	private $_original_ext = '';
	private $_sequence = 0;
	private $_image = 0; //set by me with mime_type
	private $_status = 0;
	
	public function __construct()
	{
		return;
	}
	
	//!setters
	
	public function setFileManagerId($file_manager_id)
	{
		$this->_file_manager_id = $file_manager_id;
		return;
	}
	
	public function setLinkId($link_id)
	{
		$this->_link_id = $link_id;
		return;
	}
	
	public function setModel($model)
	{
		$this->_model = $model;
		return;
	}
	
	public function setModelId($model_id)
	{
		$this->_model_id = $model_id;
		return;
	}
	
	public function setTitle($title)
	{
		$this->_title = $title;
		return;
	}
	
	public function setSDesc($sdesc)
	{
		$this->_sdesc = $sdesc;
		return;
	}
	
	public function setSecurityLevelId($security_level_id)
	{
		$this->_security_level_id = $security_level_id;
		return;
	}
	
	public function setGroupId($group_id)
	{
		$this->_group_id = $group_id;
		return;
	}
	
	public function setDir($dir)
	{
		$this->_dir = $dir;
		return;
	}
	
	public function setMimeType($mime_type)
	{
		$this->_mime_type = $mime_type;
		
		$image_a = array('image/jpeg','image/gif','image/png','image/vnd.wap.wbmp');
		
		if(in_array($mime_type, $image_a))
		{
			$this->_image = 1;	
		} else {
			$this->_image = 0;
		}
		
		return;
	}
	
	public function setSize($size)
	{
		$this->_size = $size;
		return;
	}
	
	public function setOriginalName($original_name)
	{
		$this->_original_name = $original_name;
		return;
	}
	
	public function setOriginalExt($original_ext)
	{
		$this->_original_ext = $original_ext;
		return;
	}
	
	public function setSequence($sequence)
	{
		$this->_sequence = $sequence;
		return;
	}
		
	public function setStatus($status)
	{
		$this->_status = $status;
		return;
	}
	
	//!getters

	public function getFileManagerId()
	{
		return $this->_file_manager_id;
	}
	
	public function getLinkId()
	{
		return $this->_link_id;
	}
	
	public function getModel()
	{
		return $this->_model;
	}
	
	public function getModelId()
	{
		return $this->_model_id;
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	public function getSDesc()
	{
		return $this->_sdesc;
	}
	
	public function getSecurityLevelId()
	{
		return $this->_security_level_id;
	}
	
	public function getGroupId()
	{
		return $this->_group_id;
	}
		
	public function getDir()
	{
		return $this->_dir;
	}
	
	public function getMimeType()
	{
		return $this->_mime_type;
	}
	
	public function getSize()
	{
		return $this->_size;
	}
	
	public function getOriginalName()
	{
		return $this->_original_name;
	}
	
	public function getOriginalExt()
	{
		return $this->_original_ext;
	}
	
	public function getSequence()
	{
		return $this->_sequence;
	}
	
	public function getImage()
	{
		return $this->_image;
	}
	
	public function getStatus()
	{
		return $this->_status;
	}
		
}
?>