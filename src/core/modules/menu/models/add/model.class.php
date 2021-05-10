<?php
namespace core\modules\menu\models\add;

/**
 * Final model class.
 *
 * This is a factory based on the first option.  All sub classes are of interface type admin_main 
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'add';
	protected $processPost = true;
	
	private $_menu_item_a;
	
	private $_menuCommonObj; //the commen menu form items
	private $_pageParent;
	private $_pageSecurityArray;
	
	public function __construct()
	{
		parent::__construct(); 
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$link_id = '';
		$module_id = '';
		
		$this->_menu_item_a['link_id'] = '';
		$this->_menu_item_a['parent_id'] = '';
		$this->_menu_item_a['redirect_url'] = '';
		$this->_menu_item_a['sequence_no'] = 0;
		$this->_menu_item_a['title_menu'] = '';
		$this->_menu_item_a['title_page'] = '';
		$this->_menu_item_a['template_name'] = '';
		$this->_menu_item_a['module_id'] = '';
		$this->_menu_item_a['security_level_id'] = '';
		$this->_menu_item_a['group_id'] = '';
		$this->_menu_item_a['main_link'] = 1;
		$this->_menu_item_a['quick_link'] = 0;
		$this->_menu_item_a['bottom_link'] = 0;
		$this->_menu_item_a['sitemap'] = 0;
		$this->_menu_item_a['status'] = 1;

		//need Common Menu Object
		$form_ns = NS_MODULES.'\\menu\\models\\common\\form';
		
		$this->_menuCommonFormObj = new $form_ns();
		
		//make menu parent select Array
		$this->_pageParentArray = $this->_menuCommonFormObj->makeParentOptionArray($link_id);
		
		//make menu security select Array
		$this->_pageSecurityArray = $this->_menuCommonFormObj->makeSecurityOptionArray($module_id,$this->_menu_item_a['security_level_id']);
		
		//make menu security select Array
		$this->_pageGroupArray = $this->_menuCommonFormObj->makeGroupOptionArray($module_id,$this->_menu_item_a['group_id']);
		
		//make menu security select Array
		$this->_pageTemplateArray = $this->_menuCommonFormObj->makeTemplateOptionArray($module_id,$this->_menu_item_a['template_name']);
		
		//make modules arragy
		$this->_pageModuleArray = $this->_menuCommonFormObj->makeModuleOptionArray($this->_menu_item_a['module_id']);
		
		//make check redirect
		$this->_allowRedirect = $this->_menuCommonFormObj->getAllowRedirect($module_id);
		
		$this->defaultView();
		return;
	}
		
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('edit');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//set the variables from default of input processing
		foreach($this->_menu_item_a as $key => $value)
		{
			$this->view_variables_obj->addViewVariables($key,$value);
		}
		
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
			}
		} 
		
		//only needed when editing but still have to add it because we use the same page
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->addViewVariables('canDelete',false);
		
		//set specific variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		$this->view_variables_obj->addViewVariables('baseURL',$this->baseURL);
		$this->view_variables_obj->addViewVariables('pageParent',$this->_pageParentArray);
		$this->view_variables_obj->addViewVariables('pageSecurityArray',$this->_pageSecurityArray);
		$this->view_variables_obj->addViewVariables('pageGroupArray',$this->_pageGroupArray);
		$this->view_variables_obj->addViewVariables('pageTemplateArray',$this->_pageTemplateArray);
		$this->view_variables_obj->addViewVariables('pageModuleArray',$this->_pageModuleArray);
		$this->view_variables_obj->addViewVariables('allowRedirect',$this->_allowRedirect);
		
		return;
	}
	
	protected function _rebuildMenu()
	{
		die('REBUILD NEEDS TO BE IN MENU');
		\iow\app\classes\menu_register\menu_register::rebuid();
		$this->menu_register = \iow\app\classes\menu_register\menu_register::getInstance();
	}
		
}
?>