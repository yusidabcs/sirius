<?php
namespace core\modules\menu\models\update;

/**
 * Final model class.
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 28 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'update';
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
		$menu_common_ns = NS_MODULES.'\\menu\\models\\common\\common';
		$menu_common = $menu_common_ns::getInstance();
		
		//update the menu file
		$menu_common->updateMenuIni();
		
		//rebuild the sitemaps
		$menu_common->updateSitemapFiles();
		
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