<?php
namespace core\modules\address_book\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = false;
	
	//used in the pagination search
	private $_term_search_title;
	private $_term_search_name;
	private $_search_text;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		//if page is set in options
		$this->authorize();
		if(isset($this->page_options[0]) && $this->page_options[0] > 0)
		{
			$page = $this->page_options[0];
		} else {
			$page = 0;
		}

				
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('home');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
        $this->view_variables_obj->useDatatable();
		//search file
			
		//POST and LINKS Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('modelURL',$this->baseURL.'/home'); //needs to be the full url for the pagination
		$this->view_variables_obj->addViewVariables('link_add',$this->baseURL.'/add/ab');
		$this->view_variables_obj->addViewVariables('link_edit',$this->baseURL.'/edit');
				
		return;
	}
		
}
?>