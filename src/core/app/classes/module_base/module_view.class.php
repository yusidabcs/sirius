<?php
namespace core\app\classes\module_base;

/**
 * Abstract module_view class.
 * 
 * @abstract
 * @package 	module_router
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 August 2019
 */
abstract class module_view {

	protected $viewVariables_obj;
	
	private $_templateInfo_a = array();
	
	//content
	protected $moduleContent = '';	//the content of the module for the body
	
	public function __construct()
	{
		$page_view_variables_ns = NS_APP_CLASSES.'\\page_view\\page_view_variables';
		$this->view_variables_obj = $page_view_variables_ns::getInstance();
		
		$this->_setModuleHeaderInfo();
		$this->_setModelHeaderInfo();
		$this->_setTemplateInfo();
		$this->_setTermVariables();
		$this->_outputSections();
		return;
	}
	
	/**
	 * _setModuleHeaderInfo function.
	 * 
	 * Processes the modules header ini file which loads (as required) css to header and js files to footer.
	 *
	 * @access private
	 * @return void
	 */
	private function _setModuleHeaderInfo()
	{		
		//set base directories
		$template_base_dir = DIR_MODULE_VIEWS;
		$template_href_dir = WWW_MODULES.'/'.MODULE.'/views';
		
		//load the ini file
		$headerINI = $template_base_dir.'/header.ini';
		
		//process the template header ini file to add in the css and js
	    if(is_file($headerINI))
	    {
		    $templateInfo_a = @parse_ini_file($headerINI, true);
		    
		    //add the template css's
		    if(isset($templateInfo_a['css']) && is_array($templateInfo_a['css']) )
		    {
			    $index = 300;
			    foreach($templateInfo_a['css'] as $key => $value)
			    {
				    $css_file = $template_base_dir.'/css/'.$key.'.css';
				    $href = $template_href_dir.'/css/'.$key.'.css?'.APP_VERSION;
				    $media = $value = 'default' ? '' : $value;
				    if(is_readable($css_file))
				    {
				    	$this->view_variables_obj->addHeadCSSFile($index,$href,$media);
				    	$index++;
				    } else {
					    $msg = "Module header uses css file {$css_file} but it can not be read!";
						throw new \RuntimeException($msg);
				    }
		    	}
			}
			
			//add the template js's
		    if( isset($templateInfo_a['js']) && is_array($templateInfo_a['js']) )
		    {
			   
			    $index = 300;
			    foreach($templateInfo_a['js'] as $key => $value)
			    {
				    $js_file = $template_base_dir.'/js/'.$key.'.js';
				    $href = $template_href_dir.'/js/'.$key.'.js?'.APP_VERSION;
				    
				    if(is_readable($js_file))
				    {
				    	$this->view_variables_obj->addFootSrcFile($index,$href);
				    	$index++;
				    } else {
					    $msg = "Module header file uses js file {$js_file} but it can not be read!";
						throw new \RuntimeException($msg);
				    }
		    	}
			}

		} else {
		    $msg = "Can not find the module header ini {$headerINI}!";
			throw new \RuntimeException($msg);   
	    }

		return;
	}
	
	/**
	 * _setModelHeaderInfo function.
	 *
	 * Same as above only MODEL not module
	 * 
	 * @access private
	 * @return void
	 */
	private function _setModelHeaderInfo()
	{		
		//set base directories
		$template_base_dir = DIR_MODULE_VIEWS.'/'.$this->view_variables_obj->getViewTemplate();
		$template_href_dir = WWW_MODULES.'/'.MODULE.'/views/'.$this->view_variables_obj->getViewTemplate();
		
		//load the ini file
		$headerINI = $template_base_dir.'/header.ini';
		
		//process the template header ini file to add in the css and js
	    if(is_file($headerINI))
	    {
		    $templateInfo_a = @parse_ini_file($headerINI, true);
		    
		    //add the template css's
		    if( isset($templateInfo_a['css']) && is_array($templateInfo_a['css']) )
		    {
			   
			    $index = 400;
			    foreach($templateInfo_a['css'] as $key => $value)
			    {
				    $css_file = $template_base_dir.'/css/'.$key.'.css';
				    $href = $template_href_dir.'/css/'.$key.'.css';
				    $media = $value = 'default' ? '' : $value;
				    
				    if(is_readable($css_file))
				    {
				    	$this->view_variables_obj->addHeadCSSFile($index,$href,$media);
				    	$index++;
				    } else {
					    $msg = "Model header uses css file {$css_file} but it can not be read!";
						throw new \RuntimeException($msg);
				    }
		    	}
			}
			
			//add the template js's
		    if( isset($templateInfo_a['js']) && is_array($templateInfo_a['js']) )
		    {
			   
			    $index = 400;
			    foreach($templateInfo_a['js'] as $key => $value)
			    {
				    $js_file = $template_base_dir.'/js/'.$key.'.js';
				    $href = $template_href_dir.'/js/'.$key.'.js?'.APP_VERSION;
				    
				    if(is_readable($js_file))
				    {
				    	$this->view_variables_obj->addFootSrcFile($index,$href);
				    	$index++;
				    } else {
					    $msg = "Model header file uses js file {$js_file} but it can not be read!";
						throw new \RuntimeException($msg);
				    }
		    	}
			}

		} else {
		    $msg = "Can not find the module header ini {$headerINI}!";
			throw new \RuntimeException($msg);   
	    }

		return;
	}

	private function _setTemplateInfo()
	{
		//load the ini file
		$file = DIR_MODULE_VIEWS.'/'.$this->view_variables_obj->getViewTemplate().'/view.ini';
	    if($templateInfo_a = @parse_ini_file($file, true))
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
		    $msg = "Can not find module view ini {$file}!";
			throw new \RuntimeException($msg);   
	    }
	    
	    $file = DIR_MODULE_VIEWS.'/'.$this->view_variables_obj->getViewTemplate().'/view.template';
	    $handle = @fopen($file, "r");
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
		        $msg = "Error: unexpected failure reading {$this->_templateName} template\n";
		        throw new \RuntimeException($msg); 
		    }
		    fclose($handle);
		} else {
			$msg = "Can not find module view template {$file}!";
			throw new \RuntimeException($msg);   
		}
	    return;
	}
	
	private function _setTermVariables()
	{	
		//easier to reaad :-)
		$template = $this->view_variables_obj->getViewTemplate();
		
		//load the ini file
		$termsFile = DIR_MODULE_VIEWS.'/'.$template.'/view.terms';
		
		if(is_file($termsFile))
		{
		    if($terms_a = @parse_ini_file($termsFile) )
		    {
			    //get the settings off
			    if( !empty($terms_a) )
			    {
				    foreach($terms_a as $key => $value )
				    {
					    $name = 'term_'.$key;
					    $this->view_variables_obj->addViewVariables($name,$value);
			    	}
				}
			}
	    } else {
		    $msg = "Can not find module view term file {$termsFile}!";
			throw new \RuntimeException($msg);   
	    }
	    
	    
	    //delete the old terms_a
	    unset($terms_a);
	    
	    //over write with the local terms (if any) ... it will only replace the ones in the file
	    $localTermsFile = DIR_SECURE_TERMS.'/'.MODULE.'/views/'.$template.'/view.terms';

	    if( is_file($localTermsFile) && $terms_a = @parse_ini_file($localTermsFile) )
	    {
		    //get the settings off
		    if( !empty($terms_a) )
		    {
			    foreach($terms_a as $key => $value )
			    {
				    $name = 'term_'.$key;
				    $this->view_variables_obj->addViewVariables($name,$value);
		    	}
			}
		}

		//!fix ALL referrences to TEMPLATE $this->view_variables_obj->getViewTemplate()	
		return;
	    
	}
		
	private function _outputSections()
	{
		$navRow = false;
		
		//load any of the file variables that are set
		foreach ($this->view_variables_obj->getViewVariables() as $name => $variable)
		{
			$$name = $variable;
		}
		//if there is a common nav then we need to put it out now
		if( isset($commonNavArray) && !empty($commonNavArray) )
		{
			$navFile = DIR_MODULE_VIEWS.'/navFile.php';
			if(is_file($navFile))
			{
				$this->moduleContent .= '<div class="row">';
				$this->moduleContent .= '<div class="col-lg-3 mb-3">';
				
				ob_start();
				require $navFile;
				$this->moduleContent .= ob_get_clean();
				
				$this->moduleContent .= '</div>';
				$this->moduleContent .= '<div class="col-lg-9 mb-3">';
				
				$navRow = true;
				
			} else {
		        $msg = "The common nav template file called {$navFile} does not exist!";
				throw new \RuntimeException($msg);   
		    }
		}
		
		foreach( $this->_templateInfo_a as $template)
		{
			$source = $template[0];
			$content = trim($template[1]);
			
			switch ($source) 
			{
				case 'htm':	//output the literal html
			        $this->moduleContent .= $content."\n";
			        break;

			    case 'php':	//from a php file
					
					$phpFile = DIR_MODULE_VIEWS.'/'.$this->view_variables_obj->getViewTemplate().'/'.$content.'.php';
			        if(is_file($phpFile))
			        {
				        //need to catch the output and not send it straight out
				        ob_start();
						require $phpFile;
						$this->moduleContent .= ob_get_clean();
				    } else { 
				        $msg = "The view template file called {$content} does not exist!";
						throw new \RuntimeException($msg);   
				    }
			        break;
			        			        
			    default:
			          $msg = "The module view type of source {$source} is not valid!";
					  throw new \RuntimeException($msg);   
			        break;
			}
		}
		
		if($navRow)
		{
			$this->moduleContent .= '</div>';
			$this->moduleContent .= '</div>';
		}
	}
	
	public function getModuleContent()
	{
		return $this->moduleContent;
	}
	
}
?>