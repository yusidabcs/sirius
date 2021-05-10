<?php
namespace core\modules\pages\models\order;

/**
 * Final model class.
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 Sept 2018
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'order';
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
		//get all the text content
		$pages_db = new \core\modules\pages\models\common\db;
		$this->page_contents = $pages_db->getPageContentSummary($this->link_id);

		$this->defaultView();
		return;
	}
			
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('order');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{

		//$this->view_variables_obj->useBSHtml5Sortable();
		
		$this->view_variables_obj->addViewVariables('link_id',$this->link_id);
		
		$this->view_variables_obj->addViewVariables('view_link',$this->baseURL);
		$this->view_variables_obj->addViewVariables('edit_link',$this->baseURL.'/edit');

		$this->view_variables_obj->addViewVariables('pageContentInfoArray',$this->page_contents);
		
		return;
	}
			
}
?>