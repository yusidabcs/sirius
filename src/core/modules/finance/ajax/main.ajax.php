<?php
namespace core\modules\finance\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 15 Jun 2020
 */
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$out = null;
		
		switch($this->option) 
		{	
			case 'TEST':			
				
				echo "<pre>";
				print_r($_POST);
				echo "</pre>";
				die('END');

				break;
		
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
		}
		
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>