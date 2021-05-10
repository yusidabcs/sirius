<?php
namespace core\modules\pages\ajax;

/**
 * Final fileinput class.
 * 
 * Ajax to allow for files to be uploaded
 *
 * Filename make consistent = file
 * return file object if success
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class fileinput extends \core\app\classes\module_base\module_ajax {
		
	public function run()
	{

		if( empty($_FILES) )
		{
            header('Content-Type: application/json; charset=utf-8');
            return json_encode(['error'=>'No files found for upload.']);
		}
		
		$link_id = isset($_POST['link_id']) ? trim($_POST['link_id']) : '';
		$section = isset($_POST['section']) ? trim($_POST['section']) : '';
		$type = (isset($_POST['file_type'])) ? trim($_POST['file_type']) : '';
		
		$entry = isset($_POST['entry']) ? trim($_POST['entry']) : '';
		
		if (!empty($entry)) {
			if (isset($_POST['file_type_'.$entry]) && !empty($_POST['file_type_'.$entry])) {
				$type = trim($_POST['file_type_'.$entry]);
			}
		}
		
		//check the input type is correct
		$acceptable_types_a = array('file','image');
		
		if(!in_array($type, $acceptable_types_a))
		{
            header('Content-Type: application/json; charset=utf-8');
            return json_encode(['error'=>'Please select the file type first.']);
		}
		
		//check section and make content id for file manager
		switch ($section) 
		{
		
			case 'page':
				$content_id = 'page';
				break;
		
			case 'entry':
				if(ctype_digit($entry) && $entry >= 1) 
				{
					$content_id = 'entry-'.$entry;
				} else {
					die('Ooops ... '.$entry.' does not work for me.');
				}
				break;
				
			default:
                header('Content-Type: application/json; charset=utf-8');
                return json_encode(['error'=>'Something errors']);
		
		}

        if(empty($_FILES['file'])){
            header('Content-Type: application/json; charset=utf-8');
            return json_encode(['error'=>'Your file is empty']);
        }

		if( empty($link_id) )
		{
			die('Hmmm .. that does not look right');	
		}
		
		//put all the stuff together
		
		$title = empty($_POST['title']) ? 'No Title' : $_POST['title'] ;
		$sdesc = empty($_POST['sdesc']) ? 'No Description' : $_POST['sdesc'];
		$security_level_id = 'NONE';
		$group_id = 'ALL';
		$status = isset($_POST['status']) ? $_POST['status'] : 0;
		
		$fileUpload_a['name'] = $_FILES['file']['name'];
		$fileUpload_a['type'] = $_FILES['file']['type'];
		$fileUpload_a['tmp_name'] = $_FILES['file']['tmp_name'];
		$fileUpload_a['error'] = $_FILES['file']['error'];
		$fileUpload_a['size'] = $_FILES['file']['size'];
		
		//should be good to go with uploading
		$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		$file_manager = $file_manager_ns::getInstance();

		// print_r($file_manager);
		// exit(0);

		//get latest sequence
        $sequence = $file_manager->file_manager_db->getLatestSequence($link_id,$content_id,$type);

		$file_manager->setLinkInfo($link_id,$content_id,$type);

		$data = $file_manager->addFiles($title, $sdesc, $sequence, $security_level_id, $group_id, $status, $fileUpload_a, false);

		$out['response'] = 'ok';
		$out['data'] = $data['file_obj'];

		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>