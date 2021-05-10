<?php
namespace core\app\classes\page_view;

/**
 * Final view class.
 * 
 * This is the actual view object itself.  Changes to its behaviour go here
 *
 * @final
 * @package 	page_view
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 August 2019
 */
final class page_view {

	private $_variables_obj; //object of the view variables
	private $_bodyView_obj; //the module view obj
	
    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
	    //load the body view object because the module can affect the page variable object
	    $this->_bodyView_obj = new page_view_display_html5_body();
	      
	    //variables
	    $page_view_variables_ns = NS_APP_CLASSES.'\\page_view\\page_view_variables';
        $this->_variables_obj = $page_view_variables_ns::getInstance();
        
        //set the rel meta tag if needed
        $relMetaTag = $this->_bodyView_obj->addRelTag();
        
        if($relMetaTag)
        {
	        $this->_variables_obj->setRelMetaTag($relMetaTag);
        }
        
        //handle CSS
        //handle Header part of view, like css and js
        $this->_processTemplateHeader();
        
        return;
    }
    
    private function _processTemplateHeader()
    {
	    //which template
	    $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
	    $menu_register = $menu_register_ns::getInstance();
	    
	    $page_infor_ns = NS_APP_CLASSES.'\\page_info\\page_info';
		$page_info = $page_infor_ns::getInstance();
		
		$this->_currentLink_id = $page_info->getLink();
		$templateName = $menu_register->getTemplateName($this->_currentLink_id);

		//check if template name is set or not
		if($templateName)
		{
            //set the base template directory based on teh templateName
			$template_base_dir = DIR_PAGEVIEWS.'/'.$templateName;
			$template_href_dir = WWW_PAGEVIEWS.'/'.$templateName;

		}  else {
			$msg = "No template found in page template header page view!";
			throw new \RuntimeException($msg);
		}
		
		//load the ini file
		$headerINI = $template_base_dir.'/header.ini';
		
		//process the template header ini file to add in the css and js
	    if(is_file($headerINI))
	    {
		    $templateInfo_a = @parse_ini_file($headerINI, true);
		    
		    //add the template css's
		    if( isset($templateInfo_a['css']) && is_array($templateInfo_a['css']) )
		    {
			   
			    $index = 200;
			    foreach($templateInfo_a['css'] as $key => $value)
			    {
				    $css_file = $template_base_dir.'/css/'.$key.'.css';
				    $href = $template_href_dir.'/css/'.$key.'.css';
				    $media = $value = 'default' ? '' : $value;
				    
				    if(is_readable($css_file))
				    {
				    	$this->_variables_obj->addHeadCSSFile($index,$href,$media);
				    	$index++;
				    } else {
					    $msg = "Template uses css file {$css_file} but it can not be read!";
						throw new \RuntimeException($msg);
				    }
		    	}
			}
			
			//add the template js's
		    if( isset($templateInfo_a['js']) && is_array($templateInfo_a['js']) )
		    {
			   
			    $index = 200;
			    foreach($templateInfo_a['js'] as $key => $value)
			    {
				    $js_file = $template_base_dir.'/js/'.$key.'.js';
				    $href = $template_href_dir.'/js/'.$key.'.js';
				    
				    if(is_readable($css_file))
				    {
				    	$this->_variables_obj->addFootSrcFile($index,$href);
				    	$index++;
				    } else {
					    $msg = "Template uses js file {$js_file} but it can not be read!";
						throw new \RuntimeException($msg);
				    }
		    	}
			}

		} else {
		    $msg = "Can not find header ini {$headerINI}!";
			throw new \RuntimeException($msg);   
	    }
    }

    /**
     * this function responsible to handle dan render all the html part.
     */
    public function outputHTML5()
    {
	    $nonce = $this->_variables_obj->getNonce();
	    
    	//Start the HTML
    	echo "<!DOCTYPE html>\n";
		
		//get correct html tag
		echo $this->_variables_obj->getHTMLTag();
		
		//process set header flags
		$this->_variables_obj->processSetHeaderFlags();
		
		//start head
		echo "<head>\n";
				
		//set base which is used for all links
		if($this->_variables_obj->getHeadBase())
		{
			echo $this->_variables_obj->getHeadBase();
		}
		
		//character set
		echo $this->_variables_obj->getHeadCharset();
		
    	//metaTags
    	echo $this->_variables_obj->getHeadMetaNameTags();
    	
    	//http-equiv
    	echo $this->_variables_obj->getHeadMetaHttpEquivTags();

    	//title
    	echo $this->_variables_obj->getHeadPageTitle();
    	
    	//links
    	echo $this->_variables_obj->getHeadLinks();
    	
    	//css to embed (even though it is not a good idea)
    	echo $this->_variables_obj->getHeadCSS();
    	
    	//scripts
    	echo $this->_variables_obj->getHeadScripts();
    	
    	//Google Tag Manager Header Script if we are logged in
    	if(empty($_SESSION['user_id']))
    	{
    		$gtm = $this->_bodyView_obj->getSiteTagManager();
    	} else {
	    	$gtm = false;
    	}
    	
    	if($gtm)
    	{
	    	
echo <<<EOT
	    	
<!-- Google Tag Manager -->
<script nonce="{$nonce}">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','$gtm');</script>
<!-- End Google Tag Manager -->

EOT;
	
    	}
    	
    	//Google reCAPTCHA Header Script if we are logged in
    	if(empty($_SESSION['user_id']))
    	{
    		$grc = $this->_bodyView_obj->getSiteReCAPTCHA();
    	} else {
	    	$grc = false;
    	}
    	
    	if($grc)
    	{
	    	
echo <<<EOT
	    	
<!-- Google reCAPTCHA -->
<script nonce="{$nonce}" src='https://www.google.com/recaptcha/api.js?render=$grc'></script>
<!-- End Google reCAPTCHA -->

EOT;
	
    	}
    	
    	//end head
    	echo "</head>\n";
    	
    	//start body
    	if($this->_variables_obj->getBodyJS())
		{
			echo '<body '.$this->_variables_obj->getBodyJS().">\n";
		} else {
			echo "<body>\n";
		}
		
		//Google Tag Manager Body Script
		
		if($gtm)
    	{
	    	
echo <<<EOT
	    	
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=$gtm"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

EOT;
	
    	}	
		
 		
		echo $this->_bodyView_obj->getBodyContent();
		
    	if($grc)
    	{
	    	
$version = APP_VERSION;
echo <<<EOT
<div class="text-center text-black-50">
<small>This site is protected by reCAPTCHA and the Google 
<a href="https://policies.google.com/privacy">Privacy Policy</a> and
<a href="https://policies.google.com/terms">Terms of Service</a> apply. v.$version
</small>
<input id="reCAPTCHA_Token" type="hidden" value="">
</div>
EOT;
    	}
    		    
    	if(DEBUG) {
			echo "<div id=\"debug\" class=\"jumbotron \">\n";
			
			echo "	<div class=\"card mb-4\">\n";
			echo "		<div class=\"card-header\">\n";
			echo "			Session Information\n";
			echo "		</div>\n";
			echo "		<div class=\"card-body\">\n";
			echo "			<pre>\n";
								print_r($_SESSION);
			echo "			</pre>\n";
			echo "		</div>\n";
			echo "	</div>\n";
			
			echo "	<div class=\"card mb-4\">\n";
			echo "		<div class=\"card-header\">\n";
			echo "			Ajax Info\n";
			echo "		</div>\n";
			echo "		<div class=\"card-body\">\n";
			echo "			<div id=\"debug-ajax\">";
			echo "			</div>\n";
			echo "		</div>\n";
			echo "	</div>\n";
			
			echo "	<button id=\"debug-info\" class=\"btn btn-danger btn-sm btn-block\" type=\"button\">Close Debug</button>\n";
			
			echo "</div>\n";
		}
	    
	    //foot JS files and scripts
    	echo $this->_variables_obj->getFootScripts();
    	
    	//Only allow site wide scripts if we are not logged in
    	if(empty($_SESSION['user_id']))
    	{
	    	//if there are site scripts we can include them
	    	if(is_file(DIR_SECURE_FILES.'/site_scripts.txt'))
	    	{
		    	echo "\n<!-- Site Scripts -->\n";
		    	include_once(DIR_SECURE_FILES.'/site_scripts.txt');
		    	echo "\n<!-- End Site Scripts -->\n";
	    	}
    	}
    	
    	if($grc)
    	{
	    	
echo <<<EOT
 <script nonce="{$nonce}">
	grecaptcha.ready(function () {
        grecaptcha.execute('{$grc}', { action: 'pageview' }).then(function (token) {
            $('#reCAPTCHA_Token').val(token);
        });
    });
 </script>
EOT;
    	}

	    
		//end body
	    echo "\n</body>\n";
	    
	    //end html
		echo "</html>";
    }
     	
}
?>