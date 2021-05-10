<?php
namespace core\modules\address_book\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'address_book';
	
	public function __construct()
	{	
		parent::__construct();
		$this->_setupDirectory();
		return;
	}
	
	private function _setupDirectory()
	{
		$directory = DIR_LOCAL_UPLOADS.'/address_book';
		
		if(!is_dir($directory))
		{
			if(!@mkdir($directory,0770,true))
			{
				$msg = "The address_book directory could not be set up!";
				throw new \RuntimeException($msg);
			}
		}
		
		if(!@is_writable($directory))
		{
			$msg = "Hmm ... this is bad ... the address book directory is not writable!";
			throw new \RuntimeException($msg);
		}
		
		return;
	}

	
}
?>