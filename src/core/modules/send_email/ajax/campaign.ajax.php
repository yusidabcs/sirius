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
final class campaign extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;
		
	public function run()
	{	
        $this->authorizeAjax('campaign');
        $out = null;
        $mailing_db = new \core\modules\send_email\models\common\db;
        $common_db = new \core\modules\send_email\models\common\common;

        switch ($this->option) {
            case 'list':
                $out = $mailing_db->getCampaignDatatable();
                break;

            case 'add':
                $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $tracker_code = '';

                $mailing_db->insertCampaign($_POST['name'], $_POST['email_template'], $_POST['status']);
                $campaign_id = $mailing_db->latestCampaign()['campaign_id'];
                $emails = $_POST['emails'] ?? [];

                if ($_POST['source_type'] === 'collection') {
                    $subscribers = $mailing_db->getSubscriberFromCollection($_POST['collection']);
                    $mailing_db->updateCampaign($campaign_id, ['collection_id' => $_POST['collection']]);

                    $emails = array_map(function($item) {
                        return $item['email'];
                    }, $subscribers);

                }

                foreach ($emails as $key => $value) {
                    # code...
                    for ($i=0; $i < 32; $i++) { 
                        $tracker_code .= $chars[rand(0, 32)];
                    }
                    
                    $mailing_db->insertCampaignTracker($campaign_id, $value, $tracker_code, $_POST['email_template']);
                    $tracker_code = '';
                }

                $out['message'] = 'Successfully add new campaign';
            break;

            case 'edit':
                $out['campaign'] = $mailing_db->getCampaign($_POST['campaign_id']);
                $out['trackers'] = $mailing_db->getCampaignTracker($_POST['campaign_id']);
            break;

            case 'update':
                $mailing_db->updateCampaign($_POST['campaign_id'], [
                    'name' => $_POST['name'],
                    'email_template' => $_POST['email_template'],
                    'status' => $_POST['status']
                ]);

                $out['message'] = 'Campaign has been updated';
            break;
            case 'delete':
                $mailing_db->deleteCampaign($_POST['campaign_id']);
                $mailing_db->deleteCampaignTracker($_POST['campaign_id']);

                $out = array(
                    'message' => 'Campaign has been deleted',
                    'status' => 'success'
                );
                break;
            case 'activate':
                $mailing_db->activateCampaign($_POST['campaign_id']);
                $out = array(
                    'message' => 'Campaign has been activated!',
                    'status' => 'success'
                );
            break;
            case 'draf':
                $mailing_db->drafCampaign($_POST['campaign_id']);
                $out = array(
                    'message' => 'Campaign has been draf!',
                    'status' => 'success'
                );
            break;
            case 'listTracker':
                $out = $mailing_db->getCampaignTrackerDatatable($this->page_options[1]);
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