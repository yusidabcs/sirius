<?php
namespace core\modules\admin\models\home;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'home';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		$this->authorize();
		$log = '';
		
		//!ini
		
		if( isset($_POST['action']) && $_POST['action'] == 'ini_sync')
		{
			//need to write INI
			$log = '<strong>INI Update ...</strong><br>';
			$syncIniFiles_ns = NS_APP_CLASSES.'\\ini\\sync_ini';
			$syncIniFiles = new $syncIniFiles_ns();
			$log .= $syncIniFiles->sync_ini_files();
		}
		
		//!DB Update
		
		//updating the tables 
		if( isset($_POST['action']) && $_POST['action'] == 'db_update')
		{
			$log = '<strong>Updating all DB Files Log ...</strong><br>';
			$update_ns = NS_APP_CLASSES.'\\db_update\\db_update';
			//$update = $update_ns::getInstance();
			
			//get the modules
		    if(is_file(DIR_SECURE_INI.'/site_module_config.ini')) 
		    {
				$update_ns = NS_APP_CLASSES.'\\db_update\\db_update';
				$update = new $update_ns();
				
			    //update fileman
			    $log .= "<h4>Updating System Tables</h4>\n";
				$log .= "<p>\n";
			    $log .= $update->updateAPPTables();
			    $log .= '</p>';
			    
			    //update the specific tables for this site based on modules
		    	$site_modules_info_a = parse_ini_file(DIR_SECURE_INI.'/site_module_config.ini',true); 
		    	$site_modules_a = array_keys($site_modules_info_a);
		    	
		    	foreach($site_modules_a as $module)
		    	{
			    	//site_down, admin and admin don't have setup files
			    	if($module == 'site_down' || $module == 'admin') continue;
				    
					$log .= $update->updateModuleTables($module);
		    	}
			    
		    } else {
		    	$log .= '<style="red">No local site_module_config.ini file found</style><p>'."\n";
		    }
		}
		
		//!file manager
				
		if( isset($_POST['action']) && $_POST['action'] == 'fm_update_storage')
		{
			$log = '<strong>File Manager Update Storage Log ...</strong><br>';
			$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
			$file_manager = $file_manager_ns::getInstance();
			$log .= $file_manager->setupStorage();
		}
		
		if( isset($_POST['action']) && $_POST['action'] == 'fm_clean')
		{
			$log = '<strong>File Manager Cleaning Log ...</strong><br>';
			$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
			$file_manager = $file_manager_ns::getInstance();
			$log .= $file_manager->cleanFileManager();
		}
		
		//!clear caches
		
		if( isset($_POST['action']) && $_POST['action'] == 'clear_caches')
		{
			$log = '<strong>Clearing all caches ...</strong><br>';
			$log .= system('rm '.DIR_SECURE_CACHE.'/*',$retval);
			$log .= '<p>Delete Return Value: '.$retval."</p>";
		}
			
		$this->addInput('log',$log);
		return;
	}
}
?>