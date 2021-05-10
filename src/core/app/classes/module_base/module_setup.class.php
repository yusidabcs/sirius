<?php
namespace core\app\classes\module_base;
 
/**
 * Abstract module_setup class.
 * 
 * Is the base class for all module setup.
 *
 * @abstract
 * @package 	module_setup
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */

abstract class module_setup {
	
	protected $module;
	
	private $_log = '';
	
	public function __construct()
	{
		$this->_updateModuleTables();
		$this->runSpecialSetup();
		return;
	}
	
	//update a module

    /**
     * update module table
     * based on module.ini [table] if exist
     * .xml file location in setup folder
     */
    private function _updateModuleTables()
	{
		$this->_log = '<h4>Updating for Module <span style="color: blue;">'.$this->module.'</span></h4>'."\n";
		
		//set the module tables
		$update_ns = NS_APP_CLASSES.'\\db_update\\db_update';
		$update = new $update_ns();
		$this->_log = $update->updateModuleTables($this->module);

		return;
	}
		
	protected function runSpecialSetup()
	{
		$this->_log .= '<p>No <span style="color: Green;">Special Setup</span> Found</p>'."\n";
		return;
	}
	
	public function getLog()
	{
		return $this->_log;
	}


}
?>