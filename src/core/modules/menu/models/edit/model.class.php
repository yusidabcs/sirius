<?php
namespace core\modules\menu\models\edit;

/**
 * Final model class.
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 25 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'edit';
	protected $processPost = true;
	
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
		//set link_id
		$link_id = $this->page_options[0];
		
		//if the link id is empty it should never have got here but just in case someone is playing
		if(empty($link_id))
		{
			$this->redirect = $this->baseURL;
			return;
		}
		
		$menu_db_ns = NS_MODULES.'\\menu\\models\\common\\db';
		$menu_db = $menu_db_ns::getInstance();
		
		//need Common Menu Object
		$menu_form_ns = NS_MODULES.'\\menu\\models\\common\\form';
		$menu_form = new $menu_form_ns();
		
		//need the module id for this link
		$module_id = $menu_db->getModuleFromLinkId($link_id);
		
		//need the menu details
		$this->_menu_item_a = $menu_db->getAllMenuDetailsForLinkId($link_id);

		//make menu parent select Array
		$this->_pageParentArray = $menu_form->makeParentOptionArray($link_id);
		
		//make menu security select Array
		$this->_pageSecurityArray = $menu_form->makeSecurityOptionArray($module_id,$this->_menu_item_a['security_level_id']);
		
		//make menu security select Array
		$this->_pageGroupArray = $menu_form->makeGroupOptionArray($module_id,$this->_menu_item_a['group_id']);
		
		//make menu security select Array
		$this->_pageTemplateArray = $menu_form->makeTemplateOptionArray($module_id,$this->_menu_item_a['template_name']);
		
		//make modules arragy
		$this->_pageModuleArray = $menu_form->makeModuleOptionArray($this->_menu_item_a['module_id']);
		
		//make check redirect
		$this->_allowRedirect = $menu_form->getAllowRedirect($module_id);
		
		//set if you can delete this menu item or not
		$this->canDelete = $this->system_register->getModuleActiveFlag($module_id);
		
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
		
		//need the sweetalert
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->addViewVariables('canDelete',$this->canDelete);
		
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
		
		\core\app\classes\menu_register\menu_register::rebuid();
		$this->menu_register = \iow\app\classes\menu_register\menu_register::getInstance();
	}
		
}
?>