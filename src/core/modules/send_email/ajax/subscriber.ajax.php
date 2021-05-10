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
final class subscriber extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;
		
	public function run()
	{	
        $this->authorizeAjax('subscriber');
        $out = null;
        $mailing_db = new \core\modules\send_email\models\common\db;
        $common_db = new \core\modules\send_email\models\common\common;

        switch ($this->option) {
            case 'list':
                $out = $mailing_db->getSubscriberDatatable();
                break;
            case 'all':
                $out = $mailing_db->getAllSubscriber();
                break;
            case 'delete':
                $mailing_db->deleteSubscriber($_POST['email']);
                $out = array(
                    'message' => 'Subscriber has been deleted',
                    'status' => 'success'
                );
                break;
            case 'import':
                $common_db = new \core\modules\send_email\models\common\common;
                $mailing_db = new \core\modules\send_email\models\common\db;

                $collection_id = $_POST['collection_id'];

                if ($_POST['collection_id'] == -1 && !empty($_POST['new_collection'])) {
                    $mailing_db->insertCollection($_POST['new_collection']);

                    $collection_id = $mailing_db->latestCollection()['collection_id'];
                }

                if ($collection_id == -1) {
                    $collection_id = 0;
                }

                $mailing_db->detachSubscriberFromCollection($collection_id);

                $import = $common_db->importSubscriber($_FILES['import_file']['tmp_name'], $collection_id);

                if ($import) {
                    $out = array(
                        'message' => 'Subscriber imported successfully'
                    );
                } else {
                    $out = array(
                        'message' => 'Import failed'
                    );
                }

                break;

            case 'send':
                $subscribers = $_POST['subscribers'];
                $common_db = new \core\modules\send_email\models\common\common;
                $db = new \core\modules\send_email\models\common\db;

                foreach ($subscribers as $key => $subscriber) {
                    $subscriber_detail = $db->getSubscriber($subscriber);

                    $common_db->sendMessageToSubscriber($subscriber, $subscriber_detail['full_name'], $_POST['subject'], $_POST['template_name']);
                }

                $out['message'] = 'All emails sended!';
                break;
            case 'disable':
                $mailing_db->updateSubscriberStatus($_POST['email'], 0);
                $out = array(
                    'message' => 'Subscriber has been disabled',
                    'status' => 'success'
                );
                break;
            case 'activate':
                $mailing_db->updateSubscriberStatus($_POST['email'], 1);
                $out = array(
                    'message' => 'Subscriber has been activated',
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