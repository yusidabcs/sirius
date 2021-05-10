<?php
namespace core\modules\interview;

/**
 * Final controller class.
 * 
 * Controller for the interview module
 *
 * @final
 * @extends 	module_controller
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class controller extends \core\app\classes\module_base\module_controller {
	
	protected $commonNav = true;
	protected $default_model = 'home';
	
	public function __construct()
	{
		// echo $_SERVER['HTTP_HOST'].' - '.$_SERVER['REQUEST_URI'];
		$link = explode('/',$_SERVER['REQUEST_URI']);
		if(isset($link[1])) {
			if($link[1]==='interview-principal') {
				$this->commonNav = false;
				$this->defaultModel = "interview_principal";
			}
		}
		parent::__construct();
		return;
	}
}
?>