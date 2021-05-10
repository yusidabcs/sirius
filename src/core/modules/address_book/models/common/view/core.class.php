<?php
namespace core\modules\address_book\models\common\view;

/**
 * Final core class.
 * 
 * @final
 *
 * is a singleton
 * 
 * Once common address book class is needed because it may or may not be used by model input
 *
 * @final
 * @package 	view
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 11 October 2014
 */

final class core {
	
	private static $_singleton;
    private static $_core_obj; // the actual class object for the menu register
    
	private function __construct($address_book_id)
	{
        $this->_loadViewObj($address_book_id);
		return;
	}
	
	public function __clone()
	{
		trigger_error('Cloning instances of this class is forbidden.', E_USER_ERROR);
	}
	
	public function __set($index,$value)
    {
		trigger_error('Setting variable for this register is not allowed.', E_USER_ERROR);
	}

	public function __get($index)
    {
		return $this->_vars[$index];
	}
	
	public function __isset($index)
    {
        return isset($this->_vars[$index]);
    }

    public function __unset($index)
    {
        trigger_error('Unsetting variable for this register is not allowed.', E_USER_ERROR);
    }

	public static function getInstance($address_book_id)
	{
		if(empty($address_book_id))
		{
			$msg = 'You can not instantiate core edit without an address book id!';
			throw new \RuntimeException($msg);
		} else {
		
			if (is_null(self::$_singleton))
			{
				self::$_singleton = new core($address_book_id);
			}
			
		}
		
		return self::$_core_obj;
	}
     
    private function _loadViewObj($address_book_id)
    {
        try 
        {
            self::$_core_obj = new core_obj($address_book_id);
        } catch (\Exception $e) {
            $htmlOutput = new \core\app\classes\html\htmlmsg($e,DEBUG);
            echo $htmlOutput->getHtmlOutput();
            exit();
        }
		return;
    }
    
}
?>