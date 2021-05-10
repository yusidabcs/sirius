<?php
namespace core\app\classes\ini;

final class sync_ini
{
	
	private $_write_ini; //object for saving ini files
	
	public function __construct()
	{
		//needed to process INI files
		$write_ini_ns = NS_APP_CLASSES.'\\ini\\write_ini';
		$this->_write_ini = new $write_ini_ns();
		return;
	}
	
	public function sync_ini_files()
	{
		//check DIR_SECURE_INI is writeable
		if(is_writable(DIR_SECURE_INI))
		{
			$log = '';
			$log .= $this->_normalSync('site_config.ini',false);
			$log .= $this->_normalSync('site_group_config.ini',true);
			$log .= $this->_normalSync('site_module_config.ini',true);
			$log .= $this->_normalSync('site_interface.ini',true);
			$log .= $this->_defaultsSync();
			$log .= $this->_normalSync('site_security_level_config.ini',true);
			$log .= $this->_sourceSync('site_term.ini',true);
			$log .= $this->_normalSync('system_config.ini',false);
			$log .= $this->_normalSync('file_manager_images.ini',true);
			$log .= $this->_normalSync('site_meta.ini',true);
		} else {
			$msg = "The secure INI directory is not writable!";
			throw new \RuntimeException($msg); 
		}
	    return $log;
	}
	
	private function _normalSync($filename,$multi)
	{
		$new_ini_a = array();
		
		$origin_ini_file = DIR_APP_INI.'/'.$filename;
		$secure_ini_file = DIR_SECURE_INI.'/'.$filename;
		
		if(!is_readable($origin_ini_file))
		{
			$msg = "The original INI file {$origin_ini_file} is not readable!";
			throw new \RuntimeException($msg); 
		}
		
		if(is_file($secure_ini_file) && !is_writable($secure_ini_file))
		{
			$msg = "The destination INI file {$secure_ini_file} is not writable!";
			throw new \RuntimeException($msg); 
		}
		
		//make sure the secure file is writable
		if(is_readable($secure_ini_file))
		{
			
			$orig_ini_a = parse_ini_file($origin_ini_file,$multi);
			$dest_ini_a = parse_ini_file($secure_ini_file,$multi);
			
			if($multi)
			{
				//go through the original ini file
				foreach($orig_ini_a as $key => $array)
				{
					//if the destination file contains the same key
					if(is_array($dest_ini_a[$key]))
					{
						$merge_ini_a[$key] = array_merge($orig_ini_a[$key],$dest_ini_a[$key]);
						unset($dest_ini_a[$key]);
					} else { //it is a new key and not in the destination (local) file
						$merge_ini_a[$key] = $orig_ini_a[$key];
					}
				}
				
				$new_ini_a = array_merge($merge_ini_a,$dest_ini_a);
				
			} else {
				//join them to make sure all the keys are there
				$new_ini_a = $dest_ini_a + $orig_ini_a;
			}
			
			$this->_write_ini->write_php_ini($new_ini_a, $secure_ini_file);
			
		} else {
			
			$orig_ini_a = parse_ini_file($origin_ini_file,$multi);
			$this->_write_ini->write_php_ini($orig_ini_a, $secure_ini_file);
		}
		
		$log = 'Updated '.$filename."<br>\n";
		return $log;
	}
	
	private function _defaultsSync()
	{
		//set variable
		$local_default_module_file = DIR_SECURE_INI.'/site_module_local_defaults.ini';
		$all_defaults = array();
		$new_local_defaults =  array();
		
		//load the site ini file
	    if(is_file(DIR_SECURE_INI.'/site_module_config.ini'))
	    {
	    	$a_site_module_config = parse_ini_file(DIR_SECURE_INI.'/site_module_config.ini',true);     
	    } elseif (is_file(DIR_APP_INI.'/site_module_config.ini')) {
	    	$a_site_module_config = parse_ini_file(DIR_APP_INI.'/site_module_config.ini',true);     
	    } else {
	    	$msg = 'The INI file site_module_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
	    
	    //load the local defaults that overwrite the preset defaults
    	if(is_file($local_default_module_file))
	    {
	    	$overwrite_defaults = parse_ini_file($local_default_module_file,true);
	    }
	    
		//key sort 
	    ksort($a_site_module_config);
	    
	    //load the system defaults for each module
    	foreach ($a_site_module_config as $moduleName => $info)
    	{
			if(is_file(DIR_MODULES.'/'.$moduleName.'/defaults.ini')) {
				
		    	$module_defaults = parse_ini_file(DIR_MODULES.'/'.$moduleName.'/defaults.ini',true);
		    	
		    	foreach($module_defaults as $default_name => $default_info)
		    	{
			    			    	
			    	$all_defaults[$moduleName][$default_name]['default'] = $default_info['default'];
			    	
			    	if(isset($overwrite_defaults[$default_name]))
			    	{
				    	$new_local_defaults[$default_name] = $overwrite_defaults[$default_name];
			    	} else {
				    	$new_local_defaults[$default_name] = $default_info['default'];
			    	}
			    	
			    	//unset options
			    	unset($options);
		    	}   
		    } 
	    }

		$this->_write_ini->write_php_ini($new_local_defaults, $local_default_module_file);
				
		$log = "Updated site_module_local_defaults.ini<br>\n";
		return $log;
	}
	
	private function _sourceSync($filename,$multi)
	{
		$origin_ini_file = DIR_APP_INI.'/'.$filename;
		$secure_ini_file = DIR_SECURE_INI.'/'.$filename;
		
		if(!is_readable($origin_ini_file))
		{
			$msg = "The original INI file {$origin_ini_file} is not readable!";
			throw new \RuntimeException($msg); 
		}
		
		if(is_file($secure_ini_file) && !is_writable($secure_ini_file))
		{
			$msg = "The destination INI file {$secure_ini_file} is not writable!";
			throw new \RuntimeException($msg); 
		}
		
		//make sure the secure file is writable
		if(!is_readable($secure_ini_file))
		{
			$orig_ini_a = parse_ini_file($origin_ini_file,$multi);
			$this->_write_ini->write_php_ini($orig_ini_a, $secure_ini_file);
			
		} else {
			
			$orig_ini_a = parse_ini_file($origin_ini_file,$multi);
			$dest_ini_a = parse_ini_file($secure_ini_file,$multi);

			if($multi)
			{
				foreach($orig_ini_a as $key => $array)
				{
					$source_array = $orig_ini_a[$key];
					
					//pull out the keys you don't want
					if(isset($dest_ini_a[$key]))
					{
						//we can only talk out keys if there is something to compare too
						$compare_array = $dest_ini_a[$key];
						$destination_array = $this->_removeUnusedKeys($source_array,$compare_array);
						$new_ini_a[$key] = array_merge($source_array,$destination_array);
					} else {
						$new_ini_a[$key] = $source_array;
					}
				}
				
			} else {
				$destination_array = $this->_removeUnusedKeys($orig_ini_a,$dest_ini_a);
				$new_ini_a = array_merge($orig_ini_a,$destination_array);
			}

			$this->_write_ini->write_php_ini($new_ini_a, $secure_ini_file);
		}
		
		$log = 'Updated '.$filename."<br>\n";
		return $log;
	}
	
	/*
		NO SEEN WHERE THESE ARE USED
	*/
	
	public function sync_menu_ini()
	{
		//check DIR_SECURE_INI is writeable
		if(is_writable(DIR_SECURE_INI))
		{
			$log = '';
			$log .= $this->normalSync('menu.ini',true);
		} else {
			$msg = "The secure INI directory is not writable!";
			throw new \RuntimeException($msg); 
		}
	    return $log;
	}

	private function _overwriteSync($filename,$multi)
	{
		$origin_ini_file = DIR_APP_INI.'/'.$filename;
		$secure_ini_file = DIR_SECURE_INI.'/'.$filename;
		
		if(!is_readable($origin_ini_file))
		{
			$msg = "The original INI file {$origin_ini_file} is not readable!";
			throw new \RuntimeException($msg); 
		}
		
		if(is_file($secure_ini_file) && !is_writable($secure_ini_file))
		{
			$msg = "The destination INI file {$secure_ini_file} is not writable!";
			throw new \RuntimeException($msg); 
		}
		
		$orig_ini_a = parse_ini_file($origin_ini_file,$multi);
		$this->_write_ini->write_php_ini($orig_ini_a, $secure_ini_file);
			
		$log = 'Updated '.$filename."<br>\n";
		return $log;
	}
	
	/**
	 * removeUnusedKeys function.
	 * 
	 * Delete keys form destination array that do not exist in source
	 * 
	 * @access private
	 * @param mixed $source_array
	 * @param mixed $compare_array
	 * @return void
	 */
	private function _removeUnusedKeys($source_array,$compare_array)
	{
		foreach($source_array as $key => $value)
		{
			if(isset($compare_array[$key]))
			{
				$new_array[$key] = $compare_array[$key];
			}
		}
		return $new_array;
	}
	
}	
?>