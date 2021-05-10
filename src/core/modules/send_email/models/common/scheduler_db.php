<?php
namespace core\modules\send_email\models\common;

/**
 * Final send_email db class.
 *
 * @final
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class scheduler_db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
    }

    public function getPendingEmailTracker($campaign_id, $limit)
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `email`,
                    `tracker_code`,
                    `subject`,
                    `created_on`,
                    `updated_on`,
                    `status`
                FROM
                    `mailing_tracker`
                WHERE
                    `status` = 'pending'
                AND
                    `campaign_id` = ?
                ORDER BY `created_on` asc
                
                LIMIT $limit
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->bind_result($campaign_id, $email, $tracker_code, $subject, $created_on,$updated_on, $status);
        $stmt->execute();

        while ($stmt->fetch()) {
            # code...
            $out[] = array(
                'campaign_id' => $campaign_id,
                'email' => $email,
                'tracker_code' => $tracker_code,
                'subject' => $subject,
                'created_on' => $created_on,
                'updated_on' => $updated_on,
                'status' => $status,
            );
        }

        $stmt->close();

        return $out;
    }

    public function getTrackerEmail($tracker_code){
        $out = false;

        $sql = "SELECT
                    `campaign_id`,
                    `email`,
                    `tracker_code`,
                    `subject`,
                    `created_on`,
                    `updated_on`,
                    `status`
                FROM
                    `mailing_tracker`
                WHERE
                    `tracker_code` = ?
                AND
                    `status` = 'sent'
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $tracker_code);
        $stmt->bind_result($campaign_id, $email, $tracker_code, $subject, $created_on,$updated_on, $status);
        $stmt->execute();

        if ($stmt->fetch()) {
            # code...
            $out = array(
                'campaign_id' => $campaign_id,
                'email' => $email,
                'tracker_code' => $tracker_code,
                'subject' => $subject,
                'created_on' => $created_on,
                'updated_on' => $updated_on,
                'status' => $status,
            );
        }

        $stmt->close();

        return $out;
    }

    public function updateTrackerEmail($tracker_code, $status){
        $out = false;

        $sql = "UPDATE
                    `mailing_tracker`
                SET
                    `status` = ?,
                    `updated_on` = NOW()
                WHERE
                    `tracker_code` = ?
                    AND
                    `status` != 'opened'    
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $status, $tracker_code);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getActiveCampaigns()
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`,
                    `email_template`
                FROM
                    `mailing_campaign`
                WHERE
                    `status` = 'active'";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $status, $email_template);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status,
                'email_template' => $email_template
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getActiveCampaignsWithoutReminder()
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`,
                    `email_template`
                FROM
                    `mailing_campaign`
                WHERE
                    `status` = 'active'
                AND
                    `campaign_id` NOT IN (
                        SELECT `campaign_id` FROM `mailing_reminder` GROUP BY `campaign_id`
                    )";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $status, $email_template);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status,
                'email_template' => $email_template
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getCampaign($campaign_id)
    {
        $out = false;

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`,
                    `email_template`
                FROM
                    `mailing_campaign`
                WHERE
                    `status` = 'active'
                AND
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->bind_result($id, $name, $status, $email_template);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status,
                'email_template' => $email_template
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getCampaignAllStatus($campaign_id)
    {
        $out = false;

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`,
                    `email_template`
                FROM
                    `mailing_campaign`
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->bind_result($id, $name, $status, $email_template);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status,
                'email_template' => $email_template
            ];
        }

        $stmt->close();

        return $out;
    }

}