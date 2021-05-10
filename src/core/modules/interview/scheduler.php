<?php
namespace core\modules\interview;

class scheduler {
	
	public function __construct()
	{
        $this->_sendInteviewNotification();

		return;
    }

    private function _sendInteviewNotification()
    {
        $db = new \core\modules\interview\models\common\db();
        $common_db = new \core\modules\interview\models\common\common();
        
        //interview : reminder in 7 days
        $date_check = date('Y-m-d',strtotime(date('Y-m-d').' +7 days'));
        $data_interview_location_f = $db->getDataInterviewLocationReminder($date_check);
        $data_interview_online_f = $db->getDataInterviewOnlineReminder($date_check);
        //print_r($data_interview_online);

        //interview : reminder in 2 days
        $date_check = date('Y-m-d',strtotime(date('Y-m-d').' +2 days'));
        $data_interview_location_s = $db->getDataInterviewLocationReminder($date_check);
        $data_interview_online_s = $db->getDataInterviewOnlineReminder($date_check);
        //print_r($data_interview_online);

        $common_db->sendEmailReminderInterview(array_merge($data_interview_location_s,$data_interview_location_f),'location');
        $common_db->sendEmailReminderInterview(array_merge($data_interview_online_s,$data_interview_online_f),'online');


    }

}

?>