<?php
namespace core\modules\send_email;

/**
 * @package		send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
class scheduler {
	
    private $campaign_id;

	public function __construct($campaign_id)
	{
        $this->_sendEmailTracker($campaign_id);
        $this->runReminder();
		return;
    }
    
    private function runReminder(){
        //get pending tracker
        $db = new \core\modules\send_email\models\common\scheduler_db();
        $mailing_db = new \core\modules\send_email\models\common\db();
        $maililng_common = new \core\modules\send_email\models\common\common();

        $campaign = $db->getCampaignAllStatus($this->campaign_id);
        
        if ($campaign) {
            # code...
            if ($campaign['status'] == 'finish') {
                $this->reloadCampaign();
            }
        }
    }

    private function reloadCampaign()
    {
        $mailing_db = new \core\modules\send_email\models\common\db();
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        $campaign = $mailing_db->getCampaign($this->campaign_id);
        $tracker_code = '';

        for ($i=0; $i < 32; $i++) { 
            $tracker_code .= $chars[rand(0, 32)];
        }

        $campaignTrackers = $mailing_db->getCampaignTracker($this->campaign_id);

        foreach ($campaignTrackers as $key => $value) {
            $mailing_db->insertCampaignTracker($this->campaign_id, $value['email'], $tracker_code, $value['subject']);
        }

        $mailing_db->activateCampaign($this->campaign_id);
    }
   
}

?>