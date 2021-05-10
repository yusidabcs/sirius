<?php
namespace core\app\classes\system_register\site_term;

/**
 * Final site_term class.
 * 
 * This is the site object that holds site terms
 * It contains all the term translations for site in the site_term.ini
 *
 * @final
 * @package 	system_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
final class site_term {

    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
	    $this->_loadSiteTermIni();
	    return;
    }

	/**
	 * loadSiteWideTerms function.
	 * 
	 * Loads the terms that are site wide and not in a module
	 *
	 * @access private
	 * @return void
	 */
	private function _loadSiteTermIni()
	{
		$site_term_file_local = DIR_SECURE_INI.'/site_term.ini';
    	$site_term_file_original = DIR_APP_INI.'/site_term.ini';
	    
        //load the site ini file
	    if(is_file($site_term_file_local))
	    {
		    
	    	$site_term_array = parse_ini_file($site_term_file_local,true); 
	    	    
	    } elseif (is_file($site_term_file_original)) {
		    
	    	$site_term_array = parse_ini_file($site_term_file_original,true);  
	    	   
	    } else {
	    	$msg = 'The INI file site_term can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
    	
    	foreach ($site_term_array as $term)
    	{
    		foreach($term as $key => $value)
    		{
    			$var = '_';
    			$var .= $key;
    			if(isset($this->$var))
    			{
	    			$msg = "The site term '$key' is duplicated! It can not be used twice.";
	    			throw new \RuntimeException($msg);
    			} else {
    				$this->$var = $value;
    			}
    		}	
    	}

		return;
	}

    /**
     * addTerm function.
     * 
     * @access public
     * @param mixed $index
     * @param mixed $term
     * @return void
     */
    public function addTerm($key,$value)
    {
	    $var = '_';
		$var .= $key;
		if(isset($this->$var))
		{
			$msg = "The site term '$key' is duplicated! It can not be used twice.";
			throw new \RuntimeException($msg);
		} else {
			$this->$var = $value;
		}
	    return;
    }
    
    //This function returns the private variable
    
    /**
     * __get function.
     *
     * This function returns the private variables of this class
     * I use this here because it is a subordinate class to system_register
     * and there is no other direct access to this class.  So it is controlled.
     * 
     * @access public
     * @param string $property the name of the property
     * @return string the value of the property
     */
    public function __get($property)
    {
    	$var = '_'.$property;    	
    	return isset($this->$var) ? $this->$var : '';
	}
	
}
?>