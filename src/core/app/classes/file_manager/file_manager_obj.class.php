<?php
namespace core\app\classes\file_manager;

/**
 * Final file_manager class.
 * The real obj class for file manager.
 * have function to file management is system.
 * @final
 * @package 	file manger
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class file_manager_obj {

	private $db;
	private $_config_a;
	private $_error_a;

	private $_file_obj_a = array();

	private $_link_id = ''; 	//needs to be set before processing
	private $_model = ''; 		//needs to be set before processing
	private $_model_id = ''; 	//needs to be set before processing

	private $_dir = ''; //set by construct

	public function __construct()
	{
	    //load the file_manager ini here
		$this->_setImageDefaults();

		//just check you have a database! It should always be on the local database
		$fmClass = NS_APP_CLASSES.'\file_manager\file_manager_db';
		$this->file_manager_db = new $fmClass('local');

		//set up this directory
		$this->_dir = date("Y").'/'.date('m').'/'.date('d');

		//setup globals
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$this->system_register = $system_register_ns::getInstance();

		$mime_type_ns = NS_APP_CLASSES.'\\mime_type\\mime_type';
		$this->mime_type_obj = $mime_type_ns::getInstance();

		$generic_ns = NS_APP_CLASSES.'\\generic\\generic';
		$this->generic_obj = $generic_ns::getInstance();

		return;
	}

	//!setup related

    /**
     * setup the storage for file management
     * @return string
     */
    public function setupStorage()
	{
		$log = "<strong>File manager setup</strong><br>\n";

		if(is_dir(FILE_MANAGER_STORAGE_LOCAL))
		{
			$log .= "File manager local directory already exists!<br>\n";

			if(is_writable(FILE_MANAGER_STORAGE_LOCAL))
			{
				$log .= "File manager local directory is writable!<br>\n";
			} else {
				$log .= "<span style=\"color:red\">File manager local directory is NOT writable!</span><br>\n";
			}

		} else {

			if(mkdir(FILE_MANAGER_STORAGE_LOCAL,0770,true))
			{
				$log .= "File manager directory set up!<br>\n";
			} else {
				$log .= "<span style=\"color:red\">Could not make the local directory ".FILE_MANAGER_STORAGE_LOCAL."!</span><br>\n";
			}

		}
		return $log;
	}

	//!maintenance

	public function updateLinkIds($link_id_new, $link_id_orig)
	{
		$rowsAffected = $this->file_manager_db->updateLinkIds($link_id_new, $link_id_orig);
		return;
	}

	public function cleanFileManager()
	{
		$log = "<span style=\"color:red\">File Manager cleaning is not set up yet</span><br>\n";
		return $log;
	}

	//!getters

    /**
     *
     * Get files form a link_id
     * @param $link_id
     * @param string $model
     * @param string $model_id
     * @return array
     */
    public function getFilesArray($link_id, $model='', $model_id='')
	{
		$out = array();

		//user info
	    $user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 'ALL' ;
	    $user_security_level = isset($_SESSION['user_security_level']) ? $_SESSION['user_security_level'] : 1;

	    //system register needed for security level
	    $groups_array = $this->system_register->getGroups($user_group);

	    $filesArray = $this->file_manager_db->getFilesInfoArray($link_id,$model,$model_id);

	    foreach($filesArray as $file_manager_id => $value)
	    {
			//only if security is ok
		    $security_level = $this->system_register->getSecurityLevel($value['security_level_id']);

		    if($security_level <= $user_security_level)
		    {
			    //group check
			    if($user_group == 'IOW' || $value['group_id'] == 'ALL' || $user_group == $value['group_id'] || in_array($value['group_id'], $groups_array))
			    {
				    //workout how to find them based on storage
				    if($value['image'] == 1)
				    {
					    $image_prefix = "/fm/image/{$file_manager_id}";
					} else {
						$image_prefix = "/fm/icon/{$file_manager_id}"; //I did this so it would show the icon of the mime type
					}

				    $file_prefix = "/fm/file/{$file_manager_id}";

				    //make the safe file name
				    $file_name = $this->generic_obj->safeFilename($value['title']);

				    //file extension .. start by converting backwards from the mime_type (if possible)
				    $file_ext = $this->mime_type_obj->getExtFromMimeType($value['mime_type']);

				    //if the extension is blank we need to use the original one
				    if(empty($file_ext))
				    {
					    $file_ext = $value['original_ext'];
				    }

				    //if that is also blank then what to do
				    if(empty($file_ext))
				    {
					    $file_ext = 'xxx';
				    }

				    $safe_file_name = $file_name.'.'.$file_ext;

				    $out[$file_manager_id] = array(
					    'link_id' => $value['link_id'],
					    'model' => $value['model'],
					    'model_id' => $value['model_id'],
					    'title' => $value['title'],
					    'sdesc' => $value['sdesc'],
					    'mime_type' => $value['mime_type'],
					    'size' => $value['size'],
					    'original_name' => $value['original_name'],
					    'original_ext' => $value['original_ext'],
						'sequence' => $value['sequence'],
						'security_level_id' => $value['security_level_id'],
						'group_id' => $value['group_id'],
						'status' => $value['status'],
					    'image' => $value['image'],
					    'image_prefix' => $image_prefix,
					    'file_prefix' => $file_prefix,
					    'file_name' => $safe_file_name
				    );
				}
		    }
	    }
	    return $out;
	}

    /**
     * get single file info by file_manager_id
     * @param $file_manager_id
     * @return array
     */
    public function getFileInfo($file_manager_id)
	{
		$out = array();

		//user info
	    $user_group = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 'ALL' ;
	    $user_security_level = isset($_SESSION['user_security_level']) ? $_SESSION['user_security_level'] : 1;

	    //system register needed for security level
	    $groups_array = $this->system_register->getGroups($user_group);

	    $fileInfo = $this->file_manager_db->getFileInfoArray($file_manager_id);

	    if(empty($fileInfo))
	    {
		    $html_ns = NS_HTML.'\\htmlpage';
			$htmlpage = new $html_ns(404);
			exit();
		}

		//only if security is ok
	    $security_level = $this->system_register->getSecurityLevel($fileInfo['security_level_id']);

	    if($security_level <= $user_security_level)
	    {
		    //group check
		    if($user_group == 'IOW' || $fileInfo['group_id'] == 'ALL' || $user_group == $fileInfo['group_id'] || in_array($fileInfo['group_id'], $groups_array))
		    {
			    //workout how to find them based on storage
			    if($fileInfo['image'] == 1)
			    {
				    $image_prefix = "/fm/image/{$file_manager_id}";
				} else {
					$image_prefix = "/fm/icon/{$file_manager_id}"; //I did this so it would show the icon of the mime type
				}

			    $file_prefix = "/fm/file/{$file_manager_id}";

				//make the safe file name
			    $file_name = $this->generic_obj->safeFilename($fileInfo['title']);

			    //file extension .. start by converting backwards from the mime_type (if possible)
			    $file_ext = $this->mime_type_obj->getExtFromMimeType($fileInfo['mime_type']);

			    //if the extension is blank we need to use the original one
			    if(empty($file_ext))
			    {
				    $file_ext = $fileInfo['original_ext'];
			    }

			    //if that is also blank then what to do
			    if(empty($file_ext))
			    {
				    $file_ext = 'xxx';
			    }

			    $safe_file_name = $file_name.'.'.$file_ext;


			    $out = array(
                    'file_manager_id' => $fileInfo['file_manager_id'],
                    'model' => $fileInfo['model'],
                    'model_id' => $fileInfo['model_id'],
				    'title' => $fileInfo['title'],
				    'sdesc' => $fileInfo['sdesc'],
				    'mime_type' => $fileInfo['mime_type'],
				    'size' => $fileInfo['size'],
				    'original_name' => $fileInfo['original_name'],
					'original_ext' => $fileInfo['original_ext'],
				    'dir' => $fileInfo['dir'],
					'sequence' => $fileInfo['sequence'],
					'status' => $fileInfo['status'],
				    'image' => $fileInfo['image'],
				    'image_prefix' => $image_prefix,
				    'file_prefix' => $file_prefix,
					'file_name' => $safe_file_name
			    );
			}
	    }
	    return $out;
	}

	//!setters

	public function setLinkInfo($link_id,$model='', $model_id='')
	{
		//!should really check stuff but whatever
		$this->_link_id = $link_id;
		$this->_model = $model;
		$this->_model_id = $model_id;
		return;
	}

	//!processing

    /**
     *
     * add file to database and to storage
     *
     * @param $title
     * @param $sdesc
     * @param $sequence
     * @param $security_level_id
     * @param $group_id
     * @param $status
     * @param $fileUpload_a
     * @param bool $multiArray
     * @return array|string
     */
    public function addFiles($title, $sdesc, $sequence, $security_level_id, $group_id, $status, $fileUpload_a, $multiArray = false)
	{
		//reset file_obj_a just in case
		$this->_file_obj_a = array();

		//we need to know the link_id before we can do anything so
		if(empty($this->_link_id))
		{
			$msg = 'Link Id is blank, it must be set using setLinkInfo before files can be processed!';
			throw new \RuntimeException($msg);
		}

		//if multi array then everything should be passed in an array!
		if($multiArray)
		{
			$out = array();

			//!should really chack to make sure they are all arrays!

			//multi
			if(!is_array($fileUpload_a['error']))
			{
				$msg = 'Multi file selected for file manager processing but Error is not an array!';
				throw new \RuntimeException($msg);
			}

			if( count($fileUpload_a['error']) == count($title) )
			{
				foreach($fileUpload_a['error'] as $key => $error_code)
				{
					$tmp_file_name = $fileUpload_a['tmp_name'][$key];
					if(!empty($tmp_file_name))
					{
						if( is_writable($tmp_file_name) ) //if empty there is no file if not writable then what to do!
						{
							if($error_code == 0)
							{
								$_file_manager_id = $this->file_manager_db->setNewFileManagerId(); //makes a file_manager_id and reserves the db

								//make the file and images if needed
								$this->_moveUploadedFile( $fileUpload_a['tmp_name'][$key], $fileUpload_a['type'][$key], $_file_manager_id); //it will die if it can not move it
								//we need to keep the original information ... it is from the browser so it might be fucked but what to do as not all mime_types are in my csv file so I need to 'borrow' from this if I don't have it ...
								$original_file = pathinfo($fileUpload_a['name'][$key]);

								//set the variables
								$_title = empty($title[$key]) ? 'No Title' :  $title[$key];
								$_sdesc = empty($sdesc[$key]) ? 'No Description' :  $sdesc[$key];
								$_sequence = empty($sequence[$key]) ? 0 : $sequence[$key];
								$_security_level_id = empty($security_level_id[$key]) ? 'NONE' :  $security_level_id[$key];
								$_group_id = empty($group_id[$key]) ? 'ALL' :  $group_id[$key];
								$_status = empty($status[$key]) ? 0 : $status[$key];

								$_mime_type = $fileUpload_a['type'][$key];
								$_size = $fileUpload_a['size'][$key];

								$_original_name = $original_file['filename'];
								$_original_ext = $original_file['extension'];

								$this->_file_obj_a[$_file_manager_id] = $this->_makeNewFileObj($_file_manager_id, $_title, $_sdesc, $_security_level_id, $_group_id, $_mime_type, $_size, $_original_name, $_original_ext, $_sequence, $_status);

								$out[$key] = '';
							} else {
								$msg = $this->_errorCodeToMessage($error_code);
								$out[$key] = $msg;
							}
						} else {
							$out[$key] = "Temp File '{$tmp_file_name}' or it was not Writable!";
						}
					}
				}
			} else {
				$msg = 'There are a different number of titles to files!';
				throw new \RuntimeException($msg);
			}

		} else {

			//just a single file being added

			$out = '';
			//single
			$tmp_file_name = $fileUpload_a['tmp_name'];
			if(!empty($tmp_file_name))
			{
				if( is_writable($tmp_file_name) ) //if empty there is no file if not writable then what to do!
				{
					$error_code = $fileUpload_a['error'];
					if($error_code == 0)
					{
						$_file_manager_id = $this->file_manager_db->setNewFileManagerId(); //makes a file_manager_id and reserves the db

						//make the file and images if needed
						$this->_moveUploadedFile( $fileUpload_a['tmp_name'], $fileUpload_a['type'], $_file_manager_id); //it will die if it can not move it

						//we need to keep the original information ... it is from the browser so it might be fucked but what to do as not all mime_types are in my csv file so I need to 'borrow' from this if I don't have it ...
						$original_file = pathinfo($fileUpload_a['name']);

						//set the variables
						$_title = empty($title) ? 'No Title' :  $title;
						$_sdesc = empty($sdesc) ? 'No Description' :  $sdesc;
						$_sequence = empty($sequence) ? 0 : $sequence;
						$_security_level_id = empty($security_level_id) ? 'NONE' :  $security_level_id;
						$_group_id = empty($group_id) ? 'ALL' :  $group_id;
						$_status = empty($status) ? 0 : $status;

						$_mime_type = $fileUpload_a['type'];
						$_size = $fileUpload_a['size'];

						$_original_name = $original_file['filename'];
						$_original_ext = $original_file['extension'];

						$this->_file_obj_a[$_file_manager_id] = $this->_makeNewFileObj($_file_manager_id, $_title, $_sdesc, $_security_level_id, $_group_id, $_mime_type, $_size, $_original_name, $_original_ext, $_sequence, $_status);

						$out = [];

					} else {
							$out = $this->_errorCodeToMessage($error_code);
					}
				} else {
					$out = 'Could not find Temp File or it was not Writable!';
				}
			} else {
				$out = "The was no Temp File {$tmp_file_name}!";
			}
		}

		//process the new files
		$this->_processNewFileObjects();

        //reset file_obj_a just in case
		$this->_file_obj_a = array();

		//get file obj for processing in view
        $out['file_obj'] = $this->getFileInfo($_file_manager_id);
		return $out;
	}

    /**
     * Delete file manager from storage & db by ir link_id
     * carefull it will delete all the file manager by it link_id
     * @param $link_id
     * @param string $model
     * @param string $model_id
     */
    public function deleteLinkIds($link_id, $model = '', $model_id = '')
	{
		$file_manager_a = $this->file_manager_db->getFilesInfoArray($link_id,$model,$model_id);

		if(!empty($file_manager_a))
		{
			foreach($file_manager_a as $file_manager_id => $values)
			{
				//detete the actual files (all)
				$this->_deleteFile($values['dir'], $file_manager_id, false);

				//delete from table
				$this->file_manager_db->deleteFileManagerId($file_manager_id);
			}
		}
		return;
	}

	/**
	 * deleteFile function.
	 *
	 * Used to delete single record from the database and all matching files on the system
	 *
	 * @access public
	 * @param mixed $file_manager_id
	 * @return void
	 */
	public function deleteFile($file_manager_id)
	{
		//check security
		$deleteFileInfo = $this->getFileInfo($file_manager_id);

		//empty it either does not exist or the user does not have the security level required
		if(!empty($deleteFileInfo))
		{
			//delete from table and if successful delete all the files
			if($this->file_manager_db->deleteFileManagerId($file_manager_id))
			{
				//detete the actual files (all of them associated with this record)
				$this->_deleteFile($deleteFileInfo['dir'], $file_manager_id, false);
			}
		}
		return;
	}

    /**
     * Update file manager
     *
     * @param $file_manager_id
     * @param $title
     * @param $sdesc
     * @param $security_level_id
     * @param $group_id
     * @param $sequence
     * @param $status
     * @param $fileUpload_a
     * @param bool $multiArray,  true => it will multi file selected
     * @return array|string
     */
    public function updateFiles($file_manager_id, $title, $sdesc, $security_level_id, $group_id, $sequence, $status, $fileUpload_a, $multiArray = false)
	{
		//update array
		$update_a = array();

		//if multi array then everything should be passed in an array!
		if($multiArray)
		{
			$out = array();
			//multi
			if(!is_array($fileUpload_a['error']))
			{
				$msg = 'Multi file selected for file manager processing for updating but Error is not an array!';
				throw new \RuntimeException($msg);
			}

			if( count($fileUpload_a['error']) == count($file_manager_id) )
			{
				foreach($fileUpload_a['error'] as $key => $error_code)
				{
					//check if there is anything to update
					if( $this->file_manager_db->fileManagerIdExists($file_manager_id[$key]) )
					{
						$_file_manager_id = $file_manager_id[$key];

						$update_a[$_file_manager_id]['file_manager_id'] = $_file_manager_id;
						$update_a[$_file_manager_id]['title'] = empty($title[$key]) ? 'No Title' :  $title[$key];
						$update_a[$_file_manager_id]['sdesc'] = empty($sdesc[$key]) ? 'No Description' :  $sdesc[$key];
						$update_a[$_file_manager_id]['security_level_id'] = empty($security_level_id[$key]) ? 'NONE' :  $security_level_id[$key];
						$update_a[$_file_manager_id]['group_id'] = empty($group_id[$key]) ? 'ALL' :  $group_id[$key];
						$update_a[$_file_manager_id]['sequence'] = empty($sequence[$key]) ? 0 : $sequence[$key];
						$update_a[$_file_manager_id]['status'] = empty($status[$key]) ? 0 :  $status[$key];

						$update_a[$_file_manager_id]['new_file_a'] = array();

						if( !empty($fileUpload_a['tmp_name'][$key]) )  //if empty there is no file to worry about!
						{
							if(is_writable($fileUpload_a['tmp_name'][$key]))
							{
								//updating the file IF there are no errors
								if($error_code == 0)
								{
									$_tmp_file_name = $fileUpload_a['tmp_name'][$key];
									$_mime_type = $fileUpload_a['type'][$key];

									$original_file = pathinfo($fileUpload_a['name'][$key]);
									$_original_name = $original_file['filename'];
									$_original_ext = $original_file['extension'];

									$update_a[$_file_manager_id]['new_file_a']['size'] = $fileUpload_a['size'][$key];
									$update_a[$_file_manager_id]['new_file_a']['original_name'] = $_original_name;
									$update_a[$_file_manager_id]['new_file_a']['original_ext'] = $_original_ext;
									$update_a[$_file_manager_id]['new_file_a']['mime_type'] = $_mime_type;

									//move the new file
									$this->_moveUploadedFile( $_tmp_file_name, $_mime_type, $_file_manager_id); //it will die if it can not move it

								} else {
									$msg = $this->_errorCodeToMessage($error_code);
									$out[$key] = $msg;
								}
							} else {
								$out[$key] = 'Temp file is not writable ("'.$fileUpload_a['tmp_name'][$key].'")';
							}
						}
					} else {
						$out[$key] = 'No file manager id called ("'.$file_manager_id[$key].'")';
					}
				}
			} else {
				$msg = 'There are a different number of file manager ids to files for update!';
				throw new \RuntimeException($msg);
			}

		} else {

			$out = '';
			//single - update details
			if( $this->file_manager_db->fileManagerIdExists($file_manager_id) )
			{
				$update_a[$file_manager_id]['file_manager_id'] = $file_manager_id;
				$update_a[$file_manager_id]['title'] = empty($title) ? 'No Title' :  $title;
				$update_a[$file_manager_id]['sdesc'] = empty($sdesc) ? 'No Description' :  $sdesc;
				$update_a[$file_manager_id]['security_level_id'] = empty($security_level_id) ? 'NONE' :  $security_level_id;
				$update_a[$file_manager_id]['group_id'] = empty($group_id) ? 'ALL' :  $group_id;
				$update_a[$file_manager_id]['sequence'] = empty($sequence) ? 0 : $sequence;
				$update_a[$file_manager_id]['status'] = empty($status) ? 0 :  $status;

				$update_a[$file_manager_id]['new_file_a'] = array();

				//now update the image if it needs it
				if( !empty($fileUpload_a['tmp_name']) )  //if empty there is no file to worry about!
				{
					if(is_writable($fileUpload_a['tmp_name']))
					{
						//updating the file IF there are no errors
						if( $fileUpload_a['error'] == 0)
						{
							$_tmp_file_name = $fileUpload_a['tmp_name'];
							$_mime_type = $fileUpload_a['type'];

							$original_file = pathinfo($fileUpload_a['name'][$key]);
							$_original_name = $original_file['filename'];
							$_original_ext = $original_file['extension'];

							$update_a[$file_manager_id]['new_file_a']['size'] = $fileUpload_a['size'];
							$update_a[$file_manager_id]['new_file_a']['original_name'] = $_original_name;
							$update_a[$file_manager_id]['new_file_a']['original_ext'] = $_original_ext;
							$update_a[$file_manager_id]['new_file_a']['mime_type'] = $_mime_type;

							//move the new file
							$this->_moveUploadedFile( $_tmp_file_name, $_mime_type, $file_manager_id); //it will die if it can not move it

						} else {
							$out = $this->_errorCodeToMessage($error_code);
						}
					} else {
						$out = 'Temp File was NOT writable ("'.$fileUpload_a['tmp_name'].'")';
					}
				}
			} else {
				$out = 'No file manager id called ("'.$file_manager_id.'")';
			}
		}

		//commit off
		$this->file_manager_db->commitOff();

		foreach($update_a as $value)
		{
			$this->_updateFileManagerDetails($value['file_manager_id'], $value['title'], $value['sdesc'], $value['security_level_id'], $value['group_id'], $value['sequence'], $value['status'], $value['new_file_a']);
		}

		//commit on
		$this->file_manager_db->commit();
		$this->file_manager_db->commitOn();

		return $out;
	}

    /**
     *
     * Update files info by file_manager_id
     *
     * @param $file_manager_id
     * @param $title
     * @param $sdesc
     * @param $security_level_id
     * @param $group_id
     * @param $sequence
     * @param $status
     * @param bool $multiArray
     * @return array|string
     */
    public function updateFilesInfo($file_manager_id, $title, $sdesc, $security_level_id, $group_id, $sequence, $status, $multiArray = false)
	{
		//update array
		$update_a = array();

		//if multi array then everything should be passed in an array!
		if($multiArray)
		{
			$out = array();
			foreach($file_manager_id as $key => $_file_manager_id)
			{
				//check if there is anything to update
				if( $this->file_manager_db->fileManagerIdExists($_file_manager_id) )
				{
					$update_a[$_file_manager_id]['file_manager_id'] = $_file_manager_id;
					$update_a[$_file_manager_id]['title'] = empty($title[$key]) ? 'No Title' :  $title[$key];
					$update_a[$_file_manager_id]['sdesc'] = empty($sdesc[$key]) ? 'No Description' :  $sdesc[$key];
					$update_a[$_file_manager_id]['security_level_id'] = empty($security_level_id[$key]) ? 'NONE' :  $security_level_id[$key];
					$update_a[$_file_manager_id]['group_id'] = empty($group_id[$key]) ? 'ALL' :  $group_id[$key];
					$update_a[$_file_manager_id]['sequence'] = empty($sequence[$key]) ? 0 : $sequence[$key];
					$update_a[$_file_manager_id]['status'] = empty($status[$key]) ? 0 :  $status[$key];

				} else {
					$out[$key] = 'No file manager id called ("'.$id.'")';
				}
			}

		} else {

			$out = '';
			//single - update details
			if( $this->file_manager_db->fileManagerIdExists($file_manager_id) )
			{
				$update_a[$file_manager_id]['file_manager_id'] = $file_manager_id;
				$update_a[$file_manager_id]['title'] = empty($title) ? 'No Title' :  $title;
				$update_a[$file_manager_id]['sdesc'] = empty($sdesc) ? 'No Description' :  $sdesc;
				$update_a[$file_manager_id]['security_level_id'] = empty($security_level_id) ? 'NONE' :  $security_level_id;
				$update_a[$file_manager_id]['group_id'] = empty($group_id) ? 'ALL' :  $group_id;
				$update_a[$file_manager_id]['sequence'] = empty($sequence) ? 0 : $sequence;
				$update_a[$file_manager_id]['status'] = empty($status) ? 0 :  $status;

			} else {
				$out = 'No file manager id called ("'.$file_manager_id.'")';
			}
		}


		//commit off
        //the queries is not run until ti commited
		$this->file_manager_db->commitOff();

		foreach($update_a as $value)
		{
		    //the real process updating in database
			$this->_updateFileManagerDetailsOnly($value['file_manager_id'], $value['title'], $value['sdesc'], $value['security_level_id'], $value['group_id'], $value['sequence'], $value['status']);
		}

		//commit on
		$this->file_manager_db->commit();
		$this->file_manager_db->commitOn();

		return $out;
	}

	private function _makeNewFileObj($file_manager_id, $title, $sdesc, $security_level_id, $group_id, $mime_type, $size, $original_name, $original_ext, $sequence, $status)
	{
		//this is the object we are currently working on
		$file_obj = new file_obj;

		//set the variables
		$file_obj->setFileManagerID($file_manager_id);
		$file_obj->setLinkId($this->_link_id);
		$file_obj->setModel($this->_model);
		$file_obj->setModelId($this->_model_id);

		$file_obj->setDir($this->_dir);

		$file_obj->setTitle($title);
		$file_obj->setSDesc($sdesc);
		$file_obj->setSecurityLevelId($security_level_id);
		$file_obj->setGroupId($group_id);
		$file_obj->setMimeType($mime_type);
		$file_obj->setSize($size);
		$file_obj->setOriginalName($original_name);
		$file_obj->setOriginalExt($original_ext);
		$file_obj->setSequence($sequence);
		$file_obj->setStatus($status);

		return $file_obj;
	}

	//is it private or public?
	public function _processNewFileObjects()
	{
		//check if file_obj_a empty so don't do anything
		if(empty($this->_file_obj_a))
		{
			return;
		}

		//turn off commit
		$this->file_manager_db->commitOff();

		//now update the records
		foreach($this->_file_obj_a as $file_manager_id => $file_obj)
		{
			if(!$this->file_manager_db->updateFileManagerAllDetails($file_obj))
			{
				//delete the record just in case
				$this->file_manager_db->deleteFileManagerId($file_manager_id);

				//delete files to make sure it is all cleaned up
				$this->_deleteFile($file_obj->getDir(),$file_manager_id);
			}
		}

		//turn on commit
		$this->file_manager_db->commit();
		$this->file_manager_db->commitOn();

		//reset file_obj_a just in case
		$this->_file_obj_a = array();

		return;
	}

	private function _deleteFile($dir, $file_manager_id, $imagesOnly = false)
	{
		if(empty($dir) || empty($file_manager_id))
		{
			return;
		}

    	$dst_dir = FILE_MANAGER_STORAGE_LOCAL.'/'.$dir;

    	//detlete the files (could be images)
    	if($imagesOnly)
    	{
			$dst_files = $dst_dir.'/'.$file_manager_id.'-*';
		} else {
			$dst_files = $dst_dir.'/'.$file_manager_id.'*';
		}

		array_map('unlink', glob($dst_files));

		//delete the directory if it now empty
		$dst_glob = $dst_dir.'/*';
		if( count( glob($dst_glob) ) == 0)
		{
			rmdir($dst_dir);
		}

		return;
	}

	private function _updateFileManagerDetails($file_manager_id, $title, $sdesc, $security_level_id, $group_id, $sequence, $status, $new_file_a)
	{
		$file_obj = $this->file_manager_db->getFileManagerObj($file_manager_id);

		if(!$file_obj instanceof file_obj)
		{
			$msg = 'File object ("'.$file_manager_id.'") is not an instance of file_obj!';
			throw new \RuntimeException($msg);
		}

		$orig_title = $file_obj->getTitle();
		$orig_sdesc = $file_obj->getSDesc();
		$orig_security_level_id = $file_obj->getSecurityLevelId();
		$orig_group_id = $file_obj->getGroupId();
		$orig_sequence = $file_obj->getSequence();
		$orig_status = $file_obj->getStatus();


		if($title != $orig_title)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'title',$title);
		}

		if($sdesc != $orig_sdesc)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'sdesc',$sdesc);
		}

		if($security_level_id != $orig_security_level_id)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'security_level_id',$security_level_id);
		}

		if($group_id != $orig_group_id)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'group_id',$group_id);
		}

		if($sequence != $orig_sequence)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'sequence',$sequence);
		}

		if($status != $orig_status)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'status',$status);
		}

		if(!empty($new_file_a))
		{
			$orig_dir = $file_obj->getDir();
			$orig_size = $file_obj->getSize();

			if($new_file_a['size'] != $orig_size)
			{
				$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'size',$new_file_a['size']);
			}

			//update the 'orginal' file name
			$orig_original_name = $file_obj->getOriginalName();

			if($new_file_a['original_name'] != $orig_original_name)
			{
				$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'original_name',$new_file_a['original_name']);
			}

			//update the 'orginal' file extension
			$orig_original_ext = $file_obj->getOriginalExt();

			if($new_file_a['original_ext'] != $orig_original_ext)
			{
				$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'original_ext',$new_file_a['original_ext']);
			}

			$orig_mime_type = $file_obj->getMimeType();
			$orig_image = $file_obj->getImage();

			//handle the mime type
			if($new_file_a['mime_type'] != $orig_mime_type)
			{
				$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'mime_type',$new_file_a['mime_type']);
			}

			$file_obj->setMimeType($new_file_a['mime_type']);
			$new_image = $file_obj->getImage();
			$deleteImagesOnly = false;

			if($new_image != $orig_image)
			{
				$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'image',$new_image);
				//find out if the image has changed
				if($orig_image == 1 && $new_image == 0)
				{
					$deleteImagesOnly = true;
				}
			}

			if( $orig_dir == $this->_dir )
			{
				//no update for the file but we need to delete the versions as it is no longer an image
				if($deleteImagesOnly)
				{
					$this->_deleteFile($orig_dir ,$file_manager_id , true);
				}

			} else {

				if($this->_dir != $orig_dir)
				{
					$this->file_manager_db->updateFileManagerItemDetails($_file_manager_id,'dir',$this->_dir);
				}
			}
		}
		return;
	}

	private function _updateFileManagerDetailsOnly($file_manager_id, $title, $sdesc, $security_level_id, $group_id, $sequence, $status)
	{
		$file_obj = $this->file_manager_db->getFileManagerObj($file_manager_id);

		if(!$file_obj instanceof file_obj)
		{
			$msg = 'File object ("'.$file_manager_id.'") is not an instance of file_obj!';
			throw new \RuntimeException($msg);
		}

		$orig_title = $file_obj->getTitle();
		$orig_sdesc = $file_obj->getSDesc();
		$orig_security_level_id = $file_obj->getSecurityLevelId();
		$orig_group_id = $file_obj->getGroupId();
		$orig_sequence = $file_obj->getSequence();
		$orig_status = $file_obj->getStatus();


		if($title != $orig_title)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'title',$title);
		}

		if($sdesc != $orig_sdesc)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'sdesc',$sdesc);
		}

		if($security_level_id != $orig_security_level_id)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'security_level_id',$security_level_id);
		}

		if($group_id != $orig_group_id)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'group_id',$group_id);
		}

		if($sequence != $orig_sequence)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'sequence',$sequence);
		}

		if($status != $orig_status)
		{
			$this->file_manager_db->updateFileManagerItemDetails($file_manager_id,'status',$status);
		}

		return;
	}

	private function _moveUploadedFile($src_file,$mime_type,$file_manager_id)
	{
		$dst_path = FILE_MANAGER_STORAGE_LOCAL.'/'.$this->_dir;

		//make the directory
		if(!is_dir($dst_path))
		{
			if(!mkdir($dst_path,0770,true))
			{
				$msg = "Can not make a storage destination {$dst_path}!";
				throw new \RuntimeException($msg);
			}
		}

		if(is_writable($dst_path))
		{
			$dst = $dst_path.'/'.$file_manager_id;

			if(@move_uploaded_file($src_file,$dst))
			{
					//make sure the file has the right settings
					//@chgrp($dst,'www-data');
					@chmod($dst,0660);

					//if the file is an image then process it
					$mime_type_a = array('image/jpeg','image/gif','image/png','image/vnd.wap.wbmp');

					if( in_array($mime_type,$mime_type_a) )
					{
						$this->_processImages($dst,$mime_type);
					}


			} else {
				$msg = "Destination file {$dst} could not be written!";
				throw new \RuntimeException($msg);
			}
		} else {
			$msg = "Destination path {$dst_path} is NOT writable!";
			throw new \RuntimeException($msg);
		}

		return;
	}

	private function _makeUploadDirectory()
	{
		if(!is_dir($this->_file_new_path))
		{
			if(!mkdir($this->_file_new_path,0770,true))
			{
				$msg = "System error - could not make directory ({$this->_file_new_path}) to save original!";
				$e = new \RuntimeException($msg);

				$html_msg_ns = NS_HTML.'\\htmlmsg';

				$htmlOutput = new $html_msg_ns($e,DEBUG);
				header("HTTP/1.0 500 Internal Error");
				echo $htmlOutput->getHtmlOutput();
			exit();
			}
		}
		return;
	}

   	private function _processImages($src_file,$mime_type)
	{
		$makeImage = new file_manager_image;

		//set up the original image
		if($makeImage->setSourceFile($src_file,$mime_type))
		{
			foreach($this->_image_config_a as $version => $parameter)
			{
				$dst_file = $src_file.'-'.$version;
				$maxX = $parameter['max-width'];
				$maxY = $parameter['max-height'];
				$maxQlty = $parameter['max-quality'];

				if($makeImage->setDestinationFile($dst_file,$mime_type))
				{
					if($makeImage->setParameters($maxX,$maxY,$maxQlty))
					{
						$makeImage->makeImage();
					}
				}
			}
		}

		return;
	}

	private function _setImageDefaults()
	{
	    //load the file manager ini file
	    if(is_file(DIR_SECURE_INI.'/file_manager_images.ini'))
	    {
	    	$this->_image_config_a = parse_ini_file(DIR_SECURE_INI.'/file_manager_images.ini',true);
	    } elseif (is_file(DIR_IOW_APP_INI.'file_manager_images.ini')) {
	    	$this->_image_config_a = parse_ini_file(DIR_APP_INI.'/file_manager_images.ini',true);
	    } else {
	    	$msg = 'The file manger image INI can not be found anywhere!';
	    	throw new \RuntimeException($msg);
	    }
		return;
	}

    /**
     * codeToMessage function.
     *
     * correct at 12 Jan 2015
     *
     * @access private
     * @param integer $code
     * @return string
     */
    private function _errorCodeToMessage($error_code)
    {
        switch ($error_code) {

	        case 0:
	        	$message = "There is no error, the file uploaded with success.";
                break;
            case 1:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case 2:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case 3:
                $message = "The uploaded file was only partially uploaded";
                break;
            case 4:
                $message = "No file was uploaded";
                break;
            case 5:
                $message = "Missing a temporary folder";
                break;
            case 6:
                $message = "Failed to write file to disk";
                break;
            case 7:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

}
?>