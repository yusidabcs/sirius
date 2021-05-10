<?php
namespace core\modules\security\models\logoff;

/**
 * Final login_model class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'logoff';
	protected $processPost = false;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
		//required function
	protected function main()
	{
		session_destroy();
		setcookie('PHPSESSID', '', time() - 3600);
		$this->redirect = '/';
		return;
	}
		
	//required function
	protected function setViewVariables()
	{
		return;
	}
		
}
?>