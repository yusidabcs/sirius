<?php
namespace iow\app\classes\module_base;
 
/**
 * Abstract module_setup class.
 * 
 * Is the base class for all module setup.
 *
 * @abstract
 * @package 	module_setup
 *�@author		Martin O'Dee�<martin@iow.com.au>
 *�@copyright	Martin O'Dee�21 January 2015
 */

abstract class module_setup {
	
	protected $module;
	
	private $module_config;
	private $log = '';
	
	public function __construct()
	{
		$this->_updateModuleTables();
		return;
	}
	
	public function getLog()
	{
		return $this->log;
	}
	
	//update a module
	private function _updateModuleTables()
	{
		$this->log = '<h4>Updating for Module <span style="color: blue;">'.$this->module.'</span></h4>'."\n";
		
		//set the module config file
		$this->_loadModuleConfig();
		$this->_installTables();
		$this->runSpecialSetup();
		
		return;
	}
	
	private function _loadModuleConfig()
	{
		//DIR_IOW_MODULES
		if( is_file(DIR_MODULES.'/'.$this->module.'/module.ini') ) 
		{
			$this->log .= "<p>Parsing the module ini file</p>\n";
			//DIR_IOW_MODULES
	    	$this->module_config = parse_ini_file(DIR_MODULES.'/'.$this->module.'/module.ini',true);     
	    } else {
	    	$msg = "The {$this->module} module INI file can not be found!";
	    	throw new \RuntimeException($msg); 
	    }
	    return;
	}
	
	private function _installTables()
	{
	    //ok install or update the tables
	    if( isset($this->module_config['tables']) && !empty($this->module_config['tables']) && is_array($this->module_config['tables']) )
	    {
		    //using xml2mysql to install tables
		    $xml2mysql = new \iow\app\classes\xml_mysql\xml2mysql;
		    
		    foreach($this->module_config['tables'] as $table => $db_location)
		    {
			    //sql file
			    $sqlFile = DIR_IOW_APP_SQLXML.'/'.$table.'.xml';
			    
			    if(!is_readable($sqlFile))
			    {
				    $msg = "The module {$this->module} can not install the table {$table} because the xml file ({$sqlFile}) is not readable!";
					throw new \RuntimeException($msg); 
			    }
			    
			    //do the update
			    $xml2mysql->processXmlFile($sqlFile);
			    
			    $sqllog = $xml2mysql->getSqlLog();
			    
			    if(empty($sqllog))
			    {
				    $this->log .= '<p>No Updates for <strong style="color: Purple;">'.$table.'</strong></p>'."\n";
			    } else {
				    $this->log .= '<p>Updating Table <span style="color: YellowGreen;">'.$table.'</span></p>'."\n";
				    $this->log .= "<pre>\n".$sqllog."</pre>\n\n";
				    unset($sqllog);
			    }
		    }
	    }
	    return;
    }
	
	protected function runSpecialSetup()
	{
		$this->log .= '<p>No <span style="color: Green;">Special Setup</span> Found</p>'."\n";
		return;
	}

}
?>