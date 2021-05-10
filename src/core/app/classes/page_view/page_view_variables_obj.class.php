<?php
namespace core\app\classes\page_view;

/**
 * Final page_view_variables_obj is the actual class.
 * 
 * This is a class to handle all of the view variables
 *
 * @final
 * @package 	view
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 August 2019
 */
final class page_view_variables_obj {

	//stuff for view
	private $_viewVariables_a = array();
	private $_widgetViewVariables_a = array();
	private $_cssHeadFile_a = array();
	private $_cssHead_a = array();
	
	//head items
	private $_manifest = false;
	private $_lang = 'en';
	private $_base = false;
	private $_charset = 'UTF-8';
	private $_pageTitle = '';
	private $_headPageTitle = '';
	private $_links = false;
	private $_metaHttpEquivTags_a = array();
	private $_metaNameTags_a = array();
	private $_headSrc_a = array();
	private $_headScript_a = array();
	private $_nonce;
	
	//body items
	private $_bodyJS_a = array();
	private $_view_template = ''; //the view template that the model set to use
	
	//foot items
	private $_footSrc_a = array();
	private $_footScript_a = array();
	
	//JScript Libraries
	private $_useSWAL2 = false; //Sweet Alert 2 https://sweetalert2.github.io
	private $_useSortable = false; //Sortable anything http://sortablejs.github.io/Sortable/
	private $_useEkkoLightBox = false; //http://ashleydw.github.io/lightbox/
	private $_useFlatpickr = false;
	private $_useDatatable = false;
	private $_useChartJs = false;
	private $_useBootstrapJs = false;
	private $_useSelect2 = false;
	private $_useMoment = false;

	//JQuery Libraries
	private $_useCroppie = false; //Croppie used to crop pictures https://foliotek.github.io/Croppie/
	private $_useTrumbowyg = false; //trumbowyg used as a wysiwyg editor https://alex-d.github.io/Trumbowyg/documentation/

    private $_useSimpleXlsx = false;
	private $_useFullcalendar = false;
    //Rel tag for home
	private $_relMetaTag = false;
	
	public function __construct()
	{
		//build the nonce for the scripts
		$this->_nonce = uniqid();
		
		//needed to build all pages so add it here
		$this->addViewVariables('nonce',$this->_nonce);
		
		//process the meta tags
		$this->_processMeta();
		return;
	}
	
	//process meta information
	private function _processMeta()
	{
		//load the site ini file
	    if(is_file(DIR_SECURE_INI.'/site_meta.ini'))
	    {
	    	$site_meta_a = parse_ini_file(DIR_SECURE_INI.'/site_meta.ini',true);     
	    } elseif (is_file(DIR_APP_INI.'/site_meta.ini')) {
	    	$site_meta_a = parse_ini_file(DIR_APP_INI.'/site_meta.ini',true);     
	    } else {
	    	$msg = 'The INI file site_meta can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
	    
		//charset - there can be only one
		$this->_charset = $site_meta_a['charset']['charset'];
		
		//name tags
		$index = 0;
		
		//site name tags
	    foreach($site_meta_a['name'] as $title => $content)
	    {
			$this->addHeadMetaNameTags($index,$title,$content);
			$index++;
	    }
	    
	    //http-equiv tags
		$index = 0;
	    foreach($site_meta_a['http-equiv'] as $title => $content)
	    {
			$this->addHeadMetaHttpEquivTags($index,$title,$content);
			$index++;
	    }
	    
		return;
	}
	
	//!TEMPLATE HANDLING
	public function setViewTemplate($name)
	{
		$this->_view_template = $name;
		return;
	}
	
	public function getViewTemplate()
	{
		return $this->_view_template;
	}
	
	//!HTML SET
	public function useHTMLManifest($url)
	{
		$this->_manifest = $url;
		return;
	}
	
	public function useHTMLLang($lang)
	{
		$this->_lang = $lang;
		return;
	}
	
	//!HEAD SET
	/**
	 * setBase function.
	 * 
	 * The <base> tag specifies the base URL/target for all relative URLs in a document.
	 *
	 * @access public
	 * @param string $url must be a valid url
	 * @param string $target must be a valid target
	 * @return void
	 */
	public function setHeadBase($url,$target)
	{
		$this->_base = "<base href=\"{$url}\" target=\"{$target}\">\n";
		return;
	}

	public function setHeadCharset($charset)
	{
		$this->_charset = $charset;
		return;
	}
	
	public function setPageTitle($pageTitle)
	{
		$this->_pageTitle = $pageTitle;
		return;
	}
	
	public function setHeadPageTitle($siteTitle)
	{
		if(!empty($siteTitle))
		{
			$this->_headPageTitle = "<title>{$siteTitle} : ".$this->_pageTitle."</title>\n";
		} else {
			$this->_headPageTitle = "<title>".$this->_pageTitle."</title>\n";
		}
		return;
	}
	
	public function addHeadMetaNameTags($index,$title,$content)
	{
		$this->_metaNameTags_a[$index] = array('title'=>$title,'content'=>$content);
		return;
	}
	
	public function addHeadMetaHttpEquivTags($index,$title,$content)
	{
		$this->_metaHttpEquivTags_a[$index] = array('title'=>$title,'content'=>$content);
		return;
	}
	
	//add a link to the header
	public function addHeadLink($rel,$type,$href,$media='',$hreflang='',$sizes='',$integrity='',$crossorigin='')
	{
		$output = '<link';
		if(!empty($rel)) $output .= " rel=\"$rel\"";
		if(!empty($type)) $output .= " type=\"$type\"";
		if(!empty($href)) $output .= " href=\"$href\"";
		if(!empty($media)) $output .= " href=\"$media\"";
		if(!empty($hreflang)) $output .= " hreflang=\"$hreflang\"";
		if(!empty($sizes)) $output .= " sizes=\"$sizes\"";
		if(!empty($integrity)) $output .= " integrity=\"$integrity\"";
		if(!empty($crossorigin)) $output .= " crossorigin=\"$crossorigin\"";
		$output .= ">\n";
		$this->_links .= $output;
		return;
	}
	
	//css files
	public function addHeadCSSFile($index,$href,$media='',$integrity='',$crossorigin='')
	{
		$this->_cssHeadFile_a[$index] = array('href'=>$href,'media'=>$media,'integrity'=>$integrity,'crossorigin'=>$crossorigin);
		return;
	}
	
	//css scripts
	public function addHeadCSS($css)
	{
		$this->_cssHead_a[] = $css;
		return;
	}

	//the ability to add any source file to the header in index order
	public function addHeadSrcFile($index,$src='',$type='',$async=false,$defer=false,$charset='',$integrity='',$crossorigin='')
	{
		if(!empty($src))
		{
			$this->_headSrc_a[$index] = array('src'=>$src,'type'=>$type,'async'=>$async,'defer'=>$defer,'charset'=>$charset,'integrity'=>$integrity,'crossorigin'=>$crossorigin);
		}
		return;
	}
	
	//actual script to put in header if required - not a good idea!
	public function addHeadScript($index,$script,$type='',$integrity='',$crossorigin='')
	{
		if(!empty($script))
		{
			$this->_headScript_a[$index] = array('script'=>$script,'type'=>$type,'integrity'=>$integrity,'crossorigin'=>$crossorigin);
		}
		return;
	}
	
	//!BODY SET
		
	public function addBodyJS($name,$script)
	{
		$acceptable_array = array(
								'onafterprint', //Script to be run after the document is printed
								'onbeforeprint', //Script to be run before the document is printed
								'onbeforeunload', //Script to be run when the document is about to be unloaded
								'onerror', //Script to be run when an error occur
								'onhashchange', //Script to be run when there has been changes to the anchor part of the a URL
								'onload', //Fires after the page is finished loading
								'onmessage', //Script to be run when the message is triggered
								'onoffline', //Script to be run when the browser starts to work offline
								'ononline', //Script to be run when the browser starts to work online
								'onpagehide', //Script to be run when a user navigates away from a page
								'onpageshow', //Script to be run when a user navigates to a page
								'onpopstate', //Script to be run when the window's history changes
								'onresize', //Fires when the browser window is resized
								'onstorage', //Script to be run when a Web Storage area is updated
								'onunload' //Fires once a page has unloaded or the browser window has been closed
							);
		
		if(in_array($name, $acceptable_a))
		{
			$this->_bodyJS_a[$name] = $script;
		}
		return;
	}
		
	public function addViewVariables($name,$value)
	{
		$this->_viewVariables_a[$name] = $value;
		return;
	}
		
	//!FOOTER SET
			
	public function addFootSrcFile($index,$src='',$type='',$async=false,$defer=false,$charset='',$integrity='',$crossorigin='')
	{
		if(!empty($src))
		{
			$this->_footSrc_a[$index] = array('src'=>$src,'type'=>$type,'async'=>$async,'defer'=>$defer,'charset'=>$charset,'integrity'=>$integrity,'crossorigin'=>$crossorigin);
		}
		return;
	}
	
	public function addFootScript($index,$script,$type='',$integrity='',$crossorigin='')
	{
		if(!empty($script))
		{
			$this->_footScript_a[$index] = array('script'=>$script,'type'=>$type,'integrity'=>$integrity,'crossorigin'=>$crossorigin);
		}
		return;
	}
	
	//!HTML GET

	public function getHTMLTag()
	{
		$output = '<html';
		
		if($this->_manifest)
		{
			$output .= ' manifest="'.$this->_manifest.'"';
		}
		
		if($this->_lang)
		{
			$output .= ' lang="'.$this->_lang.'"';
		}
			
		$output .= ">\n";
		
		return $output;
	}
	
	//!HEAD GET
	
	public function getHeadBase()
	{
		return $this->_base;
	}
	
	public function getHeadCharset()
	{
		if($this->_charset)
		{
			$output = '<meta charset="'.$this->_charset."\">\n";
		} else {
			$output = "<meta charset=\"UTF-8\">\n";
		}
		return $output;
	}
	
	public function getPageTitle()
	{
		return $this->_pageTitle;
	}
	
	public function getHeadPageTitle()
	{
		return $this->_headPageTitle;
	}
	
	public function getHeadMetaNameTags()
	{
		//required content security policy
		$output = '<meta http-equiv="Content-Security-Policy" ';
		$output .= 'content="';
		
		$output .= 'default-src';
			$output .= ' \'self\'';
			$output .= ';';
			
		$output .= 'frame-src';
			$output .= ' \'self\'';
			$output .= ' https://www.google.com';
			$output .= ' https://www.youtube.com';
			$output .= ';';
			
		$output .= 'connect-src';
			$output .= ' \'self\'';
            $output .= ' https://www.google-analytics.com';
            $output .= ' https://stats.g.doubleclick.net';
			$output .= ' https://generator.ngide.net';
			$output .= ';';
			
		$output .= 'script-src';
			$output .= ' \'self\'';
			$output .= ' \'nonce-'.$this->_nonce.'\'';
			$output .= ' \'unsafe-inline\'';
			$output .= ' \'unsafe-eval\'';
			$output .= ';';
			
		$output .= 'script-src-elem';
			$output .= ' \'self\'';
            $output .= ' https://www.googletagmanager.com';
            $output .= ' https://connect.facebook.net';
            $output .= ' https://www.google.com';
            $output .= ' https://www.google-analytics.com';
            $output .= ' https://stats.g.doubleclick.net';
            $output .= ' https://www.gstatic.com';
            $output .= ' https://cdnjs.cloudflare.com';
            $output .= ' \'unsafe-inline\'';
			$output .= ' \'unsafe-eval\'';
			$output .= ';';
			
		$output .= 'font-src';
			$output .= ' \'self\'';
			$output .= ' https://use.fontawesome.com';
			$output .= ' https://fonts.googleapis.com';
			$output .= ' https://fonts.gstatic.com';
			$output .= ';';

		$output .= 'style-src ';
			$output .= '\'self\' ';
			$output .= ' https://use.fontawesome.com';
			$output .= ' https://cdnjs.cloudflare.com';
            $output .= ' https://cdn.jsdelivr.net';
            $output .= ' https://cdn.datatables.net';
            $output .= ' https://fonts.googleapis.com';
			$output .= ' https://www.rugstudio.com/';
			$output .= ' \'unsafe-inline\'';
			$output .= ';';
			
		$output .= 'img-src ';
			$output .= ' \'self\' ';
			$output .= ' data: ';
			$output .= ' https://mdbootstrap.com';
            $output .= ' https://www.google.com';
            $output .= ' https://www.google.com.au';
            $output .= ' https://www.facebook.com';
            $output .= ' https://www.google-analytics.com';
            $output .= ' https://via.placeholder.com';
			$output .= ';">'."\n";
			
			
			
			//$output .= ' https: ';
			//$output .= ' https://cdn.jsdelivr.net';
			//$output .= ' https://code.jquery.com';
			//$output .= ' https://stackpath.bootstrapcdn.com';
			//$output .= ' https://use.fontawesome.com';
			//$output .= ' https://cdn.jsdelivr.net';
			//$output .= ' https://www.google.com';
			//$output .= ' https://www.facebook.com';
			//$output .= ' https://www.gstatic.com';
			//$output .= ' https://www.google-analytics.com';
			//$output .= ' https://stats.g.doubleclick.net';
			//$output .= ' \'unsafe-inline\'';
		
		if(!empty($this->_metaNameTags_a))
		{
			ksort($this->_metaNameTags_a);
			foreach($this->_metaNameTags_a as $value)
			{
				$output .= "<meta name=\"{$value['title']}\" content=\"{$value['content']}\">\n";
			}
		}
		
		//automatic name tags at the end
		if( isset($this->_viewVariables_a['page_contents']['page_info']) )
		{
			//description
			if(!empty($this->_viewVariables_a['page_contents']['page_info']['page_sdesc']))
			{
				$output .= '<meta name="description" content="'.$this->_viewVariables_a['page_contents']['page_info']['page_sdesc']."\">\n";
			}
			
			//keywords
			if(!empty($this->_viewVariables_a['page_contents']['page_info']['page_keywords']))
			{
				$output .= '<meta name="keywords" content="'.$this->_viewVariables_a['page_contents']['page_info']['page_keywords']."\">\n";
			}
		}
				
		return $output;
	}
	
	public function getNonce()
	{
		return $this->_nonce;
	}
	
	public function setRelMetaTag($link_id)
	{
		$this->_relMetaTag = $link_id;
	}
	
	public function getHeadMetaHttpEquivTags()
	{
		$output = '';
		if(!empty($this->_metaHttpEquivTags_a))
		{
			ksort($this->_metaHttpEquivTags_a);
			foreach($this->_metaHttpEquivTags_a as $value)
			{
				$output .= "<meta http-equiv=\"{$value['title']}\" content=\"{$value['content']}\">\n";
			}
		}
		return $output;
	}
	
	public function getHeadLinks()
	{
		//add the css to the links before output
		if(!empty($this->_cssHeadFile_a))
		{
			ksort($this->_cssHeadFile_a);
			foreach($this->_cssHeadFile_a as $value)
			{
				$this->addHeadLink('stylesheet','text/css',$value['href'],$value['media'],'','',$value['integrity'],$value['crossorigin']);
			}
		}
		
		if($this->_relMetaTag)
		{
			$href = HTTP_TYPE.SITE_WWW.'/'.$this->_relMetaTag;
			$this->addHeadLink('canonical','',$href,'','','','','');
		}

		//output the links;
		return $this->_links;
	}
	
	public function getHeadCSS()
	{
		$out = '';
		if(!empty($this->_cssHead_a))
		{
			$out .= "<style>\n";
			foreach($this->_cssHead_a as $css)
			{
				$out .= "$css\n";
			}
			$out .= "</style>\n";
		}
		return $out;
	}
	
	public function getHeadScripts()
	{
		$output = '';
		
		//add the source files to header
		if(!empty($this->_headSrc_a))
		{
			ksort($this->_headSrc_a);
			foreach($this->_headSrc_a as $value)
			{
				$output .= '<script nonce="'.$this->_nonce.'" src="'.$value['src'].'" ';
				if(!empty($value['type'])) $output .= " type=\"{$value['type']}\"";
				if($value['async']) 
				{
					$output .= " async";
				} elseif($value['defer']) {
					$output .= " defer";
				}
				if(!empty($value['charset'])) $output .= " charset=\"{$value['charset']}\"";
				if(!empty($value['integrity'])) $output .= " integrity=\"{$value['integrity']}\"";
				if(!empty($value['crossorigin'])) $output .= " crossorigin=\"{$value['crossorigin']}\"";
				$output .= "></script>\n";
			}
		}
		
		//add the head scripts if you really have too
		if(!empty($this->_headScript_a))
		{
			ksort($this->_headScript_a);
			foreach($this->_headScript_a as $value)
			{
				$output .= '<script nonce="'.$this->_nonce.'" ';
				if(!empty($value['type'])) $output .= " type=\"{$value['type']}\"";
				$output .= ">\n";
				$output .= $value['script'];
				$output .= "\n</script>\n";
			}
		}
		
		return $output;
	}
	
	//!BODY GET
	
	public function getBodyJS()
	{
		if(!empty($this->_bodyJS_a))
		{
			return $this->_bodyJS_a;
		} else {
			return false;
		}
	}
	
	public function getViewVariables()
	{
		return $this->_viewVariables_a;
	}
	
	//!FOOTER GET
	
	//scripts - mostly JavaScript
	public function getFootScripts()
	{
		$output = '';
		
		//add the source files to footer
		if(!empty($this->_footSrc_a))
		{
			ksort($this->_footSrc_a);
			foreach($this->_footSrc_a as $value)
			{
				$output .= '<script nonce="'.$this->_nonce.'" src="'.$value['src'].'" ';
				if(!empty($value['type'])) $output .= " type=\"{$value['type']}\"";
				if($value['async']) 
				{
					$output .= " async";
				} elseif($value['defer']) {
					$output .= " defer";
				}
				if(!empty($value['charset'])) $output .= " charset=\"{$value['charset']}\"";
				if(!empty($value['integrity'])) $output .= " integrity=\"{$value['integrity']}\"";
				if(!empty($value['crossorigin'])) $output .= " crossorigin=\"{$value['crossorigin']}\"";
				$output .= "></script>\n";
			}
		}

		//add the foot scripts if you really have too
		if(!empty($this->_footScript_a))
		{
			ksort($this->_footScript_a);
			foreach($this->_footScript_a as $value)
			{
				$output .= '<script nonce="'.$this->_nonce.'" ';
				if(!empty($value['type'])) $output .= " type=\"{$value['type']}\"";
				$output .= ">\n";
				$output .= $value['script'];
				$output .= "\n</script>\n";
			}
		}
		return $output;
	}
	
	/**
	 *
	 * MAJOR SECTION FOR INCLUDED LIBRARIES
	 * 
	 * All library controls should be below this message
	 *
	 * Base of the whole thing is MDBootstrap
	 *
	 **/
	
	//!SET FLAGS FOR LIBRARIES THAT REQUIRE JSCRIPT ONLY
	
	public function useSweetAlert()
	{
		$this->_useSWAL2 = true;
		return;
	}
	
	public function useSortable()
	{
		$this->_useSortable = true;
		return;
	}
	
	//!SET FLAGS FOR LIBRARIES THAT REQUIRE JSCRIPT AND JQUERY ONLY
	
	public function useCroppie()
	{
		$this->_useCroppie = true;
	}
	
	public function useTrumbowyg()
	{
		$this->_useTrumbowyg = true;
	}
    public function useFlatpickr()
    {
        $this->_useFlatpickr = true;
    }

    public function useEkkoLightBox()
    {
	    $this->_useEkkoLightBox = true;
    }
    public function useDatatable()
    {
	    $this->_useDatatable = true;
    }

    public function useSimpleXlsx()
    {
        $this->_useSimpleXlsx = true;
    }

    public function useChartJs()
    {
        $this->_useChartJs = true;
	}
	
	public function useSelect2()
	{
		$this->_useSelect2 = true;
	}

	public function useMoment()
	{
		$this->_useMoment = true;
	}

	public function useFullcalendar()
	{
		$this->_useFullcalendar = true;
	}

	//!ADD Header or Footer Scripts and CSS
	
	public function processSetHeaderFlags()
	{
		//loaded first as it is JS only
		
		//Sweet Alert 2 https://sweetalert2.github.io download https://www.jsdelivr.com/package/npm/sweetalert2 and https://github.com/SortableJS/jquery-sortablejs
		if($this->_useSWAL2)
		{
			$this->addFootSrcFile(32,'/lib/sweetalert2/9.1.3/sweetalert2.all.min.js');
		}
		
		//Sortable http://sortablejs.github.io/Sortable/ download https://github.com/SortableJS/Sortable
		if($this->_useSortable)
		{
			//sortable will work without bootstrap
			$this->addFootSrcFile(25,'/lib/sortable/1.10.0/sortable.min.js');
			//jquery adaption
			$this->addFootSrcFile(35,'/lib/sortable/jquery-sortable.min.js');
		}
		
		//Converted to us (mostly) MDBootstrap
		$MDB_Root = '/lib/mdb/4.12.0/';
		
		//header css
		$this->addHeadCSSFile(30,'/lib/fontawesome/5.12.1/css/all.css');
		$this->addHeadCSSFile(31,'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i');
		
		$this->addHeadCSSFile(70,$MDB_Root.'css/bootstrap.min.css');
		$this->addHeadCSSFile(80,$MDB_Root.'css/mdb.min.css');
		$this->addHeadCSSFile(90,$MDB_Root.'css/style.css');
		
		//footer scripts
		$this->addFootSrcFile(30,$MDB_Root.'js/jquery.min.js');
		
		$this->addFootSrcFile(60,$MDB_Root.'js/popper.min.js');
		$this->addFootSrcFile(70,$MDB_Root.'js/bootstrap.min.js');
		$this->addFootSrcFile(80,$MDB_Root.'js/mdb.min.js');

		//Croppie used to crop pictures https://foliotek.github.io/Croppie/
		if($this->_useCroppie)
		{
			$this->addHeadCSSFile(40,'/lib/croppie/2.6.4/croppie.css');
			$this->addFootSrcFile(40,'/lib/croppie/2.6.4/croppie.min.js');
		}
		
		//trumbowyg used as a wysiwyg editor https://alex-d.github.io/Trumbowyg/documentation/
		if($this->_useTrumbowyg)
		{
			$this->addHeadCSSFile(50,'/lib/trumbowyg/2.20.0/ui/trumbowyg.min.css');
			$this->addFootSrcFile(50,'/lib/trumbowyg/2.20.0/trumbowyg.min.js');
			
			$this->addFootSrcFile(51,'/lib/trumbowyg/2.20.0/plugins/cleanpaste/trumbowyg.cleanpaste.min.js');
			
			$this->addHeadCSSFile(52,'/lib/trumbowyg/2.20.0//plugins/colors/ui/trumbowyg.colors.min.css');
			$this->addFootSrcFile(52,'/lib/trumbowyg/2.20.0/plugins/colors/trumbowyg.colors.min.js');
		}

		if($this->_useEkkoLightBox){
            $this->addHeadCSSFile(31,'/lib/ekko-lightbox/ekko-lightbox.css');
            $this->addFootSrcFile(31,'/lib/ekko-lightbox/ekko-lightbox.min.js');
        }

        if($this->_useFlatpickr){
            $this->addHeadCSSFile(33,'/lib/flatpickr/flatpickr.min.css');
            $this->addFootSrcFile(33,'/lib/flatpickr/flatpickr.js');
        }

        if($this->_useDatatable){

            $this->addHeadCSSFile(91,'/lib/datatable/dataTables.bootstrap4.min.css');
            $this->addHeadCSSFile(92,'/lib/datatable/datatables.min.css');
            $this->addHeadCSSFile(93,'/lib/datatable/rowReorder.dataTables.min.css');
            $this->addFootSrcFile(34,'/lib/datatable/datatables.min.js');
            $this->addFootSrcFile(35,'/lib/datatable/dataTables.rowReorder.min.js');
        }

        if($this->_useChartJs){

            $this->addHeadCSSFile(100,'/lib/chart_js/2.9.3/Chart.min.css');
            $this->addFootSrcFile(100,'/lib/chart_js/2.9.3/Chart.min.js');
		}
		
		if ($this->_useSelect2) {
			$this->addHeadCSSFile(101, '/lib/select2/css/select2.min.css');
			$this->addFootSrcFile(65, '/lib/select2/js/select2.min.js');
		}

		if ($this->_useMoment) {
			$this->addFootSrcFile(110, '/lib/moment/js/moment.min.js');
		}

        if($this->_useSimpleXlsx){
		    require_once (DIR_LIB.'/simplexlsx/SimpleXLSX.php');
		}
		
		if ($this->_useFullcalendar) {
			$this->addHeadCSSFile(102, '/lib/fullcalendar/main.css');
			$this->addFootSrcFile(66, '/lib/fullcalendar/main.js');
		}
		return;
	}
	
}	
?>