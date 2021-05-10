<?php
namespace iow\app\classes\widget_base;

/**
 * Abstract widget_view class.
 * 
 * @abstract
 * @package 	widget_router
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 November 2014
 */
abstract class widget_view {

	protected $widget_name; //set by class
	
	protected $smarty;
	protected $viewVariables_obj;
	
	private $_templateInfo_a;
	
	public function __construct()
	{
		//define the models directory
		$this->_widget_views_dir = SITE_WIDGET_DIR.'/'.$this->widget_name.'/views';
		$this->_widget_views_namespace = SITE_WIDGET_NAMESPACE.'\\'.$this->widget_name.'\views';
		
		//get variables
		$this->view_variables_obj = \iow\app\classes\view\view_variables::getInstance();
		
		$this->_setTemplateInfo();
		$this->_processSections();
		return;
	}
	
	private function _setTemplateInfo()
	{
		//load the ini file
	    if($templateInfo_a = @parse_ini_file($this->_widget_views_dir.'/'.$this->widget_name.'.ini', true))
	    {
		    //get the settings off
		    if( isset($templateInfo_a['settings']) && is_array($templateInfo_a['settings']) )
		    {
			    foreach($templateInfo_a['settings'] as $key => $value )
			    {
				    $kv = '_ini_'.$key;
				    $this->$kv = $value;	
		    	}
			}
			
		} else {
		    $msg = "Can not find widget view ini file for {$this->widget_name}!";
			throw new \RuntimeException($msg);   
	    }
	    
	    $handle = @fopen($this->_widget_views_dir.'/'.$this->widget_name.'.template', "r");
		if ($handle) {
		    while (($line = fgets($handle)) !== false)
		    {
				$split = preg_split("/:/", $line,2);
				if(!empty($split[1]))
				{
					$this->_templateInfo_a[] = $split;
				}
		    }
		    if (!feof($handle)) 
		    {
		        $msg = "Error: unexpected failure reading {$this->widget_name} widget template\n";
		        throw new \RuntimeException($msg); 
		    }
		    fclose($handle);
		} else {
			$msg = "Can not find widget view template {$this->widget_name}!";
			throw new \RuntimeException($msg);   
		}
	    
	    return;
	}
	
	private function _processSections()
	{
		//load any of the file variables that are set
		foreach ($this->view_variables_obj->getWidgetViewVariables($this->widget_name) as $name => $variable)
		{
			$$name = $variable;
		}

		foreach( $this->_templateInfo_a as $template)
		{
			$source = $template[0];
			$content = trim($template[1]);
			
			switch ($source) 
			{
				case 'htm':				//output the literal html
			        echo $content."\n";
			        break;

			    case 'php':					//from a php file
			    
			        if(!include ($this->_widget_views_dir.'/php/'.$content.'.php'))
			        {
				        $msg = "The view template file called {$source} does not exist!";
						throw new \RuntimeException($msg);   
				    }
			        break;
			        
			    case 'tpl':					//from a smarty file (needs smarty)
			    
			    	if(!is_object($this->smarty))
			    	{
				    	$this->_loadSmarty();
			    	}
			    	
			        $this->smarty->display($this->_widget_views_dir.'/tpl/'.$content.'.tpl');
			        
			        break;
			        
			    default:
			          $msg = "The widget view type of source {$source} is not valid for {$this->widget_name}!";
					  throw new \RuntimeException($msg);   
			        break;
			}
		}
	}
	
	//!this needs to be set up properly
    private function _loadSmarty()
    {
    	//Require Smarty
		//require(SITE_DIR.'/smarty3/Smarty.class.php'); //if you are wanting to use another one
		require_once('Smarty.class.php');
		
		//Connect to snarty
		$this->smarty = new \Smarty();

		$smarty_dir = SITE_LOCAL.'/smarty';

		$this->smarty->setCacheDir($smarty_dir . '/cache/');
		$this->smarty->setConfigDir($smarty_dir . '/configs/');
		$this->smarty->setCompileDir($smarty_dir . '/templates_c/');
		$this->smarty->setTemplateDir(SITE_VIEWS_DIR . '/smarty/');
		
		//set up the actual smarty variables
		foreach ($this->view_variables_obj->getWidgetSmartyVariables($this->widget_name) as $name => $variable)
		{
			$this->smarty->assign($name,$variable);
		}
		
		return;
	}

	
}
?>