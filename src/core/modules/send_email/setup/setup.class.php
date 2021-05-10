<?php
namespace core\modules\send_email\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'send_email';
	
	public function __construct()
	{	
		parent::__construct();
		$this->_setupDirectory();
		return;
	}
	
	private function _setupDirectory()
	{
		$directory = DIR_LOCAL_UPLOADS.'/send_email';
		$cssFile = DIR_LOCAL_UPLOADS.'/send_email/email.css';
		
		$originalCss = DIR_MODULES.'/send_email/views/template/email.css';
		
		if(!is_dir($directory))
		{
			if(!@mkdir($directory,0770,true))
			{
				$msg = "The send email directory could not be set up!";
				throw new \RuntimeException($msg);
			}
		}
		
		if(@is_writable($directory))
		{
			if(!@is_file($cssFile))
			{
				if(!@copy($originalCss,$cssFile)) 
				{
					$msg = "Hmm ... seems to be an issue with copying the original email css file!";
					throw new \RuntimeException($msg);
				}
			}
		} else {
			$msg = "Hmm ... this is bad ... the send email directory is not writable!";
			throw new \RuntimeException($msg);
		}
		
		return;
	}
	
}
?>