<?php

require_once 'main.php';

$output_type = $_GET['t'];
$hash = $_GET['h'];

$acceptable_types_a = array('show');

if( !in_array($output_type,$acceptable_types_a) )
{
    die('That just does not do it for me!');
}

try {


    //load file manager object
    $secure_file_db = new \core\modules\interview\models\common\secure_file();

    $secure_file = $secure_file_db->getSecureFileByHash($hash);

    if(!$secure_file){
        die('Hash invalid');
    }

    if($secure_file && $secure_file['status'] == 0){
        die('Hash not active');
    }

    //checkOwnership needs to allow for thumb
    $address_book_file_id = substr($secure_file['file_id'], 0, 8);

    if($secure_file['type'] == 'ab'){
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $owners = $address_book_db->getAddressBookFileOwners($secure_file['file_id']);
        if(empty($owners) || empty($owners[0])) die('Hmmm .. that is very strange');
        //ok let's get the file and give it to them
        $file_to_output = DIR_LOCAL_UPLOADS.'/address_book/'.$owners[0].'/'.$address_book_file_id;
    }elseif($secure_file['type'] == 'fm'){
        //load file manager object
        $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
        $file_manager = $file_manager_ns::getInstance();

        //should throw error if bad
        $fileInfo_a = $file_manager->getFileInfo($secure_file['file_id']);
        $file_to_output = DIR_LOCAL_UPLOADS.'/file_manager/'.$fileInfo_a['dir'].'/'.$secure_file['file_id'];
    }

    //look up the owner(s) of the file

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