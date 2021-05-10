<?php
namespace core\modules\send_email;

/**
 * @package		send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
class scheduler_campaign {
	
	public function __construct()
	{
        $this->_sendEmailTracker();
		return;
    }
    
    private function _sendEmailTracker(){
        //get pending tracker
        $db = new \core\modules\send_email\models\common\scheduler_db();
        $mailing_db = new \core\modules\send_email\models\common\db();
        $maililng_common = new \core\modules\send_email\models\common\common();

        $campaigns = $db->getActiveCampaigns();
                
        if (count($campaigns) > 0) {
            # code...
            //start sending the email
            foreach ($campaigns as $key => $campaign) {
                # code...
                $trackers = $db->getPendingEmailTracker($campaign['campaign_id'], 10);
                $total_trackers = $mailing_db->countCampaignTracker($campaign['campaign_id']);

                foreach($trackers as $index => $tracker){
                    //send email
                    
                    //if successs update tracker status jadi sent
                    $subscriber = $mailing_db->getSubscriber($tracker['email']);
                    $subject = $mailing_db->getTemplateSubject($campaign['email_template']);
                    # code...
                    try {
                        $data = [
                            'tracking_url' => HTTP_TYPE.SITE_WWW.'/email_tracker.php?t='.$tracker['tracker_code']
                        ];
                        
                        $maililng_common->sendMessageToSubscriber($tracker['email'], $subscriber['full_name'], $subject, $campaign['email_template'], $data);
                        $mailing_db->updateTrackerStatus($tracker['campaign_id'], $tracker['email'], 'sent');
                    } catch (\Throwable $th) {
                        //if fail 
                        throw $th;
                    }
        
                }
                $tracker_sent_total = $mailing_db->countCampaignTrackerDone($campaign['campaign_id']);

                if ($total_trackers === $tracker_sent_total) {
                    $mailing_db->updateCampaign($campaign['campaign_id'], [
                        'status' => 'finish'
                    ]);
                }
            }
        }

    }
   
}

?>