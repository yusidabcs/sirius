<?php
namespace core\modules\workflow\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 15 Jun 2020
 */
final class travelpack extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $out = null;
        $this->finance_db = new \core\modules\workflow\models\common\travelpack_db();
        switch($this->option)
        {
            case 'list':

                $out = $this->finance_db->getTravelpackDatatable();

                break;

            case 'generate_invoice':

                $data = $_POST;
                $rule = [
                    'invoice_expected_on' => 'required',
                    'job_application_id' => 'required',
                    'address_book_id' => 'required',
                    'invoice_number' => 'required'
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->finance_db->updateTravelpackGenerateInvoice($data);

                 //upload image
                 $filename='';
                 if(!empty($data['invoice_base64']))
                 {
                     $filename = $this->_processInvoiceImage($data['job_application_id'],$invoice_current=false,$data['invoice_base64']);

                     $this->finance_db->updateTravelpackFilename('filename_generate',$filename,$data['job_application_id']);

                 }

                //  send email
                $this->_sendEmailInvoice($data,$filename);

                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully update travelpack tracker.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update travelpack tracker.']
                ], 400);

                break;
            case 'resend-invoice' : 
                $data = $_POST;
                $filename = '';
                $data_travelpack = $this->finance_db->getDataTravelpack($data['job_application_id']);
                if(count($data_travelpack)>0) {
                    $data['invoice_number'] = $data_travelpack[0]['invoice_number'];
                    $data['invoice_expected_on'] = $data_travelpack[0]['invoice_expected_on'];
                    if($data_travelpack[0]['filename_generate']!='') {
                        $filename = $data_travelpack[0]['filename_generate'];
                    }
                    //  send email
                    $this->_sendEmailInvoice($data,$filename);
                    return $this->response([
                        'message' => 'Successfully resend invoice.'
                    ]);
                } else {
                    return $this->response([
                        'errors' => ['Unsuccessfully resend invoice.']
                    ], 400);
                }
                
                break;
            case 'pay_invoice':

                $data = $_POST;
                $rule = [
                    'notes' => 'required',
                    'job_application_id' => 'required',
                    'status' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->finance_db->updateTravelpackPayInvoice($data);

                if(!empty($data['pay_base64']))
                {
                    $filename = $this->_processPayImage($data['job_application_id'],$pay_current=false,$data['pay_base64']);

                    $this->finance_db->updateTravelpackFilename('filename_pay',$filename,$data['job_application_id']);

                }

                if ($rs > 0) {
                    if($data['status'] == 'cancelled'){
                        $job_db = new \core\modules\job\models\common\db();
                        $job_db->updateJobApplicationStatus($data['job_application_id'], 'canceled');
                    }

                    return $this->response([
                        'message' => 'Successfully update travelpack tracker.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update travelpack tracker.']
                ], 400);

                break;

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);
                break;
        }

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

    private function _processInvoiceImage($job_application_id,$invoice_current,$invoice_base64)
    {
        $filename = 'none';

        //decode
        $data = $invoice_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$job_application_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        //delete the current generate invoice if exist
        $file = $address_book_db->getAddressBookFileArray($job_application_id,'banner','generate_invoice');
        if(count($file)>0) {
            $file_image = $file[0]['filename'];
            $address_book_common->deleteAddressBookFile($file_image,$job_application_id);
        }
        $address_book_db->deleteAddressBookFile($job_application_id,'banner','generate_invoice');

        
        //insert also saves the image in the address book folder
        $affected_rows = $address_book_db->insertAddressBookFile($filename,$job_application_id,'banner',0,'generate_invoice',1);

        if($affected_rows != 1)
        {
            $msg = "There was a major issue with addInfo in generate invoice for address id {$job_application_id}. Affected was {$affected_rows}";
            throw new \RuntimeException($msg);
        }


        return $filename;
    }

    private function _processPayImage($job_application_id,$pay_current,$pay_base64)
    {
        $filename = 'none';

        //decode
        $data = $pay_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$job_application_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        //delete the current pay invoice if exist
        $file = $address_book_db->getAddressBookFileArray($job_application_id,'banner','pay_invoice');
        if(count($file)>0) {
            $file_image = $file[0]['filename'];
            $address_book_common->deleteAddressBookFile($file_image,$job_application_id);
        }
        $address_book_db->deleteAddressBookFile($job_application_id,'banner','pay_invoice');

        
        //insert also saves the image in the address book folder
        $affected_rows = $address_book_db->insertAddressBookFile($filename,$job_application_id,'banner',0,'pay_invoice',1);

        if($affected_rows != 1)
        {
            $msg = "There was a major issue with addInfo in pay invoice for address id {$job_application_id}. Affected was {$affected_rows}";
            throw new \RuntimeException($msg);
        }


        return $filename;
    }

    private function _sendEmailInvoice($data,$filename) {
        $invoice_file = '';
        $mailing_common = new \core\modules\send_email\models\common\common;
        $generic = \core\app\classes\generic\generic::getInstance();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($data['address_book_id']);

        $to_name = $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $address_book['main_email'];

        if($filename!='') {
            $invoice_file = DIR_LOCAL_UPLOADS.'/address_book/'.$data['job_application_id'].'/'.$filename;
        }
        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('tracker_travelpack_send_invoice', [
            'to_name' => $to_name,
            'invoice_number' => $data['invoice_number'],
            'invoice_expected_on' => date('M d, Y', strtotime($data['invoice_expected_on']))
        ]);
        
        if ($template) {
            $subject = $template['subject'];
            $message = $template['html'];
        } else {
            $subject = 'Generate Invoice Travelpack Fee : ' . SITE_WWW;
        }


        //cc
        $cc ='';

        //bcc
        if(SYSADMIN_BCC_NEW_USERS)
        {
            $bcc = SYSADMIN_EMAIL;
        } else {
            $bcc = '';
        }

        //html
        $html = true;
        $fullhtml = true;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $generic = \core\app\classes\generic\generic::getInstance();
        return $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink,$invoice_file,'Invoice Travel Package');
    }

}
?>