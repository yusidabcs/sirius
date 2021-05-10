<?php
namespace core\modules\pages\ajax;

/**
 * Final fileinput class.
 * 
 * Ajax to allow for files to be uploaded
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class fileupdate extends \core\app\classes\module_base\module_ajax {
		
	public function run()
	{
		//set Link id
		$link_id = isset($_POST['link_id']) ? $_POST['link_id'] : '';

		if( empty($link_id) )
		{
			die('Hmmm .. that does not look right');
		} else {
			//need menu to check
			$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
			$menu_register = $menu_register_ns::getInstance();
			
			$security_level_id = $menu_register->getLinkSecurityLevelId($link_id);
			$group_id = $menu_register->getLinkGroupId($link_id);
		}

		//array for the updates
		$update_a = array();

		//need to convert the post to something almost useful
		if(!empty($_POST))
		{
            //build update array
            $update_a['file_manager_id'][] = $_POST['file_id'];
            $update_a['title'][] = empty($_POST['title']) ? 'No Title' :  $_POST['title'];
            $update_a['sdesc'][] = empty($_POST['sdesc']) ? 'No Description' : $_POST['sdesc'];
            $update_a['security_level_id'][] = empty($security_level_id) ? 'NONE' :  $security_level_id;
            $update_a['group_id'][] = empty($group_id) ? 'ALL' :  $group_id;
            $update_a['sequence'][] = $_POST['sequence'];
            $update_a['status'][] = $_POST['status'];

		} else {
			die('Strange you even got to this point but what to do!');
		}
		
		if(!empty($insert_a))
		{
			//sanity check
			if( count($insert_a['title']) != count($fileUpload_a['error']) || count($insert_a['title']) != count($fileUpload_a['tmp_name']) )
			{
				die('Error ... bad file count!');
			}
		}
		
		//should be good to go with uploading
		$file_manager = \core\app\classes\file_manager\file_manager::getInstance();
		$file_manager->setLinkInfo($link_id);
		
		if(!empty($update_a))
		{
			//update every variable is in an array even if there is only one item
			$file_manager->updateFilesInfo(
				$update_a['file_manager_id'], 
				$update_a['title'], 
				$update_a['sdesc'], 
				$update_a['security_level_id'], 
				$update_a['group_id'], 
				$update_a['sequence'], 
				$update_a['status'], 
				true
			);
		}
		
		$out['success'] = true;
		$out['message'] = 'Successfully update file.';

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