<?php
namespace core\modules\admin\models\config;


/**
 * Final model class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee�20 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'config';
	protected $processPost = true;
	
	private $_system_ini_a;
	private $_site_ini_a;
	
	public function __construct()
	{
		parent::__construct();
		
		//this model uses a number of different functions
		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('config');
		
		//system config
		if(is_file(DIR_SECURE_INI.'/system_config.ini'))
	    {
	    	$this->_system_ini_a = parse_ini_file(DIR_SECURE_INI.'/system_config.ini');     
	    } else {
	    	die('You need to have a local system configuration ini file before you can edit it!');
	    }
	    
	    //site config
	    if(is_file(DIR_SECURE_INI.'/site_config.ini'))
	    {
		    //ini file
	    	$this->_site_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');
	    	$this->_site_ini_a['update_id'] = md5($this->_site_ini_a['SALT'].SITE_WWW);
	    	
	    	/*
		    	echo "<pre>";
		    	print_r($this->_site_ini_a);
		    	echo "</pre>";
		    	die('END');
	    	*/
	    	
	    	//permitted default links
	    	$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
			$menu_register = $menu_register_ns::getInstance();
	    	$this->_all_open_links = $menu_register->getDefaultableLinks();
	    	
	    } else {
	    	die('You need to have a local site configuration ini file before you can edit it!');
	    }
	    
	    //site groups
	    if(is_file(DIR_SECURE_INI.'/site_group_config.ini'))
	    {
	    	$this->_site_group_config_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_group_config.ini',true);     
	    } else {
	    	die('You need to have a local site group config ini file before you can edit it!');
	    }
	    
	    //site users
	    if(is_file(DIR_SECURE_INI.'/site_security_level_config.ini'))
	    {
	    	$this->_site_security_level_config_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_security_level_config.ini',true);     
	    } else {
	    	die('You need to have a local site security level config ini file before you can edit it!');
	    }
	    
	    //site meta
	    if(is_file(DIR_SECURE_INI.'/site_meta.ini'))
	    {
	    	$this->_site_meta_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_meta.ini',true);     
	    } else {
	    	die('You need to have a local site meta ini file before you can edit it!');
	    }
	    
	    //site_scripts
	    if(is_file(DIR_SECURE_FILES.'/site_scripts.txt'))
	    {
	    	$this->_site_scripts = file_get_contents(DIR_SECURE_FILES.'/site_scripts.txt');     
	    } else {
	    	$this->_site_scripts = '';
	    }
	    
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//model stuff
		$this->view_variables_obj->addViewVariables('system_ini_a',$this->_system_ini_a);
		$this->view_variables_obj->addViewVariables('site_ini_a',$this->_site_ini_a);
		$this->view_variables_obj->addViewVariables('all_open_links',$this->_all_open_links);
		$this->view_variables_obj->addViewVariables('site_group_config_ini_a',$this->_site_group_config_ini_a);
		$this->view_variables_obj->addViewVariables('site_security_level_config_ini_a',$this->_site_security_level_config_ini_a);
		$this->view_variables_obj->addViewVariables('site_meta_ini_a',$this->_site_meta_ini_a);
		$this->view_variables_obj->addViewVariables('site_scripts',$this->_site_scripts);
		
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->modelURL);
		
		if($this->input_obj)
		{
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
			
		}

		return;
	}
		
}
?>