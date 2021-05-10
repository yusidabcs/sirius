<?php
namespace core\modules\send_email\ajax;

/**
 * Final default class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 December 2016
 */
final class template extends \core\app\classes\module_base\module_ajax {
		
	public function run()
	{	
        $this->authorizeAjax('template');
        $out = null;
        $mailing_db = new \core\modules\send_email\models\common\db;
        $mailing_common = new \core\modules\send_email\models\common\common;

        switch ($this->option) {
            case 'list':
                $out = $mailing_db->getEmailTemplateDatatable();
            break;
            case 'edit':
                $out = $mailing_db->getTemplate($this->page_options[1]);
            break;
            case 'add':
                $template_name = strtolower(str_replace(' ', '_', trim($_POST['name'])));
                
                if ($mailing_db->hasTemplate($template_name)) {
                    header('Content-Type: application/json; charset=utf-8');
			        return json_encode(array(
                        'message' => 'Template name already been use',
                        'status' => 'error'
                    )); 
                }

                if (!empty(SUBMITTED_TEXT)) {
                    $clean_html = preg_replace('#<\?php(.*?)\\?>#is', '', preg_replace('#<script(.*?)>(.*?)</script>#is', '', SUBMITTED_TEXT));
                    $mailing_db->insertTemplate($template_name, $_POST['subject'], $_POST['title'], $_POST['type'], $_POST['header_template'], $_POST['footer_template'], $_POST['main_template'], $clean_html);
                }

                $out = array('message' => 'Template has been created!', 'status' => 'success');
            break;
            case 'update':

                $template_name = strtolower(str_replace(' ', '_', trim($_POST['name'])));
                
                if (!empty(SUBMITTED_TEXT)) {
                    $clean_html = preg_replace('#<\?php(.*?)\\?>#is', '', preg_replace('#<script(.*?)>(.*?)</script>#is', '', SUBMITTED_TEXT));
                    $mailing_db->updateTemplate($this->page_options[1], $template_name, $_POST['subject'], $_POST['title'], $_POST['type'], $_POST['header_template'], $_POST['footer_template'], $_POST['main_template'], $clean_html);
                }

                $out = array('message' => 'Template saved!', 'status' => 'success');
            break;
            case 'delete':
                $mailing_db->deleteTemplate($_POST['template_id']);
                $out = array('status' => 'success', 'message' => 'Template has been deleted!');
            break;

            case 'preview':
                
                if ($_POST['type'] === 'master' || $_POST['type'] === 'header' || $_POST['type'] === 'footer') {
                    $out['content'] = $mailing_common->renderEmailTemplate('preview')['html'];
                } else {
                    $out['content'] = $mailing_common->renderEmailTemplate($this->page_options[1])['html'];
                }
            break;

            case 'get-template-parts':
                $out['content'] = $mailing_db->getTemplateParts();
                break;

            case 'send':
                $mailing_common->sendEmailTest($_POST['from_email'],$_POST['to_email'],$_POST['template_name']);

                $out['message'] = 'Email sended';
                $out['status'] = 'success';
                break;
            
            default:
                # code...
                break;
        }
				
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>