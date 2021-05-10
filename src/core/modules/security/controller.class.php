<?php
namespace core\modules\security;

/**
 * Final controller class.
 * 
 * Controller for the secuity module
 *
 * @final
 * @extends iow
 */
final class controller extends \core\app\classes\module_base\module_controller {

	protected $commonNav = false;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function setModelId() 
	{
		//check Options
		if(empty($this->page_info_options))
		{
			
			if( isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] > 0)
			{
				$this->model = 'logout';
			} else {
				$this->model = 'login';
			}
			
		} else {
			
			//correct the situation
			if( $this->page_info_options[0] == 'login' || $this->page_info_options[0] == 'logout')
			{
				if( isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] > 0)
				{
					$this->model = 'logout';
				} else {
					$this->model = 'login';
				}
			} else {
				$this->model = $this->page_info_options[0];
			}
		}
		
		//check the default is one of the models
	    if(!array_key_exists($this->model, $this->model_a))
	    {
		    $html_ns = NS_HTML.'\\htmlpage';
	    	$htmlpage = new $html_ns(404);
			exit();
		}
		return;
		
	}
	
}
?>