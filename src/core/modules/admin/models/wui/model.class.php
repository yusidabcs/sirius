<?php
namespace core\modules\admin\models\wui;


/**
 * Final model class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'wui';
	protected $processPost = true;

	private $_nav;
	private $_link;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->defaultView();
		
		//site interface ini
		if(is_file(DIR_SECURE_INI.'/site_interface.ini'))
	    {
	    	$wui_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_interface.ini',true);     
	    } else {
	    	die('You need to have a local site interface ini file before you can edit it!');
	    }
	    
	    $this->_nav = $wui_ini_a['nav'];
	    $this->_link = $wui_ini_a['link'];
	    
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('wui');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->modelURL);
		
		$this->view_variables_obj->addViewVariables('nav',$this->_nav);
		$this->view_variables_obj->addViewVariables('link',$this->_link);
		
		if($this->input_obj)
		{
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
			
		}

		return;
	}
		
}
?>