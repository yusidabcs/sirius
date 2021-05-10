<?php
namespace core\modules\profile\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
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
		//if page is set in options
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0)
		{
			$user_id = $_SESSION['user_id'];
			
			//Get all the user information
			$user_db = new \core\modules\user\models\common\user_db;

			$this->personal_db = new \core\modules\personal\models\common\db;
			
			$this->user_info = $user_db->selectUserDetails($user_id);
			
			$this->user_info = $this->user_info[$user_id];

			//convert to an address book id if there is one
			$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
			
			$this->address_book_id = $address_book_db->getPersonhAddressBookIdFromEmail($this->user_info['email']);
			
			//if there is no address book entry then we need to add one
			if(empty($this->address_book_id))
			{
				header('Location: '.$this->baseURL.'/add');
				exit();
			}
			
		} else {
			$msg = "Wow you should never see this error ... very bad!";
			throw new \RuntimeException($msg);
		}
		
		//set the session with the address book id so we can double check it later
		$_SESSION['address_book_id'] = $this->address_book_id;
	
		//include common
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($this->address_book_id);
		
		//main file
		$this->main_file = $view_core->getContentViewFile('main');
		
		//address file
		$this->address_file = $view_core->getContentViewFile('address');
		
		//pots file
		$this->pots_file = $view_core->getContentViewFile('pots');
		
		//internet file
		$this->internet_file = $view_core->getContentViewFile('internet');
		
		//avatar file
		$this->avatar_file = $view_core->getContentViewFile('avatar');

		$this->icon_internet = [
			'skype' => '<i class="fab fa-skype fa-lg mb-2"></i>',
			'facebook' => '<i class="fab fa-facebook-square fa-lg mb-2"></i>',
			'youtube-video' => '<i class="fab fa-youtube-square fa-lg mb-2"></i>',
			'youtube-channel' => '<i class="fab fa-youtube-square fa-lg mb-2"></i>',
			'twitter' => '<i class="fab fa-twitter-square fa-lg mb-2"></i>',
			'linked-in' => '<i class="fab fa-linkedin fa-lg mb-2"></i>',
			'google-plus' => '<i class="fab fa-google-plus-square fa-lg mb-2"></i>',
			'instagram' => '<i class="fab fa-instagram-square fa-lg mb-2"></i>'
		];

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
		//include
		if (isset($_SESSION['personal'])){
			$this->view_variables_obj->addViewVariables('verification',$this->personal_db->checkVerification($_SESSION['personal']['address_book_id']));
		} else {
			$this->view_variables_obj->addViewVariables('verification',$this->personal_db->checkVerification($_SESSION['address_book_id']));
		}
		$this->view_variables_obj->addViewVariables('edit_link',$this->baseURL.'/edit');	
		$this->view_variables_obj->addViewVariables('user_info',$this->user_info);
		$this->view_variables_obj->addViewVariables('icon_internet',$this->icon_internet);
		
		$this->view_variables_obj->useSweetAlert();

        $this->view_variables_obj->addViewVariables('profile_complete',true);
        if(count($this->view_variables_obj->getViewVariables()['avatar']) == 0){
            $this->view_variables_obj->addViewVariables('profile_complete',false);
            return;
        }
        if(count($this->view_variables_obj->getViewVariables()['address']) == 0){
            $this->view_variables_obj->addViewVariables('profile_complete',false);
            return;
        }
        if(count($this->view_variables_obj->getViewVariables()['pots']) == 0){
            $this->view_variables_obj->addViewVariables('profile_complete',false);
            return;
        }

		//panels
		$panels = array();
		
		$modulesWithProfiles = array('cv','education_application','survey_taker','job','personal');
	
		foreach($modulesWithProfiles as $index => $moduleName)
		{
			if($this->system_register->getModuleIsInstalled($moduleName))
			{
				if($this->system_register->getModuleIsInstalled($moduleName))
				{
                    //DIR_IOW_MODULES
                    $profileViewFile = DIR_MODULES.'/'.$moduleName.'/views/profile/panel.class.php';

					$profileClassName = '\core\modules\\'.$moduleName.'\models\profile\profile';
					
					$module_model_profile = new $profileClassName($this->address_book_id);

					if($module_model_profile->getViewVariables()['show_profile'] == true){


                        $panels[$moduleName] = $profileViewFile;

                        foreach($module_model_profile->getViewVariables() as $variableName => $value)
                        {
                            $this->view_variables_obj->addViewVariables($variableName,$value);
                        }

                        foreach($module_model_profile->getViewSwitches() as $switchName => $version)
                        {
                            $this->view_variables_obj->$switchName($version);
                        }

                        foreach($module_model_profile->getViewSwitches() as $switchName => $version)
                        {
                            $this->view_variables_obj->$switchName($version);
                        }

                        $index = 300 + $index;
                        foreach($module_model_profile->getViewJs() as $key => $value)
                        {
                            $js_file = DIR_MODULES.'/'.$moduleName.'/views/profile/js/'.$value.'.js';
                            $js_link = WWW_MODULES.'/'.$moduleName.'/views/profile/js/'.$value.'.js?'.APP_VERSION;
                            if(is_readable($js_file))
                            {
                                $this->view_variables_obj->addFootSrcFile($index,$js_link);
                                $index++;
                            } else {
                                $msg = "Module header file uses js file {$js_link} but it can not be read!";
                                throw new \RuntimeException($msg);
                            }
                        }

                    }
				} 
			}
		}
		$this->view_variables_obj->addViewVariables('panels',$panels);
		$this->view_variables_obj->addViewVariables('panelCount',count($panels));
		$this->view_variables_obj->addViewVariables('address_book_id',$_SESSION['address_book_id']);
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
			}
			
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