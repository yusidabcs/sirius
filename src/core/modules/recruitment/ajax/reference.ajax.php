<?php
namespace core\modules\recruitment\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class reference extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        if($this->option == 'resend'){
            $personal_db = new \core\modules\personal\models\common\db();
            $reference = $personal_db->getReference($this->page_options[1]);
            $reference_check = $personal_db->getLatestReferenceCheck($this->page_options[1]);
            $ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();
            $person = $ab_db->getAddressBookMainDetails($reference['address_book_id']);
            $this->_sendReferenceEmail($person['title'].' '.$person['number_given_name'].' '.$person['entity_family_name'], $reference_check['hash'], $reference['family_name'], $reference['given_names'], $reference['email']);

            return $this->response([
                'message' => 'Successfully resend the email!'
            ]);
        }

        $data = $_POST;
        $personal_db = new \core\modules\personal\models\common\db();

        if($data['contact_method'] == 'phone'){
            $check = [
                'reference_id' => $data['reference_id'],
                'contact_method' => $data['contact_method'],
                'status' => 'completed',
                'hash' => '',
                'question_group_name' => $data['question_group_name'],
                'completed_on' => date('Y-m-d H:i:s'),
                'completed_by' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0,
            ];
            $personal_db->insertReferenceCheck($check);

            if(isset($data['answer'])){
                foreach ($data['answer'] as $key => $item){
                    $personal_db->insertReferenceCheckAnswer([
                        'reference_id' => $data['reference_id'],
                        'question_id' => $key,
                        'answer' => $item,
                    ]);
                }
            }
            return $this->response([
                'message' => 'Successfully insert reference check!'
            ]);
        }else if($data['contact_method'] == 'email'){
            $hash = md5($data['reference_id'].date('Y-m-d H:i:s'));
            $check = [
                'reference_id' => $data['reference_id'],
                'contact_method' => $data['contact_method'],
                'status' => 'pending',
                'hash' => $hash,
                'question_group_name' => $data['question_group_name'],
                'completed_on' => '0000-00-00 00:00:00',
                'completed_by' => 0,
                'requested_on' => date('Y-m-d H:i:s'),
                'requested_by' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0
            ];

            $personal_db->insertReferenceCheck($check);

            //send email part
            $reference = $personal_db->getReference($data['reference_id']);
            $ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();

            $person = $ab_db->getAddressBookMainDetails($reference['address_book_id']);

            $this->_sendReferenceEmail($person['title'].' '.$person['number_given_name'].' '.$person['entity_family_name'], $hash, $reference['family_name'], $reference['given_names'], $reference['email']);
            return $this->response([
                'message' => 'Successfully insert reference check!'
            ]);
        }

    }

    private function _sendReferenceEmail($user_name, $hash,$family_name,$given_name,$main_email)
    {
        $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
        $menu_register = $menu_register_ns::getInstance();
        $link_id = $menu_register->getModuleLink('referencecheck');

        $mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;

        $to_name = empty($family_name) ? $given_name : $given_name.' '.$family_name;
        $to_email = $main_email;

        //from the system info
        $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $template = $mailing_common->renderEmailTemplate('reference_check', [
            'to_name' => $to_name,
            'user_name' => $user_name,
            'link' => HTTP_TYPE.SITE_WWW.'/'.$link_id .'/process/'.$hash
        ]);
        if ($template) {
            $subject = $template['subject'];
        } else {

            $subject = 'Reference Check - '.SITE_WWW;
        }

        //message
        $message = $template['subject'];

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
        $fullhtml = false;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $generic = \core\app\classes\generic\generic::getInstance();
        $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);

        return;
    }


}