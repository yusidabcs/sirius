<?php
/**
* This is the actual messages object itself.  Changes to its behaviour go here.
* I am going to make this a factory model in case I need more logs in the future
*/
namespace core\app\classes\messages_register;

final class message {

	private $_typeId; //number as defined in messages object equating to error, warning, note etc
	private $_source; //the module id
	private $_section; //the section of the module
	private $_id; //the actual id within the section if needed
	private $_info; //the information that needs to be shared with the user
    
    public function __construct($info,$typeId,$source,$section,$id)
    {
    	$this->_info = $info;
    	$this->_typeId = $typeId;
    	$this->_source = $source;
    	$this->_section = $section;
    	$this->_id = $id;
    	
    	return;
    }
    
    public function getTypeId()
    {
	    return $this->_typeId;
    }
    
    public function getMessageArray($long)
    {
    	if($long)
    	{
    		$out['source'] = $this->_source;
    		$out['section'] = $this->_section;
    		$out['id'] =  $this->_id;
    		$out['info'] = $this->_info;
    	} else {
    		$out['info'] = $this->_info;
    	}
    	
    	return $out;
    }
    
}  
?>