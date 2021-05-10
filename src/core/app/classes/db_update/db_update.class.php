<?php
namespace core\app\classes\db_update;

/**
 * Final db_update class.
 * 
 * This is a class that takes all the approate xml files and updates the sql tables
 *
 * @final
 * @package 	db_update
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 August 2019
 */
final class db_update {
	
	private $_xml2mysql; //script to update sql from xml

	public function __construct()
	{	
		//using xml2mysql to install tables
	    $xml2mysql_ns = NS_APP_CLASSES.'\\xml_mysql\\xml2mysql';
	    $this->_xml2mysql = new $xml2mysql_ns();
		
		return;
	}
	
	public function updateAPPTables()
	{
		$app_tables_a = array('file_manager');
	
	    foreach($app_tables_a as $table)
	    {
		    //sql file
		    $sqlFile = DIR_APP_SQLXML.'/'.$table.'.xml';
		    
		    $sqllog = $this->_runXML2SQL($sqlFile);
		   
		    if(empty($sqllog))
		    {
			    $log = '<p>No Updates for <strong style="color: Purple;">'.$table.'</strong></p>'."\n";
		    } else {
			    $log = '<p>Updating Table <span style="color: YellowGreen;">'.$table.'</span></p>'."\n";
			    $log .= "<pre>\n".$sqllog."</pre>\n\n";
			    unset($sqllog);
		    }
	    }
	    
	    return $log;
    }
	
	public function updateModuleTables($module)
	{
		$log = '';
		
		if( is_file(DIR_MODULES.'/'.$module.'/module.ini') ) 
		{
			$module_config = parse_ini_file(DIR_MODULES.'/'.$module.'/module.ini',true);     
	    } else {
	    	$msg = "The {$module} module INI file can not be found!";
	    	throw new \RuntimeException($msg); 
	    }
	    
	    //ok install or update the tables
	    if( isset($module_config['tables']) && !empty($module_config['tables']) && is_array($module_config['tables']) )
	    {
		    foreach($module_config['tables'] as $table => $db_location)
		    {
			    //sql file
			    $sqlFile = DIR_APP_SQLXML.'/'.$table.'.xml';
			    
			    $sqllog = $this->_runXML2SQL($sqlFile);
			   
			    if(empty($sqllog))
			    {
				    $log .= '<p>No Updates for <strong style="color: Purple;">'.$table.'</strong></p>'."\n";
			    } else {
				    $log .= '<p>Updating Table <span style="color: YellowGreen;">'.$table.'</span></p>'."\n";
				    $log .= "<pre>\n".$sqllog."</pre>\n\n";
				    unset($sqllog);
			    }
		    }
	    } else {
		    $log .= '<p>No Tables found for Module <span style="color: red;">'.$module."</span></p>\n";
	    }
	    return $log;
    }
    
    private function _runXML2SQL($sqlFile)
    {
	    if(!is_readable($sqlFile))
	    {
		    $msg = "The table xml file ({$sqlFile}) is not readable!";
			throw new \RuntimeException($msg); 
	    }
			    
	    $this->_xml2mysql->processXmlFile($sqlFile);
		$sqllog = $this->_xml2mysql->getSqlLog();
	    return $sqllog;
    }
    
}
?>