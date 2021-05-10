<?php
namespace core\modules\partner\models\edit;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	partner
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'edit';
	
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
        //check data and insert to database

        if (empty($_POST['partner_code']) && $_POST['partner_code'] == '')
        {
            $this->addError('partner_code','partner code is not set');
        }

        if (empty($_POST['countryCode_id']))
        {
            $this->addError('country','country not set');
        }

        if (empty($_POST['countrySubCode_id']))
        {
            $this->addError('subcountry','Subcountry not set');
        }
        if (empty($_POST['chk_lp']) && empty($_POST['chk_lep']))
		{
			$this->addError('partner type : ','Please check at least one Partner Type');
        }
        if(count($this->errors) > 0)
        {
            return;
        }

		//get partner db
		$partner_db = new \core\modules\partner\models\common\db;

        $address_book_id = $_POST['address_book_id'];
        $partner_code = trim($_POST['partner_code']);
        //$partner_type = $_POST['partner_type'];

        $countryCode_id = $_POST['countryCode_id'];
        $countrySubCode_id = $_POST['countrySubCode_id'];
        $countrySubCode_idLength = $_POST['countrySubCode_idLength'];

        foreach ($countryCode_id as $country)
        {
            $selected_subc = count($countrySubCode_id[$country]);
            $total_subc = $countrySubCode_idLength[$country];
            //check if selected all, change to 999
            if ($selected_subc == $total_subc)
            {
                $countrySubCode_id[$country] = array('999') ;
            }
        }
        
        $data = array(
            'address_book_id' => $address_book_id,
            'partner_code' => $partner_code,
            'countrySubCode_id' => $countrySubCode_id,
            'status' => '1',
            //'partner_type' => $partner_type,
            'modified_by' => $_SESSION['user_id'],
        );

        //insert or update the image
        if(!empty($_POST['banner_base64']))
        {
            $banner_current = empty($_POST['banner_current']) ? false : $_POST['banner_current'];
            $filename = $this->_processBannerImage($address_book_id,$banner_current,$_POST['banner_base64']);
        } else {
            $filename = empty($_POST['banner_current']) ? '' : $_POST['banner_current'];
        }

        $out = $partner_db->updatePartner($data); //get inserted partner id
        //insert data to parner type
        $partner_db->deletePartnerType($address_book_id);
        if(!empty($_POST['chk_lp'])) {
            $partner_db->insertPartnerType($address_book_id,'lp');
        }
        if(!empty($_POST['chk_lep'])) {
            $partner_db->insertPartnerType($address_book_id,'lep');
        }
        
        if($out == -1){
            $this->addError('partner','Failed to insert data.');
        }else{
            $this->addMessage('partner','Successfully to update data.');
            unset($_POST);
        }

		return;
	}

    private function _processBannerImage($address_book_id,$banner_current,$banner_base64)
    {
        $filename = 'none';

        //decode
        $data = $banner_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($banner_current)
        {
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($banner_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,'banner',0,'register',1);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in banner for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'banner',0,'register',1);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in banner for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

}
?>