<?php
namespace core\modules\address_book\models\common;

/**
 * Final address_book_common_obj class.
 * 
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 December 2016
 */
final class address_book_common_obj {
	
	//js and css index
	private $_index = 500; //should be far enough away from all the others
	
	public function __construct()
	{
		//connect to view variables object and variables need for all views
		$this->view_variables_obj = \core\app\classes\page_view\page_view_variables::getInstance();
		
		//set link to address book db because they all need it to add, modify and delete
		$this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		return;
	}
	
	/* Common to all content */

	public function setTerms($view_base_dir,$contentName)
	{
		$termsFile = DIR_BASE.$view_base_dir.'/'.$contentName.'/'.$contentName.'.terms';
		
		if(is_file($termsFile))
		{
		    if($terms_a = @parse_ini_file($termsFile) )
		    {
			    //get the settings off
			    if( !empty($terms_a) )
			    {
				    foreach($terms_a as $key => $value )
				    {
					    $name = 'term_'.$key;
					    $this->view_variables_obj->addViewVariables($name,$value);
			    	}
				}
			}
	    } 
	    		
		return;
	}
	
	public function setJS($view_base_dir,$contentName)
	{
		$jsHref = $view_base_dir.'/'.$contentName.'/'.$contentName.'.js';
		
		if(is_readable(DIR_BASE.$jsHref))
	    {
	    	$this->view_variables_obj->addFootSrcFile($this->_index,$jsHref);
	    	$this->_index++;
	    } 
	    
	    return;
	}
	
	public function setCSS($view_base_dir,$contentName)
	{
		$cssHref = $view_base_dir.'/'.$contentName.'/'.$contentName.'.css';
		
		if(is_readable(DIR_BASE.$cssHref))
	    {
	    	$this->view_variables_obj->addHeadCSSFile($this->_index,$cssHref.'?'.APP_VERSION);
	    	$this->_index++;
	    } 
	    
	    return;
	}
	
	public function setViewVariables($viewVariables)
	{
	    foreach($viewVariables as $key => $value )
	    {
		    $this->view_variables_obj->addViewVariables($key,$value);
    	}
	    			
		return;
	}
	
	public function setViewSwitches($viewSwitches)
	{
	    foreach($viewSwitches as $flag )
	    {
		    $this->view_variables_obj->$flag();
    	}
	    			
		return;
	}
	
	/* Used by Ajax */
	
	public function checkMainEmail($type,$main_email)
	{
		$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Error', 'message' => 'Their is an error!', 'showAdd' => false, 'sameRequired' => true);
		
		//check if this email is in use
		if(filter_var($main_email, FILTER_VALIDATE_EMAIL))
		{
			//ok now do an mx check on the domain
			list($name,$domain) = explode('@',$main_email);
			if(!checkdnsrr($domain,'MX'))
			{
				$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Bad MX Check', 'message' => 'The main email address is not valid because of mx record.', 'showAdd' => false, 'sameRequired' => false);
				return $out;
			} 
		} else {
			$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Bad Email', 'message' => 'The main email address failed validation.', 'showAdd' => false, 'sameRequired' => false);
			return $out;
		}
		
		//see if the address is associated with an individual user already
		if($per_address_book_id = $this->address_book_db->checkPersonEmail($main_email))
		{
			//if the type is a person then we can not add it
			if($type == 'per')
			{
				$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Existing Person', 'message' => 'The email address is already associated with another person in the address book.', 'showAdd' => false, 'sameRequired' => false);
				return $out;
			} else {
				$out = array('per_address_book_id' => $per_address_book_id, 'level' => 'success', 'heading' => 'Link to Existing Person', 'message' => 'There is an person in the address book with this email address.  The entity will be using the same email address', 'showAdd' => false, 'sameRequired' => false );
				return $out;
			}
		}
		
		//they are not a person in the address book but are they a user?
		$user_common = new \core\modules\user\models\common\user_common;
		$user_db = new \core\modules\user\models\common\user_db;
		
		$user_id = $user_db->checkEmailInUse($main_email);
		
		if($user_id)
		{
			if($type == 'per')
			{
				$out = array('per_address_book_id' => 0, 'level' => 'warning', 'heading' => 'Add Personal Details', 'message' => 'Please add the details for this person.  They are already a user on the system but are not in the address book.', 'showAdd' => false, 'sameRequired' => false );
			} else {
				$out = array('per_address_book_id' => 0, 'level' => 'warning', 'heading' => 'Known User', 'message' => 'This is a users email but there are no details in the address book. Please fill in the Key Contact details.', 'showAdd' => false, 'sameRequired' => true );
			}
			
		} else {
			
			if($type == 'per')
			{
				$out = array('per_address_book_id' => 0, 'level' => 'success', 'heading' => 'New Email', 'message' => 'Please add the details for the person.', 'showAdd' => true, 'sameRequired' => false);
			} else {
				$out = array('per_address_book_id' => 0, 'level' => 'warning', 'heading' => 'Email Not In System', 'message' => 'This email address is new to the system.', 'showAdd' => true, 'sameRequired' => false);
			}
		}
		
		return $out;

	}
	
	public function checkAdminEmail($admin_email)
	{
		$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Error', 'message' => 'Their is an error!', 'showAdd' => false );
		
		//check if this email is in use
		if(filter_var($admin_email, FILTER_VALIDATE_EMAIL))
		{
			//ok now do an mx check on the domain
			list($name,$domain) = explode('@',$admin_email);
			if(!checkdnsrr($domain,'MX'))
			{
				$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Bad MX Check', 'message' => 'The key person email address is not valid because of mx record.', 'showAdd' => false );
				return $out;
			} 
		} else {
			$out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Bad Email', 'message' => 'The key person email address failed validation.', 'showAdd' => false );
			return $out;
		}
		
		//see if the address is associated with an individual user already
		if($per_address_book_id = $this->address_book_db->checkPersonEmail($admin_email))
		{
			//if the type is a person then we can not add it
			$out = array('per_address_book_id' => $per_address_book_id, 'level' => 'success', 'heading' => 'Existing Person', 'message' => 'The key contact is a known person in the address book.', 'showAdd' => false );
			return $out;
		}
		
		//they are not a person in the address book but are they a user?
		$user_common = new \core\modules\user\models\common\user_common;
		$user_db = new \core\modules\user\models\common\user_db;
		
		$user_id = $user_db->checkEmailInUse($admin_email);
		
		if($user_id)
		{
			$out = array('per_address_book_id' => 0, 'level' => 'warning', 'heading' => 'Personal Details Required', 'message' => 'Please add the key contact details.  They are already a user on the system but are not in the address book yet.', 'showAdd' => false );
						
		} else {
			
			$out = array('per_address_book_id' => 0, 'level' => 'warning', 'heading' => 'New Email', 'message' => 'Please add the key contact details.', 'showAdd' => true );
		}
		
		return $out;

	}
	
	public function checkMainData($type,$entity_family_name,$number_given_name,$middle_names,$dob,$sex,$address_book_id,$main_email)
	{
		$runTest = true;
		
		//exclude the address book id if it is set!
		if($address_book_id > 0)
		{
			$currentDataArray = $this->address_book_db->getAddressBookMainDetails($address_book_id);
			
			//check if we have to handle the name change or not
			if($type == 'per')
			{
				if(	$entity_family_name == $currentDataArray['entity_family_name'] && $number_given_name == $currentDataArray['number_given_name'] && $middle_names == $currentDataArray['middle_names'] && $dob == $currentDataArray['dob'] && $sex == $currentDataArray['sex'] && $main_email == $currentDataArray['main_email'])
				{
					$out['heading'] = 'OK';
					$out['message'] = 'There are no changes to the person from what is on file.';
					$out['level'] = 'success';
					
					$runTest = false;
				}
				
				if($main_email != $currentDataArray['main_email'])
				{
					//check if the new email is being used by someone
					$email_address_book_id = $this->address_book_db->checkPersonEmail($main_email);
					
					if($email_address_book_id != $address_book_id)
					{
						$out['heading'] = 'Bad Email';
						$out['message'] = 'The email address can not be used as it is already associate with another person.';
						$out['level'] = 'error';
						
						$runTest = false;
					}
				}
				
			} else {
				if(	$entity_family_name == $currentDataArray['entity_family_name'] && $number_given_name == $currentDataArray['number_given_name'] && $main_email == $currentDataArray['main_email'])
				{
					$out['heading'] = 'OK';
					$out['message'] = 'There are no changes to the organisation from what is on file.';
					$out['level'] = 'success';
					
					$runTest = false;
				}
			}
			
		} else {
			
			if($type == 'per' && !empty($main_email))
			{
				if($this->address_book_db->checkPersonEmail($main_email))
				{
					$out['heading'] = 'Bad Email';
					$out['message'] = 'The email address can not be used as it is already associate with another person.';
					$out['level'] = 'error';
					
					$runTest = false;
				}
			}
		
		}
		
		if($runTest)
		{
			$acceptable_types = array('per','ent');
			
			if(in_array($type, $acceptable_types))
			{
				$out = array();
				
				if($type == 'per')
				{
					//make sure the person given name is provided as we always need it
					if(empty($number_given_name))
					{
						$out['heading'] = 'Stop!';
						$out['message'] = 'The given name for a person can not be blank';
						$out['level'] = 'error';
					} else {
						
						$score = $this->checkPerson($entity_family_name,$number_given_name,$middle_names,$dob,$sex);
							
						switch ($score) 
						{
						    case 0:
						    	$out['heading'] = 'OK';
						        $out['message'] = 'This looks like a unique entry.';
								$out['level'] = 'success';
						        break;
						    case 1:
						    	$out['heading'] = 'Take Care';
						    	$out['message'] = 'This entry is similar to another entry!';
								$out['level'] = 'warning';
						        break;
						    case 2:
						    	$out['heading'] = 'Double Check!';
						    	$out['message'] = 'This entry is almost exactly the same as another entry! Are you sure it is unique?';
								$out['level'] = 'warning';
						        break;
						    case 3:
						    	$out['heading'] = 'Stop!';
						        $out['message'] = 'This is an exact match for another entry! It can not be added.';
								$out['level'] = 'error';
						        break;
						}
					}
				}
				
				if($type == 'ent')
				{
					//make sure the entity name is provided as we always need it
					if(empty($entity_family_name))
					{
						$out['heading'] = 'Stop!';
						$out['message'] = 'The organisation name can not be blank';
						$out['level'] = 'error';
					} else {
						
						$score = $this->checkEntity($entity_family_name,$number_given_name);
						
						switch ($score) 
						{
						    case 0:
						    	$out['heading'] = 'OK';
						        $out['message'] = 'This looks like a unique entry.';
								$out['level'] = 'success';
						        break;
						    case 1:
						    	$out['heading'] = 'Take Care';
						    	$out['message'] = 'This entry is similar to another entry!';
								$out['level'] = 'warning';
						        break;
						    case 2:
						    	 $out['heading'] = 'Double Check!';
						    	$out['message'] = 'This entry is almost exactly the same as another entry! Are you sure it is unique?';
								$out['level'] = 'warning';
						        break;
						    case 3:
						    	$out['heading'] = 'Stop!';
						        $out['message'] = 'This is an exact match for another entry! It can not be added.';
								$out['level'] = 'error';
						        break;
						}
					}				
				}
				
			} else {
				$msg = 'Call to check main address name details but expected variable type is not set or is not valid!';
				throw new \RuntimeException($msg);
			}
		}
		
		return $out;
	}
	
	/* General check function */
	
	public function checkEntity($entity_family_name,$number_given_name)
	{	
		//do checks
		$details_array = $this->address_book_db->getEntityDetails($entity_family_name);
		
		$out = 0;
				
		if(!empty($details_array))
		{
			if(empty($number_given_name)) 
			{
				$out = 3; //there is another entity with this name and no number
			} else {
				
				$score = 0;

				foreach($details_array as $key => $value)
				{
					if( $number_given_name == $value['number_given_name'] )
					{
							$score = 3; //there is another entity with exactly this name and number
					} else {
							$score = 2; //there is a name that is the same but the number and the email are different so should be ok
					}
										
					$out = $score > $out ? $score : $out;
				}
			}
		}
		
		return $out;
	}
	
	public function checkPerson($entity_family_name,$number_given_name,$middle_names,$dob,$sex)
	{	
		//do checks
		$details_array = $this->address_book_db->getPersonDetails($number_given_name);
		
		$out = 0;
				
		if(!empty($details_array))
		{
			$score = 0;
			
			foreach($details_array as $key => $value)
			{
				
				if($entity_family_name == $value['entity_family_name']) $score++;
				if($middle_names == $value['middle_names']) $score++;
				if($dob == $value['dob']) $score++;
				if($sex == $value['sex']) $score++;
				
				if($score > 0) $score--;
									
				$out = $score > $out ? $score : $out;
				
				$score = 0;
			}
		}
		
		return $out;
	}
	
	public function checkEmail($user_email)
	{
		$out = array();
		
		if(!empty($user_common))
		{
			$user_common = new \core\modules\user\models\common\user_common;
			
			if(!$user_common->valueOk('email',$user_email)) 
			{
				$out['user_email'] = 'Is not an acceptable email address.';
			}
		}
				
		return $out;
	}
	
	/* files */
	
    public function storeAddressBookFileData($data,$address_book_id,$makeThumb = false)
	{
		$directory = $this->_checkAddressBookFileDirectory($address_book_id);
		
		if(empty($data))
		{
			$msg = "What is the point! You are trying to save an empty image for address book id {$address_book_id}!";
			throw new \RuntimeException($msg);
		}
		
		$filename = $this->address_book_db->uniqueAddressBookFileName();
		
		//save the file
		$dst_file = $directory.'/'.$filename;
		
		if(!file_put_contents($dst_file, $data))
		{
			$msg = "The file {$filename} could not be saved in the directory {$directory}!";
			throw new \RuntimeException($msg);
		}
		
		if($makeThumb)
		{
			$mime_type = mime_content_type($directory.'/'.$filename);
			
			$mime_type_a = array('image/jpeg','image/gif','image/png','image/vnd.wap.wbmp');
			
			if( in_array($mime_type,$mime_type_a) )
			{
				$this->_processThumbnail($dst_file,$mime_type);
			}
		}
		
		return $filename;
	}
		
	public function deleteAddressBookFile($filename,$address_book_id)
	{
		if(empty($filename))
		{
			$msg = "What is the point! You are trying delete a no name file for address book id {$address_book_id}!";
			throw new \RuntimeException($msg);
		}
		
		if( empty($address_book_id) || $address_book_id < 1 )
		{
			$msg = "Bad Address Book Id! You need an address book id that is real to delete a file ({$address_book_id})!";
			throw new \RuntimeException($msg);
		}
		
		//check the directory
		$file = DIR_LOCAL_UPLOADS.'/address_book/'.$address_book_id.'/'.$filename;
		
		//delete the file
		if(file_exists($file))
		{
			if(!@unlink($file))
			{
				$msg = "The file {$filename} could not deleted!";
				throw new \RuntimeException($msg);
			}
			
			//unlink the thumbnail if it exists
			$thumb = $file.'-thumb';
			
			if(file_exists($thumb))
			{
				if(!@unlink($thumb))
				{
					$msg = "The thumbnail for {$filename} exists but could not deleted!";
					throw new \RuntimeException($msg);
				}
			}
			
		}
		
		return $filename;
	}

	public function checkAddressBookFileExists($address_book_id, $model_code, $model_sub_code)
	{
		if(empty($model_code))
		{
			$msg = "Please specify model code {$address_book_id}!";
			throw new \RuntimeException($msg);
		}

		if(empty($model_sub_code))
		{
			$msg = "Please specify model sub code {$address_book_id}!";
			throw new \RuntimeException($msg);
		}
		
		if( empty($address_book_id) || $address_book_id < 1 )
		{
			$msg = "Bad Address Book Id! You need an address book id that is real to delete a file ({$address_book_id})!";
			throw new \RuntimeException($msg);
		}

		return $this->address_book_db->checkAddressBookFileExists($address_book_id, $model_code, $model_sub_code);
	}
		
	public function storeUploadedFile($address_book_id,$model_code,$sequence,$src_file,$mime_type)
	{
		//source for this is the temp file that was uploaded
		
		//this should match the enum in the address_book_file table
		$acceptable_model_codes = array();
		
		if(!in_array($model_code, $acceptable_model_codes))
		{
			$msg = "The model code {$model_code}. Is not a valide model_code";
			throw new \RuntimeException($msg);
		}
		
		//make a file name that is unique
		$filename = $this->address_book_db->uniqueAddressBookFileName();
		
		//set the destination
		$dst_path = $this->_checkAddressBookFileDirectory($address_book_id);
		$dst = $dst_path.'/'.$filename;
					
		if(@move_uploaded_file($src_file,$dst))
		{
				//make sure the file has the right settings
				//@chgrp($dst,'www-data');
				@chmod($dst,0660);
				
				//if the file is an image then process it
				$mime_type_a = array('image/jpeg','image/gif','image/png','image/vnd.wap.wbmp');
				
				if( in_array($mime_type,$mime_type_a) )
				{
					$this->_processThumbnail($dst,$mime_type);
				}
				
				$affected_rows = $this->address_book_db->insertAddressBookFile($filename,$address_book_id,$model_code,$sequence);
					
				if($affected_rows != 1)
				{
					$msg = "There was a major issue with insertAddressBookFile for address id {$address_book_id}. Affected was {$affected_rows}";
					throw new \RuntimeException($msg);
				}
				
				
		} else {
			$msg = "Destination file {$dst} could not be written!";
			throw new \RuntimeException($msg);
		}
				
		return;
	}
	
	private function _checkAddressBookFileDirectory($address_book_id)
	{
		if( empty($address_book_id) || $address_book_id < 1 )
		{
			$msg = "Bad Address Book Id! You need an address book id not ({$address_book_id})!";
			throw new \RuntimeException($msg);
		}
		
		//check the directory
		//make a folder if one is not there already for this address book 
		$directory = DIR_LOCAL_UPLOADS.'/address_book/'.$address_book_id;
		
		if(!is_dir($directory))
		{
			if(!@mkdir($directory,0770,true))
			{
				$msg = "The address_book {$address_book_id} directory could not be set up!";
				throw new \RuntimeException($msg);
			}
		}
		
		return $directory;
	}

	private function _processThumbnail($src_file,$mime_type)
	{
		$makeImage = new \core\app\classes\file_manager\file_manager_image;
		
		//the source for this is an image that is already in the persons address book files folder
		
		//set up the original image
		if($makeImage->setSourceFile($src_file,$mime_type))
		{
			/*
				I used the settings from the file_manager_images.ini file but I might need to adjust this later
				
				[thumb]
				max-width = 100
				max-height = 100
				max-quality = 60
			*/
			
			$dst_file = $src_file.'-thumb';
			$maxX = 200;
			$maxY = 200;
			$maxQlty = 60;
			
			if($makeImage->setDestinationFile($dst_file,$mime_type))
			{
				if($makeImage->setParameters($maxX,$maxY,$maxQlty))
				{
					if(!$makeImage->makeImage())
					{
						$msg = "Problem Making Image {$src_file}"; 
						throw new \RuntimeException($msg);
					}
				} else {
					$msg = "Bad Parameter ({$mime_type},{$maxX},{$maxY},{$maxQlty})"; 
					throw new \RuntimeException($msg);
				}
			} else {
				$msg = "Bad file ({$src_file},{$dst_file})"; 
				throw new \RuntimeException($msg);
			}
			
		} else {
			$msg = "Could set source file {$src_file}"; 
			throw new \RuntimeException($msg);
		}
		
		return;
	}

	public function getListAddressBookDatatable($params = []){
        return $this->address_book_db->getListAddressBookDatatable($params);
    }

    public function getAddressBookMainDetails($address_book_id){
		$data = $this->address_book_db->getAddressBookMainDetails($address_book_id);
		$data['dob'] = date('d M Y', strtotime($data['dob']));
        $data['file'] = $this->address_book_db->getAddressBookAvatarDetails($address_book_id, 'avatar');
        $data['ent_admin_details'] = $this->address_book_db->getAddressBookAdminLinks($address_book_id);
		
		$address_data = $this->address_book_db->getAddressBookAddressDetails($address_book_id);
		if (!empty($address_data['main']) && !empty($address_data['main']['state']) && !empty($address_data['main']['country']))
		{
			$core_db = new \core\app\classes\core_db\core_db;
			$state_full = $core_db->getSubCountryCodes($address_data['main']['country'])[$address_data['main']['state']];
			$address_data['main']['state'] = $state_full;
		}
		$data['address'] = $address_data;

		$pots_data = $this->address_book_db->getAddressBookPotsDetails($address_book_id);
		// var_dump($pots_data);
		if ( !empty($pots_data) )
		{
			foreach ($pots_data as $key => $pots)
			{
				if (!empty($pots_data[$key]['country']) && !empty($pots_data[$key]['number']) )
				{
					$core_db = new \core\app\classes\core_db\core_db;
					$dialCodes = $core_db->getAllDialCodes();
					$number_ext = '+'.$dialCodes[$pots_data[$key]['country']]['dialCode'];
					$pots_data[$key]['number'] = $number_ext. $pots_data[$key]['number'];
				}
			}
		}
		$data['pots'] = $pots_data;
		
        $data['internet'] = $this->address_book_db->getAddressBookInternetDetails($address_book_id);
        return $data;
    }

    /*
     * Check if email is already in address book
     * if already in ab, check if already link to other entity
     */
    public function checkContactEmail($admin_email)
    {
        $out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Error', 'message' => 'Their is an error!', 'showAdd' => false );

        //check if this email is in use
        if(filter_var($admin_email, FILTER_VALIDATE_EMAIL))
        {
            //ok now do an mx check on the domain
            list($name,$domain) = explode('@',$admin_email);
            if(!checkdnsrr($domain,'MX'))
            {
                $out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Bad MX Check', 'message' => 'The key person email address is not valid because of mx record.', 'showAdd' => false );
                return $out;
            }
        } else {
            $out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Bad Email', 'message' => 'The key person email address failed validation.', 'showAdd' => false );
            return $out;
        }

        //see if the address is associated with an individual user already
        if($per_address_book_id = $this->address_book_db->checkPersonEmail($admin_email))
        {

            //check if ab already linked to entity
            if($address_book_ent_id = $this->address_book_db->checkPersonInEntity($per_address_book_id)){
                $out = array('per_address_book_id' => 0, 'level' => 'error', 'heading' => 'Linked Email', 'message' => 'The key person email address already linked to other entity.', 'showAdd' => false );
                return $out;
            }
            //if the type is a person then we can not add it
            $out = array('per_address_book_id' => $per_address_book_id, 'level' => 'success', 'heading' => 'Existing Person', 'message' => 'The key contact is a known person in the address book. You can link this person to entity.', 'showAdd' => false );
            return $out;
        }

        //they are not a person in the address book but are they a user?
        $user_common = new \core\modules\user\models\common\user_common;
        $user_db = new \core\modules\user\models\common\user_db;

        $user_id = $user_db->checkEmailInUse($admin_email);

        if($user_id)
        {
            $out = array('per_address_book_id' => 0, 'level' => 'success', 'heading' => 'Personal Details Required', 'message' => 'Please add the key contact details.  They are already a user on the system but are not in the address book yet.', 'showAdd' => false );

        } else {

            $out = array('per_address_book_id' => 0, 'level' => 'success', 'heading' => 'New Email', 'message' => 'Please add the key contact details and then it will automatically linked to entity. ', 'showAdd' => true );
        }

        return $out;

    }

    public function linkAddressBookEntity($ent,$per, $type, $security_level_id){
        if(!$this->address_book_db->checkAddressID($ent))
        {
            $msg = "Address book entity not found for id({$ent})";
            throw new \RuntimeException($msg);
        }
        if(!$this->address_book_db->checkAddressID($per))
        {
            $msg = "Address book person not found for id({$ent})";
            throw new \RuntimeException($msg);
        }

        $out = $this->address_book_db->addAddressBookAdminLink($ent, $per, $type, $security_level_id);
        if($out == 1){
            $out = [
                'success' => 1
            ];
        }
        return $out;
    }

    public function deleteAddressBookAdminLink($ent,$per){
        if(!$this->address_book_db->checkAddressID($ent))
        {
            $msg = "Address book entity not found for id({$ent})";
            throw new \RuntimeException($msg);
        }
        if(!$this->address_book_db->checkAddressID($per))
        {
            $msg = "Address book person not found for id({$ent})";
            throw new \RuntimeException($msg);
        }

        $out = $this->address_book_db->deleteAddressBookAdminLink($ent, $per);
        if($out == 1){
            $out = [
                'success' => 1
            ];
        }
        return $out;
    }


    public function addAddressBookAdminLink($ent,$per, $type, $security_level_id){
        if(!$this->address_book_db->checkAddressID($ent))
        {
            $msg = "Address book entity not found for id({$ent})";
            throw new \RuntimeException($msg);
        }
        if(!$this->address_book_db->checkAddressID($per))
        {
            $msg = "Address book person not found for id({$ent})";
            throw new \RuntimeException($msg);
        }


        $out = $this->address_book_db->addAddressBookAdminLink($ent, $per, $type, $security_level_id);
        if($out == 1){
            $out = [
                'success' => 1
            ];
        }
        return $out;
    }


}
?>