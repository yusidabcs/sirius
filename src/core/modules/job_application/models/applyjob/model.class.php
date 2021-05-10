<?php
namespace core\modules\job_application\models\applyjob;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'applyjob';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
        $this->authorize();
        $this->job_db = new \core\modules\job\models\common\db();
        if(empty($this->page_options[0]))
        {
            die('Empty job code');
        }
        
        $this->job = $this->job_db->getJobSpeedy($this->page_options[0]);

        if (!$this->job){
            $msg = 'No Job Data found with code '.$this->page_options[0];
            throw new \RuntimeException($msg);
        }

        //include common
		$view_core = \core\modules\address_book\models\common\view\core::getInstance(isset($_SESSION['personal']['address_book_id'])?$_SESSION['personal']['address_book_id']:$_SESSION['address_book_id']);
		
		//main file
		$this->main_file = $view_core->getContentViewFile('main');
		
		//pots file
		$this->pots_file = $view_core->getContentViewFile('pots');
		
		//avatar file
		$this->avatar_file = $view_core->getContentViewFile('avatar');

        $this->_checkIfSuitable();
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('applyjob');
		return;
	}
	
	//required function
	protected function setViewVariables()
    {
        $this->view_variables_obj->addViewVariables('job',$this->job);
        $this->view_variables_obj->addViewVariables('mode',$this->mode);
        $this->view_variables_obj->addViewVariables('address_book_id',$this->address_book_id);
        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);
        $this->view_variables_obj->addViewVariables('stcw_flag',$this->stcw_flag);
        $this->view_variables_obj->addViewVariables('min_education_flag',$this->min_education_flag);
        $this->view_variables_obj->addViewVariables('english_experience_flag',$this->english_experience_flag);
        $this->view_variables_obj->addViewVariables('experience_flag',$this->experience_flag);
        $this->view_variables_obj->addViewVariables('exist_job_flag',$this->exist_job_flag);
        $this->view_variables_obj->addViewVariables('education',$this->education);
        $this->view_variables_obj->addViewVariables('works',$this->works);
        $this->view_variables_obj->addViewVariables('references',$this->references);
        $this->view_variables_obj->addViewVariables('personal_references_count',$this->reference_personal_count);
        $this->view_variables_obj->addViewVariables('work_references_count',$this->reference_work_count);

        $this->view_variables_obj->addViewVariables('update_personal_link','/'.$this->menu_register->getModuleLink('personal').(($this->mode == 'recruitment')? '/home/'.$this->address_book_id : ''));
        if ($this->mode == 'recruitment')
            $this->view_variables_obj->addViewVariables('listjob_link','/'.$this->menu_register->getModuleLink('job_application').'/listjob/'.$this->address_book_id);

		return;
	}

    private function _checkIfSuitable()
    {
        $profile_common = new \core\modules\personal\models\common\common;

        $this->mode = 'personal';

        $user_id = $_SESSION['user_id'];

        //Get all the user information
        $user_db = new \core\modules\user\models\common\user_db;

        $this->personal_db = new \core\modules\personal\models\common\db;

        $this->user_info = $user_db->selectUserDetails($user_id);

        $this->user_info = $this->user_info[$user_id];

        //convert to an address book id if there is one
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        $address_book_id = $address_book_db->getPersonhAddressBookIdFromEmail($this->user_info['email']);
        


        // check if there is option id, if there is then it should be applying for another person
        if(!empty($this->page_options[1]))
        {
            if (is_numeric($this->page_options[1]))
            {
                //check user security level        
                if ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )
                {
                    $param_id = $this->page_options[1];
					if ($address_book_db->checkAddressID($param_id))
					{
						$this->mode = 'recruitment';
						$address_book_id = $param_id;
					}else{	
						$msg = "No data found with that address book id";
						throw new \RuntimeException($msg);
                    }

                }else{
                    $msg = "Only admin can access this feature!";
                    throw new \RuntimeException($msg);
                }
            }else{
				$msg = "Wrong address book id parameter format";
				throw new \RuntimeException($msg);
            }
        }

        $this->address_book_id = $address_book_id;

        $this->stcw_flag = true;
        $this->min_education_flag = true;
        $this->english_experience_flag = true;
        $this->experience_flag = true;
        $this->exist_job_flag = true;

        $total_job = $this->job_db->getTotalJobApplication($address_book_id);

        if($total_job == 0)
        {
            $this->exist_job_flag = false;
        }

        $this->profile_info = $profile_common->getProfileInfo($address_book_id);
        if($this->profile_info)
        {
            //check stcw
            $stcw_req = $this->job['stcw_req'];
            $stcw_document = $profile_common->personal_db->getEducationSTCW($address_book_id);
            if($stcw_req && !empty($stcw_document))
            {
                $this->stcw_flag = false;
            }else if (!$stcw_req){
                $this->stcw_flag = false;
            }

            //check min_education
            $min_education = $this->job['min_education'];
            $all_educations = $profile_common->personal_db->getEducationList($address_book_id);
            $education_level = array(
                'school' => 1,
                'certificate' => 2,
                'diploma' => 3,
                'degree' => 4,
                'honours' => 5,
                'masters' => 6,
                'doctorate' => 7
            );
            
            $qualified_education = [];
            foreach ($all_educations as $key => $item)
            {
                //check if user education level is equal or higher than job minimum education level
                if ( $item['level'] != 'stcw' && ($education_level[$item['level']] >= $education_level[$min_education]) )
                {
                    $this->min_education_flag = false;
                    $qualified_education[] = $item;
                }
            }
            $this->education = $qualified_education;

            //check experience
            $min_experience = $this->job['min_experience'];
            $this->works = $profile_common->personal_db->getEmploymentList($address_book_id);
            $this->job_category_id = $this->job['job_speedy_category_id'];
            $this->works = $profile_common->personal_db->getEmploymentList($address_book_id,$this->job_category_id);
            if($min_experience > 0)
            {

                $total_ex = 0;
                foreach ($this->works as $key => $item)
                {
                    $d1 = new \DateTime($item['from_date']);
                    if($item['active'] == 'active')
                    {
                        $d2 = new \DateTime();
                    }else{
                        $d2 = new \DateTime($item['to_date']);
                    }
                    $dif = $d1->diff($d2);
                    $total_ex += $dif->m + ($dif->y * 12);
                }
                if($total_ex >= $min_experience)
                    $this->experience_flag = false;

            }else if ($min_experience == 0){
                $this->experience_flag = false;
            }

            //check english experience
            $min_english_experience = $this->job['min_english_experience'];
            if($min_english_experience > 0)
            {
                $langs = $profile_common->personal_db->getLanguage($address_book_id);

                foreach ($langs as $key => $item)
                {
                    if($key == 'en')
                    {
                        if($min_english_experience <= $item['experience'])
                        {
                            $this->english_experience_flag = false;
                        }
                    }

                }
            }else if ($min_english_experience == 0){
                $this->english_experience_flag = false;
            }

            $this->references = $profile_common->personal_db->getReferenceList($address_book_id);
            $personal_count = 0;
            $work_count = 0;
            foreach ($this->references as $key => $item) {
                if($item['type'] == 'personal'){
                    $personal_count++;
                }elseif($item['type'] == 'work'){
                    $work_count++;
                }
            }
            $this->reference_personal_count = $personal_count;
            $this->reference_work_count = $work_count;
        }else{
            die('Personal data is empty');
        }
    }
		
}
?>