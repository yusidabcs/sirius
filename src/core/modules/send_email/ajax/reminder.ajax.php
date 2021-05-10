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
final class reminder extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;
		
	public function run()
	{	
        $this->authorizeAjax('reminder');
        $out = null;
        $mailing_db = new \core\modules\send_email\models\common\db;

        switch ($this->option) {
            case 'list':
                $out = $mailing_db->getReminderDatatable();
                break;

            case 'add':
                $mailing_db->insertReminder($_POST['title'], $_POST['campaign_id'], $_POST['cron_time']);

                $out['message'] = 'Successfully add new reminder';
            break;

            case 'edit':
                $out = $mailing_db->getReminder($this->page_options[1]);    
            break;

            case 'update':
                $mailing_db->updateReminder($this->page_options[1], $_POST['title'], $_POST['campaign_id'], $_POST['cron_time']);

                $out['message'] = 'Reminder has been updated';
            break;
            case 'delete':
                $mailing_db->deleteReminder($_POST['reminder_id']);

                $out = array(
                    'message' => 'Reminder has been deleted',
                    'status' => 'success'
                );
                break;
            case 'activate':
                $mailing_db->activateReminder($this->page_options[1]);
                $out = array(
                    'message' => 'Reminder has been activated!',
                    'status' => 'success'
                );
            break;
            case 'deactivate':
                $mailing_db->deactivateReminder($this->page_options[1]);
                $out = array(
                    'message' => 'Reminder has been deactivated!',
                    'status' => 'success'
                );
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