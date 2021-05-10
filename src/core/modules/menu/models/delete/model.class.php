<?php
namespace core\modules\menu\models\delete;

/**
 * Final model class.
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 28 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'delete';
	protected $processPost = false;
	
	public function __construct()
	{
		parent::__construct(); 
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		//set link_id
		$link_id = $this->page_options[0];
		
		//if the link id is empty it should never have got here but just in case someone is playing
		if(empty($link_id))
		{
			$this->redirect = $this->baseURL;
			return;
		}
		
		$menu_common_ns = NS_MODULES.'\\menu\\models\\common\\common';
		$menu_common = $menu_common_ns::getInstance();
		$menu_common->deleteLinkFromMenu($link_id);

		//redirect to menu base
		$this->redirect = $this->baseURL;
		
		return;
	}
	
	protected function setViewVariables()
	{
		return;
	}
		
}
?>