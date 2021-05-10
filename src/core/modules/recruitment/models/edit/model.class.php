<?php
namespace core\modules\recruitment\models\edit;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'edit';
	protected $processPost = true;
	protected $personal_model;

	public function __construct()
	{
	    $this->personal_model = new \core\modules\personal\models\home\model();
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
        $this->authorize();
        if(empty($this->page_options[1])){
            die('No address book id');
            exit();
        }
        $this->address_book_id = $this->page_options[1];
        $this->personal_db = new \core\modules\personal\models\common\db;
        $this->core_db = new \core\app\classes\core_db\core_db;
        $this->view_core = \core\modules\address_book\models\common\view\core::getInstance($this->address_book_id);
        //get the countryCodes
        $this->countryCodes = $this->core_db->getAllCountryCodes();
        switch ($this->page_options[0]){
            case 'general';
            $this->_mainGeneral();
                break;

            case 'language';
                $this->_mainLanguage();
                break;

            case 'english';
                $this->_mainEnglish();
                break;

            case 'checklist';
                $this->_mainChecklist();
                break;
            case 'passport';
                $this->_mainPassport();
                break;
            case 'visa';
                $this->_mainVisa();
                break;
            case 'idcard';
                $this->_mainIdcard();
                break;
            case 'idcheck';
                $this->_mainIdcheck();
                break;
            case 'employment';
                $this->_mainEmployment();
                break;
            case 'education';
                $this->_mainEducation();
                break;
            case 'medical';
                $this->_mainMedical();
                break;
            case 'vaccination';
                $this->_mainVaccination();
                break;
            case 'tattoo';
                $this->_mainTattoo();
                break;
            case 'reference';
                $this->_mainReference();
                break;
        }

        $main_file = $this->view_core->getContentViewFile('main'); //we actually don't use the file

        $this->defaultView();
    }

    private function _mainReference(){
//make sure we have a specific the type
        if(isset($this->page_options[2]))
        {
            $acceptable_types = array('personal','work');

            if(in_array($this->page_options[2], $acceptable_types))
            {
                $type = $this->page_options[2];
            } else {
                $msg = "What no valid type specified! How did that happen?";
                throw new \RuntimeException($msg);
            }

        } else {

            $msg = "What no type specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        //make sure we have a specific reference nominated which can be "new"
        if(isset($this->page_options[3]))
        {
            $reference_id = $this->page_options[3];

        } else {

            $msg = "What no reference specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($reference_id == 'new')
        {
            $this->reference = array(
                'reference_id' => '',
                'type' => $type,
                'family_name' => '',
                'given_names' => '',
                'relationship' => '',
                'line_1' => '',
                'line_2' => '',
                'line_3' => '',
                'countryCode_id' => '',
                'number_type' => '',
                'number' => '',
                'email' => '',
                'skype' => '',
                'comment' => '',
                'filename' => ''
            );

        } else {

            //get the existing information (if any)
            $this->reference = $this->personal_db->getReference($reference_id);
            if(empty($this->reference))
            {
                $msg = "What no reference information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }
    private function _mainTattoo(){
//make sure we have a specific tattoo nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $tattoo_id = $this->page_options[2];

        } else {

            $msg = "What no tattoo specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($tattoo_id == 'new')
        {
            $this->tattoo = array(
                'tattoo_id' => '',
                'location' => '',
                'short_description' => '',
                'concealable' => '',
                'filename' => ''
            );

        } else {

            $this->tattoo = $this->personal_db->getTattoo($tattoo_id);
            if(empty($this->tattoo))
            {
                $msg = "What no tattoo information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
        $this->locationArray = array('ankle','deltoid','elbow','face','foot','hand','head','knee','lower arm','lower leg','neck','other','upper arm','upper back','upper chest','upper leg','wrist');
    }

    private function _mainMedical(){
        //make sure we have a specific medical nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $medical_id = $this->page_options[2];

        } else {

            $msg = "What no medical specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($medical_id == 'new')
        {
            $this->medical = array(
                'medical_id' => '',
                'institution' => '',
                'countryCode_id' => '',
                'website' => '',
                'email' => '',
                'phone' => '',
                'type' => '',
                'fit' => '',
                'certificate_date' => '',
                'certificate_number' => '',
                'doctor' => '',
                'certificate_expiry' => '',
                'filename' => '',
                'certificate_from' => '',
                'certificate_to' => ''
            );

        } else {

            //get the existing information (if any)
            $personal_db = new \core\modules\personal\models\common\db;
            $this->medical = $personal_db->getMedical($medical_id);
            if(empty($this->medical))
            {
                $msg = "What no medical information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }
    private function _mainVaccination(){
        //make sure we have a specific vaccination nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $vaccination_id = $this->page_options[2];

        } else {

            $msg = "What no vaccination specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($vaccination_id == 'new')
        {
            $this->vaccination = array(
                'vaccination_id' => '',
                'institution' => '',
                'countryCode_id' => '',
                'website' => '',
                'email' => '',
                'phone' => '',
                'type' => '',
                'vaccination_date' => '',
                'vaccination_number' => '',
                'doctor' => '',
                'vaccination_expiry' => '',
                'filename' => '',
                'vaccination_from' => '',
                'vaccination_to' => ''
            );

        } else {

            $this->vaccination = $this->personal_db->getVaccination($vaccination_id);
            if(empty($this->vaccination))
            {
                $msg = "What no vaccination information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }
    private function _mainEducation(){
        //make sure we have a specific education nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $education_id = $this->page_options[2];

        } else {

            $msg = "What no education specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($education_id == 'new')
        {
            $this->education = array(
                'education_id' => '',
                'from_date' => '',
                'to_date' => '',
                'institution' => '',
                'countryCode_id' => '',
                'website' => '',
                'email' => '',
                'phone' => '',
                'qualification' => '',
                'type' => '',
                'description' => '',
                'level' => '',
                'attended_countryCode_id' => '',
                'active' => '',
                'english' => '',
                'certificate_date' => '',
                'certificate_number' => '',
                'certificate_expiry' => '',
                'filename' => '',
                'view_from' => '',
                'view_to' => '',
                'certificate_from' => '',
                'certificate_to' => ''
            );

        } else {

            //get the existing information (if any)
            $personal_db = new \core\modules\personal\models\common\db;
            $this->education = $personal_db->getEducation($education_id);
            if(empty($this->education))
            {
                $msg = "What no education information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }
    private function _mainEmployment(){
        //make sure we have a specific employment nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $employment_id = $this->page_options[2];

        } else {

            $msg = "What no employment specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($employment_id == 'new')
        {
            $this->employment = array(
                'employment_id' => '',
                'from_date' => '',
                'to_date' => '',
                'employer' => '',
                'countryCode_id' => '',
                'website' => '',
                'email' => '',
                'phone' => '',
                'job_title' => '',
                'type' => '',
                'description' => '',
                'active' => '',
                'filename' => '',
                'view_from' => '',
                'view_to' => ''
            );

        } else {

            //get the existing information (if any)
            $this->employment = $this->personal_db->getEmployment($employment_id);
            if(empty($this->employment))
            {
                $msg = "What no employment information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }

    private function _mainIdcard(){
        if(isset($this->page_options[2]))
        {
            $idcard_id = $this->page_options[2];

        } else {

            $msg = "What no idcard specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($idcard_id == 'new')
        {
            $this->idcard = array(
                'idcard_id' => '',
                'idcard_orig' => '',
                'countryCode_id' => '',
                'from_date' => '',
                'to_date' => '',
                'family_name' => '',
                'given_names' => '',
                'full_name' => '',
                'type' => '',
                'authority' => '',
                'active' => '',
                'filename' => '',
                'filename_back' => ''
            );

        } else {

            //get the existing information (if any)
            $this->idcard = $this->personal_db->getIDCard($idcard_id);
            if(empty($this->idcard))
            {
                $msg = "What no ID Card information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }

    }
    private function _mainIdcheck(){
//make sure we have a specific idcheck nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $idcheck_id = $this->page_options[2];

        } else {

            $msg = "What no idcheck specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($idcheck_id == 'new')
        {
            $this->idcheck = array(
                'idcheck_id' => '',
                'institution' => '',
                'countryCode_id' => '',
                'website' => '',
                'email' => '',
                'phone' => '',
                'type' => '',
                'idcheck_date' => '',
                'idcheck_number' => '',
                'idcheck_expiry' => '',
                'filename' => '',
                'idcheck_from' => '',
                'idcheck_to' => ''
            );

        } else {

            //get the existing information (if any)
            $this->idcheck = $this->personal_db->getIdcheck($idcheck_id);
            if(empty($this->idcheck))
            {
                $msg = "What no idcheck information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }

    }
    private function _mainGeneral(){
        $this->general = $this->personal_db->getGeneral($this->address_book_id);

        if(empty($this->general))
        {
            $this->general = array(
                'height_weight' => 'me',
                'height_cm' => '',
                'weight_kg' => '',
                'height_in' => '',
                'weight_lb' => '',
                'bmi' => '',
                'tattoo' => '',
                'relationship' => '',
                'children' => '',
                'employment' => '',
                'job_hunting' => '',
                'seafarer' => '',
                'migration' => '',
                'country_born' => '',
                'country_residence' => '',
                'passport' => '',
                'travelled_overseas' => '',
                'nok_family_name' => '',
                'nok_given_names' => '',
                'nok_relationship' => '',
                'nok_line_1' => '',
                'nok_line_2' => '',
                'nok_line_3' => '',
                'nok_country' => '',
                'nok_number_type' => '',
                'nok_number' => '',
                'nok_email' => '',
                'nok_skype' => '',
                'filename' => ''
            );
        }

        return;

    }

    private function _mainLanguage(){
        //get the existing information (if any)
        $this->language = $this->personal_db->getLanguage($this->address_book_id);

        //get the countryCodes
        $this->countryCodes = $this->core_db->getAllCountryCodes();

        //languageCodes
        $this->languageCodes = $this->core_db->getMajorLanguageCodes();

        //set main details for the title
        $main_file = $this->view_core->getContentViewFile('main'); //we actually don't use the file
    }

    private function _mainEnglish(){
        //make sure we have a specific english nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $english_id = $this->page_options[2];

        } else {

            $msg = "What no english specified! How did that happen?";
            throw new \RuntimeException($msg);

        }
        if($english_id == 'new')
        {
            $this->english = array(
                'english_id' => '',
                'type' => '',
                'overall' => '',
                'breakdown' => '',
                'where' => '',
                'when' => '',
                'filename' => ''
            );

        } else {

            //need the common db
            $personal_db = new \core\modules\personal\models\common\db;

            //get the existing information (if any)
            $this->english = $personal_db->getEnglish($english_id);

            if(empty($this->english))
            {
                $msg = "What no english information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }

    private function _mainChecklist(){
        $acceptable_checklists = array("character","health");

        if(isset($this->page_options[2]) && in_array($this->page_options[2], $acceptable_checklists))
        {
            $this->checklist_type = $this->page_options[2];

        } else {

            $msg = "What no checklist specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        $answer_array = $this->personal_db->getChecklist($this->address_book_id,$this->checklist_type);

        switch ($this->checklist_type)
        {
            case 'character':
                $this->checklist = $this->core_db->getChecklistCharacter();
                break;

            case 'health':
                $this->checklist = $this->core_db->getChecklistHealth();
                break;
            default:
                $msg = "They checklist type is not correct! How did that happen?";
                throw new \RuntimeException($msg);
        }

        //put them together
        foreach($this->checklist as $question_id => $values)
        {
            if( isset($answer_array[$question_id]) )
            {
                $answer = $answer_array[$question_id]['answer'];
                $text = $answer_array[$question_id]['text'];
            } else {
                $answer = 'not specified';
                $text = '';
            }

            $this->checklist[$question_id]['answer'] = $answer;
            $this->checklist[$question_id]['text'] = $text;
        }
    }

    private function _mainPassport(){
        //make sure we have a specific passport nominated which can be "new"
        if(isset($this->page_options[2]))
        {
            $passport_id = $this->page_options[2];

        } else {

            $msg = "What no passport specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        if($passport_id == 'new')
        {
            $this->passport = array(
                'passport_id' => '',
                'countryCode_id' => '',
                'from_date' => '',
                'to_date' => '',
                'family_name' => '',
                'given_names' => '',
                'full_name' => '',
                'nationality' => '',
                'sex' => '',
                'place_issued' => '',
                'dob' => '',
                'pob' => '',
                'type' => '',
                'code' => '',
                'authority' => '',
                'active' => '',
                'filename' => ''
            );

        } else {

            $this->passport = $this->personal_db->getPassport($passport_id);
            if(empty($this->passport))
            {
                $msg = "What no passport information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }
    private function _mainVisa(){
        if(isset($this->page_options[2]))
        {
            $visa_id = $this->page_options[2];

        } else {

            $msg = "What no visa specified! How did that happen?";
            throw new \RuntimeException($msg);

        }

        $passportList = $this->personal_db->getPassportList($this->page_options[1]);

        if(empty($passportList))
        {
            $msg = "What no passports? You can not add a visa without a passport?";
            throw new \RuntimeException($msg);
        } else {
            $this->passportArray = array_keys($passportList);
        }

        if($visa_id == 'new')
        {
            $this->visa = array(
                'visa_id' => '',
                'countryCode_id' => '',
                'from_date' => '',
                'to_date' => '',
                'family_name' => '',
                'given_names' => '',
                'full_name' => '',
                'place_issued' => '',
                'entry' => '',
                'type' => '',
                'class' => '',
                'authority' => '',
                'active' => '',
                'passport_id' => '',
                'filename' => ''
            );

        } else {

            //get the existing information (if any)
            $this->visa = $this->personal_db->getVisa($visa_id);
            if(empty($this->visa))
            {
                $msg = "What no visa information! How did that happen?";
                throw new \RuntimeException($msg);
            }
        }
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate($this->page_options[0]);
        return;
    }
    //required function
    protected function setViewVariables()
    {
        //required scripts for the image
        $this->view_variables_obj->useCroppie("2.5.1");
        $this->view_variables_obj->useSweetAlert("6.6.2");
        $this->view_variables_obj->useFlatpickr();

        $this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/personal/'.$this->address_book_id);
        $this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
        switch ($this->page_options[0]){
            case 'general';
                $this->viewGeneral();
                break;
            case 'language';
                $this->viewLanguage();
                break;
            case 'english';
                $this->viewEnglish();
                break;
            case 'checklist';
                $this->viewChecklist();
                break;
            case 'passport';
                $this->viewPassport();
                break;
            case 'visa';
                $this->viewVisa();
                break;
            case 'idcard';
                $this->viewIdcard();
                break;
            case 'idcheck';
                $this->viewIdcheck();
                break;
            case 'employment';
                $this->viewEmployment();
                break;
            case 'education';
                $this->viewEducation();
                break;
            case 'medical';
                $this->viewMedical();
                break;
            case 'vaccination';
                $this->viewVaccination();
                break;
            case 'tattoo';
                $this->viewTattoo();
                break;
            case 'reference';
                $this->viewReference();
                break;
        }

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

    protected function viewGeneral(){
        $this->view_variables_obj->addViewVariables('general',$this->general);
    }
    protected function viewLanguage(){
        $this->view_variables_obj->useEkkoLightBox();
        $this->view_variables_obj->addViewVariables('language',$this->language);
        $this->view_variables_obj->addViewVariables('languageCodes',$this->languageCodes);
    }
    protected function viewEnglish(){
        $this->view_variables_obj->addViewVariables('english',$this->english);
    }

    protected function viewChecklist(){
        $this->view_variables_obj->addViewVariables('checklist_type',$this->checklist_type);
        $this->view_variables_obj->addViewVariables('checklist',$this->checklist);
    }

    protected function viewPassport(){
        $this->view_variables_obj->addViewVariables('passport',$this->passport);
        $this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
    }

    protected function viewVisa(){
        $this->view_variables_obj->addViewVariables('visa',$this->visa);
        $this->view_variables_obj->addViewVariables('passportArray',$this->passportArray);
    }

    protected function viewIdcard(){
        $this->view_variables_obj->addViewVariables('idcard',$this->idcard);
    }

    protected function viewIdcheck(){
        $this->view_variables_obj->addViewVariables('idcheck',$this->idcheck);
    }

    protected function viewEmployment(){
        $this->view_variables_obj->addViewVariables('employment',$this->employment);
    }

    protected function viewEducation(){
        $this->view_variables_obj->addViewVariables('education',$this->education);
    }

    protected function viewMedical(){
        $this->view_variables_obj->addViewVariables('medical',$this->medical);
    }

    protected function viewVaccination(){
        $this->view_variables_obj->addViewVariables('vaccination',$this->vaccination);
    }

    protected function viewTattoo(){
        $this->view_variables_obj->addViewVariables('tattoo',$this->tattoo);
        $this->view_variables_obj->addViewVariables('locationArray',$this->locationArray);
    }
    protected function viewReference(){
        $this->view_variables_obj->addViewVariables('reference',$this->reference);
    }

}
?>