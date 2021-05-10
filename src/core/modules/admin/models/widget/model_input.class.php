<?php
namespace iow\modules\admin\models\widget;

/**
 * Final model_input class.
 *
 * This is a factory based on the first option.  All sub classes are of interface type admin_main 
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 27 December 2014
 */
final class model_input extends \iow\app\classes\module_base\module_model_input {

	protected $model_name = 'widget';
	
	protected $catchaClearAlways = true;
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		die('END');
		return;
	}
}
?>