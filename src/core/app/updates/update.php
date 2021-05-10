<?php
namespace core\app\updates;

/**
*
* Whatever is in this file's runUpdate will be executed by the root level update.php every time it runs
* DELETE any code after you have run a Update Sites then run Update Base again to overwrite.
*
**/
	
final class update {

	private $log = array();
	
	public function __construct()
    {
        return;
    }
    
	public function getLog()
	{
		$out = '';
		if(empty($this->log))
		{
			$out .= "<p>No Updates Required</p>\n";
		} else {
			
			$out .= "<pre>\n";
			
			foreach($this->log as $key => $value)
			{
				$out .= "{$key} - {$value}\n";
			}
			
			$out .= "</pre>\n";
			
		}
		return $out;
	}
	
	public function runUpdate()
	{
		//this is where you put something you want executed but you must delete it after it has run one (unless you want to run it every time!
		return;
	}

}
?>