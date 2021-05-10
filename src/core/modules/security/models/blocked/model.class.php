<?php
namespace core\modules\security\models\blocked;

/**
 * Final model class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'blocked';
	protected $processPost = true;
	
	public function __construct()
	{
        parent::__construct();
        //security restricting login attempts
		if(isset($_SESSION['login_count']))
		{	
			if( $_SESSION['login_count'] > 3 )
			{
				$inactive = 600;
				$session_life = time() - $_SESSION['last_login'];
				if($session_life > $inactive){
					session_destroy();
					$_SESSION['login_count'] = 0;
					header("Location: /security");
				}
				
			} 
			
		} 
		return;
	}
	
	//required function
	protected function main()
	{
		$this->view_variables_obj->setViewTemplate('blocked');
		return;
	}
		
	//required function
	protected function setViewVariables()
	{	
		
	}
		
}
?>