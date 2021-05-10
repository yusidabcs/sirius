<?php
namespace core\modules\recruitment\models\edit;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'edit';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
        $this->authorize();
        //if Session Resume Address_book_id is not set then we should not be here
        if(!isset($this->page_options[1]))
        {
            header('Location: '.$this->baseURL);
            exit();
        } else {
            $this->personal_id = $this->page_options[1];
        }
	    switch ($this->page_options[0]){

            case 'general':
                $this->_processGeneral();
                break;

            case 'language':
                $this->_processLanguage();
                break;

            case 'english':
                $this->_processEnglish();
                break;

            case 'checklist':
                $this->_processChecklist();
                break;

            case 'passport':
                $this->_processPassport();
                break;

            case 'visa':
                $this->_processVisa();
                break;

            case 'idcard':
                $this->_processIdcard();
                break;

            case 'idcheck':
                $this->_processIdcheck();
                break;

            case 'employment':
                $this->_processEmployment();
                break;

            case 'education':
                $this->_processEducation();
                break;

            case 'medical':
                $this->_processMedical();
                break;
            case 'vaccination':
                $this->_processVaccination();
                break;
            case 'tattoo':
                $this->_processTattoo();
                break;
            case 'reference':
                $this->_processReference();
                break;
        }
		
		return;
	}

	private function _processReference(){
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

        //process the inputs
        $error_a = $this->_checkDataReference();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up reference information
            $reference = array(
                'reference_id' => $_POST['reference_id'],
                'type' => $type,
                'family_name' => $_POST['family_name'],
                'given_names' => $_POST['given_names'],
                'relationship' => $_POST['relationship'],
                'line_1' => $_POST['line_1'],
                'line_2' => $_POST['line_2'],
                'line_3' => $_POST['line_3'],
                'countryCode_id' => $_POST['countryCode_id'],
                'number_type' => $_POST['number_type'],
                'number' => $_POST['number'],
                'email' => $_POST['email'],
                'skype' => $_POST['skype'],
                'comment' => $_POST['comment'],
                'filename' => isset($_POST['reference_current']) ? $_POST['reference_current'] : ''
            );

            //information for the form
            $this->addInput('reference',$reference);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['reference_base64']))
            {
                $reference_current = empty($_POST['reference_current']) ? false : $_POST['reference_current'];
                $filename = $this->_processReferenceImage($this->personal_id,$reference_current,$_POST['reference_base64']);
            } else {
                $filename = empty($_POST['reference_current']) ? '' : $_POST['reference_current'];
            }

            $reference_id = $_POST['reference_id'];
            $address_book_id = $this->personal_id;
            $family_name = $_POST['family_name'];
            $given_names = $_POST['given_names'];
            $relationship = $_POST['relationship'];
            $line_1 = $_POST['line_1'];
            $line_2 = $_POST['line_2'];
            $line_3 = $_POST['line_3'];
            $countryCode_id = $_POST['countryCode_id'];
            $number_type = $_POST['number_type'];
            $number = $_POST['number'];
            $email = $_POST['email'];
            $skype = $_POST['skype'];
            $comment = $_POST['comment'];


            //insert or update the reference information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putReference($reference_id,$address_book_id,$type,$family_name,$given_names,$relationship,$line_1,$line_2,$line_3,$countryCode_id,$number_type,$number,$email,$skype,$comment,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/ref';
            } else {
                $this->redirect = $this->baseURL.'/edit/reference/'.$this->personal_id.'/'.$type.'/new';
            }

        }

        return;
    }

    private function _checkDataReference()
    {
        $out = array();

        if(empty($_POST['given_names']))
        {

            $out['Given Name(s)'] = 'Please specify a given name';

        }

        if(empty($_POST['email']))
        {
            $out['Email'] = 'Please provide an email address';
        }

        return $out;
    }

    private function _processReferenceImage($address_book_id,$reference_current,$reference_base64)
    {
        $filename = 'none';

        //decode
        $data = $reference_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($reference_current)
        {
            //delete the current reference image
            $address_book_common->deleteAddressBookFile($reference_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'reference',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in reference for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'reference',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in reference for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

	private function _processTattoo(){
        //fix up if the radio has not be set
        if(empty($_POST['concealable'])) $_POST['concealable'] = '';

        //process the inputs
        $error_a = $this->_checkDataTattoo();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up tattoo information
            $tattoo = array(
                'tattoo_id' => $_POST['tattoo_id'],
                'location' => $_POST['location'],
                'short_description' => $_POST['short_description'],
                'concealable' => $_POST['concealable'],
                'filename' => isset($_POST['tattoo_current']) ? $_POST['tattoo_current'] : ''
            );

            //information for the form
            $this->addInput('tattoo',$tattoo);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['tattoo_base64']))
            {
                $tattoo_current = empty($_POST['tattoo_current']) ? false : $_POST['tattoo_current'];
                $filename = $this->_processTattooImage($this->personal_id,$tattoo_current,$_POST['tattoo_base64']);
            } else {
                $filename = empty($_POST['tattoo_current']) ? '' : $_POST['tattoo_current'];
            }

            $tattoo_id = $_POST['tattoo_id'];
            $address_book_id = $this->personal_id;
            $location = $_POST['location'];
            $short_description = trim($_POST['short_description']);
            $concealable = $_POST['concealable'];

            //insert or update the tattoo information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putTattoo($tattoo_id,$address_book_id,$location,$short_description,$concealable,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/tat';
            } else {
                $this->redirect = $this->baseURL.'/edit/tattoo/'.$this->personal_id.'/new';
            }

        }

        return;
    }

    private function _checkDataTattoo()
    {
        $out = array();

        if(empty($_POST['location']))
        {

            $out['Location'] = 'Please specify a location';

        } else {

            if($_POST['location'] == 'other')
            {

                $_POST['concealable'] = 'yes';
                $_POST['tattoo_base64'] = '';

            } else {

                if(empty($_POST['short_description']))
                {
                    $out['Short Description'] = 'Please give a short description of the tattoo';
                }

            }

        }

        if(empty($_POST['concealable']))
        {
            $out['Coverable'] = 'Please say if the tattoo can be covered or not';
        }

        return $out;
    }

    private function _processTattooImage($address_book_id,$tattoo_current,$tattoo_base64)
    {
        $filename = 'none';

        //decode
        $data = $tattoo_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($tattoo_current)
        {
            //delete the current tattoo image
            $address_book_common->deleteAddressBookFile($tattoo_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'tattoo',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in tattoo for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'tattoo',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in tattoo for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

	private function _processMedical(){
        //fix up if the radio has not be set
        if(empty($_POST['english'])) $_POST['english'] = '';
        if(empty($_POST['fit'])) $_POST['fit'] = '';

        //process the inputs
        $error_a = $this->_checkDataMedical();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up medical information
            $medical = array(
                'medical_id' => $_POST['medical_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'institution' => $_POST['institution'],
                'website' => $_POST['website'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'type' => $_POST['type'],
                'fit' => $_POST['fit'],
                'certificate_date' => $_POST['certificate_date'],
                'certificate_number' => $_POST['certificate_number'],
                'doctor' => $_POST['doctor'],
                'certificate_expiry' => $_POST['certificate_expiry'],
                'filename' => isset($_POST['medical_current']) ? $_POST['medical_current'] : '',
                'certificate_from' => $_POST['certificate_date'],
                'certificate_to' => $_POST['certificate_expiry']
            );

            //information for the form
            $this->addInput('medical',$medical);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['medical_base64']))
            {
                $medical_current = empty($_POST['medical_current']) ? false : $_POST['medical_current'];
                $filename = $this->_processMedicalImage($this->personal_id,$medical_current,$_POST['medical_base64']);
            } else {
                $filename = empty($_POST['medical_current']) ? '' : $_POST['medical_current'];
            }

            $medical_id = $_POST['medical_id'];
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $institution = trim($_POST['institution']);
            $website = trim($_POST['website']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $type = $_POST['type'];
            $fit = $_POST['fit'];
            $certificate_date = empty($_POST['certificate_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_date']));
            $certificate_number = trim($_POST['certificate_number']);
            $doctor = $_POST['doctor'];
            $certificate_expiry = empty($_POST['certificate_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_expiry']));

            //insert or update the medical information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putMedical($medical_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$fit,$certificate_date,$certificate_number,$doctor,$certificate_expiry,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/med';
            } else {
                $this->redirect = $this->baseURL.'/edit/medical/'.$this->personal_id.'/med';
            }

        }

        return;
    }

    private function _checkDataMedical()
    {
        $out = array();

        if(empty($_POST['institution']))
        {
            $out['Institution Name'] = 'You must enter a institution name';
        }

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this medical';
        }

        if(empty($_POST['type']))
        {
            $out['Type'] = 'You must say what type of medical you had';
        }

        if(empty($_POST['fit']))
        {
            $out['Fit'] = 'You must say if your Medical says Fit or Not';
        }

        if(empty($_POST['doctor']))
        {
            $out['Doctor'] = 'You must enter name of the Doctor who signed the medical';
        }

        if(empty($_POST['certificate_date']) )
        {
            $out['Certificate Date'] = 'You must say what the certificate date is';
        }

        if(empty($_POST['certificate_number']) )
        {
            $out['Certificate Number'] = 'You must say what the certificate number is';
        }

        return $out;
    }

    private function _processMedicalImage($address_book_id,$medical_current,$medical_base64)
    {
        $filename = 'none';

        //decode
        $data = $medical_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($medical_current)
        {
            //delete the current medical image
            $address_book_common->deleteAddressBookFile($medical_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'medical',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in medical for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'medical',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in medical for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

	private function _processChecklist(){

        foreach($_POST as $checklist_type => $answer)
        {

            foreach($answer as $question_id => $value)
            {
                $answer = isset($value['answer']) ? $value['answer'] : 'not specified' ;
                $text = empty($value['text']) || $value['answer'] != "yes" ? false : $value['text'];

                $answer_array[$question_id] = array( 'answer' => $answer, 'text' => $text );
            }
        }

        //stick the answers in the db
        $personal_db = new \core\modules\personal\models\common\db;
        $personal_db->putChecklist($this->personal_id,$checklist_type,$answer_array);

        $this->redirect = $this->baseURL.'/personal/'.$this->personal_id;

        return;
    }
	private  function _processGeneral(){


        //process the inputs
        $error_a = $this->_checkData();

        /**

        BMI = 730 x lbs / in2
        BMI = kg / m2

        so cm need to convert to m

        BMI Table for Adults

        This is the World Health Organization's (WHO) recommended body weight based on BMI values for adults. It is used for both men and women, age 18 or older.

        Category	BMI range - kg/m2
        Severe Thinness	< 16
        Moderate Thinness	16 - 17
        Mild Thinness	17 - 18.5
        Normal	18.5 - 25
        Overweight	25 - 30
        Obese Class I	30 - 35
        Obese Class II	35 - 40
        Obese Class III	> 40
         **/

        //calculate bmi
        switch ($_POST['height_weight'])
        {
            case 'me':

                if(empty($_POST['height_cm']) || empty($_POST['weight_kg']))
                {
                    $height_cm = 0;
                    $weight_kg = 0;
                    $height_in = 0;
                    $weight_lb = 0;
                    $bmi = '';

                } else {

                    $height_cm = round($_POST['height_cm'],2);
                    $weight_kg = round($_POST['weight_kg'],2);
                    $height_in = '';
                    $weight_lb = '';

                    $bmi = round($weight_kg/pow(($height_cm/100),2),2);

                }

                break;

            case 'im':

                if(empty($_POST['height_in']) || empty($_POST['weight_lb']))
                {
                    $height_cm = 0;
                    $weight_kg = 0;
                    $height_in = 0;
                    $weight_lb = 0;
                    $bmi = '';

                } else {

                    $height_cm = '';
                    $weight_kg = '';
                    $height_in = round($_POST['height_in'],2);
                    $weight_lb = round($_POST['weight_lb'],2);

                    $bmi = round(703*$weight_lb/pow($height_in,2),2);

                }
                break;

            default:
                $height_cm = 0;
                $weight_kg = 0;
                $height_in = 0;
                $weight_lb = 0;
                $bmi = 0;
        }

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up general information
            $general = array(
                'height_cm' => $height_cm,
                'weight_kg' => $weight_kg,
                'height_in' => $height_in,
                'weight_lb' => $weight_lb,
                'bmi' => $bmi,
                'tattoo' => $_POST['tattoo'],
                'relationship' => $_POST['relationship'],
                'employment' => $_POST['employment'],
                'job_hunting' => $_POST['job_hunting'],
                'seafarer' => $_POST['seafarer'],
                'migration' => $_POST['migration'],
                'country_born' => $_POST['country_born'],
                'country_residence' => $_POST['country_residence'],
                'passport' => $_POST['passport'],
                'travelled_overseas' => $_POST['travelled_overseas'],
                'nok_family_name' => $_POST['nok_family_name'],
                'nok_given_names' => $_POST['nok_given_names'],
                'nok_relationship' => $_POST['nok_relationship'],
                'nok_line_1' => $_POST['nok_line_1'],
                'nok_line_2' => $_POST['nok_line_2'],
                'nok_line_3' => $_POST['nok_line_3'],
                'nok_country' => $_POST['nok_country'],
                'nok_number_type' => $_POST['nok_number_type'],
                'nok_number' => $_POST['nok_number'],
                'nok_email' => $_POST['nok_email'],
                'nok_skype' => $_POST['nok_skype'],
                'filename' => $_POST['filename']
            );

            //information for the form
            $this->addInput('general',$general);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['general_base64']))
            {
                $general_current = empty($_POST['general_current']) ? false : $_POST['general_current'];
                $filename = $this->_processGeneralImage($this->personal_id,$general_current,$_POST['general_base64']);
            } else {
                $filename = empty($_POST['general_current']) ? '' : $_POST['general_current'];
            }

            $height_cm = $height_cm;
            $weight_kg = $weight_kg;
            $height_in = $height_in;
            $weight_lb = $weight_lb;
            $bmi = $bmi;
            $tattoo = $_POST['tattoo'];
            $relationship = $_POST['relationship'];
            $children = $_POST['children'];
            $employment = $_POST['employment'];
            $job_hunting = $_POST['job_hunting'];
            $seafarer = $_POST['seafarer'];
            $migration = $_POST['migration'];
            $country_born = $_POST['country_born'];
            $country_residence = $_POST['country_residence'];
            $passport = $_POST['passport'];
            $nok_family_name = $_POST['nok_family_name'];
            $nok_given_names = $_POST['nok_given_names'];
            $nok_relationship = $_POST['nok_relationship'];
            $nok_line_1 = $_POST['nok_line_1'];
            $nok_line_2 = $_POST['nok_line_2'];
            $nok_line_3 = $_POST['nok_line_3'];
            $nok_country = $_POST['nok_country'];
            $nok_number_type = $_POST['nok_number_type'];
            $nok_number = $_POST['nok_number'];
            $nok_email = $_POST['nok_email'];
            $nok_skype = $_POST['nok_skype'];
            $travelled_overseas = $_POST['travelled_overseas'];

            //insert or update the general information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putGeneral($this->personal_id,$height_cm,$weight_kg,$height_in,$weight_lb,$bmi,$tattoo,$relationship,$children,$employment,$job_hunting,$seafarer,$migration,$country_born,$country_residence,$passport,$travelled_overseas,$nok_family_name,$nok_given_names,$nok_relationship,$nok_line_1,$nok_line_2,$nok_line_3,$nok_country,$nok_number_type,$nok_number,$nok_email,$nok_skype,$filename);

            $this->redirect = $this->baseURL.'/personal/'.$this->personal_id;
        }

        return;
    }

    private function _checkData()
    {
        $out = array();

        if(empty($_POST['employment']))
        {
            $out['Employment'] = 'Please say your current employment status is';
        }

        if(empty($_POST['seafarer']))
        {
            $out['Seafarer'] = 'Please say if you want to work at sea or not';
        }

        if(empty($_POST['migration']))
        {
            $out['Migration'] = 'Please say if you are currently interested to migrate to Australia or not';
        }

        if(empty($_POST['passport']))
        {
            $out['Passport'] = 'Please say if you have a passport or not';
        }


        if(empty($_POST['relationship']))
        {
            $out['Relationship Status'] = 'Please say what your current relationship status is';
        }

        if(empty($_POST['children']))
        {
            $out['Children'] = 'Please say if you have children or not';
        }

        if(empty($_POST['tattoo']))
        {
            $out['Tattoo'] = 'Please say if you have a tattoo or not';
        }

        return $out;
    }

    private function _processGeneralImage($address_book_id,$general_current,$general_base64)
    {
        $filename = 'none';

        //decode
        $data = $general_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($general_current)
        {
            //delete the current general image
            $address_book_common->deleteAddressBookFile($general_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'general',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in general for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'general',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in general for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private  function _processLanguage(){

        //access db

        $resume_db = new \core\modules\personal\models\common\db;

        $resume_db->commitOff();

        //process
        if(is_array($_POST['language']))
        {
            foreach($_POST['language'] as $key => $languageCode_id)
            {
                $level = $_POST['level'][$key];
                $experience = $_POST['experience'][$key];

                if(!empty($level))
                {
                    $resume_db->putLanguage($this->personal_id,$languageCode_id,$level,$experience);
                    $keep_key[$languageCode_id] = 1;
                }
            }
        }

        if(!empty($_POST['keep']))
        {
            foreach($_POST['keep'] as $languageCode_id)
            {
                $keep_key[$languageCode_id] = 1;
            }

            $keep = array_keys($keep_key);
            $resume_db->deleteLanguage($this->personal_id,$keep);
        }

        $resume_db->commit();
        $resume_db->commitOn();

        return;
    }

    private function _processEnglish()
    {
        //process the inputs
        $error_a = $this->_checkDataEnglish();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up english information
            $english = array(
                'english_id' => $_POST['english_id'],
                'type' => $_POST['type'],
                'overall' => $_POST['overall'],
                'breakdown' => $_POST['breakdown'],
                'when' => $_POST['when'],
                'where' => $_POST['where'],
                'filename' => isset($_POST['english_current']) ? $_POST['english_current'] : ''
            );

            //information for the form
            $this->addInput('english',$english);

        } else { //no errors so process

            //we need an english id so if it is empty make a blank one (chicken-egg)
            $personal_db = new \core\modules\personal\models\common\db;

            $english_id = $_POST['english_id'];
            $address_book_id = $this->personal_id;
            if(empty($english_id))
            {
                $english_id = $personal_db->insertEnglish($address_book_id);
                if($english_id < 1)
                {
                    $msg = "There was a major issue with addInfo inserting english for address id {$address_book_id}. ID was {$affected_rows}";
                    throw new \RuntimeException($msg);
                }
            }

            //insert or update the image
            if(!empty($_POST['english_base64']))
            {
                $english_current = empty($_POST['english_current']) ? false : $_POST['english_current'];
                $filename = $this->_processEnglishImage($this->personal_id,$english_current,$_POST['english_base64'],$english_id);
            } else {
                $filename = empty($_POST['english_current']) ? '' : $_POST['english_current'];
            }

            $type = $_POST['type'];
            $overall = $_POST['overall'];
            $breakdown = $_POST['breakdown'];
            $when = date('Y-m-d',strtotime($_POST['when']));
            $where = $_POST['where'];

            //update the english information
            $personal_db->updateEnglish($type,$overall,$breakdown,$when,$where,$filename,$english_id);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id;
            } else {
                $this->redirect = $this->baseURL.'/edit/english/'.$this->personal_id.'/new';
            }
        }

        return;
    }

    private function _checkDataEnglish()
    {
        $out = array();

        if(empty($_POST['overall']))
        {
            $out['Overall'] = 'Please give us the overall score';
        }

        if(empty($_POST['breakdown']))
        {
            $out['Breakdown'] = 'Please enter the score breakdown';
        }

        if(empty($_POST['when']))
        {
            $out['When'] = 'Please specify the date you did the test';
        }

        if(empty($_POST['where']))
        {
            $out['Where'] = 'Please say where you did the test';
        }

        return $out;
    }

    private function _processEnglishImage($address_book_id,$english_current,$english_base64,$english_id)
    {
        $filename = 'none';

        //decode
        $data = $english_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($english_current)
        {
            //delete the current english image
            $address_book_common->deleteAddressBookFile($english_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'english',0,$english_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in english for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'english',0,$english_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in english for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processPassport(){
        //make sure the passport_id is safe to use as a link (it should always be)
        $generic = \core\app\classes\generic\generic::getInstance();
        $_POST['passport_id'] = $generic->safeLinkId($_POST['passport_id']);

        //fix active
        $_POST['active'] = isset($_POST['active']) ? $_POST['active'] : '';

        //process the inputs
        $error_a = $this->_checkDataPassport();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up passport information
            $passport = array(
                'passport_id' => $_POST['passport_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'from_date' => $_POST['from_date'],
                'to_date' => $_POST['to_date'],
                'family_name' => $_POST['family_name'],
                'given_names' => $_POST['given_names'],
                'full_name' => $_POST['full_name'],
                'nationality' => $_POST['nationality'],
                'sex' => $_POST['sex'],
                'place_issued' => $_POST['place_issued'],
                'dob' => $_POST['dob'],
                'pob' => $_POST['pob'],
                'type' => $_POST['type'],
                'code' => $_POST['code'],
                'authority' => $_POST['authority'],
                'active' => $_POST['active'],
                'filename' => isset($_POST['passport_current']) ? $_POST['passport_current'] : ''
            );

            //information for the form
            $this->addInput('passport',$passport);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['passport_base64']))
            {
                $passport_current = empty($_POST['passport_current']) ? false : $_POST['passport_current'];
                $filename = $this->_processPassportImage($this->personal_id,$passport_current,$_POST['passport_base64'],$_POST['passport_id']);
            } else {
                $filename = empty($_POST['passport_current']) ? '' : $_POST['passport_current'];
            }

            $passport_id = strtoupper(trim($_POST['passport_id']));
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $from_date = date('Y-m-d',strtotime($_POST['from_date']));
            $to_date = date('Y-m-d',strtotime($_POST['to_date']));
            $family_name = strtoupper(trim($_POST['family_name']));
            $given_names = strtoupper(trim($_POST['given_names']));
            $full_name = strtoupper(trim($_POST['full_name']));
            $nationality = strtoupper(trim($_POST['nationality']));
            $sex = $_POST['sex'];
            $place_issued = strtoupper(trim($_POST['place_issued']));
            $dob = date('Y-m-d',strtotime($_POST['dob']));
            $pob = strtoupper(trim($_POST['pob']));
            $type = strtoupper(trim($_POST['type']));
            $code = strtoupper(trim($_POST['code']));
            $authority = strtoupper(trim($_POST['authority']));
            $active = $_POST['active'];

            //insert or update the passport information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putPassport($passport_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$nationality,$sex,$place_issued,$dob,$pob,$type,$code,$authority,$active,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id;
            } else {
                $this->redirect = $this->baseURL.'/edit/passport/'.$this->personal_id.'/new';
            }

        }

        return;
    }

    private function _checkDataPassport()
    {
        $out = array();

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this passport';
        }

        if(empty($_POST['passport_id']))
        {
            $out['Passport Number'] = 'You must enter a passport number';
        }

        if(empty($_POST['active']))
        {
            $out['Active'] = 'You must say if the passport is Active or Not';
        }

        if( strtotime($_POST['to_date']) < time() && $_POST['active'] == 'active')
        {
            $out['Active'] = 'An item can not be active that is out of date';
        }

        switch ($_POST['name_style'])
        {
            case 'full':
                if(empty($_POST['full_name']))
                {
                    $out['Full Name'] = 'You must enter the full name';
                }
                break;
            default:
                if(empty($_POST['family_name']) && empty($_POST['given_names']))
                {
                    $out['Name'] = 'You must at least a family or given name';
                }
        }

        return $out;
    }

    private function _processPassportImage($address_book_id,$passport_current,$passport_base64,$passport_id)
    {
        $filename = 'none';

        //decode
        $data = $passport_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($passport_current)
        {
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($passport_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'passport',0,$passport_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in passport for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'passport',0,$passport_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in passport for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processVisa(){
        //make sure the passport_id is safe to use as a link (it should always be)
        $generic = \core\app\classes\generic\generic::getInstance();
        $_POST['visa_id'] = $generic->safeLinkId($_POST['visa_id']);

        //fix active
        $_POST['active'] = isset($_POST['active']) ? $_POST['active'] : '';

        //process the inputs
        $error_a = $this->_checkDataVisa();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up visa information
            $visa = array(
                'visa_id' => $_POST['visa_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'from_date' => $_POST['from_date'],
                'to_date' => $_POST['to_date'],
                'family_name' => $_POST['family_name'],
                'given_names' => $_POST['given_names'],
                'full_name' => $_POST['full_name'],
                'place_issued' => $_POST['place_issued'],
                'entry' => $_POST['entry'],
                'type' => $_POST['type'],
                'class' => $_POST['class'],
                'authority' => $_POST['authority'],
                'active' => $_POST['active'],
                'passport_id' => $_POST['passport_id'],
                'filename' => isset($_POST['visa_current']) ? $_POST['visa_current'] : ''
            );

            //information for the form
            $this->addInput('visa',$visa);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['visa_base64']))
            {
                $visa_current = empty($_POST['visa_current']) ? false : $_POST['visa_current'];
                $filename = $this->_processVisaImage($this->personal_id,$visa_current,$_POST['visa_base64'],$_POST['visa_id']);
            } else {
                $filename = empty($_POST['visa_current']) ? '' : $_POST['visa_current'];
            }

            $visa_id = strtoupper($_POST['visa_id']);
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $from_date = date('Y-m-d',strtotime($_POST['from_date']));
            $to_date = date('Y-m-d',strtotime($_POST['to_date']));
            $family_name = strtoupper(trim($_POST['family_name']));
            $given_names = strtoupper(trim($_POST['given_names']));
            $full_name = strtoupper(trim($_POST['full_name']));
            $place_issued = strtoupper(trim($_POST['place_issued']));
            $entry = $_POST['entry'];
            $type = strtoupper(trim($_POST['type']));
            $class = strtoupper(trim($_POST['class']));
            $authority = strtoupper(trim($_POST['authority']));
            $active = $_POST['active'];
            $passport_id = $_POST['passport_id'];

            //insert or update the visa information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putVisa($visa_id,$address_book_id,$countryCode_id,$from_date,$to_date,$family_name,$given_names,$full_name,$place_issued,$entry,$type,$class,$authority,$active,$passport_id,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/passp';
            } else {
                $this->redirect = $this->baseURL.'/edit/visa/'.$this->personal_id.'/new';
            }

        }
    }

    private function _checkDataVisa()
    {
        $out = array();

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this visa';
        }

        if(empty($_POST['visa_id']))
        {
            $out['Visa Number'] = 'You must enter a visa number';
        }

        if(empty($_POST['active']))
        {
            $out['Active'] = 'You must say if the Visa is Active or Not';
        }

        if( strtotime($_POST['to_date']) < time() && $_POST['active'] == 'active')
        {
            $out['Active'] = 'An item can not be active that is out of date';
        }

        switch ($_POST['name_style'])
        {
            case 'full':
                if(empty($_POST['full_name']))
                {
                    $out['Full Name'] = 'You must enter the full name';
                }
                break;
            default:
                if(empty($_POST['family_name']) && empty($_POST['given_names']))
                {
                    $out['Name'] = 'You must at least a family or given name';
                }
        }

        if(!in_array($_POST['entry'],array('single','multiple')))
        {
            $out['Entry'] = 'You must say if it is a single or multiple entry';
        }


        if(empty($_POST['passport_id']))
        {
            $out['Passport'] = 'You must select a Passport to link the visa too';
        }

        return $out;
    }

    private function _processVisaImage($address_book_id,$visa_current,$visa_base64,$visa_id)
    {
        $filename = 'none';

        //decode
        $data = $visa_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($visa_current)
        {
            //delete the current visa image
            $address_book_common->deleteAddressBookFile($visa_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'visa',0,$visa_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in visa for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'visa',0,$visa_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in visa for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processIdcheck(){

        //fix up if the radio has not be set
        if(empty($_POST['english'])) $_POST['english'] = '';
        if(empty($_POST['fit'])) $_POST['fit'] = '';

        //process the inputs
        $error_a = $this->_checkDataIdcheck();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up idcheck information
            $idcheck = array(
                'idcheck_id' => $_POST['idcheck_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'institution' => $_POST['institution'],
                'website' => $_POST['website'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'type' => $_POST['type'],
                'fit' => $_POST['fit'],
                'idcheck_date' => $_POST['idcheck_date'],
                'idcheck_number' => $_POST['idcheck_number'],
                'idcheck_expiry' => $_POST['idcheck_expiry'],
                'filename' => isset($_POST['idcheck_current']) ? $_POST['idcheck_current'] : '',
                'idcheck_from' => $_POST['idcheck_date'],
                'idcheck_to' => $_POST['idcheck_expiry']
            );

            //information for the form
            $this->addInput('idcheck',$idcheck);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['idcheck_base64']))
            {
                $idcheck_current = empty($_POST['idcheck_current']) ? false : $_POST['idcheck_current'];
                $filename = $this->_processIdcheckImage($this->personal_id,$idcheck_current,$_POST['idcheck_base64']);
            } else {
                $filename = empty($_POST['idcheck_current']) ? '' : $_POST['idcheck_current'];
            }

            $idcheck_id = strtoupper($_POST['idcheck_id']);
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $institution = trim($_POST['institution']);
            $website = trim($_POST['website']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $type = $_POST['type'];
            $fit = $_POST['fit'];
            $idcheck_date = empty($_POST['idcheck_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['idcheck_date']));
            $idcheck_number = trim($_POST['idcheck_number']);
            $idcheck_expiry = empty($_POST['idcheck_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['idcheck_expiry']));

            //insert or update the idcheck information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putIdcheck($idcheck_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$idcheck_date,$idcheck_number,$idcheck_expiry,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/ids';
            } else {
                $this->redirect = $this->baseURL.'/edit/idcheck/'.$this->personal_id.'/new';
            }

        }

        return;
    }

    private function _checkDataIdcheck()
    {
        $out = array();

        if(empty($_POST['institution']))
        {
            $out['Institution Name'] = 'You must enter a institution name';
        }

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this idcheck';
        }

        if(empty($_POST['type']))
        {
            $out['Type'] = 'You must say what type of idcheck you had';
        }

        if(empty($_POST['idcheck_date']) )
        {
            $out['Idcheck Date'] = 'You must say what the idcheck date is';
        }

        if(empty($_POST['idcheck_number']) )
        {
            $out['Idcheck Number'] = 'You must say what the idcheck number is';
        }

        return $out;
    }

    private function _processIdcheckImage($address_book_id,$idcheck_current,$idcheck_base64)
    {
        $filename = 'none';

        //decode
        $data = $idcheck_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($idcheck_current)
        {
            //delete the current idcheck image
            $address_book_common->deleteAddressBookFile($idcheck_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'idcheck',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in idcheck for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'idcheck',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in idcheck for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processIdcard(){
        //make sure the passport_id is safe to use as a link (it should always be)
        $generic = \core\app\classes\generic\generic::getInstance();
        $_POST['idcard_safe'] = $generic->safeLinkId($_POST['idcard_orig']);

        //fix active
        $_POST['active'] = isset($_POST['active']) ? $_POST['active'] : '';

        //process the inputs
        $error_a = $this->_checkDataIdcard();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up idcard information
            $idcard = array(
                'idcard_id' => $_POST['idcard_id'],
                'idcard_orig' => $_POST['idcard_orig'],
                'countryCode_id' => $_POST['countryCode_id'],
                'from_date' => $_POST['from_date'],
                'to_date' => $_POST['to_date'],
                'family_name' => $_POST['family_name'],
                'given_names' => $_POST['given_names'],
                'full_name' => $_POST['full_name'],
                'type' => $_POST['type'],
                'authority' => $_POST['authority'],
                'active' => $_POST['active'],
                'filename' => isset($_POST['idcard_current']) ? $_POST['idcard_current'] : '',
                'filename_back' => isset($_POST['idcard_back_current']) ? $_POST['idcard_back_current'] : ''
            );

            //information for the form
            $this->addInput('idcard',$idcard);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['idcard_base64']))
            {
                $idcard_current = empty($_POST['idcard_current']) ? false : $_POST['idcard_current'];
                $filename = $this->_processIDCardImage($this->personal_id,$idcard_current,$_POST['idcard_base64'],$_POST['idcard_safe'],0);
            } else {
                $filename = empty($_POST['idcard_current']) ? '' : $_POST['idcard_current'];
            }

            //insert or update the image
            if(!empty($_POST['idcard_back_base64']))
            {
                $idcard_back_current = empty($_POST['idcard_back_current']) ? false : $_POST['idcard_back_current'];
                $filename_back = $this->_processIDCardImage($this->personal_id,$idcard_back_current,$_POST['idcard_back_base64'],$_POST['idcard_safe'],1);
            } else {
                $filename_back = empty($_POST['idcard_back_current']) ? '' : $_POST['idcard_back_current'];
            }

            $idcard_id = strtoupper($_POST['idcard_id']);
            $idcard_safe = strtoupper($_POST['idcard_safe']);
            $idcard_orig = strtoupper($_POST['idcard_orig']);
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $from_date = date('Y-m-d',strtotime($_POST['from_date']));
            $to_date = date('Y-m-d',strtotime($_POST['to_date']));
            $family_name = strtoupper(trim($_POST['family_name']));
            $given_names = strtoupper(trim($_POST['given_names']));
            $full_name = strtoupper(trim($_POST['full_name']));
            $type = strtoupper(trim($_POST['type']));
            $authority = strtoupper(trim($_POST['authority']));
            $active = $_POST['active'];

            //insert or update the idcard information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putIDCard($idcard_id,$idcard_safe,$idcard_orig,$countryCode_id,$address_book_id,$from_date,$to_date,$family_name,$given_names,$full_name,$type,$authority,$active,$filename,$filename_back);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/ids';
            } else {
                $this->redirect = $this->baseURL.'/edit/idcard/'.$this->personal_id.'/new';
            }
        }

        return;
    }

    private function _checkDataIdCard()
    {
        $out = array();

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this ID Card';
        }

        if(empty($_POST['idcard_safe']))
        {
            $out['IDCard Number'] = 'You must enter a ID Card number';
        }

        if(empty($_POST['active']))
        {
            $out['Active'] = 'You must say if the ID Card is Active or Not';
        }

        if( strtotime($_POST['to_date']) < time() && $_POST['active'] == 'active')
        {
            $out['Active'] = 'An item can not be active that is out of date';
        }

        switch ($_POST['name_style'])
        {
            case 'full':
                if(empty($_POST['full_name']))
                {
                    $out['Full Name'] = 'You must enter the full name';
                }
                break;
            default:
                if(empty($_POST['family_name']) && empty($_POST['given_names']))
                {
                    $out['Name'] = 'You must at least a family or given name';
                }
        }

        return $out;
    }

    private function _processIDCardImage($address_book_id,$idcard_current,$idcard_base64,$idcard_id,$sequence)
    {
        $filename = 'none';

        //decode
        $data = $idcard_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($idcard_current)
        {
            //delete the current idcard image
            $address_book_common->deleteAddressBookFile($idcard_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'idcard',$sequence,$idcard_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in idcard for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'idcard',$sequence,$idcard_id);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in idcard for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processEmployment(){
        if(empty($_POST['active'])) $_POST['active'] = '';

        //process the inputs
        $error_a = $this->_checkDataEmployment();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up employment information
            $employment = array(
                'employment_id' => $_POST['employment_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'view_from' => $_POST['from_date'],
                'view_to' => $_POST['to_date'],
                'employer' => $_POST['employer'],
                'website' => $_POST['website'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'job_title' => $_POST['job_title'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'active' => $_POST['active'],
                'filename' => isset($_POST['employment_current']) ? $_POST['employment_current'] : ''
            );

            //information for the form
            $this->addInput('employment',$employment);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['employment_base64']))
            {
                $employment_current = empty($_POST['employment_current']) ? false : $_POST['employment_current'];
                $filename = $this->_processEmploymentImage($this->personal_id,$employment_current,$_POST['employment_base64']);
            } else {
                $filename = empty($_POST['employment_current']) ? '' : $_POST['employment_current'];
            }

            $employment_id = $_POST['employment_id'];
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $from_date = date('Y-m-d',strtotime($_POST['from_date']));
            $to_date = empty($_POST['to_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['to_date']));
            $employer = trim($_POST['employer']);
            $website = trim($_POST['website']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $job_title = $_POST['job_title'];
            $type = $_POST['type'];
            $description = trim($_POST['description']);
            $active = $_POST['active'];

            //insert or update the employment information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putEmployment($employment_id,$address_book_id,$from_date,$to_date,$employer,$countryCode_id,$website,$email,$phone,$job_title,$type,$description,$active,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/employ';
            } else {
                $this->redirect = $this->baseURL.'/edit/idcheck/'.$this->personal_id.'/employ';
            }

        }

        return;
    }

    private function _checkDataEmployment()
    {
        $out = array();

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this employment';
        }

        if(empty($_POST['employer']))
        {
            $out['Employer Name'] = 'You must enter a employer name';
        }

        if(empty($_POST['active']))
        {
            $out['Active'] = 'You must say if the Employment is Current or Not';
        }

        if( $_POST['active'] == 'not_active' && strtotime($_POST['to_date']) < strtotime($_POST['from_date']))
        {
            $out['Dates'] = 'You can not start before you finish';
        }

        if(empty($_POST['from_date']))
        {
            $out['From Date'] = 'You must say when you started the job';
        }

        return $out;
    }

    private function _processEmploymentImage($address_book_id,$employment_current,$employment_base64)
    {
        $filename = 'none';

        //decode
        $data = $employment_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($employment_current)
        {
            //delete the current employment image
            $address_book_common->deleteAddressBookFile($employment_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'employment',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in employment for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'employment',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in employment for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processEducation(){
        //fix up if the radio has not be set
        if(empty($_POST['english'])) $_POST['english'] = '';
        if(empty($_POST['active'])) $_POST['active'] = '';

        //process the inputs
        $error_a = $this->_checkDataEducation();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up education information
            $education = array(
                'education_id' => $_POST['education_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'view_from' => $_POST['from_date'],
                'view_to' => $_POST['to_date'],
                'institution' => $_POST['institution'],
                'website' => $_POST['website'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'qualification' => $_POST['qualification'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'level' => $_POST['level'],
                'attended_countryCode_id' => $_POST['attended_countryCode_id'],
                'active' => $_POST['active'],
                'english' => $_POST['english'],
                'certificate_date' => $_POST['certificate_date'],
                'certificate_number' => $_POST['certificate_number'],
                'certificate_expiry' => $_POST['certificate_expiry'],
                'filename' => isset($_POST['education_current']) ? $_POST['education_current'] : ''
            );

            //information for the form
            $this->addInput('education',$education);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['education_base64']))
            {
                $education_current = empty($_POST['education_current']) ? false : $_POST['education_current'];
                $filename = $this->_processEducationImage($this->personal_id,$education_current,$_POST['education_base64']);
            } else {
                $filename = empty($_POST['education_current']) ? '' : $_POST['education_current'];
            }

            $education_id = $_POST['education_id'];
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $from_date = date('Y-m-d',strtotime($_POST['from_date']));
            $to_date = empty($_POST['to_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['to_date']));
            $institution = trim($_POST['institution']);
            $website = trim($_POST['website']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $qualification = $_POST['qualification'];
            $type = $_POST['type'];
            $description = trim($_POST['description']);
            $level = $_POST['level'];
            $attended_countryCode_id = $_POST['attended_countryCode_id'];
            $active = $_POST['active'];
            $english = $_POST['english'];
            $certificate_date = empty($_POST['certificate_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_date']));
            $certificate_number = trim($_POST['certificate_number']);
            $certificate_expiry = empty($_POST['certificate_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['certificate_expiry']));

            //insert or update the education information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putEducation($education_id,$address_book_id,$from_date,$to_date,$institution,$countryCode_id,$website,$email,$phone,$qualification,$type,$description,$level,$attended_countryCode_id,$active,$english,$certificate_date,$certificate_number,$certificate_expiry,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/edu';
            } else {
                $this->redirect = $this->baseURL.'/edit/idcard/'.$this->personal_id.'/new';
            }

        }

        return;
    }

    private function _checkDataEducation()
    {
        $out = array();

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this education';
        }

        if(empty($_POST['institution']))
        {
            $out['Institution Name'] = 'You must enter a institution name';
        }

        if(empty($_POST['active']))
        {
            $out['Active'] = 'You must say if the Education is Current or Not';
        }

        if( $_POST['active'] == 'not_active' && strtotime($_POST['to_date']) < strtotime($_POST['from_date']))
        {
            $out['Dates'] = 'You can not start before you finish';
        }

        if(empty($_POST['from_date']))
        {
            $out['From Date'] = 'You must say when you started the job';
        }

        return $out;
    }

    private function _processEducationImage($address_book_id,$education_current,$education_base64)
    {
        $filename = 'none';

        //decode
        $data = $education_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($education_current)
        {
            //delete the current education image
            $address_book_common->deleteAddressBookFile($education_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'education',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in education for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'education',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in education for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

    private function _processVaccination(){
        //fix up if the radio has not be set
        if(empty($_POST['english'])) $_POST['english'] = '';
        if(empty($_POST['fit'])) $_POST['fit'] = '';

        //process the inputs
        $error_a = $this->_checkDataVaccination();

        if(!empty($error_a))
        {
            //load up the errors
            foreach($error_a as $key => $value)
            {
                $this->addError($key,$value);
            }

            //set up vaccination information
            $vaccination = array(
                'vaccination_id' => $_POST['vaccination_id'],
                'countryCode_id' => $_POST['countryCode_id'],
                'institution' => $_POST['institution'],
                'website' => $_POST['website'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'type' => $_POST['type'],
                'fit' => $_POST['fit'],
                'vaccination_date' => $_POST['vaccination_date'],
                'vaccination_number' => $_POST['vaccination_number'],
                'doctor' => $_POST['doctor'],
                'vaccination_expiry' => $_POST['vaccination_expiry'],
                'filename' => isset($_POST['vaccination_current']) ? $_POST['vaccination_current'] : '',
                'vaccination_from' => $_POST['vaccination_date'],
                'vaccination_to' => $_POST['vaccination_expiry']
            );

            //information for the form
            $this->addInput('vaccination',$vaccination);

        } else { //no errors so process

            //insert or update the image
            if(!empty($_POST['vaccination_base64']))
            {
                $vaccination_current = empty($_POST['vaccination_current']) ? false : $_POST['vaccination_current'];
                $filename = $this->_processVaccinationImage($this->personal_id,$vaccination_current,$_POST['vaccination_base64']);
            } else {
                $filename = empty($_POST['vaccination_current']) ? '' : $_POST['vaccination_current'];
            }

            $vaccination_id = $_POST['vaccination_id'];
            $address_book_id = $this->personal_id;
            $countryCode_id = $_POST['countryCode_id'];
            $institution = trim($_POST['institution']);
            $website = trim($_POST['website']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $type = $_POST['type'];
            $fit = $_POST['fit'];
            $vaccination_date = empty($_POST['vaccination_date']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['vaccination_date']));
            $vaccination_number = trim($_POST['vaccination_number']);
            $doctor = $_POST['doctor'];
            $vaccination_expiry = empty($_POST['vaccination_expiry']) ? '0000-00-00' : date('Y-m-d',strtotime($_POST['vaccination_expiry']));

            //insert or update the vaccination information
            $personal_db = new \core\modules\personal\models\common\db;
            $personal_db->putVaccination($vaccination_id,$address_book_id,$institution,$countryCode_id,$website,$email,$phone,$type,$vaccination_date,$vaccination_number,$doctor,$vaccination_expiry,$filename);

            if($_POST['next'] == 'home')
            {
                $this->redirect = $this->baseURL.'/personal/'.$this->personal_id.'/med';
            } else {
                $this->redirect = $this->baseURL.'/edit/vaccination/'.$this->personal_id.'/med';
            }

        }

        return;
    }

    private function _checkDataVaccination()
    {
        $out = array();

        if(empty($_POST['institution']))
        {
            $out['Institution Name'] = 'You must enter a institution name';
        }

        if(empty($_POST['countryCode_id']))
        {
            $out['Country'] = 'You must enter an issuing country for this vaccination';
        }

        if(empty($_POST['type']))
        {
            $out['Type'] = 'You must say what type of vaccination you had';
        }

        if(empty($_POST['doctor']))
        {
            $out['Doctor'] = 'You must enter name of the Doctor who signed the vaccination';
        }

        if(empty($_POST['vaccination_date']) )
        {
            $out['Vaccination Date'] = 'You must say what the vaccination date is';
        }

        if(empty($_POST['vaccination_number']) )
        {
            $out['Vaccination Number'] = 'You must say what the vaccination number is';
        }

        return $out;
    }

    private function _processVaccinationImage($address_book_id,$vaccination_current,$vaccination_base64)
    {
        $filename = 'none';

        //decode
        $data = $vaccination_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($vaccination_current)
        {
            //delete the current vaccination image
            $address_book_common->deleteAddressBookFile($vaccination_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'vaccination',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in vaccination for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'vaccination',0);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in vaccination for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }
}
?>