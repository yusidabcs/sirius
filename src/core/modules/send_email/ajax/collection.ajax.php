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
final class collection extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;
		
	public function run()
	{	
        $this->authorizeAjax('collection');
        $out = null;
        $mailing_db = new \core\modules\send_email\models\common\db;
        $common_db = new \core\modules\send_email\models\common\common;

        switch ($this->option) {
            case 'list':
                $out = $mailing_db->getCollectionDatatable();
                break;

            case 'add':
                $mailing_db->insertCollection($_POST['collection_name']);
                $collection_id = $mailing_db->latestCollection()['collection_id'];

                if (isset($_POST['emails'])) {
                    if (count($_POST['emails']) > 0 && !empty($_POST['emails'][0])) {
                        foreach ($_POST['emails'] as $key => $value) {
                            if (!$mailing_db->subscriberExists($value)) {
                                $mailing_db->insertSubscriber($value, strstr($value, '@', true));
                            }

                            $mailing_db->attachSubscriberCollection($value, $collection_id);
                        }
                    }
                }

                $out['message'] = 'Successfully add new collection';
            break;

            case 'edit':
                $out['collection'] = $mailing_db->getCollection($_POST['collection_id']);
                $out['subscribers'] = $mailing_db->getSubscriberFromCollection($_POST['collection_id']);
            break;

            case 'update':

                $mailing_db->updateCollection($_POST['collection_id'], $_POST['collection_name']);

                $mailing_db->detachSubscriberFromCollection($_POST['collection_id']);
                
                if (isset($_POST['emails'])) {
                    if (count($_POST['emails']) > 0) {
                        foreach ($_POST['emails'] as $key => $value) {
                            $mailing_db->attachSubscriberCollection($value, $_POST['collection_id']);
                        }
                    }
                }

                $mailing_db->detachSubscriberFromCollection($_POST['collection_id']);
                
                if (isset($_POST['emails'])) {
                    if (count($_POST['emails']) > 0) {
                        foreach ($_POST['emails'] as $key => $value) {
                            $mailing_db->attachSubscriberCollection($value, $_POST['collection_id']);
                        }
                    }
                }

                $out['message'] = 'Collection has been updated';
            break;
            case 'delete':
                $mailing_db->deleteCollection($_POST['collection_id']);
                $mailing_db->detachSubscriberFromCollection($_POST['collection_id']);
                $out = array(
                    'message' => 'Collection has been deleted',
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