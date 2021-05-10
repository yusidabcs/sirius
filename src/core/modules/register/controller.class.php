<?php
namespace core\modules\register;

/**
 * Final controller class.
 * 
 * Controller for the register module
 *
 * @final
 * @extends 	module_controller
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class controller extends \core\app\classes\module_base\module_controller {
	
	protected $commonNav = false;
	
	public function __construct()
	{
		
		
		$page_info_ns = NS_APP_CLASSES.'\\page_info\\page_info';
		$page_info = $page_info_ns::getInstance();
		$page_info_options = $page_info->getOptions();

		if (isset($page_info_options[0])){// check if there is option
			$slug = $page_info_options[0];
			$models = [];

			//get avaiable models first
			if(!$module_ini_a = @parse_ini_file(DIR_MODULE.'/module.ini',true))
			{
				$msg = MODULE." module ini file it does not exists or is empty!\n";
				throw new \RuntimeException($msg);
			}

			$defaultModel = '';
			//make sure there is a default model and set it
			if(!empty($module_ini_a['config']['defaultModel']))
			{
				$defaultModel = $module_ini_a['config']['defaultModel'];
			} else {
				$msg = MODULE." module ini file does not contain a default model!\n";
				throw new \RuntimeException($msg);
			}

			if(!empty($module_ini_a['models']))
			{
				
				foreach($module_ini_a['models'] as $model => $value)
				{
					$models[$model] = $value;
				}
				
			} else {
				$msg = MODULE." module ini file does not contain a list of models!\n";
				throw new \RuntimeException($msg);
			}

			//check if options is in one of the registered models, 
			if ( array_key_exists($slug,$models) )
			{
				// then continue business as usual
				parent::__construct();
			}else{
				//if not, then continue check redirect to home(default model) with partner-code slug|

				$redirect = '/'.$page_info->getLink().'/'.$defaultModel.'/'.$slug;
				header("Location: ".$redirect);
				
			}
		}else{// if not then continue as usual
			parent::__construct();
		}
		return;
	}
}
?>