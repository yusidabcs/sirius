<?php
namespace core\modules\partner\models\edit;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'edit';
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
        $core_db = new \core\app\classes\core_db\core_db;
        $partner_db = new \core\modules\partner\models\common\db;
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        
        if (!empty($this->page_options[1])){
            // if there is, go process disable / delete
            $type = $this->page_options[0];
            $id = $this->page_options[1];

            if ( $type == 'disable' ){
                $this->processDisable($id);
            }else if ( $type == 'delete' ){
                $this->processDelete($id);
            }else if ( $type == 'enable' ){
                $this->processEnable($id);
            }else {
                //wrong page option error
                exit();
            }
		}else{
            // if there isn't go normal path
            if (isset($this->page_options[0])) //check if there is option id
            {
                if (filter_var($this->page_options[0], FILTER_VALIDATE_INT)) //check if option id is integer
                {   
                    $partner_id = $this->page_options[0]*1;    
                    $this->countries = $core_db->getAllCountryCodes();
                    $this->partner_details = $partner_db->getPartnerDetail($partner_id);
                    $this->partner_type=array();
                    if ($this->partner_details)
                    {
                        $this->partner_subcountries = $core_db->getMultipleSubCountryCodes($this->partner_details['countryCode_id']);
                        $this->partner_file = $partner_db->getPartnerFile($partner_id);
                        //get partner type
                        $type = $partner_db->getPartnerType($partner_id);
                        if(count($type)>0) {
                            $this->partner_type = $type;
                        } else {
                            $this->partner_type[0]= strtoupper($this->partner_details['type']);
                        }

                        $this->ent_admin_details = $address_book_db->getAddressBookAdminLinks($partner_id);

                        $this->defaultView();
                        return;        
                    }else{
                        //no data from inserted id
                        $msg = "Wrong partner id parameter option in partner edit models, data not found";
                        throw new \RuntimeException($msg);
                    }
                }else{
                    //parameter id not int
                    $msg = "Wrong partner id parameter option format in partner edit models , parameter id must be in integer format ";
                    throw new \RuntimeException($msg);
                }
            }else{
                //not isset parameter id
                
                //parameter id not int
                $msg = "Wrong partner id parameter option not set in partner edit models , must have parameter id ";
                throw new \RuntimeException($msg);
            }
		}
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('edit');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useCroppie();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->addViewVariables('countries', $this->countries);
        $this->view_variables_obj->addViewVariables('partner_subcountries', $this->partner_subcountries);
        $this->view_variables_obj->addViewVariables('partner', $this->partner_details);

        $this->view_variables_obj->addViewVariables('partner_file', $this->partner_file);
        $this->view_variables_obj->addViewVariables('back_link', $this->baseURL);
        $this->view_variables_obj->addViewVariables('page_link', $this->modelURL);
        $this->view_variables_obj->addViewVariables('partner_type', $this->partner_type);
        $this->view_variables_obj->addViewVariables('ent_admin_details', $this->ent_admin_details);

        //POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);

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
            if($this->input_obj->hasMessages())
            {
                $this->view_variables_obj->addViewVariables('messages',$this->input_obj->getMessages());
            }
        }
        return;
    }
    
    private function processDisable($id){
        $common_db = new \core\modules\partner\models\common\db;
        $data = array(
            'status' => '0',
            'id' => $id
        );
        $check = $common_db->editPartnerStatus($data);
        if ($check != 1){
            //error disable
            $msg = "There was a major issue with disable Partner ";
            throw new \RuntimeException($msg);
        }
        $_SESSION['partner_action'] = 'Disable';
        $_SESSION['partner_action_status'] = 'Success';
        header("Location: $this->baseURL");
    }

    private function processEnable($id){
        $common_db = new \core\modules\partner\models\common\db;
        $data = array(
            'status' => '1',
            'id' => $id
        );
        
        $check = $common_db->editPartnerStatus($data);
        if ($check != 1){
            //error enable
            $msg = "There was a major issue with enablePartner ";
            throw new \RuntimeException($msg);
        }
        // return;
        $_SESSION['partner_action'] = 'Enable';
        $_SESSION['partner_action_status'] = 'Success';
        header("Location: $this->baseURL");
    }

    private function processDelete($id){
        $common_db = new \core\modules\partner\models\common\db;
        $check = $common_db->deletePartner($id);
        if ($check != 1){
            //error enable
            $msg = "There was a major issue with deletePartner ";
            throw new \RuntimeException($msg);
        }
        //continue delete partner_ab_link
        $check = $common_db->deletePartnerABLinkbyId($id);
        if ($check == -1){
            //error enable
            $msg = "There was a major issue with deletePartnerABLinkById ";
            throw new \RuntimeException($msg);
        }
        //continue delete partner_file
        $check = $common_db->deletePartnerFileById($id);
        if ($check == -1){
            //error enable
            $msg = "There was a major issue with deletePartnerFilebyId ";
            throw new \RuntimeException($msg);
        }

        $_SESSION['partner_action'] = 'Delete';
        $_SESSION['partner_action_status'] = 'Success';
        header("Location: $this->baseURL");
    }
 
}
?>
