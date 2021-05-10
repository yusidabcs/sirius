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
final class db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
    }

    public function getSubscriberDatatable()
    {
        $request = $_POST;
        $tablename = 'mailing_subscriber';
        $this->validateRequest($request, ['collection_id', 'status']);
		$primaryKey = $tablename.'.email';

        $columns = array(
            array( 'db' => $tablename.'.`email`', 'dt' => 'email' ),
            array( 'db' => $tablename.'.full_name', 'dt' => 'full_name' ),
            array( 'db' => $tablename.'.status', 'dt' => 'status' ),
            array( 'db' => 'mailing_subscriber_collection.collection_id', 'dt' => 'collection_id'),
            array( 'db' => 'mailing_collection.name', 'dt' => 'collection_name'),
			array( 'db' => $tablename.'.created_on', 'dt' => 'created_on'),
			array( 'db' => $tablename.'.updated_on', 'dt' => 'updated_on')
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        
        $join = " LEFT JOIN mailing_subscriber_collection ON $tablename.`email` = mailing_subscriber_collection.email";
        $join .= " LEFT JOIN mailing_collection ON `mailing_collection`.`collection_id` = `mailing_subscriber_collection`.`collection_id`";
        
        if (!empty($_POST['collection_id'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `mailing_subscriber_collection`.`collection_id` = ".$_POST['collection_id'];
        }

        if ($_POST['status'] !== "") {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " $tablename.`status` = ".(int)$_POST['status'];
        }


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
             $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function getCollectionDatatable()
    {
        $request = $_POST;
        $tablename = 'mailing_collection';
        // $this->validateRequest($request, ['status', 'level','startDate','endDate']);
		$primaryKey = $tablename.'.collection_id';

        $columns = array(
            array( 'db' => $tablename.'.`collection_id`', 'dt' => 'collection_id' ),
            array( 'db' => $tablename.'.name', 'dt' => 'name' ),
            array( 'db' => '
                (
                    SELECT COUNT(*) as email
                    FROM `mailing_subscriber_collection`
                    WHERE `mailing_subscriber_collection`.`collection_id` = `mailing_collection`.`collection_id`
                )
            ', 'as' => 'total_subscriber', 'dt' => 'total_email' )
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        $join = "";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function getCampaignTrackerDatatable($campaign_id)
    {
        $request = $_POST;
        $tablename = 'mailing_tracker';
        $this->validateRequest($request, ['status']);
		$primaryKey = $tablename.'.email';

        $columns = array(
            array( 'db' => $tablename.'.`campaign_id`', 'dt' => 'campaign_id' ),
            array( 'db' => $tablename.'.`email`', 'dt' => 'email' ),
            array( 'db' => $tablename.'.subject', 'dt' => 'subject' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.tracker_code', 'dt' => 'tracker_code'),
			array( 'db' => $tablename.'.updated_on', 'dt' => 'updated_on')
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= " $tablename.`campaign_id` = '$campaign_id'";

        if (!empty($_POST['status'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " $tablename.`status` = '".$_POST['status']."'";
        }

        $join = "";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function getEmailTemplateDatatable()
    {
        $request = $_POST;
        $tablename = 'mailing_template';
        $this->validateRequest($request, ['type']);
		$primaryKey = $tablename.'.template_id';

        $columns = array(
            array( 'db' => $tablename.'.`template_id`', 'dt' => 'template_id' ),
            array( 'db' => $tablename.'.`name`', 'dt' => 'name' ),
            array( 'db' => $tablename.'.`subject`', 'dt' => 'subject' ),
            array( 'db' => $tablename.'.`title`', 'dt' => 'title' ),
            array( 'db' => $tablename.'.`type`', 'dt' => 'type' ),
            array( 'db' => $tablename.'.created_on', 'dt' => 'created_on' )
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        if (!empty($_POST['type'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " $tablename.`type` = '".$_POST['type']."'";
        }

        $join = "";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function getCampaignDatatable()
    {
        $request = $_POST;
        $tablename = 'mailing_campaign';
        // $this->validateRequest($request, ['status', 'level','startDate','endDate']);
		$primaryKey = $tablename.'.campaign_id';

        $columns = array(
            array( 'db' => $tablename.'.`campaign_id`', 'dt' => 'campaign_id' ),
            array( 'db' => $tablename.'.`created_on`', 'dt' => 'created_on' ),
            array( 'db' => $tablename.'.name', 'dt' => 'name' ),
            array( 'db' => $tablename.'.email_template', 'dt' => 'email_template' ),
            array( 'db' => $tablename.'.status', 'dt' => 'status' ),
            array( 'db' => 'mailing_collection.collection_id', 'dt' => 'collection_id' ),
            array( 'db' => 'mailing_collection.name', 'as' => 'collection_name', 'dt' => 'collection_name' ),
            array( 'db' => '
                (SELECT
                    COUNT(*)
                FROM
                    `mailing_tracker`
                WHERE
                    `mailing_tracker`.`campaign_id` = '.$tablename.'.`campaign_id`)', 'as' => 'total_trackers', 'dt' => 'total_trackers' ),
            array( 'db' => '
                (
                    ((SELECT
                    COUNT(*)
                FROM
                    `mailing_tracker`
                WHERE
                    `mailing_tracker`.`campaign_id` = '.$tablename.'.`campaign_id`
                AND `mailing_tracker`.`status` = "opened") / (SELECT
                COUNT(*)
            FROM
                `mailing_tracker`
            WHERE
                `mailing_tracker`.`campaign_id` = '.$tablename.'.`campaign_id`)) * 100
                )
            ', 'as' => 'open_rate', 'dt' => 'open_rate')
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        $join = " LEFT JOIN mailing_collection ON $tablename.collection_id = mailing_collection.collection_id";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
             $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }
    
    public function insertSubscriber($email, $fullname)
    {
        $out = false;
        $sql = "INSERT
                    INTO 
                        `mailing_subscriber`
                    SET
                        `email` = ?,
                        `full_name` = ?,
                        `created_on` = CURRENT_TIMESTAMP
                    ON DUPLICATE KEY UPDATE
                        `full_name` = ?,
                        `updated_on` = CURRENT_TIMESTAMP";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $email, $fullname, $fullname);

        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;

    }

    public function subscriberExists($email)
    {
        $out = false;
        $sql = "SELECT `email`
                    FROM 
                        `mailing_subscriber`
                    WHERE
                        `email` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $email);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getSubscriber($email)
    {
        $out = [];

        $sql = "SELECT
                    `email`,
                    `full_name`,
                    `status`
                FROM
                    `mailing_subscriber`
                WHERE
                    `email` = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('s', $email);
        $stmt->bind_result($_email, $fullname, $status);
        $stmt->execute();

        if ($stmt->fetch()) {
            # code...
            $out = array(
                'email' => $email,
                'full_name' => $fullname,
                'status' => $status
            );
        }

        $stmt->close();

        return $out;
    }

    public function getAllSubscriber()
    {
        $out = false;

        $sql = "SELECT
                    `email`,
                    `full_name`,
                    `status`
                FROM
                    `mailing_subscriber`
                WHERE
                    `status` = 1
                ORDER BY `full_name`";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_result($_email, $fullname, $status);
        $stmt->execute();

        while ($stmt->fetch()) {
            # code...
            $out[] = array(
                'email' => $_email,
                'full_name' => $fullname,
                'status' => $status
            );
        }

        $stmt->close();

        return $out;
    }

    public function getSubscriberFromCollection($collection_id)
    {
        $out = false;

        $sql = "SELECT
                    `mailing_subscriber`.`email`
                FROM
                    `mailing_subscriber_collection`
                LEFT JOIN
                    `mailing_subscriber`
                    ON
                        `mailing_subscriber_collection`.`email` = `mailing_subscriber`.`email`
                WHERE
                    `mailing_subscriber`.`status` = 1
                AND
                    `mailing_subscriber_collection`.`collection_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $collection_id);
        $stmt->bind_result($_email);
        $stmt->execute();

        while ($stmt->fetch()) {
            # code...
            $out[] = array(
                'email' => $_email
            );
        }

        $stmt->close();

        return $out;
    }

    public function updateSubscriberStatus($email, $status)
    {
        $out = false;

        $sql = "UPDATE 
                    `mailing_subscriber`
                SET
                    `status` = ?
                WHERE
                    `email` = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('is', $status, $email);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            # code...
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deleteSubscriber($email)
    {
        $out = false;

        $sql = "DELETE
                    FROM 
                        `mailing_subscriber`
                    WHERE
                        `email` = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('s', $email);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            # code...
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deleteSubscriberForCollection($collection_id)
    {
        $out = false;

        $sql = "DELETE
                    FROM 
                        `mailing_subscriber`
                    WHERE
                        `collection_id` = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('i', $collection_id);
        $stmt->execute();

        if ($stmt->affected_rows >= 1) {
            # code...
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function insertCollection($name)
    {
        $out = false;

        $sql = "INSERT
                    INTO
                        `mailing_collection`(
                            `name`
                        )
                        VALUES(?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $name);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function updateCollection($collection_id, $name)
    {
        $out = false;
        $update_set = '';

        $sql = "UPDATE
                    `mailing_collection`
                SET
                    `name` = ?
                WHERE
                    `collection_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $name, $collection_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deleteCollection($collection_id)
    {
        $out = false;

        $sql = "DELETE
                    FROM
                        `mailing_collection`
                WHERE
                    `collection_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $collection_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getCollection($collection_id)
    {
        $out = [];

        $sql = "SELECT
                    `collection_id`,
                    `name`
                FROM
                    `mailing_collection`
                WHERE
                    `collection_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $collection_id);
        $stmt->bind_result($id, $name);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = [
                'collection_id' => $id,
                'name' => $name
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getAllCollection()
    {
        $out = [];

        $sql = "SELECT
                    `collection_id`,
                    `name`
                FROM
                    `mailing_collection`
                ORDER BY `name`";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'collection_id' => $id,
                'name' => $name
            ];
        }

        $stmt->close();

        return $out;
    }

    public function latestCollection()
    {
        $out = [];

        $sql = "SELECT
                    `collection_id`,
                    `name`
                FROM
                    `mailing_collection`
                ORDER BY `collection_id` DESC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = [
                'collection_id' => $id,
                'name' => $name
            ];
        }

        $stmt->close();

        return $out;
    }

    public function insertCampaign($name, $email_template, $status)
    {
        $out = false;

        $sql = "INSERT
                    INTO
                        `mailing_campaign`(
                            `name`,
                            `created_on`,
                            `user_id`,
                            `email_template`,
                            `status`
                        )
                        VALUES(?,CURRENT_TIMESTAMP,$this->user_id,?,?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $name, $email_template, $status);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function updateCampaign($campaign_id, $data)
    {
        $out = false;
        $update_set = ' ';

        foreach ($data as $key => $value) {
            $update_set .= "$key = '$value',";
        }

        $update_set = rtrim($update_set,',');

        $sql = "UPDATE
                    `mailing_campaign`
                SET
                    $update_set
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deleteCampaign($campaign_id)
    {
        $out = false;

        $sql = "DELETE
                    FROM
                        `mailing_campaign`
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getCampaign($campaign_id)
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `email_template`,
                    `status`
                FROM
                    `mailing_campaign`
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->bind_result($id, $name, $email_template, $status);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = [
                'campaign_id' => $id,
                'name' => $name,
                'email_template' => $email_template,
                'status' => $status
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getAllCampaign()
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`
                FROM
                    `mailing_campaign`";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $status);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getCampaignByStatus($status)
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`
                FROM
                    `mailing_campaign`
                WHERE
                    `status` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $status);
        $stmt->bind_result($id, $name, $status);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status
            ];
        }

        $stmt->close();

        return $out;
    }

    public function insertCampaignTracker($campaign_id, $email, $tracker_code, $subject)
    {
        $out = false;

        $sql = "INSERT
                    INTO
                        `mailing_tracker`(
                            `campaign_id`,
                            `email`,
                            `tracker_code`,
                            `subject`,
                            `updated_on`
                        )
                    VALUES(?,?,?,?,CURRENT_TIMESTAMP)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isss', $campaign_id, $email, $tracker_code, $subject);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function latestCampaign()
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `name`,
                    `status`
                FROM
                    `mailing_campaign`
                ORDER BY
                    `campaign_id`
                DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $status);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = [
                'campaign_id' => $id,
                'name' => $name,
                'status' => $status
            ];
        }

        $stmt->close();

        return $out;
    }

    public function activateCampaign($campaign_id)
    {
        $out = false;

        $sql = "UPDATE
                    `mailing_campaign`
                SET
                    `status` = 'active'
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function countCampaignTracker($campaign_id)
    {
        $out = 0;

        $sql = "SELECT
                    COUNT(`campaign_id`) 
                AS 
                    `total`
                FROM
                    `mailing_tracker`
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);

        $stmt->bind_result($total);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = $total;
        }

        $stmt->close();

        return $out;
    }

    public function getCampaignTracker($campaign_id)
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `email`,
                    `subject`,
                    `tracker_code`,
                    `status`
                FROM
                    `mailing_tracker`
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->bind_result($_campaign_id, $email, $subject, $tracker_code, $status);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'campaign_id' => $_campaign_id,
                'email' => $email,
                'subject' => $subject,
                'tracker_code' => $tracker_code,
                'status' => $status
            ];
        }

        $stmt->close();

        return $out;
    }

    public function getCampaignTrackerByStatus($campaign_id, $status)
    {
        $out = [];

        $sql = "SELECT
                    `campaign_id`,
                    `email`,
                    `subject`,
                    `tracker_code`,
                    `status`
                FROM
                    `mailing_tracker`
                WHERE
                    `campaign_id` = ?
                AND
                    `status` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $campaign_id);
        $stmt->bind_result($_campaign_id, $email, $subject, $tracker_code, $_status);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = [
                'campaign_id' => $_campaign_id,
                'email' => $email,
                'subject' => $subject,
                'tracker_code' => $tracker_code,
                'status' => $_status
            ];
        }

        $stmt->close();

        return $out;
    }

    public function countCampaignTrackerDone($campaign_id)
    {
        $out = 0;

        $sql = "SELECT
                    COUNT(*) AS `total`
                FROM
                    `mailing_tracker`
                WHERE
                    `campaign_id` = ?
                AND
                    `status` IN('sent','opened')";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->bind_result($total);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = $total;
        }

        $stmt->close();

        return $out;
    }

    public function deleteCampaignTracker($campaign_id)
    {
        $out = false;

        $sql = "DELETE
                    FROM
                        `mailing_tracker`
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);

        $stmt->execute();

        if ($stmt->affected_rows >= 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function updateTrackerStatus($campaign_id, $email, $status)
    {
        $out = false;

        $sql = "UPDATE
                    `mailing_tracker`
                SET
                    `status` = ?,
                    `updated_on` = CURRENT_TIMESTAMP
                WHERE
                    `campaign_id` = ?
                AND
                    `email` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sis', $status, $campaign_id, $email);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getAllTemplate()
    {
        $out = [];

        $sql = "SELECT
                    `id`,
                    `name`,
                    `subject`,
                    `title`,
                FROM
                    `mailing_template`";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $subject, $title);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'id' => $id,
                'name' => $name,
                'subject' => $subject,
                `title` => $title
            );
        }

        $stmt->close();

        return $out;
    }

    public function insertTemplate($name, $subject, $title, $type, $header, $footer, $main_template, $content)
    {
        $out = false;

        $sql = "INSERT
                    INTO
                        `mailing_template`(
                            `name`,
                            `subject`,
                            `title`,
                            `type`,
                            `footer_template`,
                            `header_template`,
                            `main_template`,
                            `content`,
                            `created_by`
                        )
                    VALUES(?,?,?,?,?,?,?,?,{$this->user_id})";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssss', $name, $subject, $title, $type, $footer, $header, $main_template, $content);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function updateTemplate($id, $name, $subject, $title, $type, $header, $footer, $main_template, $content)
    {
        $out = false;

        $sql = "UPDATE
                    `mailing_template`
                SET
                    `name` = ?,
                    `subject` = ?,
                    `title` = ?,
                    `type` = ?,
                    `header_template` = ?,
                    `footer_template` = ?,
                    `main_template` = ?,
                    `content` = ?
                WHERE
                    `template_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssssi', $name, $subject, $title, $type, $header, $footer, $main_template, $content, $id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deleteTemplate($id)
    {
        $out = false;

        $sql = "DELETE
                    FROM
                        `mailing_template`
                WHERE
                    `template_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getTemplateContent($template_name)
    {
        $out = false;

        $sql = "SELECT
                    `content`
                FROM
                    `mailing_template`
                WHERE
                    `name` = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $template_name);
        $stmt->bind_result($content);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = $content;
        }

        $stmt->close();

        return $out;
    }

    public function getTemplateSubject($template_name)
    {
        $out = '';

        $sql = "SELECT
                    `subject`
                FROM
                    `mailing_template`
                WHERE
                    `name` = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $template_name);
        $stmt->bind_result($subject);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = $subject;
        }

        $stmt->close();

        return $out;
    }

    public function getTemplateByName($name)
    {
        $out = false;

        $sql = "SELECT
                    `template_id`,
                    `name`,
                    `subject`,
                    `title`,
                    `type`,
                    `header_template`,
                    `footer_template`,
                    `main_template`,
                    `content`
                FROM
                    `mailing_template`
                WHERE
                    `name` = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $name);
        $stmt->bind_result($id, $name, $subject, $title, $type, $header, $footer, $main_template, $content);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = array(
                'template_id' => $id,
                'name' => $name,
                'subject' => $subject,
                'title' => $title,
                'type' => $type,
                'header_template' => $header,
                'footer_template' => $footer,
                'main_template' => $main_template,
                'content' => $content
            );
        }

        $stmt->close();

        return $out;
    }

    public function getTemplateParts()
    {
        $out = [];

        $sql = "SELECT
                    `template_id`,
                    `name`,
                    `subject`,
                    `title`,
                    `type`,
                    `header_template`,
                    `footer_template`,
                    `main_template`
                FROM
                    `mailing_template`
                WHERE
                    `type` = 'template_part'";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $subject, $title, $type, $header, $footer, $main_template);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'template_id' => $id,
                'name' => $name,
                'title' => $title,
                'subject' => $subject,
                'type' => $type,
                'header_template' => $header,
                'footer_template' => $footer,
                'main_template' => $main_template,
            );
        }

        $stmt->close();

        return $out;
    }

    public function getTemplate($id)
    {
        $out = false;

        $sql = "SELECT
                    `template_id`,
                    `name`,
                    `title`,
                    `subject`,
                    `type`,
                    `header_template`,
                    `footer_template`,
                    `main_template`,
                    `content`
                FROM
                    `mailing_template`
                WHERE
                    `template_id` = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->bind_result($id, $name, $title, $subject, $type, $header, $footer, $main_template, $content);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = array(
                'template_id' => $id,
                'name' => $name,
                'subject' => $subject,
                'title' => $title,
                'type' => $type,
                'header_template' => $header,
                'footer_template' => $footer,
                'main_template' => $main_template,
                'content' => $content
            );
        }

        $stmt->close();

        return $out;
    }

    public function hasTemplate($name)
    {
        $out = 0;

        $sql = "SELECT
                    COUNT(*) as total
                FROM
                    `mailing_template`
                WHERE
                    `name` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $name);
        $stmt->bind_result($total);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = $total;
        }

        $stmt->close();

        return $out;
    }

    public function drafCampaign($campaign_id)
    {
        $out = false;

        $sql = "UPDATE
                    `mailing_campaign`
                SET
                    `status` = 'draf'
                WHERE
                    `campaign_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $campaign_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getPrimaryTemplates()
    {
        $out = [];

        $sql = "SELECT
                    `template_id`,
                    `name`,
                    `subject`,
                    `type`
                FROM
                    `mailing_template`
                WHERE
                    `type` NOT IN('template_part')
                ORDER BY `name`";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $subject, $type);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'template_id' => $id,
                'name' => $name,
                'subject' => $subject,
                'type' => $type
            );
        }

        $stmt->close();

        return $out;
    }

    public function getMarketingTemplates()
    {
        $out = [];

        $sql = "SELECT
                    `template_id`,
                    `name`,
                    `subject`,
                    `type`
                FROM
                    `mailing_template`
                WHERE
                    `type` = 'marketing'
                ORDER BY `name`";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $name, $subject, $type);

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'template_id' => $id,
                'name' => $name,
                'subject' => $subject,
                'type' => $type
            );
        }

        $stmt->close();

        return $out;
    }

    public function attachSubscriberCollection($email, $collection_id)
    {
        $out = false;

        $sql = "INSERT
                    INTO
                        `mailing_subscriber_collection`(email,collection_id)
                    VALUES(?,?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $email, $collection_id);

        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function detachSubscriberFromCollection($collection_id)
    {
        $out = false;

        $sql = "DELETE
                    FROM
                        `mailing_subscriber_collection`
                    WHERE
                        `collection_id` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $collection_id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getCollectionByName($name)
    {
        $out = false;

        $sql = "SELECT
                    `collection_id`,
                    `name`
                FROM
                    `mailing_collection`
                WHERE
                    `name` = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $name);
        $stmt->bind_result($id, $name);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = [
                'collection_id' => $id,
                'name' => $name
            ];
        }

        $stmt->close();

        return $out;
    }

    public function detachSubscriber($collection_id, $email)
    {
        $out = false;

        $sql = "DELETE
                    FROM
                        `mailing_subscriber_collection`
                    WHERE
                        `collection_id` = ?
                    AND
                        `email` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $collection_id, $email);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function checkEmailInCollection($email, $collection_id)
    {
        $out = 0;

        $sql = "SELECT COUNT(*) as total 
                    FROM 
                        `mailing_subscriber_collection`
                    WHERE
                        `collection_id` = ?
                    AND
                        `email` = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $collection_id, $email);
        $stmt->bind_result($total);

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = $total;
        }

        $stmt->close();

        return $out;
    }

    public function getReminderDatatable()
    {
        $request = $_POST;
        $tablename = 'mailing_reminder';
        $this->validateRequest($request, ['type']);
		$primaryKey = $tablename.'.reminder_id';

        $columns = array(
            array( 'db' => $tablename.'.`reminder_id`', 'dt' => 'reminder_id' ),
            array( 'db' => $tablename.'.`title`', 'dt' => 'title' ),
            array( 'db' => $tablename.'.`campaign_id`', 'dt' => 'campaign_id' ),
            array( 'db' => $tablename.'.`cron_timing`', 'dt' => 'cron_timing' ),
            array( 'db' => $tablename.'.`is_active`', 'dt' => 'is_active' ),
            array( 'db' => $tablename.'.last_run', 'dt' => 'last_run' ),
            array( 'db' => 'mailing_campaign.name', 'dt' => 'campaign_name' ),
		);

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

        if (!empty($_POST['type'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " $tablename.`type` = '".$_POST['type']."'";
        }

        $join = " LEFT JOIN `mailing_campaign` ON $tablename.`campaign_id` = `mailing_campaign`.`campaign_id`";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where
             $order
			 $limit";

        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );
    }

    public function insertReminder($title, $campaign_id, $cron_time)
    {
        $out = false;
        $sql = "INSERT 
                    INTO 
                        `mailing_reminder`
                            (
                                title,
                                campaign_id,
                                cron_timing,
                                is_active
                            ) 
                    VALUES(?,?,?,0)";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('sis', $title, $campaign_id, $cron_time);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function updateReminder($reminder_id, $title, $campaign_id, $cron_time)
    {
        $out = false;
        $sql = "UPDATE  
                    `mailing_reminder`
                        SET
                            `title` = ?,
                            `campaign_id` = ?,
                            `cron_timing` = ?
                        WHERE
                            `reminder_id` = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('sisi', $title, $campaign_id, $cron_time, $reminder_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function getReminder($reminder_id)
    {
        $out = null;

        $sql = "SELECT * FROM `mailing_reminder` WHERE reminder_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $reminder_id);
        $stmt->bind_result($id, $title, $campaign_id, $cron_timing, $is_active, $last_run );

        $stmt->execute();

        if ($stmt->fetch()) {
            $out = array(
                'id' => $id,
                'title' => $title,
                'campaign_id' => $campaign_id,
                'cron_timing' => $cron_timing,
                'is_active' =>  $is_active,
                'last_run' => $last_run
            );
        }

        $stmt->close();

        return $out;
    }

    public function getActiveReminder()
    {
        $out = null;

        $sql = "SELECT * FROM `mailing_reminder` WHERE is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($id, $title, $campaign_id, $cron_timing, $is_active, $last_run );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'id' => $id,
                'title' => $title,
                'campaign_id' => $campaign_id,
                'cron_timing' => $cron_timing,
                'is_active' =>  $is_active,
                'last_run' => $last_run
            );
        }

        $stmt->close();

        return $out;
    }

    public function updateReminderTimestamp($reminder_id)
    {
        $out = false;
        $sql = "UPDATE  
                    `mailing_reminder`
                        SET
                            `last_run` = CURRENT_TIMESTAMP
                        WHERE
                            `reminder_id` = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param('i', $reminder_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deleteReminder($reminder_id)
    {
        $out = false;

        $sql = "DELETE FROM `mailing_reminder` WHERE `reminder_id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reminder_id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function activateReminder($reminder_id)
    {
        $out = false;

        $sql = "UPDATE `mailing_reminder` SET `is_active` = 1 WHERE `reminder_id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reminder_id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

    public function deactivateReminder($reminder_id)
    {
        $out = false;

        $sql = "UPDATE `mailing_reminder` SET `is_active` = 0 WHERE `reminder_id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$reminder_id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $out = true;
        }

        $stmt->close();

        return $out;
    }

}