<?php

	require_once 'main.php';
	
	$output_type = $_GET['t'];
	$file_id = $_GET['f'];
	$output_file_name = isset($_GET['n']) ? $_GET['n'] : '';
	
	$acceptable_types_a = array('show','download');
	
	if( !in_array($output_type,$acceptable_types_a) ) 
	{
		die('That just does not do it for me!');
	}
	
	try {
		
		//start the session
		session_start();
		
		//security - they must be logged in to access any files in address book
		if(empty($_SESSION)) die('Bad Access Attempt: Log in');
		
		//do this just in case someone every changes the numbers
		$system_register = \core\app\classes\system_register\system_register::getInstance();
		$site_admin_security_level = $system_register->getSecurityLevel('STAFF');
		
		//load file manager object
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		//checkOwnership needs to allow for thumb
		$address_book_file_id = substr($file_id, 0, 8);
		
		//look up the owner(s) of the file
		$owners = $address_book_db->getAddressBookFileOwners($address_book_file_id);
		
		if(empty($owners) || empty($owners[0])) die('Hmmm .. that is very strange');
		
		//if the person has site system admin level or higher then no problem
		if($_SESSION['user_security_level'] < $site_admin_security_level)
		{	
			if( !in_array($_SESSION['personal']['address_book_id'], $owners ) && !in_array($_SESSION['address_book_id'], $owners ) )
			{
				//check if the security level is high enough
				die('Sorry but you do not have the security access to get this file.');
			}	
		}
				
		//ok let's get the file and give it to them
		$file_to_output = DIR_LOCAL_UPLOADS.'/address_book/'.$owners[0].'/'.$file_id;
		
		//mime type object
		$mime_type_obj = \core\app\classes\mime_type\mime_type::getInstance();
		
		$file_mime_type = mime_content_type($file_to_output);
		
		$file_type_ext = $mime_type_obj->getExtFromMimeType($file_mime_type);
		
		//output the file header
		header("Content-Type: $file_mime_type");
		
		if($output_type == 'download')
		{
			//if we have been given a name then just use it
			if(empty($output_file_name))
			{
				//set the full file_name
				$full_file_name = $file_id.'.'.$file_type_ext;
			} else {
				//make sure whatever they gave us is safe
				$generic_obj = \core\app\classes\generic\generic::getInstance();
				$output_file_name = $generic_obj->safeFilename($output_file_name);
				$full_file_name = $output_file_name.'.'.$file_type_ext ;
			}
			
			header("Content-Disposition: attachment; filename=\"$full_file_name\"");
		}
		
		// The PDF source is in original.pdf
		readfile($file_to_output);
		
	} catch (Exception $e) {
        //process the error
        $htmlOutput = new \core\app\classes\html\htmlmsg($e,DEBUG);
        header("HTTP/1.0 500 Internal Error");
        echo $htmlOutput->getHtmlOutput();
        exit();
    }
    
?>