<?php
namespace core\modules\admin\models\config;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'config';
	
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
		if( !empty($_POST['action']) )
		{
			switch ($_POST['action']) 
			{
			    case 'update_system_config':
			        $this->_update_system_config();
			        break;
			        
			    case 'update_site_config':
			        $this->_update_site_config();
			        break;
			        
			    case 'update_site_group':
			        $this->_update_site_group();
			        break;
			        
			    case 'update_site_meta':
			        $this->_update_site_meta();
			        break;

				case 'update_site_scripts':
			        $this->_update_site_scripts();
			        break;
			        
			    default:
			       die("Don't do anything yet!");
			}
		} else {
			die("Don't act yet!");
		}
		
		//if you get to here then you need to delete the systemRegister.cache file
		unlink( DIR_SECURE_CACHE.'/systemRegister.cache' );
		
		return;
	}
	
	private function _update_system_config()
	{
		//check data
		$update_array['DEBUG'] = isset($_POST['DEBUG']) ? $_POST['DEBUG'] : 0;
		$update_array['PAGINATION_NUMBER'] = $_POST['PAGINATION_NUMBER'] > 1 ? $_POST['PAGINATION_NUMBER'] : 0;
		$update_array['SYSADMIN_NAME'] = empty($_POST['SYSADMIN_NAME']) ? 'IOW System Administrator': $_POST['SYSADMIN_NAME'];
		$update_array['SYSADMIN_EMAIL'] = empty($_POST['SYSADMIN_EMAIL']) ? 'sysadmin@iow.com.au' : $_POST['SYSADMIN_EMAIL'];
		$update_array['SYSADMIN_BCC_NEW_USERS'] = isset($_POST['SYSADMIN_BCC_NEW_USERS']) ? $_POST['SYSADMIN_BCC_NEW_USERS'] : 0;
		$update_array['WEBSERVER_EMAIL'] = empty($_POST['WEBSERVER_EMAIL']) ? 'sysadmin@iow.com.au' : $_POST['WEBSERVER_EMAIL'];
		$update_array['BYPASS_USER_PROCESS'] = empty($_POST['BYPASS_USER_PROCESS']) ? 0 : $_POST['BYPASS_USER_PROCESS'];
		$update_array['REFERENCE_REPLY_TO'] = empty($_POST['REFERENCE_REPLY_TO']) ? '' : $_POST['REFERENCE_REPLY_TO'];
		
		//system config
		$iniFile = DIR_SECURE_INI.'/system_config.ini';
		
		$writeIni_ns = NS_APP_CLASSES.'\\ini\\write_ini';
		$writeIni = new $writeIni_ns();
		$writeIni->write_php_ini($update_array, $iniFile);
		
		return;
	}
	
	private function _update_site_config()
	{		
		//check data
		$update_array['USERNAME'] = empty($_POST['USERNAME']) ? 'sysadmin' : $_POST['USERNAME'];

		$update_array['SITE_TITLE'] = empty($_POST['SITE_TITLE']) ? 'SITE_TITLE' : $_POST['SITE_TITLE'];
		$update_array['SITE_SLOGAN'] = empty($_POST['SITE_SLOGAN']) ? 'SITE_SLOGAN' : $_POST['SITE_SLOGAN'];
		$update_array['CLIENT_NAME'] = empty($_POST['CLIENT_NAME']) ? 'SITE_TITLE' : $_POST['CLIENT_NAME'];
		$update_array['SITE_EMAIL_ADD'] = empty($_POST['SITE_EMAIL_ADD']) ? 'SITE_EMAIL_ADD' : $_POST['SITE_EMAIL_ADD'];
		$update_array['SITE_EMAIL_NAME'] = empty($_POST['SITE_EMAIL_NAME']) ? 'SITE_EMAIL_NAME' : $_POST['SITE_EMAIL_NAME'];
		$update_array['SITE_EMAIL_SUBJECT'] = empty($_POST['SITE_EMAIL_SUBJECT']) ? 'SITE_EMAIL_SUBJECT' : $_POST['SITE_EMAIL_SUBJECT'];
		$update_array['SITE_TAG_MANAGER'] = empty($_POST['SITE_TAG_MANAGER']) ? '' : $_POST['SITE_TAG_MANAGER'];
		$update_array['SITE_RECAPTCHA_KEY'] = empty($_POST['SITE_RECAPTCHA_KEY']) ? '' : $_POST['SITE_RECAPTCHA_KEY'];
		$update_array['SITE_RECAPTCHA_SECRET'] = empty($_POST['SITE_RECAPTCHA_SECRET']) ? '' : $_POST['SITE_RECAPTCHA_SECRET'];
		$update_array['SEARCH_SUBMIT'] = isset($_POST['SEARCH_SUBMIT']) ? $_POST['SEARCH_SUBMIT'] : 0;
		$update_array['SITE_DOWN'] = isset($_POST['SITE_DOWN']) ? $_POST['SITE_DOWN'] : 0;

        $update_array['SITE_EMAIL_SMTP'] = empty($_POST['SITE_EMAIL_SMTP']) ? '' : $_POST['SITE_EMAIL_SMTP'];
        $update_array['SITE_EMAIL_SMTP_USERNAME'] = empty($_POST['SITE_EMAIL_SMTP_USERNAME']) ? '' : $_POST['SITE_EMAIL_SMTP_USERNAME'];
        $update_array['SITE_EMAIL_SMTP_PASSWORD'] = empty($_POST['SITE_EMAIL_SMTP_PASSWORD']) ? '' : $_POST['SITE_EMAIL_SMTP_PASSWORD'];
        $update_array['SITE_EMAIL_SMTP_PORT'] = empty($_POST['SITE_EMAIL_SMTP_PORT']) ? '' : $_POST['SITE_EMAIL_SMTP_PORT'];

		$update_array['LINK_DEFAULT'] = empty($_POST['LINK_DEFAULT']) ? 'security' : $_POST['LINK_DEFAULT'];
		
		//site config
		$iniFile = DIR_SECURE_INI.'/site_config.ini';
		$site_ini_a = parse_ini_file($iniFile);
	    
	    //update Password if SET
		if(!empty($_POST['site_config_new_password']))
		{
			$new = $_POST['site_config_new_password'];
			$new_password = md5($new.$site_ini_a['SALT']);
			$update_array['PASSWORD'] = $new_password;
		}
	    
	    //update the current values
	    foreach($site_ini_a as $key => $value)
	    {
		    if(isset($update_array[$key]))
		    {
			    $site_ini_a[$key] = $update_array[$key];
		    }
	    }
	    
	    //inject missing ones
	    foreach($update_array as $key => $value)
	    {
		    if(!isset($site_ini_a[$key]))
		    {
			    $site_ini_a[$key] = $value;
		    }
	    }
	    
	    $writeIni_ns = NS_APP_CLASSES.'\\ini\\write_ini';
		$writeIni = new $writeIni_ns();
		$writeIni->write_php_ini($site_ini_a, $iniFile);
			
		return;
	}
	
	private function _update_site_group()
	{
		$code = strtoupper($_POST['site_group_code']);
		$title = $_POST['site_group_title'];
		$desc = $_POST['site_group_desc'];
		$members = strtoupper($_POST['site_group_members']);
		
		if(empty($code) || empty($members))
		{
			die('You need to have a code and a member set to do anything!');
			return;
		}
		
		if($code == 'ALL' )
		{
			die('No they are fixed!');
			return;
		}
		
		$iniFile = DIR_SECURE_INI.'/site_group_config.ini';
		$site_group_ini_a = parse_ini_file($iniFile,true);
		
		if(empty($title))
		{
			//delete it if there is no title
			unset($site_group_ini_a[$code]);
		} else {
			//add or update it if there is an existing key of the same code
			$site_group_ini_a[$code]['title'] = $title;
			$site_group_ini_a[$code]['desc'] = $desc;
			$site_group_ini_a[$code]['members'] = $members;
		}
		
		$writeIni_ns = NS_APP_CLASSES.'\\ini\\write_ini';
		$writeIni = new $writeIni_ns();
		$writeIni->write_php_ini($site_group_ini_a, $iniFile);
		
		return;
	}
	
	private function _update_site_meta()
	{
		$iniFile = DIR_SECURE_INI.'/site_meta.ini';
		
		//clean up charset
		if(empty($_POST['charset']['charset']))
		{
			$charset['charset'] = 'UTF-8';
		} else {
			$charset['charset'] = $_POST['charset']['charset'];
		}
		
		//clean up name
		foreach($_POST['name'] as $item_name => $item_content)
		{
			if($item_content)
			{
				$name[$item_name] = $item_content;
			}
		}
		
		//clean up http-equiv
		if(empty($_POST['http-equiv']))
		{
			$http_eqiv = array();
		} else {
			foreach($_POST['http-equiv'] as $item_name => $item_content)
			{
				if($item_content)
				{
					$http_eqiv[$item_name] = $item_content;
				}
			}
		}
		
		$site_meta_ini_a['charset'] = $charset;
		$site_meta_ini_a['name'] = $name;
		
		if(empty($http_eqiv))
		{
			$site_meta_ini_a['http-equiv'] = array();
		} else {
			$site_meta_ini_a['http-equiv'] = $http_eqiv;
		}
		
		$writeIni_ns = NS_APP_CLASSES.'\\ini\\write_ini';
		$writeIni = new $writeIni_ns();
		$writeIni->write_php_ini($site_meta_ini_a, $iniFile);
		
		return;
	}
	
	private function _update_site_scripts()
	{
		if(!empty($_POST['site_scripts']))
		{
			file_put_contents(DIR_SECURE_FILES.'/site_scripts.txt',$this->orig_post['site_scripts']);
		} else {
			unlink(DIR_SECURE_FILES.'/site_scripts.txt');
		}
		return;
	}
		
}
?>