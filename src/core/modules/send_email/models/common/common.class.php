<?php
namespace core\modules\send_email\models\common;

use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;

final class common {
    public $mailing_db,$generic_db;

    public function __construct()
    {
        $this->mailing_db = new db();
        $this->generic_db = new \core\app\classes\generic\generic_obj();
    }

    public function getTemplateSubject($template){
        return $this->mailing_db->getTemplateSubject($template);
    }

    public function sendEmailToAllSubscriber($title)
    {
        $subscribers = $this->mailing_db->getAllSubscriber();

        foreach ($subscribers as $key => $subscriber) {
            $this->mailing_db->sendMessageToSubscriber($subscriber['email'], $title, $subscriber['full_name']);
        }
    }

    public function unsubscribe($email)
    {
        return $this->updateSubscriberStatus($email, 0);
    }

    public function sendMessageToSubscriber($email, $fullname, $subject, $mail_template = 'template', $data = [])
    {
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $data['full_name'] = $fullname;

        $subject = $subject;
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

        $content = $this->renderEmailTemplate($mail_template, $data);

        $this->generic_db->sendEmail($fullname, $email, $from_name, $from_email, $content['subject'], $content['html'], $cc, $bcc, $html, $fullhtml, $unsubscribelink);
    }

    public function sendEmailTest($from_email, $to_email, $mail_template)
    {
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');

        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        $template = $this->renderEmailTemplate($mail_template, $data);

        $fullname = explode("@", $to_email)[0];

        $subject = $template['subject'];
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

        $content = $template['html'];
        $this->generic_db->sendEmail($fullname, $to_email, $from_name, $from_email, $subject, $content, $cc, $bcc, $html, $fullhtml, $unsubscribelink);
    }

    public function importSubscriber($file, $collection_id)
    {
        require_once (DIR_LIB.'/simplexlsx/SimpleXLSX.php');
        if ($xlsx = \SimpleXLSX::parse($file)) {
            foreach ($xlsx->rows() as $index => $row) {
                if ($index >= 1) {
                    if (!empty($row[2])) {
                        $this->mailing_db->insertSubscriber(strip_tags($row[2]), strip_tags($row[1]));
                        $this->mailing_db->attachSubscriberCollection(strip_tags($row[2]), $collection_id);
                    }
                }
            }
        }

        return true;
    }

    public function renderEmailTemplate($template_name, $data = array())
    {
        if (empty($template_name)) {
            throw new \Exception("Please specify template name");            
        }

        $template = $this->mailing_db->getTemplateByName(strtolower($template_name));
        
        $template_vars = [
            'header_template' => (empty($template['header_template'])) ? 'header' : $template['header_template'],
            'footer_template' => (empty($template['footer_template'])) ? 'footer' : $template['footer_template'],
            'master_template' => (empty($template['main_template'])) ? 'master' : $template['main_template'],
            'title' => (!empty($template['title'])) ? $template['title'] : $template['subject']
        ];

        if (count($data) > 0) {
            $template_vars = array_merge($template_vars, $data);
        }

        $template_vars['content'] = $this->renderTemplatePart($template_name, $template_vars);

        if (!empty($template_vars['header_template'])) {
            $template_vars['header'] = $this->renderTemplatePart($template_vars['header_template'], $data);
        }

        if (!empty($template_vars['footer_template'])) {
            $template_vars['footer'] = $this->renderTemplatePart($template_vars['footer_template'], $data);
        }

        $html = $this->renderTemplatePart($template_vars['master_template'], $template_vars);

        return [
            'html' => $html,
            'subject' => $template['subject'],
            'title' => $template['title']
        ];
    }

    public function renderTemplatePart($template_name, $vars = array())
    {

        if (!isset($vars['site_url'])) {
            $vars['site_url'] = HTTP_TYPE.SITE_WWW;
        }

        $html = $this->mailing_db->getTemplateContent(strtolower($template_name));

        foreach ($vars as $key => $value) {
            $html = str_replace('{{ '.$key.' }}', $value, $html);
        }

        return $html;
    }

    public function putEmailToCollection($email, $fullname, $collection_name)
    {

        if (!$this->mailing_db->subscriberExists($email)) {
            $this->mailing_db->insertSubscriber($email, $fullname);
        }

		$collection = $this->mailing_db->getCollectionByName($collection_name);

		if ($collection) {
			$this->mailing_db->attachSubscriberCollection($email, $collection['collection_id']);
		}
    }

    public function removeEmailFromCollection($email, $collection_name)
    {
        
		$collection = $this->mailing_db->getCollectionByName($collection_name);

		if ($collection) {
			$this->mailing_db->detachSubscriber($collection['collection_id'], $email);
		}
    }

    public function moveSubscriberToCollection($old_collection, $new_collection, $email)
    {

        $old_collection_data = $this->mailing_db->getCollectionByName($old_collection);
        $new_collection_data = $this->mailing_db->getCollectionByName($new_collection);

        if ($old_collection_data && $new_collection_data) {
            # code...
            $this->mailing_db->detachSubscriber($old_collection_data['collection_id'], $email);
            $this->mailing_db->attachSubscriberCollection($email, $new_collection_data['collection_id']);
        }

    }
}