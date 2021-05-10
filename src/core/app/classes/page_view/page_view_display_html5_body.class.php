<?php
namespace core\app\classes\page_view;

/**
 * Final page_view_display_html5_body class.
 * 
 * This is a class to handle all of the html5 output of the body other than 
 * the content of the actual module!  That is in module_view_display_html5.class.php
 *
 * @final
 * @package     view_display_html5_body
 * @author      Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 16 August 2019
 */
final class page_view_display_html5_body {
    
    //derived variables
    private $_main_nav;
    private $_mobile_nav;
    private $_footer_nav;
    private $_template_base_dir; //the base directory for the template base on name
    
    //registers
    private $_menu_register;
    private $_system_register;
    
    //known and defined variables
    private $_isHome;           //page_info - is home
    private $_link;             //page_info - link
    
    private $_templateName;
    private $_templateInfo_a;
    private $_main_nav_a;
    private $_quick_nav_a;
    private $_bottom_nav_a;
    private $_ini_acceptableNavFormats = array('standard','modern','sidebar');
    private $_ini_navFormat = 'standard';
    
    //output variables
    private $_siteTitle;
    private $_siteSlogan;
    private $_clientName;
    private $_siteTagManager;
    private $_siteReCAPTCHA;
    
    //body output
    private $_bodyContent = '';
    
    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        //load in the registers
        $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
        $this->_menu_register = $menu_register_ns::getInstance();
        
        //need for page production
        $system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
        $this->_system_register = $system_register_ns::getInstance();
        
        //setup required page info variables
        $page_view_ns = NS_APP_CLASSES.'\\page_info\\page_info';
        $page_info = $page_view_ns::getInstance();
        $this->_link = $page_info->getLink();
        $this->_isHome = $page_info->getHome();
        
        //all the functions
        $this->_setKnownVariables();
        $this->_setTemplateInfo();
        $this->_setPageMainNav();
        $this->_setMobileMainNav_sidebar();
        $this->_setPageFooterNav();
        $this->_setOutputVariables();
        $this->_processSections();
        return;
    }
    
    public function getBodyContent()
    {
        return $this->_bodyContent;
    }
    
    public function getSiteTagManager()
    {
        if(empty($this->_siteTagManager))
        {
            $out = false;
        } else {
            $out = $this->_siteTagManager;
        }
        return $out;
    }
    
    public function getSiteReCAPTCHA()
    {
        if(empty($this->_siteReCAPTCHA))
        {
            $out = false;
        } else {
            $out = $this->_siteReCAPTCHA;
        }
        return $out;
    }
    
    public function addRelTag()
    {
        $out = false;
        
        if($this->_isHome)
        {
            $out = $this->_link;
        }
            
        return $out;
    }
    
    private function _setKnownVariables()
    {
        //get template name
        $this->_templateName = $this->_menu_register->getTemplateName($this->_link);
        
        //set the base template directory based on the templateName
        if($this->_templateName)
        {
            
            $this->_template_base_dir = DIR_PAGEVIEWS.'/'.$this->_templateName;
            
        } else {
            $msg = "No template found in page view display!";
            throw new \RuntimeException($msg);
        }
        
        //site interface ini
        if(is_file(DIR_SECURE_INI.'/site_interface.ini'))
        {
            
            $wui_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_interface.ini',true);
            
        } else if(is_file(DIR_APP_INI.'/site_interface.ini')) {
            
            $wui_ini_a = parse_ini_file(DIR_APP_INI.'/site_interface.ini',true);
            
        } else {
            
            $msg = 'Some how you are missing the site interface ini file!';
            throw new \RuntimeException($msg);
            
        }
        
        foreach($wui_ini_a as $zone => $record)
        {
            foreach($record as $name => $value)
            {
                $id = '_'.$zone.'_'.$name;
                $this->$id = $value;
            }
        }
        
        return;
    }
    
    private function _setTemplateInfo()
    {
        //load the ini file
        $viewINI = $this->_template_base_dir.'/base.ini';
        
        if(is_file($viewINI))
        {
            $templateInfo_a = @parse_ini_file($viewINI, true);
            
            //get the settings off
            if( isset($templateInfo_a['settings']) && is_array($templateInfo_a['settings']) )
            {
                foreach($templateInfo_a['settings'] as $key => $value)
                {
                    if($key == 'navFormat')
                    {
                        if(in_array($value, $this->_ini_acceptableNavFormats))
                        {
                            $this->_ini_navFormat = $value;
                        } else {
                            $msg = "Unacceptable navigation format {$value} in {$this->_templateName} ini file!";
                            throw new \RuntimeException($msg);
                        }
                    } else {
                        $kv = '_ini_'.$key;
                        $this->$kv = $value;    
                    }   
                }
            }
            
        } else {
            $msg = "Can not find page ini {$viewINI}!";
            throw new \RuntimeException($msg);   
        }
        
        $templateFile = $this->_template_base_dir.'/base.template';
        $handle = @fopen($templateFile, "r");
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
                $msg = "Error: unexpected failure reading {$templateFile} template\n";
                throw new \RuntimeException($msg); 
            }
            fclose($handle);
        } else {
            $msg = "Can not find page template {$templateFile}!";
            throw new \RuntimeException($msg);   
        }
        
        return;
    }
    
    
    /**
     * _setPageMainNav function.
     * 
     * Sort of a factory using _ini_navFormat to define which one to build
     *
     * @access private
     * @return void
     */
    private function _setPageMainNav()
    {
        //figure out which one we need
        $navFormatFunc = '_setHTMLMainNav_'.$this->_ini_navFormat;
        $this->$navFormatFunc();
        return;
    }

    /**
     * _setHTMLMainNav_standard function.
     * 
     * General nav format and inserts the nav (ul) from the next function
     * sort of a wrapper to the ul.
     *
     * @access private
     * @return void
     */
    private function _setHTMLMainNav_standard()
    {
        //this is the standard ul version of the navigation
        $this->_main_nav = '<ul class="navbar-nav mr-auto">'."\n";
        
        $this->_main_nav .= $this->_setHTMLMainNav_standard_UL();
        
        $this->_main_nav .= '</ul>'."\n";
        
        //check log-in or log-out still needs security link
        $security_link = $this->_menu_register->getModuleLink('security');
        
        if(isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] > 0)
        {
            //insert the security link for log out
            $this->_main_nav .= '<ul class="navbar-nav navbar-right">'."\n";
            
                if($this->_system_register->getModuleIsInstalled('profile'))
                {
                    $profile_link = $this->_menu_register->getModuleLink('profile');
                } else {
                    $profile_link = false;
                }
                
                if($profile_link)
                {
                    if($this->_link == $profile_link)
                    {
                        //insert profile link (only if logged in) 
                        $this->_main_nav .= '   <li class="nav-item active"><a class="nav-link" href="/'.$profile_link.'"><i class="fas fa-user"></i> Profile</a></li>'."\n";
                    } else {
                        $this->_main_nav .= '   <li class="nav-item"><a class="nav-link" href="/'.$profile_link.'"><i class="fas fa-user"></i> Profile</a></li>'."\n";
                    }
                }


            if($this->_link == $security_link)
                {
                    $this->_main_nav .= '   <li class="nav-item active"><a class="nav-link" href="/'.$security_link.'/logoff"><i class="fas fa-sign-out-alt"></i> Log Off</a></li>'."\n";
                } else {
                    $this->_main_nav .= '   <li class="nav-item"><a class="nav-link" href="/'.$security_link.'/logoff"><i class="fas fa-sign-out-alt"></i> Log Off</a></li>'."\n";  
                }

            $this->_main_nav .= "</ul>\n";
            
        } else {
            //insert the security link for log in
            $this->_main_nav .= '<ul class="navbar-nav navbar-right">'."\n";
                
            if($this->_link_useInTopNav)
            {
        
                $this->_main_nav .= '<!--Social Icons-->'."\n";
                $this->_main_nav .= '<li><ul class="navbar-nav nav-flex-icons">'."\n";
                
                if($this->_link_facebook)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.facebook.com/'.$this->_link_facebook.'/">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-facebook"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }
                
                if($this->_link_twitter)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.twitter.com/'.$this->_link_twitter.'/">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-twitter"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }
                
                if($this->_link_instagram)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.instagram.com/'.$this->_link_instagram.'/">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-instagram"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }
                
                if($this->_link_youtube)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.youtube.com/channel/'.$this->_link_youtube.'">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-youtube"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }
                
                $this->_main_nav .= '</ul></li>'."\n";
            }
            
            if($this->_link == $security_link)
            {
                $this->_main_nav .= '   <li class="nav-item active"><a class="nav-link" href="/'.$security_link.'"><i class="fas fa-sign-in-alt"></i> Login</a></li>'."\n";
                $this->_main_nav .= '   <li class="nav-item active"><a class="nav-link" href="/'.$security_link.'"><i class="fas fa-user"></i> Register</a></li>'."\n";
            } else {
                $this->_main_nav .= '   <li class="nav-item"><a class="nav-link" href="/'.$security_link.'"><i class="fas fa-sign-in-alt"></i> Login</a></li>'."\n";
                $this->_main_nav .= '   <li class="nav-item"><a class="nav-link" href="/'.$security_link.'"><i class="fas fa-user"></i> Register</a></li>'."\n";
            }
            
            $this->_main_nav .= '</ul>'."\n";
        }
        
        return;
    }

    /**
     * _setHTMLMainNav_standard function.
     *
     * General nav format and inserts the nav (ul) from the next function
     * sort of a wrapper to the ul.
     *
     * @access private
     * @return void
     */
    private function _setHTMLMainNav_modern()
    {
        //this is the standard ul version of the navigation
        $this->_main_nav = '<ul class="navbar-nav mr-auto">'."\n";

        $this->_main_nav .= $this->_setHTMLMainNav_standard_UL();

        $this->_main_nav .= '</ul>'."\n";

        //check log-in or log-out still needs security link
        $security_link = $this->_menu_register->getModuleLink('security');

        //check if register module is installed
        if($this->_system_register->getModuleIsInstalled('register'))
        {
            $register_link = $this->_menu_register->getModuleLink('register');
        } else {
            $register_link = false;
        }

        if(isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] > 0)
        {
            //insert the security link for log out
            $this->_main_nav .= '<ul class="navbar-nav navbar-right">'."\n";

            if($this->_system_register->getModuleIsInstalled('profile'))
            {
                $profile_link = $this->_menu_register->getModuleLink('profile');
            } else {
                $profile_link = false;
            }

            $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
            $main = $this->address_book_db->getAddressBookMainDetails($_SESSION['address_book_id']);
            $this->_main_nav .= '<li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Hi '.$main['number_given_name'].'
                <!--<img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">-->
              </a>
              <div class="dropdown-menu 
              dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
            if($profile_link)
            {
                $this->_main_nav .= '   <a class="dropdown-item" href="/'.$profile_link.'">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>';

            }

            if($this->_system_register->getModuleIsInstalled('personal'))
            {
                $personal_link = $this->_menu_register->getModuleLink('personal');
                $this->_main_nav .= '   <a class="dropdown-item" href="/'.$personal_link.'">
                  <i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                  Personal
                </a>';
            } else {
                $personal_link = false;
            }

            //cek status verification -- job application
            $this->personal_db = new \core\modules\personal\models\common\db;
            $data_verification = $this->personal_db->checkVerification($_SESSION['address_book_id']);
            if(count($data_verification)>0) {
                if($data_verification['status']=='verified') {
                    if($this->_system_register->getModuleIsInstalled('job_application'))
                    {
                        $job_link = $this->_menu_register->getModuleLink('job_application');
                        $this->_main_nav .= '   <a class="dropdown-item" href="/'.$job_link.'">
                        <i class="fas fa-handshake fa-sm fa-fw mr-2 text-gray-400"></i>
                        Job
                        </a>';
                    } else {
                        $job_link = false;
                    }
                }
            }

            //cek status verification -- education
            $this->personal_db = new \core\modules\personal\models\common\db;
            $data_verification = $this->personal_db->checkVerification($_SESSION['address_book_id']);
            if(count($data_verification)>0) {
                if($data_verification['status']=='verified') {
                    if($this->_system_register->getModuleIsInstalled('education_application'))
                    {
                        $education_link = $this->_menu_register->getModuleLink('education_application');
                        $this->_main_nav .= '   <a class="dropdown-item" href="/'.$education_link.'">
                        <i class="fas fa-book-open fa-sm fa-fw mr-2 text-gray-400"></i>
                        Education
                        </a>';
                    } else {
                        $education_link = false;
                    }
                }
            }

            $this->_main_nav .= '<div class="dropdown-divider"></div>';
            $this->_main_nav .= '<a class="dropdown-item" href="/'.$security_link.'/logoff" >
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>';




            $this->_main_nav .= "</ul>\n";

        } else {
            //insert the security link for log in
            $this->_main_nav .= '<ul class="navbar-nav navbar-right">'."\n";

            if($this->_link_useInTopNav)
            {

                $this->_main_nav .= '<!--Social Icons-->'."\n";
                $this->_main_nav .= '<li><ul class="navbar-nav nav-flex-icons">'."\n";

                if($this->_link_facebook)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.facebook.com/'.$this->_link_facebook.'/">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-facebook"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }

                if($this->_link_twitter)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.twitter.com/'.$this->_link_twitter.'/">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-twitter"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }

                if($this->_link_instagram)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.instagram.com/'.$this->_link_instagram.'/">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-instagram"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }

                if($this->_link_youtube)
                {
                    $this->_main_nav .= '   <li class="nav-item">'."\n";
                    $this->_main_nav .= '       <a class="nav-link" href="https://www.youtube.com/channel/'.$this->_link_youtube.'">'."\n";
                    $this->_main_nav .= '           <i class="fab fa-youtube"></i>'."\n";
                    $this->_main_nav .= '       </a>'."\n";
                    $this->_main_nav .= '   </li>'."\n";
                }

                $this->_main_nav .= '</ul></li>'."\n";
            }

            $this->_main_nav .= '   <li class="nav-item '.($this->_link == $security_link ? 'active' : '').'"><a class="nav-link" href="/'.$security_link.'"><i class="fas fa-sign-in-alt"></i> Login</a></li>'."\n";

            if($register_link != false)
                $this->_main_nav .= '   <li class="nav-item"><a class="nav-link '.($this->_link == $register_link ? 'active' : '').'" href="/'.$register_link.'"><i class="fas fa-user"></i> Register</a></li>'."\n";


            $this->_main_nav .= '</ul>'."\n";
        }

        return;
    }
    
    private function _setHTMLMobileNav_modern()
    {
        $security_link = $this->_menu_register->getModuleLink('security');
        $this->_mobile_nav = $this->_setMobileMainNav_sidebar_title();

        if($this->_system_register->getModuleIsInstalled('register'))
        {
            $register_link = $this->_menu_register->getModuleLink('register');
        } else {
            $register_link = false;
        }
        //this is the standard ul version of the navigation
        $this->_mobile_nav .= '<ul class="navbar-nav mr-auto pt-3">'."\n";
        
        $this->_mobile_nav .= $this->_setHTMLMainNav_standard_UL();

        if (empty($_SESSION['user_security_level'])) {
            # code...
            $this->_mobile_nav .= '<li class="nav-item '.($this->_link == $security_link ? 'active' : '').'"><a class="nav-link" href="/'.$security_link.'"><span><i class="fas fa-sign-in-alt"></i> Login</a></span></li>'."\n";
    
            if($register_link != false)
                $this->_mobile_nav .= ' <li class="nav-item"><a class="nav-link '.($this->_link == $register_link ? 'active' : '').'" href="/'.$register_link.'"><span><i class="fas fa-user"></i> Register</a></span></li>'."\n";
        }
        

        if(isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] > 0) {
            # code...
            $this->_mobile_nav .= '<li class="nav-item logout-link"><a class="nav-link " href="/'.$security_link.'/logoff" >
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
          </a></li>';
        }
        

        $this->_mobile_nav .= '</ul>'."\n";

        return;
    }
    
    /**
     * _setHTMLMainNav_standard_UL function.
     * 
     * Makes a formatted UL of the main nav array
     *
     * @access private
     * @return void
     */
    private function _setHTMLMainNav_standard_UL()
    {
        $out = '';
        
        $security_link = $this->_menu_register->getModuleLink('security');
    
        if( $this->_system_register->getModuleIsInstalled('profile') )
        {
            $profile_link = $this->_menu_register->getModuleLink('profile');
        } else {
            $profile_link = false;
        }

        if( $this->_system_register->getModuleIsInstalled('register') )
        {
            $register_link = $this->_menu_register->getModuleLink('register');
        } else {
            $register_link = false;
        }
        
        //main menu a
        $menu_a = $this->_menu_register->getMenuArray($this->_link,'main');
        
        foreach($menu_a as $key => $value)
        {
            //we don't want these as they are in the top nav
            if($value['link_id'] == $security_link || $value['link_id'] == $profile_link || $value['link_id'] == $register_link)
            {
                continue;
            } 
            
            $dropdown = empty($value['children']) ? false : true;
            
            //class setting for li
            if($value['active'] == 1 && $dropdown) 
            {
                $class = 'nav-item active dropdown'; //active and dropdown
            } elseif($value['active'] == 1) {
                $class = 'nav-item active'; //active and not dropdown
            } elseif($dropdown) {
                $class = 'nav-item dropdown';  //not active dropdown
            } else {
                $class = 'nav-item';  //has not active and not dropdown
            }
            
            //the li
            $out .= '<li class="'.$class.'">'."\n";
            
            if($dropdown)
            {
                $out .= '<a class="nav-link dropdown-toggle" id="'.$key.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$value['title_menu'].'</a>'."\n";
                
                //insert the drop down
                $out .= '<div class="dropdown-menu dropdown-primary" aria-labelledby="'.$key.'">'."\n";
                
                foreach( $value['children'] as $dkey => $dvalue)
                {
                    if(!empty($dvalue['redirect_url']))
                    {
                        $out .= '<a class="dropdown-item" href="'.$dvalue['redirect_url'].'" target="_blank">'.$dvalue['title_menu'].'</a>'."\n";
                    } else if($dvalue['active']) {
                        $out .= '<a class="dropdown-item active" href="/'.$dvalue['link_id'].'">'.$dvalue['title_menu'].'</a>'."\n";
                    } else {
                        $out .= '<a class="dropdown-item" href="/'.$dvalue['link_id'].'">'.$dvalue['title_menu'].'</a>'."\n";
                    }
                }

                $out .= '</div>'."\n";

            } else {
                
                if(!empty($value['redirect_url']))
                {
                    $out .= '<a class="nav-link" href="'.$value['redirect_url'].'" target="_blank">'.$value['title_menu'].'</a>'."\n";
                } else if($value['active']) {
                    $out .= '<a class="nav-link active" href="/'.$value['link_id'].'">'.$value['title_menu'].'</a>'."\n";
                } else {
                    $out .= '<a class="nav-link" href="/'.$value['link_id'].'">'.$value['title_menu'].'</a>'."\n";
                }
                    
            }
                
            //end of li
            $out .= '</li>'."\n";
        }
        
        return $out;
    }


    /**
     * _setHTMLMainNav_standard function.
     *
     * General nav format and inserts the nav (ul) from the next function
     * sort of a wrapper to the ul.
     *
     * @access private
     * @return void
     */
    private function _setHTMLMainNav_sidebar()
    {
        //this is the standard ul version of the navigation
        $this->_main_nav = '';

        $this->_main_nav .= $this->_setHTMLMainNav_sidebar_UL();

        $this->_main_nav .= '';

        $this->_profile_nav = $this->_setHTMLProfileNav_sidebar_UL();



        return;
    }
    
    private function _setMobileMainNav_sidebar()
    {
        $this->_setHTMLMobileNav_modern();
        return;
    }

    private function _setMobileMainNav_sidebar_title()
    {
		if(empty($_SESSION['address_book_id'])){
			return;
		}
        $address_book_db = new \core\modules\address_book\models\common\address_book_db_obj;
        $avatar = $address_book_db->getAddressBookFileArray($_SESSION['address_book_id'], 'avatar')[0]['filename'];
        $out = "<div class='row p-3 bg-nav-title'>";
        $nav_profile = '';

        $nav_profile .= '<ul class="navbar-nav navbar-right">'."\n";

            if($this->_system_register->getModuleIsInstalled('profile'))
            {
                $profile_link = $this->_menu_register->getModuleLink('profile');
            } else {
                $profile_link = false;
            }

			$this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
			if(isset($_SESSION['address_book_id'])){
				$main = $this->address_book_db->getAddressBookMainDetails($_SESSION['address_book_id']);
				$nav_profile .= '<li class="nav-item dropdown no-arrow">
				  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Hi '.$main['number_given_name'].'
					<!--<img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">-->
				  </a>
				  <div class="dropdown-menu 
				  dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
				if($profile_link)
				{
					$nav_profile .= '   <a class="dropdown-item" href="/'.$profile_link.'">
					  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
					  Profile
					</a>';
	
				}
	
				if($this->_system_register->getModuleIsInstalled('personal'))
				{
					$personal_link = $this->_menu_register->getModuleLink('personal');
					$nav_profile .= '   <a class="dropdown-item" href="/'.$personal_link.'">
					  <i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i>
					  Personal
					</a>';
				} else {
					$personal_link = false;
				}
			}
            

            //cek status verification -- job application
			$this->personal_db = new \core\modules\personal\models\common\db;
			if(isset($_SESSION['address_book_id'])){
				$data_verification = $this->personal_db->checkVerification($_SESSION['address_book_id']);
				if(count($data_verification)>0) {
					if($data_verification['status']=='verified') {
						if($this->_system_register->getModuleIsInstalled('job_application'))
						{
							$job_link = $this->_menu_register->getModuleLink('job_application');
							$nav_profile .= '   <a class="dropdown-item" href="/'.$job_link.'">
							<i class="fas fa-handshake fa-sm fa-fw mr-2 text-gray-400"></i>
							Job
							</a>';
						} else {
							$job_link = false;
						}
						
						if($this->_system_register->getModuleIsInstalled('education_application'))
						{
							$education_link = $this->_menu_register->getModuleLink('education_application');
							$nav_profile .= '   <a class="dropdown-item" href="/'.$education_link.'">
							<i class="fas fa-book-open fa-sm fa-fw mr-2 text-gray-400"></i>
							Education
							</a>';
						} else {
							$education_link = false;
						}
					}
				}
			}
            
			
            $nav_profile .= '</div></li>';


            $nav_profile .= "</ul>\n";

        $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if (isset($_SESSION['user_id'])) {

            $out .= "<div class='col-4 col-sm-4 col-md-4'>
            <a href='/profile'>
                <img src='/ab/show/$avatar' class='img img-fluid img-thumbnail rounded-circle' alt=''>
            </a>
        </div>
        <div class='col-8 col-sm-8 col-md-8 d-flex align-items-center nav-title'>
            ".$nav_profile."
        </div>";
        } else {
            $out .= "<p style='margin: 0px auto; text-align: center'>
            <b>{$this->_system_register->site_info('SITE_TITLE')}</b>
        </p>";
        }

        $out .= "</div>";

        return $out;

    }

    private function _setHTMLMainNav_sidebar_UL()
    {
        $out = '';

        $security_link = $this->_menu_register->getModuleLink('security');

        if( $this->_system_register->getModuleIsInstalled('profile') )
        {
            $profile_link = $this->_menu_register->getModuleLink('profile');
        } else {
            $profile_link = false;
        }

        //main menu a
        $menu_a = $this->_menu_register->getMenuArray($this->_link,'main');

        foreach($menu_a as $key => $value)
        {
            //we don't want these as they are in the top nav
            if($value['link_id'] == $security_link || $value['link_id'] == $profile_link)
            {
                continue;
            }
            //only show group id IOW
            if($value['group_id'] !== 'IOW')
            {
                continue;
            }

            $dropdown = empty($value['children']) ? false : true;

            //class setting for li
            if($value['active'] == 1 && $dropdown)
            {
                $class = 'nav-item active'; //active and dropdown
            } elseif($value['active'] == 1) {
                $class = 'nav-item active'; //active and not dropdown
            } elseif($dropdown) {
                $class = 'nav-item';  //has not active and not dropdown
            } else {
                $class = 'nav-item';  //has not active and not dropdown
            }

            //the li
            $out .= '<li class="'.$class.'">'."\n";

            if($dropdown)
            {
                $out .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse'.$key.'" id="'.$key.'" aria-haspopup="true" aria-expanded="false"><span>'.$value['title_menu'].'</span></a>'."\n";

                //insert the drop down

                $out .= '<div class="collapse" id="collapse'.$key.'">'."\n";
                $out .= '<div class="bg-white py-2 collapse-inner rounded">';

                foreach( $value['children'] as $dkey => $dvalue)
                {
                    if(!empty($dvalue['redirect_url']))
                    {
                        $out .= '<a class="dropdown-item" href="'.$dvalue['redirect_url'].'" target="_blank"><span>'.$dvalue['title_menu'].'</span></a>'."\n";
                    } else if($dvalue['active']) {
                        $out .= '<a class="dropdown-item active" href="/'.$dvalue['link_id'].'"><span>'.$dvalue['title_menu'].'</span></a>'."\n";
                    } else {
                        $out .= '<a class="dropdown-item" href="/'.$dvalue['link_id'].'"><span>'.$dvalue['title_menu'].'</span></a>'."\n";
                    }
                }

                $out .= '</div>'."\n";
                $out .= '</div>'."\n";

            } else {

                if(!empty($value['redirect_url']))
                {
                    $out .= '<a class="nav-link" href="'.$value['redirect_url'].'" target="_blank"><span>'.$value['title_menu'].'</span></a>'."\n";
                } else if($value['active']) {
                    $out .= '<a class="nav-link active" href="/'.$value['link_id'].'"><span>'.$value['title_menu'].'</span></a>'."\n";
                } else {
                    $out .= '<a class="nav-link" href="/'.$value['link_id'].'"><span>'.$value['title_menu'].'</span></a>'."\n";
                }

            }

            //end of li
            $out .= '</li>'."\n";
        }

        return $out;
    }

    private function _setHTMLProfileNav_sidebar_UL()
    {
        $out = '';

        //check log-in or log-out still needs security link
        $security_link = $this->_menu_register->getModuleLink('security');

        if(isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] > 0) {
            //insert the security link for log out
            $out .= '<ul class="navbar-nav ml-auto">' . "\n";

            if ($this->_system_register->getModuleIsInstalled('profile')) {
                $profile_link = $this->_menu_register->getModuleLink('profile');
            } else {
                $profile_link = false;
            }

            $out .= '<li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hi ' . $_SESSION['user_name'] . '</span>
                <!--<img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">-->
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">';
            if ($profile_link) {
                $out .= '   <a class="dropdown-item" href="/' . $profile_link . '">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>';

            }

            if ($this->_system_register->getModuleIsInstalled('personal')) {
                $personal_link = $this->_menu_register->getModuleLink('personal');
                $out .= '   <a class="dropdown-item" href="/' . $personal_link . '">
                  <i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                  Personal
                </a>';
            } else {
                $personal_link = false;
            }


            $out .= '<div class="dropdown-divider"></div>';
            $out .= '<a class="dropdown-item" href="/' . $security_link . '/logoff" >
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>';

            $out .= '</ul>';
        }

        return $out;
    }
    
    private function _setPageFooterNav()
    {
        $this->_footer_nav = "<nav class='footer-links text-lg-right text-center pt-2 pt-lg-0'>";
        $security_link = $this->_menu_register->getModuleLink('security');
        //main menu a
        $menu_a = $this->_menu_register->getMenuArray($this->_link,'bottom');
        $index = 0;
        foreach($menu_a as $key => $value)
        {
            //we don't want these as they are in the top nav
            if($value['link_id'] == $security_link)
            {
                continue;
            }

            $dropdown = empty($value['children']) ? false : true;
            $class = '';

            if($dropdown)
            {
                $this->_footer_nav .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse'.$key.'" id="'.$key.'" aria-haspopup="true" aria-expanded="false"><span>'.$value['title_menu'].'</span></a>'."\n";

                //insert the drop down

                $this->_footer_nav .= '<div class="collapse" id="collapse'.$key.'">'."\n";
                $this->_footer_nav .= '<div class="bg-white py-2 collapse-inner rounded">';

                foreach( $value['children'] as $dkey => $dvalue)
                {
                    if(!empty($dvalue['redirect_url']))
                    {
                        $this->_footer_nav .= '<a class="dropdown-item" href="'.$dvalue['redirect_url'].'" target="_blank"><span>'.$dvalue['title_menu'].'</span></a>'."\n";
                    } else if($dvalue['active']) {
                        $this->_footer_nav .= '<a class="dropdown-item active" href="/'.$dvalue['link_id'].'"><span>'.$dvalue['title_menu'].'</span></a>'."\n";
                    } else {
                        $this->_footer_nav .= '<a class="dropdown-item" href="/'.$dvalue['link_id'].'"><span>'.$dvalue['title_menu'].'</span></a>'."\n";
                    }
                }

                $this->_footer_nav .= '</div>'."\n";
                $this->_footer_nav .= '</div>'."\n";

            } else {

                if ($index > 0) {
                    $class .= ' pl-2';
                }

                if(!empty($value['redirect_url']))
                {
                    $this->_footer_nav .= '<a class="'.$class.'" href="'.$value['redirect_url'].'" target="_blank"><span>'.$value['title_menu'].'</span></a>'."\n";
                } else if($value['active']) {
                    $this->_footer_nav .= '<a class="'.$class.'" href="/'.$value['link_id'].'"><span>'.$value['title_menu'].'</span></a>'."\n";
                } else {
                    $this->_footer_nav .= '<a class="'.$class.'" href="/'.$value['link_id'].'"><span>'.$value['title_menu'].'</span></a>'."\n";
                }

            }
            $index++;
        
        }
        $this->_footer_nav .= '</nav>'."\n";
        return;
    }


    private function _processSections()
    {
        foreach( $this->_templateInfo_a as $template)
        {
            $source = $template[0];
            $content = trim($template[1]);
            
            switch ($source) 
            {
                case 'htm':             //output the literal html
                    $this->_bodyContent .= $content."\n";
                    break;
                    
                case 'mod':             //run the actual model content
                    $module_view = NS_MODULES.'\\'.MODULE.'\\views\\view';
                    $moduleView = new $module_view();
                    $this->_bodyContent .= $moduleView->getModuleContent();
                    break;
                    
                case 'php':             //from a php file
                
                    $phpFile = $this->_template_base_dir.'/php/'.$content.'.php';
                    if(is_file($phpFile))
                    {
                        //need to catch the output and not send it straight out
                        ob_start();
                        require $phpFile;
                        $this->_bodyContent .= ob_get_clean();
                    } else {
                        $msg = "The page template file for {$phpFile} does not exist!";
                        throw new \RuntimeException($msg);   
                    }
                    break;
                
                default:
                      $msg = "The page template source {$source} does not exist!";
                      throw new \RuntimeException($msg);   
                    break;
            }
        }
    }
        
    //Set Variables for File Outputs
    private function _setOutputVariables()
    {
        $this->_siteTitle = $this->_system_register->site_info('SITE_TITLE');
        $this->_siteSlogan = $this->_system_register->site_info('SITE_SLOGAN');
        $this->_clientName = $this->_system_register->site_info('CLIENT_NAME');
        $this->_siteTagManager = $this->_system_register->site_info('SITE_TAG_MANAGER');
        $this->_siteReCAPTCHA = $this->_system_register->site_info('SITE_RECAPTCHA_KEY');
        
        return;
    }

}   
?>