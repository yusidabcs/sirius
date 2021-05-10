<?php

	require_once 'main.php';
	
	$output_type = $_GET['t'];
	$file_id = $_GET['f'];
	$output_file_name = isset($_GET['n']) ? $_GET['n'] : '';
	
	$acceptable_types_a = array('show');
	
	if( !in_array($output_type,$acceptable_types_a) ) 
	{
		die('That just does not do it for me!');
	}
	
	try {
		
		
		//load file manager object
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		//checkOwnership needs to allow for thumb
		$address_book_file_id = substr($file_id, 0, 8);
		
		//look up the owner(s) of the file
		$owners = $address_book_db->getAddressBookPublicFileOwners($address_book_file_id);
		
		if(empty($owners) || empty($owners[0])) die('Hmmm .. that is very strange');
				
		//ok let's get the file and give it to them
		$file_to_output = DIR_LOCAL_UPLOADS.'/address_book/'.$owners[0].'/'.$file_id;
		
		//mime type object
		$mime_type_obj = \core\app\classes\mime_type\mime_type::getInstance();
		
		$file_mime_type = mime_content_type($file_to_output);
		
		$file_type_ext = $mime_type_obj->getExtFromMimeType($file_mime_type);
		
		//output the file header
		header("Content-Type: $file_mime_type");
		
		readfile($file_to_output);
		
	} catch (Exception $e) {
        //process the error
        $htmlOutput = new \core\app\classes\html\htmlmsg($e,DEBUG);
        header("HTTP/1.0 500 Internal Error");
        echo $htmlOutput->getHtmlOutput();
        exit();
    }
    
?>