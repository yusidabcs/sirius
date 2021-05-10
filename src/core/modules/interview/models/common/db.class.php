<?php
namespace core\modules\interview\models\common;
/*
 * Final interview/db class.
 *
 * @final
 * 
 */
final class db extends \core\app\classes\module_base\module_db_report  {

	public function __construct()
	{

        parent::__construct('local', 'interview'); //sets up db connection to use local database and user_id as global protected variables
		return;
    }
    

    public function getInterviewLocationDatatable($countries,$subCountries,$ent=false){
        $this->countries = $countries;
        $this->subCountries = $subCountries;

        $request = $_POST;
        $table = 'interview_location';

        $primaryKey = 'interview_location.interview_location_id';

        $columns = array(
            array( 'db' => 'interview_location.interview_location_id', 'dt' => 'interview_location_id' , 'as' => 'interview_location_id'),
            array( 'db' => 'interview_location.organizer_id', 'dt' => 'organizer_id' , 'as' => 'organizer_id'),
            array( 'db' => 'interview_location.interview_title', 'dt' => 'interview_title' , 'as' => 'interview_title'),
            array( 'db' => 'interview_location.interview_description', 'dt' => 'interview_description' , 'as' => 'interview_description'),
            array( 'db' => 'interview_location.start_on', 'dt' => 'start_on' , 'as' => 'start_on'),
            array( 'db' => 'interview_location.finish_on', 'dt' => 'finish_on' , 'as' => 'finish_on'),
            array( 'db' => 'interview_location.countryCode_id', 'dt' => 'country' , 'as' => 'countryCode_id'),
            array( 'db' => 'interview_location.countrySubCode_id', 'dt' => 'subcountry' , 'as' => 'countrySubCode_id'),
            array( 'db' => 'interview_location.address', 'dt' => 'address' , 'as' => 'address'),
            array( 'db' => 'interview_location.google_map', 'dt' => 'google_map' , 'as' => 'google_map'),
            array( 'db' => 'interview_location.status', 'dt' => 'status' , 'as' => 'status'),
            array( 'db' => 'interview_location.visible', 'dt' => 'visible' , 'as' => 'visible'),
            array( 'db' => 'partner.entity_family_name', 'dt' => 'organizer_name', 'as' => 'organizer_name'),
            array( 'db' => 'interview_physical_group.total_candidate', 'dt' => 'total_candidate'),
            array( 'db' => 'interview_location.countryCode_id', 'dt' => 'country_name' , 'as' => 'country_name', 'formatter' => function ($d, $row) {
                return isset($this->countries[$row['country_name']])?$this->countries[$row['country_name']]:'-';
            }),
            array( 'db' => 'interview_location.countrySubCode_id', 'dt' => 'country_sub_name' , 'as' => 'country_sub_name', 'formatter' => function ($d, $row) {
                return isset($this->subCountries[$row['country_name']][$row['country_sub_name']])?$this->subCountries[$row['country_name']][$row['country_sub_name']]:'-';
            })
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` `partner` on `partner`.`address_book_id` = interview_location.organizer_id';
        $join .= ' LEFT JOIN  (SELECT count(interview_physical.schedule_id) as total_candidate, interview_physical.interview_location_id from interview_physical JOIN interview_schedule on interview_schedule.schedule_id = interview_physical.schedule_id group by interview_location_id ) as interview_physical_group on interview_physical_group.interview_location_id  = interview_location.interview_location_id
        ';
        $where = $this->filter( $request, $columns, $bindings  );


        if(isset($request['start_on']) && $request['start_on'] != ''){
            $start_on = date('Y-m-d 00:00:00', strtotime($request['start_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= '`interview_location`.`start_on` >= "'.$start_on.'"';
        }

        if(isset($request['finish_on']) && $request['finish_on'] != ''){
            $finish_on = date('Y-m-d 24:00:00', strtotime($request['finish_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= '`interview_location`.`finish_on` <= "'.$finish_on.'"';
        }

        if( isset($request['organizer_id']) && $request['organizer_id'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= '`interview_location`.`organizer_id` = '.$request['organizer_id'];
        }

        if( isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= '`interview_location`.`status` = '.$request['status'];
        }

        if( isset($request['visible']) && $request['visible'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= '`interview_location`.`visible` = '.$request['visible'];
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= $table.".`organizer_id` = '{$ent}' ";
        }

        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `$table`.`organizer_id` = '{$ent}' ";
        }


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM $table
			 $join
			 $where
			 $order
			 $limit";

        //echo $qry1;
        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   $table
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   $table  $join";
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

    public function sendInterviewNotificationEmail($interview_data, $to_name, $to_email, $from_name, $from_email,$lp_name,$action='new')
	{
        $mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;

		//need a reset code
		$random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
		$resetCode = md5($random_string);
		
		//insert the site
		$security_db_ns = NS_MODULES.'\security\models\common\security_db';
		$security_db = new $security_db_ns;
        $security_db->setResetCode($resetCode,$to_email);

        $content = '';

        if ($interview_data['type'] === 'physical') {
            $content .= '<p>Interview Location</p>';
            $content .= '<p><strong>Address: '.$interview_data['interview_address'].'</strong></p>';
            $content .= '<p><strong>Start on : '. $interview_data['interview_start_on']. '</strong></p>';
            $content .= '<p><strong>Finish on : '.$interview_data['interview_finish_on'].'</strong></p>';
            $content .= '<p><strong>Maps : <a href="'.$interview_data['google_map'].'">'.$interview_data['google_map'].'</a></strong></p>';
        } else {
            $content .= '<p>Interview Time : '.$interview_data['schedule_on'].' '.$interview_data['timezone'].'</p>';
            $content .= '<p>Google Meet Code : <a href="'.$interview_data['google_meet_code'].'">'.$interview_data['google_meet_code'].'</a></p>';
        }
        $email_template = "interview_schedule";
        if($action=='removed') {
            $email_template = "interview_schedule_removed";
        } else if($action=='change') {
            $email_template = "interview_schedule_change";
        }

        $template = $mailing_common->renderEmailTemplate($email_template, [
            'type' => $interview_data['type'],
            'content' => $content
        ]);
		
		//subject
		$subject = $template['subject'];
		
		//message
        $message = $template['html'];
		
		//cc
		$cc = '';
		
		//bcc
		if(SYSADMIN_BCC_NEW_USERS)
		{
			$bcc = SYSADMIN_EMAIL;
		} else {
			$bcc = '';
		}
		
		//html
		$html = true;
		$fullhtml = false;
		
		//unsubscribe link
		$unsubscribelink = false;
	
		//generic for the sendmail
		$generic = \core\app\classes\generic\generic::getInstance();
		$generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
		
		return;
	}

    public function getInterviewLocation(){
        $out = null;
        $sql =  "SELECT 
                    `interview_location`.`interview_location_id`,
                    `interview_location`.`interview_title`,
                    `interview_location`.`interview_description`,
                    `interview_location`.`organizer_id`,
                    `interview_location`.`start_on`,
                    `interview_location`.`finish_on`,
                    `interview_location`.`countryCode_id`,
                    `interview_location`.`countrySubCode_id`,
                    `interview_location`.`address`,
                    `interview_location`.`google_map`,
                    `interview_location`.`status`,
                    `interview_location`.`visible`
                FROM
                    `interview_location`
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $interview_location_id,
            $interview_title,
            $interview_description,
            $organizer_id,
            $start_on,
            $finish_on,
            $countryCode_id,
            $countrySubCode_id,
            $address,
            $google_map,
            $status,
            $visible
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'interview_location_id' => $interview_location_id,
                'interview_title' => $interview_title,
                'interview_description' => $interview_description,
                'organizer_id' => $organizer_id,
                'start_on' => $start_on,
                'finish_on' => $finish_on,
                'countryCode_id' => $countryCode_id,
                'countrySubCode_id' => $countrySubCode_id,
                'address' => $address,
                'google_map' => $google_map,
                'status' => $status,
                'visible' => $visible
            );

        }

        $stmt->close();
        return $out;
    }

    public function getActiveInterviewLocation(){
        $out = [];
        $start_on = date('Y-m-d 00:00:00');
        $sql =  "SELECT 
                    `interview_location`.`interview_location_id`,
                    `interview_location`.`interview_title`,
                    `interview_location`.`interview_description`,
                    `interview_location`.`organizer_id`,
                    `interview_location`.`start_on`,
                    `interview_location`.`finish_on`,
                    `interview_location`.`countryCode_id`,
                    `interview_location`.`countrySubCode_id`,
                    `interview_location`.`address`,
                    `interview_location`.`google_map`,
                    `interview_location`.`status`,
                    `interview_location`.`visible`
                FROM
                    `interview_location`
                WHERE
                    `interview_location`.`start_on` > '".$start_on."' AND `interview_location`.`status` = 1";

        $request = $_POST;
        if(isset($request['organizer_id']) && $request['organizer_id'] != ''){
            $sql .=' AND interview_location.organizer_id = '.$request['organizer_id'];
        }
        if(isset($request['interview_location_id']) && $request['interview_location_id'] != ''){
            $sql .=' AND interview_location.interview_location_id != '.$request['interview_location_id'];
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $interview_location_id,
            $interview_title,
            $interview_description,
            $organizer_id,
            $start_on,
            $finish_on,
            $countryCode_id,
            $countrySubCode_id,
            $address,
            $google_map,
            $status,
            $visible
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'interview_location_id' => $interview_location_id,
                'interview_title' => $interview_title,
                'interview_description' => $interview_description,
                'organizer_id' => $organizer_id,
                'start_on' => $start_on,
                'finish_on' => $finish_on,
                'countryCode_id' => $countryCode_id,
                'countrySubCode_id' => $countrySubCode_id,
                'address' => $address,
                'google_map' => $google_map,
                'status' => $status,
                'visible' => $visible
            );

        }

        $stmt->close();
        return $out;
    }

    public function getInterviewLocationById($id){
        $out = null;
        $sql =  "SELECT 
                    `interview_location`.`interview_location_id`,
                    `interview_location`.`interview_title`,
                    `interview_location`.`interview_description`,
                    `interview_location`.`organizer_id`,
                    `interview_location`.`start_on`,
                    `interview_location`.`finish_on`,
                    `interview_location`.`countryCode_id`,
                    `interview_location`.`countrySubCode_id`,
                    `interview_location`.`address`,
                    `interview_location`.`google_map`,
                    `interview_location`.`status`,
                    `interview_location`.`visible`,
                    `partner`.entity_family_name as organizer
                FROM
                    `interview_location`
                LEFT JOIN  `address_book` `partner` on `partner`.`address_book_id` = interview_location.organizer_id
                WHERE 
                    `interview_location`.`interview_location_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->bind_result(
            $interview_location_id,
            $interview_title,
            $interview_description,
            $organizer_id,
            $start_on,
            $finish_on,
            $countryCode_id,
            $countrySubCode_id,
            $address,
            $google_map,
            $status,
            $visible,
            $organizer
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = array(
                'interview_location_id' => $interview_location_id,
                'interview_title' => $interview_title,
                'interview_description' => $interview_description,
                'organizer_id' => $organizer_id,
                'start_on' => $start_on,
                'finish_on' => $finish_on,
                'countryCode_id' => $countryCode_id,
                'countrySubCode_id' => $countrySubCode_id,
                'address' => $address,
                'google_map' => $google_map,
                'status' => $status,
                'visible' => $visible,
                'organizer' => $organizer,
            );

        }

        $stmt->close();
        return $out;
    }

    public function insertInterviewLocation($data){
        $out = null;
        $sql =  "INSERT INTO
                    `interview_location`
                SET
                    `interview_title` = ?,
                    `interview_description` = ?,
                    `organizer_id` = ?,
                    `start_on` = ?,
                    `finish_on` = ?,
                    `countryCode_id` = ?,
                    `countrySubCode_id` = ?,
                    `address` = ?,
                    `google_map` = ?,
                    `status` = ?,
                    `visible` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssissssssii',
            $data['interview_title'],
            $data['interview_description'],
            $data['organizer_id'],
            $data['start_on'],
            $data['finish_on'],
            $data['countryCode_id'],
            $data['countrySubCode_id'],
            $data['address'],
            $data['google_map'],
            $data['status'],
            $data['visible']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewLocation($data){
        $out = null;
        $sql =  "UPDATE
                    `interview_location`
                SET
                    `interview_title` = ?,
                    `interview_description` = ?,
                    `organizer_id` = ?,
                    `start_on` = ?,
                    `finish_on` = ?,
                    `countryCode_id` = ?,
                    `countrySubCode_id` = ?,
                    `address` = ?,
                    `google_map` = ?,
                    `status` = ?,
                    `visible` = ?
                WHERE
                    `interview_location_id` = ?
                  
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssissssssiii',
            $data['interview_title'],
            $data['interview_description'],
            $data['organizer_id'],
            $data['start_on'],
            $data['finish_on'],
            $data['countryCode_id'],
            $data['countrySubCode_id'],
            $data['address'],
            $data['google_map'],
            $data['status'],
            $data['visible'],
            $data['interview_location_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteInterviewLocation($id){
        $out = null;
        $sql =  "DELETE FROM
                    `interview_location`
                WHERE
                    `interview_location_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function nonActiveInterviewLocation(){
        $out = null;
        $sql =  "UPDATE
                    `interview_location`
                SET
                    `status` = 0,
                    `visible` = 0
                WHERE
                    DATE(`start_on`) < CURDATE()
                  
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * Close interview location
     * return effected row, 1,0 or -1
     */
    public function closeInterviewLocation($interview_location_id){
        $out = null;
        $sql =  "UPDATE
                    `interview_location`
                SET
                    `status` = 0
                WHERE
                    `interview_location_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$interview_location_id);
        $stmt->execute();
        $out = $stmt->affected_rows;

        $stmt->close();
        return $out;
    }


	public function insertInterviewSchedule($data){
        $out = null;
        $sql =  "INSERT INTO
                    `interview_physical`
                SET
                    `organizer_id` = ?,
                    `interviewer_id` = ?,
                    `start_on` = ?,
                    `finish_on` = ?,
                    `countryCode_id` = ?,
                    `countrySubCode_id` = ?,
                    `address` = ?,
                    `google_map` = ?,
                    `status` = ?,
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iissssssi',$data['organizer_id'],$data['interviewer_id'], $data['start_on'], $data['finish_on'],$data['countryCode_id'], $data['countrySubCode_id'], $data['address'], $data['google_map'], $data['status']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    public function getOnGoingInterviewLocation($ent=false,$date=false){
        $out = [];
        $sql =  "SELECT 
                    `interview_location`.`interview_location_id`,
                    `interview_location`.`interview_title`,
                    `interview_location`.`interview_description`,
                    `interview_location`.`organizer_id`,
                    `interview_location`.`start_on`,
                    `interview_location`.`finish_on`,
                    `interview_location`.`countryCode_id`,
                    `interview_location`.`countrySubCode_id`,
                    `interview_location`.`address`,
                    `interview_location`.`google_map`,
                    `interview_location`.`status`,
                    `interview_location`.`visible`,
                    `partner`.entity_family_name as organizer,
                    `interview_physical_group`.`total_candidate`
                FROM
                    `interview_location`
                LEFT JOIN  `address_book` `partner` on `partner`.`address_book_id` = interview_location.organizer_id
                LEFT JOIN  (
                    SELECT count(`interview_physical`.`schedule_id`) as total_candidate, `interview_physical`.`interview_location_id` from `interview_physical` JOIN `interview_schedule` on `interview_schedule`.`schedule_id` = `interview_physical`.`schedule_id` group by `interview_location_id` 
                    ) as interview_physical_group on `interview_physical_group`.`interview_location_id`  = `interview_location`.`interview_location_id`
                WHERE
                     DATE(`interview_location`.`start_on`) >= CURDATE() AND `interview_location`.`status` = 1 ";

                if ($ent != false) {
                    $sql .= ' AND ';
                    $sql .= " `interview_location`.`organizer_id` = '{$ent}' ";
                }

                if($date!=false) {
                    $sql .= ' AND ';
                    $sql .= " (DATE_FORMAT(`interview_location`.`start_on`,'%Y-%m-%d') = '{$date}')";
                }
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $interview_location_id,
            $interview_title,
            $interview_description,
            $organizer_id,
            $start_on,
            $finish_on,
            $countryCode_id,
            $countrySubCode_id,
            $address,
            $google_map,
            $status,
            $visible,
            $organizer,
            $total_candidate
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'interview_location_id' => $interview_location_id,
                'interview_title' => $interview_title,
                'interview_description' => $interview_description,
                'organizer_id' => $organizer_id,
                'start_on' => $start_on,
                'finish_on' => $finish_on,
                'countryCode_id' => $countryCode_id,
                'countrySubCode_id' => $countrySubCode_id,
                'address' => $address,
                'google_map' => $google_map,
                'status' => $status,
                'visible' => $visible,
                'organizer' => $organizer,
                'total_candidate' => $total_candidate
            );

        }

        $stmt->close();
        return $out;
    }

    public function getTotalInterviewOnlinePerDay($ent=false) {
        $out = [];
        $sql =  "SELECT
                    DATE_FORMAT(`interview_online`.`schedule_on`,'%Y-%m-%d') as start_interview,
                    DATE_FORMAT(`interview_online`.`schedule_on`,'%Y-%m-%d') as finish_interview,
                    COUNT(`interview_online`.`schedule_id`) as total_interview 
                FROM
                    `interview_online`
                LEFT JOIN 
                    `interview_schedule` ON `interview_online`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN
                    `job_application` on `interview_schedule`.`job_application_id` = `job_application`.`job_application_id`
                LEFT JOIN
                    `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id` and `address_book_connection`.`connection_type`='lp'
                WHERE
                    DATE_FORMAT(`interview_online`.`schedule_on`,'%Y-%m-%d')>=CURDATE()
                    ";
            if ($ent != false) {
                $sql .= ' AND ';
                $sql .= "`address_book_connection`.`connection_id` = '{$ent}'";
            }

            $sql .= " GROUP BY start_interview,finish_interview ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($start_interview, $finish_interview, $total_interview);
        $stmt->execute();
        while ($stmt->fetch()) {
            $out[] = array(
                'start_interview' => $start_interview,
                'finish_interview' => $finish_interview,
                'total_interview' => $total_interview
            );

        }
        $stmt->close();
        return $out;
    }

    public function getTotalInterviewLocationPerDay($ent=false) {
        $out = [];
        $sql =  "SELECT
                    DATE_FORMAT(`interview_location`.`start_on`,'%Y-%m-%d') as start_interview,
                    DATE_FORMAT(`interview_location`.`finish_on`,'%Y-%m-%d') as finish_interview,
                    COUNT(`interview_physical`.`schedule_id`) as total_interview 
                FROM
                    `interview_physical`
                JOIN 
                    `interview_location` ON `interview_physical`.`interview_location_id`=`interview_location`.`interview_location_id`
                LEFT JOIN 
                    `interview_schedule` ON `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN
                    `job_application` on `interview_schedule`.`job_application_id` = `job_application`.`job_application_id`
                LEFT JOIN
                    `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id` and `address_book_connection`.`connection_type`='lp'
                WHERE
                    DATE_FORMAT(`interview_location`.`start_on`,'%Y-%m-%d')>=CURDATE()
                    ";
                if ($ent != false) {
                    $sql .= ' AND ';
                    $sql .= " (`interview_location`.`organizer_id` = '{$ent}' OR  `address_book_connection`.`connection_id` = '{$ent}')";
                }
                $sql .= " GROUP BY start_interview,finish_interview ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($start_interview,$finish_interview, $total_interview);
        $stmt->execute();
        while ($stmt->fetch()) {
            $out[] = array(
                'start_interview' => $start_interview,
                'finish_interview' => $finish_interview,
                'total_interview' => $total_interview
            );

        }
        $stmt->close();
        return $out;
    }

    public function getTotalHireInterview($interview_location_id = false,$ent=false){
        $out = 0;
        $sql =  "SELECT 
                    COUNT(`interview_result`.`schedule_id`) as total
                FROM
                    `interview_result`
                LEFT JOIN
                    `interview_schedule` on `interview_schedule`.`schedule_id` = `interview_result`.`schedule_id`
                LEFT JOIN
                    `interview_physical` on `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN
                    `interview_location` on `interview_physical`.`interview_location_id` = `interview_location`.`interview_location_id`
                LEFT JOIN
                    `job_application` on `interview_result`.`job_application_id` = `job_application`.`job_application_id`
                LEFT JOIN
                    `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id` and `address_book_connection`.`connection_type`='lp'     
                WHERE
                     `job_application`.`status` in ('hired','reapply','allocated') ";
        if($interview_location_id != false){
            $sql .= ' AND `interview_physical`.`interview_location_id` = '.$interview_location_id;
        }
        if ($ent != false) {
            $sql .= ' AND ';
            $sql .= " (`interview_location`.`organizer_id` = '{$ent}' OR  `address_book_connection`.`connection_id` = '{$ent}')";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->fetch();
        $out = $total;
        $stmt->close();
        return $out;
    }

    public function getTotalInterviewCandidate($ent=false){
        $out = 0;
        $sql =  "select count(*) from job_application where not EXISTS (select * from interview_schedule where interview_schedule.job_application_id = job_application.job_application_id) and `status` = 'interview'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->fetch();
        $out = $total;
        $stmt->close();
        return $out;
    }

    public function getTotalScheduleCandidate($ent=false){
        $out = 0;
        $sql =  "select count(*) from job_application where EXISTS (select * from interview_schedule where interview_schedule.job_application_id = job_application.job_application_id) and `status` = 'interview'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->fetch();
        $out = $total;
        $stmt->close();
        return $out;
    }

    public function getTotalNotHireInterview($interview_location_id = false,$ent=false){
        $out = 0;
        $sql =  "SELECT 
                    COUNT(`interview_result`.`schedule_id`) as total
                FROM
                    `interview_result`
                LEFT JOIN
                    `interview_schedule` on `interview_schedule`.`schedule_id` = `interview_result`.`schedule_id`
                LEFT JOIN
                    `interview_physical` on `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN
                    `interview_location` on `interview_physical`.`interview_location_id` = `interview_location`.`interview_location_id`
                LEFT JOIN
                    `job_application` on `interview_result`.`job_application_id` = `job_application`.`job_application_id`
                LEFT JOIN
                    `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id` and `address_book_connection`.`connection_type`='lp'
                        
                WHERE
                     `job_application`.`status` = 'not_hired' ";
        if($interview_location_id != false){
            $sql .= ' AND `interview_physical`.`interview_location_id` = '.$interview_location_id;
        }
        if ($ent != false) {
            $sql .= ' AND ';
            $sql .= " (`interview_location`.`organizer_id` = '{$ent}' OR  `address_book_connection`.`connection_id` = '{$ent}')";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($total);
        $stmt->execute();
        $stmt->fetch();
        $out = $total;
        $stmt->close();
        return $out;
    }

    public function getTotalHireByJob($interview_location_id = false){
        $out = [];
        $sql =  "SELECT 
                    count(`interview_result`.job_application_id) as total,
                    `job_speedy`.`job_speedy_code`,
                    `job_speedy`.`job_title`
                    
                FROM
                    `interview_result`                
                    
                LEFT JOIN job_application on interview_result.job_application_id = job_application.job_application_id
                LEFT JOIN job_speedy on job_speedy.job_speedy_code = job_application.job_speedy_code
                LEFT JOIN interview_schedule on interview_schedule.schedule_id = interview_result.schedule_id
                LEFT JOIN interview_physical on interview_physical.schedule_id = interview_schedule.schedule_id
                
                WHERE
                     `job_application`.`status` = 'hired' ";

        if($interview_location_id != false){
            $sql .= ' AND interview_physical.interview_location_id = '.$interview_location_id;
        }
        $sql .= ' GROUP BY `job_speedy`.`job_speedy_code`,`job_speedy`.`job_title`';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($total,$job_speedy_code,$job_title);
        $stmt->execute();
        while($stmt->fetch()){
            $out[] = [
                'total' => $total,
                'job_speedy_code' => $job_speedy_code,
                'job_title' => $job_title,
            ];
        }
        $stmt->close();
        return $out;
    }

    public function getOnlineInterviewList($ent=false,$date=false) {
        $out = [];
        $sql = '
            SELECT
                `interview_online`.`schedule_id`,
                `interview_online`.`schedule_on`,
                `interview_online`.`timezone`,
                `interview_schedule`.`interviewer_id`,
                `interview_online`.`google_meet_code`,
                `interview_result`.`schedule_id` as `interview_result_id`,
                `job_application`.`job_application_id`,
                `job_application`.`job_speedy_code`,
                `job_application`.`address_book_id`,
                `job_application`.`status`,
                `job_speedy`.`job_title`,
                `address_book`.`number_given_name`,
                `address_book`.`entity_family_name`,
                `address_book`.`main_email`,
                `address_book`.`address_book_id`,
                `partner`.`entity_family_name` as `partner_entity_family_name`,
                `partner`.`number_given_name` as `partner_number_given_name`,
                `interviewer`.`entity_family_name` as `interviewer_entity_family_name`,
                `interviewer`.`number_given_name` as `interviewer_number_given_name`
            FROM
                `interview_online`
            LEFT JOIN 
                `interview_schedule` on `interview_schedule`.`schedule_id` = `interview_online`.`schedule_id`
            LEFT JOIN 
                `interview_result` on `interview_result`.`schedule_id` = `interview_schedule`.`schedule_id`
            LEFT JOIN
                `job_application` on `job_application`.`job_application_id` = `interview_schedule`.`job_application_id`
            LEFT JOIN
                `job_speedy` on `job_speedy`.`job_speedy_code` = `job_application`.`job_speedy_code`
            LEFT JOIN
                `address_book` ON `job_application`.`address_book_id` = `address_book`.`address_book_id`
            LEFT JOIN
                `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`="lp"
            LEFT JOIN 
                `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id`
            LEFT JOIN 
                `address_book` as `interviewer` ON `interview_schedule`.`interviewer_id` = `interviewer`.`address_book_id`
            WHERE
                DATE(`interview_online`.`schedule_on`) >= CURDATE() AND `job_application`.`status`="interview"
        ';

                if ($ent != false) {
                    $sql .= ' AND ';
                    $sql .= " `address_book_connection`.`connection_id` = '{$ent}' ";
                }

                if($date!=false) {
                    $sql .= ' AND ';
                    $sql .= " (DATE_FORMAT(`interview_online`.`schedule_on`,'%Y-%m-%d') = '{$date}')";
                }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $schedule_id,
            $schedule_on,
            $timezone,
            $interviewer_id,
            $google_meet_code,
            $interview_result_id,
            $job_application_id,
            $job_speedy_code,
            $address_book_id,
            $status,
            $job_title,
            $number_given_name,
            $entity_family_name,
            $main_email,
            $address_book_id,
            $partner_number_given_name,
            $partner_entity_family_name,
            $interviewer_number_given_name,
            $interviewer_entity_family_name
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'schedule_id' => $schedule_id,
                'schedule_on' => $schedule_on,
                'timezone' => $timezone,
                'interviewer_id' => $interviewer_id,
                'google_meet_code' => $google_meet_code,
                'interview_result_id' => $interview_result_id,
                'job_application_id' => $job_application_id,
                'job_speedy_code' => $job_speedy_code,
                'address_book_id' => $address_book_id,
                'status' => $status,
                'job_title' => $job_title,
                'number_given_name' => $number_given_name,
                'entity_family_name' => $entity_family_name,
                'main_email' => $main_email,
                'address_book_id' => $address_book_id,
                'partner_number_given_name' => $partner_number_given_name,
                'partner_entity_family_name' => $partner_entity_family_name,
                'interviewer_number_given_name' => $interviewer_number_given_name,
                'interviewer_entity_family_name' => $interviewer_entity_family_name
            );

        }

        $stmt->close();
        return $out;
    }

    public function getOnlineInterviewSchedule($ent=false){
        $request = $_POST;
        $table = 'interview_online';
        $primaryKey = 'interview_online.schedule_id';

        $columns = array(
            array( 'db' => 'interview_online.schedule_id', 'dt' => 'schedule_id' ),
            array( 'db' => 'interview_online.schedule_on', 'dt' => 'schedule_on' ),
            array( 'db' => 'interview_online.timezone', 'dt' => 'timezone' ),
            array( 'db' => 'interview_schedule.interviewer_id', 'dt' => 'interviewer_id' ),
            array( 'db' => 'interview_online.google_meet_code', 'dt' => 'google_meet_code' ),
            array( 'db' => 'job_application.job_application_id', 'dt' => 'job_application_id' ),
            array( 'db' => 'job_application.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_application.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'job_application.status', 'dt' => 'job_application_status' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'interview_result.schedule_id','as' => 'interview_result_id', 'dt' => 'interview_result_id' ),
            array( 'db' => 'address_book_per.title', 'dt' => 'title' ),
            array( 'db' => 'address_book_per.middle_names', 'dt' => 'middle_names' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),

            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),

            array( 'db' => 'job_speedy.created_on', 'dt' => 'created_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'job_speedy.modified_on', 'dt' => 'modified_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'interviewer.interviewer', 'dt' => 'interviewer'),
            array('db' => 'partner.entity_family_name', 'as' => 'partner_name', 'dt' => 'partner_name'),
        );

        $join = ' JOIN `interview_schedule` on `interview_schedule`.`schedule_id` = interview_online.schedule_id';
        $join .= ' LEFT JOIN `interview_result` on `interview_result`.`schedule_id` = interview_schedule.schedule_id';
        $join .= ' LEFT JOIN `job_application` on `job_application`.`job_application_id` = interview_schedule.job_application_id';
        $join .= ' JOIN job_speedy on job_speedy.job_speedy_code = job_application.job_speedy_code';
        $join .= ' JOIN `address_book_per` ON `job_application`.`address_book_id` = `address_book_per`.`address_book_id` ';
        $join .= ' JOIN `address_book` ON `job_application`.`address_book_id` = `address_book`.`address_book_id` ';
        $join .= ' JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';
        $join .= 'LEFT JOIN (SELECT concat(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name) as interviewer,address_book.address_book_id from `address_book`  ) as interviewer on `interviewer`.`address_book_id` = interview_schedule.interviewer_id';

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $where = $this->filter( $request, $columns, $bindings  );

        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .=' `job_application`.`status` = "interview"';

        if(isset($request['start_on']) && $request['start_on'] != ''){
            $start_on = date('Y-m-d 00:00:00', strtotime($request['start_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_online`.`schedule_on` >= "'.$start_on.'"';
        }

        if(isset($request['finish_on']) && $request['finish_on'] != ''){
            $finish_on = date('Y-m-d 24:00:00', strtotime($request['finish_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_online`.`schedule_on` <= "'.$finish_on.'"';
        }

        if(isset($request['interviewer_id']) && $request['interviewer_id'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_schedule`.`interviewer_id` <= "'.$request['interviewer_id'].'"';
        }
        if ($ent != false) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`  $join";
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

    public function getInterviewSchedule(){
        $request = $_POST;
        $table = 'interview_physical';

        $primaryKey = 'interview_physical.schedule_id';

        $columns = array(
            array( 'db' => 'interview_physical.schedule_id', 'dt' => 'schedule_id' ),
            array( 'db' => 'interview_physical.organizer_id', 'dt' => 'organizer_id' ),
            array( 'db' => 'interview_physical.interviewer_id', 'dt' => 'interviewer_id' ),
            array( 'db' => 'interview_physical.start_on', 'dt' => 'start_on' ),
            array( 'db' => 'interview_physical.finish_on', 'dt' => 'finish_on' ),
            array( 'db' => 'interview_physical.countryCode_id', 'dt' => 'country' ),
            array( 'db' => 'interview_physical.countrySubCode_id', 'dt' => 'subcountry' ),
            array( 'db' => 'interview_physical.address', 'dt' => 'address' ),
            array( 'db' => 'interview_physical.google_map', 'dt' => 'google_map' ),
            array( 'db' => 'interview_physical.status', 'dt' => 'status' ),
            array( 'db' => 'interview_physical.created_on', 'dt' => 'created_on'),
            array( 'db' => 'interview_physical.created_by', 'dt' => 'created_by'),
            array( 'db' => 'interview_physical.modified_on', 'dt' => 'modified_on'),
            array( 'db' => 'interview_physical.modified_by', 'dt' => 'modified_by'),
            array( 'db' => 'partner.entity_family_name', 'dt' => 'organizer_name', 'as' => 'organizer_name'),
            array( 'db' => 'interviewer.entity_family_name', 'dt' => 'entity_family_name'),
            array( 'db' => 'interviewer.number_given_name', 'dt' => 'number_given_name'),
            array( 'db' => 'interviewer.number_given_name', 'dt' => 'number_given_name'),
            array( 'db' => 'interview_physical_group.total_candidate', 'dt' => 'total_candidate'),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN   `address_book` `partner` on `partner`.`address_book_id` = interview_physical.organizer_id';
        $join .= ' LEFT JOIN   `address_book` `interviewer` on `interviewer`.`address_book_id` = interview_physical.interviewer_id';
        $join .= ' LEFT JOIN  (SELECT count(job_application_id) as total_candidate, schedule_id from interview_physical_group group by schedule_id ) as interview_physical_group on interview_physical_group.schedule_id  = interview_physical.schedule_id';

        $where = $this->filter( $request, $columns, $bindings  );


        if(isset($request['start_on']) && $request['start_on'] != ''){
            $start_on = date('Y-m-d 00:00:00', strtotime($request['start_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_physical`.`start_on` >= "'.$start_on.'"';
        }

        if(isset($request['finish_on']) && $request['finish_on'] != ''){
            $finish_on = date('Y-m-d 24:00:00', strtotime($request['finish_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_physical`.`finish_on` <= "'.$finish_on.'"';
        }

        if( isset($request['organizer_id']) && $request['organizer_id'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_physical`.`organizer_id` = '.$request['organizer_id'];
        }

        if( isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_physical`.`status` = '.$request['status'];
        }


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`  $join";
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

    public function getInterviewScheduleById($schedule_id){
        $out = null;
        $sql =  "SELECT 
                    `interview_schedule`.`schedule_id`,
                    `interview_schedule`.`job_application_id`,
                    `interview_schedule`.`interviewer_id`,
                    `interview_schedule`.`type`,
                    `interview_schedule`.`created_on`,
                    `interview_schedule`.`created_by`,
                    `interview_schedule`.`modified_on`,
                    `interview_schedule`.`modified_by`,
                    `interview_schedule`.`status`,
                    `interview_physical`.`interview_location_id`,
                    `interview_online`.`schedule_on`,
                    `interview_online`.`timezone`,
                    `interview_online`.`google_meet_code`,
                    concat(`interviewer`.`entity_family_name`,' ', `interviewer`.number_given_name) as interviewer
                    
                FROM
                    `interview_schedule`
                LEFT JOIN `interview_physical` on `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN `interview_online` on `interview_online`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN `address_book` `interviewer` on `interviewer`.`address_book_id` = interview_schedule.interviewer_id 
                WHERE
                    `interview_schedule`.`schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$schedule_id);
        $stmt->bind_result(
            $schedule_id,
            $job_application_id,
            $interviewer_id,
            $type,
            $created_on,
            $created_by,
            $modified_on,
            $modified_by,
            $status,
            $interview_location_id,
            $schedule_on,
            $timezone,
            $google_meet_code,
            $interviewer
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = array(
                'schedule_id' => $schedule_id,
                'job_application_id' => $job_application_id,
                'interviewer_id' => $interviewer_id,
                'type' => $type,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'status' => $status,
                'interview_location_id' => $interview_location_id,
                'schedule_on' => $schedule_on,
                'timezone' => $timezone,
                'google_meet_code' => $google_meet_code,
                'interviewer' => $interviewer,
            );

        }

        $stmt->close();
        return $out;
    }

    public function getLatestInterviewSchedule($job_application_id)
    {
        $out = null;
        $sql = "SELECT 
        `interview_schedule`.`schedule_id`,
        `interview_schedule`.`job_application_id`,
        `interview_schedule`.`interviewer_id`,
        `interview_schedule`.`type`,
        `interview_schedule`.`created_on`,
        `interview_schedule`.`created_by`,
        `interview_schedule`.`modified_on`,
        `interview_schedule`.`modified_by`,
        `interview_schedule`.`status`,
        `interview_physical`.`interview_location_id`,
        `interview_location`.`start_on` as interview_start_on,
        `interview_location`.`finish_on` as interview_finish_on,
        `interview_location`.`address` as interview_address,
        `interview_location`.`google_map` as google_map,
        `interview_online`.`schedule_on`,
        `interview_online`.`timezone`,
        `interview_online`.`google_meet_code`,
        `address_book`.`main_email` as candidate_email,
        `address_book`.`number_given_name` as candidate_name,
        `address_book`.`number_given_name`,
        `address_book`.`entity_family_name`,
        `lp`.`number_given_name` as lp_number_given_name,
        `lp`.`entity_family_name` as lp_entity_family_name,
        concat(`interviewer`.`entity_family_name`,' ', `interviewer`.number_given_name) as interviewer
        
    FROM
        `interview_schedule`
    LEFT JOIN `interview_physical` on `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
    LEFT JOIN `interview_location` on `interview_physical`.`interview_location_id` = `interview_location`.`interview_location_id`
    LEFT JOIN `interview_online` on `interview_online`.`schedule_id` = `interview_schedule`.`schedule_id`
    LEFT JOIN `address_book` `interviewer` on `interviewer`.`address_book_id` = `interview_schedule`.`interviewer_id`
    LEFT JOIN `job_application` on `interview_schedule`.`job_application_id` = `job_application`.`job_application_id`
    LEFT JOIN `address_book` on `job_application`.`address_book_id` = `address_book`.`address_book_id`
    LEFT JOIN `address_book_connection` on `address_book_connection`.`address_book_id` = `job_application`.`address_book_id` and `address_book_connection`.`connection_type`='lp'
    LEFT JOIN `address_book` `lp` on `lp`.`address_book_id` = `address_book_connection`.`connection_id`
    WHERE `interview_schedule`.`job_application_id` = ?
    ORDER BY `interview_schedule`.`schedule_id` DESC LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $job_application_id);
    $stmt->bind_result($schedule_id, $job_application_id, $interviewer_id, $type, $created_on, $created_by, $modified_on, $modified_by, $status, $interview_location_id, $interview_start_on, $interview_finish_on, $interview_address,$google_map, $schedule_on, $timezone, $google_meet_code, $candidate_email, $candidate_name, $number_given_name, $entity_family_name,$lp_number_given_name, $lp_entity_family_name, $interviewer);

    $stmt->execute();
    
    if ($stmt->fetch()) {
        $out = [
            'schedule_id' => $schedule_id,
            'job_application_id' => $job_application_id,
            'integerviewer_id' => $interviewer_id,
            'type' => $type,
            'created_on' => $created_on,
            'created_by' => $created_by,
            'modified_on' => $modified_on,
            'modified_by' => $modified_by,
            'status' => $status,
            'interview_location_id' => $interview_location_id,
            'interview_start_on' => $interview_start_on,
            'interview_finish_on' => $interview_finish_on,
            'interview_address' => $interview_address,
            'google_map' => $google_map,
            'schedule_on' => $schedule_on,
            'timezone' => $timezone,
            'google_meet_code' => $google_meet_code,
            'candidate_email' => $candidate_email,
            'candidate_name' => $candidate_name,
            'number_given_name' => $number_given_name,
            'entity_family_name' => $entity_family_name,
            'lp_number_given_name' => $lp_number_given_name,
            'lp_entity_family_name' => $lp_entity_family_name,
            'interviewer' => $interviewer
        ];

        
    }
    
    $stmt->close();
    return $out;
    
    }


    public function updateInterviewSchedule($data){
        $out = null;
        $sql =  "UPDATE
                    `interview_physical`
                SET
                    `organizer_id` = ?,
                    `interviewer_id` = ?,
                    `start_on` = ?,
                    `finish_on` = ?,
                    `countryCode_id` = ?,
                    `countrySubCode_id` = ?,
                    `address` = ?,
                    `google_map` = ?,
                    `status` = ?,
                    `modified_on` = CURRENT_TIMESTAMP,
                    `modified_by`= {$this->user_id}
                WHERE
                    `schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iissssssii',$data['organizer_id'],$data['interviewer_id'], $data['start_on'], $data['finish_on'],$data['countryCode_id'], $data['countrySubCode_id'], $data['address'], $data['google_map'], $data['status'], $data['schedule_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;

        $stmt->close();
        return $out;
    }

    public function deleteInterviewSchedule($schedule_id){
        $out = null;
        $sql =  "DELETE FROM
                    `interview_physical`
                WHERE
                    `schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$schedule_id);
        $stmt->execute();
        $out = $stmt->affected_rows;

        $stmt->close();
        return $out;
    }

    public function insertInterviewScheduleByType($data){
        $out = null;
        $sql =  "INSERT INTO
                    `interview_schedule`
                SET
                    `interview_schedule`.`job_application_id` = ?,
                    `interview_schedule`.`interviewer_id` = 0,
                    `interview_schedule`.`type` = ?,
                    `interview_schedule`.`start_on` = '0000-00-00 00:00:00',
                    `interview_schedule`.`status` = 0,
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `modified_by` = {$this->user_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is',$data['job_application_id'],$data['type']);
        $stmt->execute();
        $schedule_id = $stmt->insert_id;
        $stmt->close();

        if($data['type'] == 'physical'){
            $sql =  "INSERT INTO
                    `interview_physical`
                SET
                    `interview_physical`.`interview_location_id` = ?,
                    `interview_physical`.`schedule_id` = ?
                ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii',$data['interview_location_id'],$schedule_id);
            $stmt->execute();
            $out = $stmt->affected_rows;
            $stmt->close();
        }elseif($data['type'] == 'online'){
            $sql =  "INSERT INTO
                    `interview_online`
                SET
                    `interview_online`.`schedule_on` = ?,
                    `interview_online`.`timezone` = ?,
                    `interview_online`.`google_meet_code` = ?,
                    `interview_online`.`schedule_id` = ?
                ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sssi',$data['schedule_on'],$data['timezone'],$data['google_meeting_code'],$schedule_id);
            $stmt->execute();
            $out = $stmt->affected_rows;
            $stmt->close();
        }


        return $out;
    }

    public function updatePhysicalInterview($data){
        $out = null;

        $sql =  "UPDATE
                    `interview_physical`
                SET
                    `interview_physical`.`interview_location_id` = ?
                WHERE
                    `interview_physical`.`schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$data['interview_location_id'],$data['schedule_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    public function updateOnlineInterview($data){
        $out = null;

        $sql =  "UPDATE
                    `interview_online`
                SET
                    `interview_online`.`schedule_on` = ?,
                    `interview_online`.`timezone` = ?
                WHERE
                    `interview_online`.`schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',$data['schedule_on'],$data['timezone'],$data['schedule_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    /*
     * set interviewer for each schedule
     * schedule_id
     * interviewer_id
     */
    public function setInterviewerForSchedule($data){
        $out = null;

        $sql =  "UPDATE
                    `interview_schedule`
                SET
                    `interview_schedule`.`interviewer_id` = ?
                WHERE
                    `interview_schedule`.`schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii',$data['address_book_id'],$data['schedule_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }


    public function removeInterviewSchedule($data){
        $out = null;

        $sql =  "DELETE FROM
                    `interview_physical`
                WHERE
                    `interview_physical`.`schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$data['schedule_id']);
        $stmt->execute();
        $stmt->close();


        $sql =  "DELETE FROM
                    `interview_online`
                WHERE
                    `interview_online`.`schedule_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$data['schedule_id']);
        $stmt->execute();
        $stmt->close();

        $sql =  "UPDATE
                    `workflow_interview_ready_tracker`
                SET
                    `status` = 'request_schedule',
                    `notes` = 'Schedule Removed'
                WHERE
                    `address_book_id` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        $sql =  "DELETE FROM
                    `interview_schedule`
                WHERE
                    `interview_schedule`.`schedule_id` = ?
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$data['schedule_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function getScheduleByJobApplication($id){
        $out = null;
        $sql =  "SELECT 
                    `interview_schedule`.`schedule_id`,
                    `interview_schedule`.`job_application_id`,
                    `interview_schedule`.`interviewer_id`,
                    `interview_schedule`.`type`,
                    `interview_schedule`.`start_on` as schedule_start_on,
                    `interview_physical`.`interview_location_id`,
                    `interview_online`.`schedule_on`,
                    `interview_online`.`timezone`,
                    `interview_online`.`google_meet_code`,
                    `interview_location`.`start_on`,
                    `interview_location`.`finish_on`,
                    `interview_location`.`countryCode_id`,
                    `interview_location`.`countrySubCode_id`,
                    `interview_location`.`address`,
                    `interview_location`.`google_map`,
                    `interview_location`.`status`,
                    concat(`interviewer`.`entity_family_name`,' ', `interviewer`.number_given_name) as interviewer
                    
                FROM
                    `interview_schedule`
                LEFT JOIN `interview_online` on `interview_online`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN `interview_physical` on `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN `interview_location` on `interview_location`.`interview_location_id` = `interview_physical`.`interview_location_id`
                LEFT JOIN `address_book` `interviewer` on `interviewer`.`address_book_id` = interview_schedule.interviewer_id 
                WHERE
                    `interview_schedule`.`job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->bind_result(
            $schedule_id,
            $job_application_id,
            $interviewer_id,
            $type,
            $schedule_start_on,
            $interview_location_id,
            $schedule_on,
            $timezone,
            $google_meet_code,
            $start_on,
            $finish_on,
            $countryCode_id,
            $countrySubCode_id,
            $address,
            $google_map,
            $status,
            $interviewer
        );

        $stmt->execute();

        while ($stmt->fetch()) {
            $out = array(
                'schedule_id' => $schedule_id,
                'job_application_id' => $job_application_id,
                'interviewer_id' => $interviewer_id,
                'type' => $type,
                'schedule_start_on' => $schedule_start_on,
                'interview_location_id' => $interview_location_id,
                'schedule_on' => $schedule_on,
                'timezone' => $timezone,
                'google_meet_code' => $google_meet_code,
                'start_on' => $start_on,
                'finish_on' => $finish_on,
                'countryCode_id' => $countryCode_id,
                'countrySubCode_id' => $countrySubCode_id,
                'address' => $address,
                'google_map' => $google_map,
                'status' => $status,
                'interviewer' => $interviewer
            );

        }

        $stmt->close();
        return $out;
    }

    public function getListCandidateInGroup($id){
        $request = $_POST;
        $table = 'interview_physical';

        $primaryKey = 'interview_physical.schedule_id';

        $columns = array(
            array( 'db' => 'interview_physical.schedule_id', 'dt' => 'schedule_id' ),
            array( 'db' => 'interview_physical.interview_location_id', 'dt' => 'interview_location_id' ),
            array( 'db' => 'job_application.job_application_id', 'dt' => 'job_application_id' ),
            array( 'db' => 'job_application.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_application.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'job_application.status','as' => 'job_application_status', 'dt' => 'job_application_status' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'interview_result.schedule_id', 'as' => 'interview_result_id', 'dt' => 'interview_result_id' ),
            array( 'db' => 'address_book_per.title', 'dt' => 'title' ),
            array( 'db' => 'address_book_per.middle_names', 'dt' => 'middle_names' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),

            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),

            array( 'db' => 'job_speedy.created_on', 'dt' => 'created_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
            array( 'db' => 'job_speedy.modified_on', 'dt' => 'modified_on', 'formatter' => function( $d, $row ) {return date( 'M jS Y h:i:s', strtotime($d));}),
        );

        $join = ' JOIN `interview_schedule` on `interview_schedule`.`schedule_id` = interview_physical.schedule_id';
        $join .= ' LEFT JOIN `interview_result` on `interview_result`.`schedule_id` = interview_schedule.schedule_id';
        $join .= ' JOIN `job_application` on `job_application`.`job_application_id` = interview_schedule.job_application_id';
        $join .= ' JOIN job_speedy on job_speedy.job_speedy_code = job_application.job_speedy_code';
        $join .= ' JOIN `address_book_per` ON `job_application`.`address_book_id` = `address_book_per`.`address_book_id` ';
        $join .= ' JOIN `address_book` ON `job_application`.`address_book_id` = `address_book`.`address_book_id` ';

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $where = $this->filter( $request, $columns, $bindings  );
        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .=' `interview_physical`.`interview_location_id` = '.$id;

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $limit";


        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`  $join $where";
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


    public function getIntreviewQuestionDatatable()
    {
        $request = $_POST;
        $table = 'interview_question';

        $primaryKey = 'interview_question.question_id';

        $columns = array(
            array( 'db' => 'interview_question.question_id', 'dt' => 'question_id' ),
            array( 'db' => 'interview_question.question', 'dt' => 'question' ),
            array( 'db' => 'interview_question.type', 'dt' => 'type' ),
            array( 'db' => 'interview_question.help', 'dt' => 'help' ),
            array( 'db' => 'interview_question.answer_heading', 'dt' => 'answer_heading' ),
            array( 'db' => 'interview_question.status', 'dt' => 'status' ),
            array( 'db' => 'interview_question.locked', 'dt' => 'locked' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
        );

        $join = '
            left join `interview_question_job` on interview_question.question_id = interview_question_job.question_id
			left join `job_speedy` on job_speedy.job_speedy_code = interview_question_job.job_speedy_code
        ';

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $where = $this->filter( $request, $columns, $bindings  );

        if(isset($request['type']) && $request['type'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_question`.`type` = "'.$request['type'].'"';
        }

        if(isset($request['job_speedy_code']) && $request['job_speedy_code'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' (SELECT count(*) from `interview_question_job` where `interview_question_job`.question_id = `interview_question`.`question_id` and `interview_question_job`.`job_speedy_code` = \''.$request['job_speedy_code'].'\') > 0';
        }

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
             $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`  $join";
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

    public function getOthersIntreviewQuestion()
    {
        $request = $_POST;
        $type = $request['type'];
        $job = $request['job'];
        $ids = isset($request['question_ids'])?$request['question_ids']:[];
        $str_ids = implode( "," , $ids );
        $and_where="";
        if($str_ids!='') {
            $and_where = "and `question_id` NOT IN ( " . $str_ids . " )";
        }
        $out = array();

        $sql = "SELECT
					`interview_question`.`question_id`,
					`interview_question`.`question`,
					`interview_question`.`type`,
					`interview_question`.`help`,
					`interview_question`.`answer_heading`,
					`interview_question`.`status`
				FROM 
					`interview_question`
                WHERE `interview_question`.`type` = '".$type."' ".$and_where;
        if($type !== 'general'){
            $sql .="  AND (SELECT count(*) from `interview_question_job` where `interview_question_job`.`question_id` = `interview_question`.`question_id` and `interview_question_job`.`job_speedy_code` = '".$job."') > 0";
        }
        $sql .= " ORDER BY `interview_question`.question_id ASC
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($question_id,$question,$type,$help,$answer_heading,$status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'question_id' => $question_id,
                'question' => $question,
                'type' => $type,
                'help' => $help,
                'answer_heading' => $answer_heading,
                'status' => $status
            );
        }
        $stmt->close();
        return $out;
    }
    public function getRandomIntreviewQuestion($type, $limit, $job = false)
    {
        $out = array();

        $sql = "SELECT
					`interview_question`.`question_id`,
					`interview_question`.`question`,
					`interview_question`.`type`,
					`interview_question`.`help`,
					`interview_question`.`answer_heading`,
					`interview_question`.`status`
				FROM 
					`interview_question`
                JOIN
                  (SELECT CEIL(RAND() *
                     (SELECT MAX(question_id)
                        FROM `interview_question`)) AS question_id)
                  AS `interview_question2`
                WHERE  
                `interview_question`.question_id >= `interview_question2`.question_id
                AND `interview_question`.`type` = '".$type."'";
        if($job !== false){
            $sql .="  AND (SELECT count(*) from `interview_question_job` where `interview_question_job`.question_id = `interview_question`.`question_id` and `interview_question_job`.`job_speedy_code` = '".$job."') > 0";
        }
        $sql .= " ORDER BY `interview_question`.question_id ASC
                LIMIT ".$limit."
			";



        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($question_id,$question,$type,$help,$answer_heading,$status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'question_id' => $question_id,
                'question' => $question,
                'type' => $type,
                'help' => $help,
                'answer_heading' => $answer_heading,
                'status' => $status
            );
        }
        $stmt->close();

        return $out;
    }
    public function getIntreviewQuestion()
    {
        $out = array();

        $sql = "SELECT
					`question_id`,
					`question`,
					`type`,
					`help`,
					`answer_heading`,
					`status`
				FROM 
					`interview_question`
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($question_id,$question,$type,$help,$answer_heading,$status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'question_id' => $question_id,
                'question' => $question,
                'type' => $type,
                'help' => $help,
                'answer_heading' => $answer_heading,
                'status' => $status
            );
        }
        $stmt->close();

        return $out;
    }
    public function getOneIntreviewQuestion($id)
    {
        $out = array();

        $sql = "SELECT
					`question_id`,
					`question`,
					`type`,
					`help`,
					`answer_heading`,
					`status`
				FROM 
					`interview_question`
                WHERE
                  `question_id` = ?
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->bind_result($question_id,$question,$type,$help,$answer_heading,$status);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
                'question_id' => $question_id,
                'question' => $question,
                'type' => $type,
                'help' => $help,
                'answer_heading' => $answer_heading,
                'status' => $status
            );
        }
        $stmt->close();

        return $out;
    }

    public function updateIntreviewQuestion($data)
    {
        $sql = "UPDATE `interview_question`
        
                SET
					`question` = ?,
					`type` = ?,
					`help` = ?,
					`answer_heading` = ?,
					`status` = ?
                WHERE
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssii",$data['question'],$data['type'],$data['help'],$data['answer_heading'],$data['status'], $data['question_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function lockIntreviewQuestion($question_id)
    {
        $sql = "UPDATE `interview_question`
                SET
					`locked` = 1
                WHERE
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$question_id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function updateStatusPreIntreviewQuestion($question_id, $status)
    {
        $out = array();

        $sql = "UPDATE `interview_question`
        
                SET
					`status` = ?
				FROM 
					`interview_question`
                WHERE
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($status,$question_id);
        $stmt->execute();
        $stmt->close();

        return $out;
    }


    public function insertIntreviewQuestion($data)
    {
        $out = array();
        $sql = "INSERT INTO `interview_question`
                SET
					`question` = ?,
					`type` = ?,
					`help` = ?,
					`answer_heading` = ?,
					`status` = ?,
					`locked` = 0
                    
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi",$data['question'],$data['type'],$data['help'],$data['answer_heading'],$data['status']);
        $stmt->execute();
        $out = $stmt->insert_id;
        $stmt->close();

        return $out;
    }

    public function deleteIntreviewQuestion($id){

        $out = [];

        $sql = "DELETE
					FROM `interview_question`
                WHERE
					`question_id` = ?
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $out = $stmt->execute();
        $stmt->close();
        return $out;
    }

    public function insertQuestionJob($data){
        $out = array();
        $sql = "INSERT INTO `interview_question_job`
                SET
					`question_id` = ?,
					`job_speedy_code` = ?
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is",$data['question_id'],$data['job_speedy_code']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }
    public function deleteQuestionJob($id)
    {
        $out = array();
        $sql = "DELETE FROM `interview_question_job`
                WHERE
					`question_id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $stmt->close();

        return $out;
    }
    public function getQuestion($question_id)
    {
        $out = false;

        $sql = "SELECT
					`question_id`
				FROM 
					`interview_question`
                WHERE 
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$question_id);
        $stmt->bind_result($question_id);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
                'question_id' => $question_id
            );
        }
        $stmt->close();

        return $out;
    }

    public function getQuestionJobs($question_id)
    {
        $out = array();

        $sql = "SELECT
					`question_id`,
					`job_speedy_code`
				FROM 
					`interview_question_job`
                WHERE 
                    `question_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$question_id);
        $stmt->bind_result($question_id,$job_speedy_code);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'question_id' => $question_id,
                'job_speedy_code' => $job_speedy_code
            );
        }
        $stmt->close();

        return $out;
    }

    public function saveIntreviewResult($data){
        $out = array();
        $sql = "INSERT INTO `interview_result`
                SET
					`job_application_id` = ?,
					`schedule_id` = ?,
					`interviewer_id` = ?,
					`type` = ?,
					`communication_level_skill` = ?,
					`interview_comment` = ?,
					`created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `modified_on` = CURRENT_TIMESTAMP,
                    `modified_by`= {$this->user_id}
                    
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("siisss",
            $data['job_application_id'],
            $data['schedule_id'],
            $data['interviewer_id'],
            $data['type'],
            $data['communication_level_skill'],
            $data['interview_comment']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        echo $stmt->error;
        $stmt->close();

        return $out;
    }

    /*
     * save interview result prefer
     */

    public function saveIntreviewResultPrefer($data){
        $out = array();
        $sql = "INSERT INTO `interview_result_prefer`
                SET
					`job_application_id` = ?,
					`job_master_id` = ?,
					`type` = ?,
					`reason` = ?,
					`fixed` = ?,
					`created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `modified_on` = CURRENT_TIMESTAMP,
                    `modified_by`= {$this->user_id}
                    
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sissi",
            $data['job_application_id'],
            $data['job_master_id'],
            $data['prefer_type'],
            $data['reason'],
            $data['fixed']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    /*
     * getIntreviewResultDatatable
     * return datatable response
     */

    public function getIntreviewResultDatatable($ent_id = false){
        $this->generic = \core\app\classes\generic\generic::getInstance();
        $request = $_POST;
        $table = 'interview_result';

        $primaryKey = 'interview_result.schedule_id';

        $columns = array(
            array( 'db' => 'interview_result.schedule_id', 'dt' => 'schedule_id' ),
            array( 'db' => 'interview_result.interviewer_id', 'dt' => 'interviewer_id' ),
            array( 'db' => 'interview_result.type', 'dt' => 'type' ),
            array( 'db' => 'interview_result.communication_level_skill', 'dt' => 'communication_level_skill' ),
            array( 'db' => 'interview_result.interview_comment', 'dt' => 'interview_comment' ),
            array( 'db' => 'interview_result.created_on', 'dt' => 'created_on' ),
            array( 'db' => 'job_application.status', 'dt' => 'status' ),
            array( 'db' => 'job_application.job_application_id', 'dt' => 'job_application_id' ),
            array( 'db' => 'job_application.job_speedy_code', 'dt' => 'job_speedy_code' ),
            array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
            array( 'db' => 'job_application.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'job_application.status', 'dt' => 'job_application_status' ),
            array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
            array( 'db' => 'concat(`interviewer`.entity_family_name,\' \' ,`interviewer`.number_given_name)','as' => 'interviewer', 'dt' => 'interviewer' ),
            array( 'db' => 'concat(`address_book_per`.title,\' \' ,`address_book`.entity_family_name,\' \' ,`address_book`.number_given_name)', 'dt' => 'candidate', 'as' => 'candidate'  ),
            array( 'db' => 'organizer.entity_family_name', 'dt' => 'organizer', 'as' => 'organizer'),
            array( 'db' => 'ab_principal.entity_family_name', 'dt' => 'principal_entity_family_name', 'as' => 'principal_entity_family_name' ),
            array( 'db' => 'ab_principal.number_given_name', 'dt' => 'principal_number_given_name', 'as' => 'principal_number_given_name' ),
            array( 'db' => 'ab_principal.entity_family_name', 'dt' => 'principal_fullname', 'formatter' => function ($d, $row) {
                return $this->generic->getName('ent', $row['principal_entity_family_name'], $row['principal_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            })
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' JOIN   `address_book` `interviewer` on `interviewer`.`address_book_id` = interview_result.interviewer_id
                JOIN `interview_schedule` on `interview_schedule`.`schedule_id` = `interview_result`.`schedule_id`
                LEFT JOIN `interview_physical` on `interview_physical`.`schedule_id` = `interview_schedule`.`schedule_id`
                LEFT JOIN `interview_location` on `interview_location`.`interview_location_id` = `interview_physical`.`interview_location_id`
                LEFT JOIN  `address_book` `organizer` on `organizer`.`address_book_id` = interview_location.organizer_id
                LEFT JOIN `interview_online` on `interview_online`.`schedule_id` = `interview_schedule`.`schedule_id`
                JOIN `job_application` on `interview_result`.`job_application_id` = `job_application`.`job_application_id`
                JOIN job_speedy on job_speedy.job_speedy_code = job_application.job_speedy_code
                JOIN `address_book_per` ON `job_application`.`address_book_id` = `address_book_per`.`address_book_id`
                JOIN `address_book` ON `job_application`.`address_book_id` = `address_book`.`address_book_id` 
                LEFT JOIN `interview_result_principal` ON `'.$table.'`.`job_application_id` = `interview_result_principal`.`job_application_id`
                LEFT JOIN `principal` ON `interview_result_principal`.`principal_code` = `principal`.`code`
                LEFT JOIN `address_book` as ab_principal ON `ab_principal`.`address_book_id` = `principal`.`address_book_id`   
                
                ';

        $where = $this->filter( $request, $columns, $bindings  );

        if(isset($request['start_on']) && $request['start_on'] != ''){
            $start_on = date('Y-m-d 00:00:00', strtotime($request['start_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_result`.`created_on` >= "'.$start_on.'"';
        }

        if(isset($request['end_on']) && $request['end_on'] != ''){
            $finish_on = date('Y-m-d 23:59:59', strtotime($request['end_on']));
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_result`.`created_on` <= "'.$finish_on.'"';
        }


        if( isset($request['organizer_id']) && $request['organizer_id'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_location`.`organizer_id` = '.$request['organizer_id'];
        }

        if( isset($request['interviewer_id']) && $request['interviewer_id'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_result`.`interviewer_id` = '.$request['interviewer_id'];
        }

        if( isset($request['status']) && $request['status'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `job_application`.`status` = \''.$request['status'].'\'';
        }

        if( isset($request['type']) && $request['type'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `interview_result`.`type` = \''.$request['type'].'\'';
        }

        if( isset($request['job_speedy_code']) && $request['job_speedy_code'] != ''){
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .=' `job_application`.`job_speedy_code` = \''.$request['job_speedy_code'].'\'';
        }
        if($ent_id!=false) {
            if(isset($request['menu']) && $request['menu'] == 'principal') {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .=' `ab_principal`.`address_book_id` = '.$ent_id;
            }
        } else {
            if(isset($request['menu']) && $request['menu'] == 'principal' && ( isset($request['principal']) && $request['principal'] != '')) {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .=' `ab_principal`.`address_book_id` = '.$request['principal'];
            }
        }
        


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`  $join";
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

    public function getIntreviewResult($job_application_id){
        $out = array();
        $sql = "SELECT 
                    interview_result.`job_application_id`,
					interview_result.`schedule_id`,
					interview_result.`interviewer_id`,
					interview_result.`type`,
					interview_result.`communication_level_skill`,
					interview_result.`interview_comment`,
					interview_result.`created_on`,
                    interview_result.`created_by`,
                    concat(`interviewer`.entity_family_name,' ' ,`interviewer`.number_given_name) as interviewer,
                    `job_speedy`.`job_title`,
                    `candidate`.`entity_family_name` as candidate_entity_family_name,
                    `candidate`.`number_given_name` as candidate_number_given_name,
                    `interviewer`.`entity_family_name` as interviewer_entity_family_name,
                    `interviewer`.`number_given_name` as interviewer_number_given_name
                FROM 
                    `interview_result`
                LEFT JOIN `address_book` `interviewer` on `interviewer`.`address_book_id` = interview_result.interviewer_id
                LEFT JOIN
                    `job_application` ON `interview_result`.`job_application_id`=`job_application`.`job_application_id`
                LEFT JOIN
                `address_book` as candidate ON `job_application`.`address_book_id` = `candidate`.`address_book_id`
                LEFT JOIN
                `job_speedy` ON `job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code`
                WHERE
					interview_result.`job_application_id` = ?
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $job_application_id);
        $stmt->bind_result($job_application_id,
            $schedule_id,
            $interviewer_id,
            $type,
            $communication_level_skill,
            $interview_comment,
            $created_on,
            $created_by,
            $interviewer,
            $job_title,$candidate_entity_family_name,$candidate_number_given_name,$interviewer_entity_family_name,$interviewer_number_given_name
        );
        $stmt->execute();
        while($stmt->fetch())
        {
            $out = array(
                'job_application_id' => $job_application_id,
                'schedule_id' => $schedule_id,
                'interviewer_id' => $interviewer_id,
                'type' => $type,
                'communication_level_skill' => $communication_level_skill,
                'interview_comment' => $interview_comment,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'interviewer' => $interviewer,
                'job_title' => $job_title,
                'candidate_entity_family_name' => $candidate_entity_family_name,
                'candidate_number_given_name' => $candidate_number_given_name,
                'interviewer_entity_family_name' => $interviewer_entity_family_name,
                'interviewer_number_given_name' => $interviewer_number_given_name,
            );
        }
        $stmt->close();

        return $out;
    }

    public function getIntreviewAnswer($job_application_id){
        $out = array();
        $sql = "SELECT 
                    `interview_answer`.`job_application_id`,
                    `interview_answer`.`question_id`,
                    `interview_answer`.`text`,
                    `interview_answer`.`created_on`,
                    `interview_question`.`question`,
                    `interview_question`.`type`
                FROM 
                    `interview_answer`
                JOIN
                    `interview_question` on `interview_question`.`question_id` = `interview_answer`.`question_id`  
                WHERE
					`job_application_id` = ?
                ORDER BY
                    `interview_question`.`type`
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $job_application_id);
        $stmt->bind_result($job_application_id,$question_id,$text,$created_on,$question, $type);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_application_id' => $job_application_id,
                'question_id' => $question_id,
                'text' => $text,
                'created_on' => $created_on,
                'question' => $question,
                'type' => $type,
            );
        }
        $stmt->close();

        return $out;
    }

    public function saveQuestionAnswer($data){
        $out = array();
        $sql = "INSERT INTO `interview_answer`
                SET
					`job_application_id` = ?,
					`question_id` = ?,
					`text` = ?,
					`created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `modified_on` = CURRENT_TIMESTAMP,
                    `modified_by`= {$this->user_id}
                    
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sis",
            $data['job_application_id'],
            $data['question_id'],
            $data['text']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    /*
     * Get list of interviewer
     */
    public function getListInterviewer(){
        $out = [];
        $sql = 'SELECT
                        `interview_interviewer`.`address_book_id`,
                        `address_book`.`main_email`,
                        `address_book`.`type`,
                        `address_book`.`entity_family_name`,
                        `address_book`.`number_given_name`,
                        `address_book_per`.`title`,
                        `address_book_per`.`middle_names`
                  FROM
                    `interview_interviewer`
                  JOIN `address_book` on `address_book`.`address_book_id` = `interview_interviewer`.`address_book_id`
                  JOIN `address_book_per` on `address_book_per`.`address_book_id` = `interview_interviewer`.`address_book_id`';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $address_book_id,
            $main_email,
            $type,
            $entity_family_name,
            $number_given_name,
            $title,
            $middle_names
            );
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'address_book_id' => $address_book_id,
                'main_email' => $main_email,
                'type' => $type,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name,
                'title' => $title,
                'middle_names' => $middle_names
            );
        }
        $stmt->close();
        return $out;
    }

    /*
     * Insert interviewer to table interview_interviewer
     */
    public function insertInterviewer($data){
        $sql = 'INSERT INTO 
                    `interview_interviewer`
                SET
                  `interview_interviewer`.`address_book_id` = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $data['address_book_id']
        );
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }
    /*
     * Delete interviewer to table interview_interviewer
     */
    public function deleteInterviewer($id){
        $sql = 'DELETE FROM
                    `interview_interviewer`
                WHERE
                  `interview_interviewer`.`address_book_id` = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }


    /*
     * get interview security list for datatable
     */
    function getInterviewSecurityDatatable(){
        $request = $_POST;
        $this->validateRequest($request);
        $table = 'interview_security_checker';

        $primaryKey = 'interview_security_checker.principal_code';

        $columns = array(
            array( 'db' => '`interview_security_checker`.`principal_code`', 'dt' => 'principal_code' ),
            array( 'db' => 'interview_security_checker.countryCode_id', 'dt' => 'countryCode_id' ),
            array( 'db' => 'interview_security_checker.checker_id', 'dt' => 'checker_id' ),
            array( 'db' => 'CONCAT(`address_book`.`entity_family_name`,\' \', `address_book`.number_given_name)', 'as' => 'checker', 'dt' => 'checker' ),
            array( 'db' => '`address_book`.`main_email`', 'dt' => 'main_email' ),
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN `address_book` on `address_book`.`address_book_id` = interview_security_checker.checker_id ';
        $where = $this->filter( $request, $columns, $bindings  );

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $join
			 $where
			 $order
			 $limit";


        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`  $join";
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

    public function insertInterviewSecurity($data){
        $out = null;
        $sql =  "INSERT INTO
                    `interview_security_checker`
                SET
                    `principal_code` = ?,
                    `countryCode_id` = ?,
                    `checker_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi',
            $data['principal_code'],
            $data['countryCode_id'],
            $data['address_book_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function updateInterviewSecurity($data){
        $out = null;
        $sql =  "UPDATE
                    `interview_security_checker`
                SET
                    `principal_code` = ?,
                    `countryCode_id` = ?,
                    `checker_id` = ?
                WHERE
                    `principal_code` = ? AND
                    `countryCode_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssiss',
            $data['principal_code'],
            $data['countryCode_id'],
            $data['address_book_id'],
            $data['principal_code'],
            $data['countryCode_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function deleteInterviewSecurity($data){
        $out = null;
        $sql =  "DELETE
                    FROM `interview_security_checker`
                WHERE
                    `principal_code` = ? AND
                    `countryCode_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $data['principal_code'],
            $data['countryCode_id']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function checkNeedSecurityCheck($principal_code, $country_code){
        $out = null;
        $sql =  "SELECT
                    count(`interview_security_checker`.`principal_code`) as total
                FROM
                    `interview_security_checker`
                WHERE
                    `principal_code` = ? AND
                    `countryCode_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $principal_code,
            $country_code);
        $stmt->bind_result(
            $total
        );
        $stmt->execute();
        $stmt->fetch();
        $out = $total > 0 ? true : false;
        $stmt->close();
        return $out;
    }


    public function insertInterviewSecurityCheck($job_application_id){
        $out = null;
        $sql =  "INSERT INTO
                    `interview_security_tracker`
                SET
                    `job_application_id` = ?,
                    `status` = 'request_file',
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `level`= 1
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',
            $job_application_id);
        $stmt->execute();
        echo $stmt->error;
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
    }

    public function saveInterviewResultPrincipal($data){
        $out = array();
        $sql = "INSERT INTO `job_application_principal`
                SET
					`job_application_id` = ?,
					`principal_code` = ?
                    
			";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss",
            $data['job_application_id'],
            $data['principal_code']);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();

        return $out;
    }

    public function getDataInterviewLocationReminder($date_check) {
        $out = [];
        $sql = ' SELECT 
                `job_application`.`address_book_id`,
                `address_book_connection`.`connection_id`,
                `interview_schedule`.`interviewer_id`,
                `interview_location`.`start_on`,
                `interview_location`.`finish_on`,
                `interview_location`.`address`,
                `interview_location`.`google_map`
        FROM
        `interview_schedule`
        LEFT JOIN `job_application` on `interview_schedule`.`job_application_id` = `job_application`.`job_application_id`
        LEFT JOIN `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id` and `address_book_connection`.`connection_type`="lp"
        LEFT JOIN `interview_physical` on `interview_schedule`.`schedule_id` = `interview_physical`.`schedule_id`
        LEFT JOIN `interview_location` on `interview_physical`.`interview_location_id` = `interview_location`.`interview_location_id`
        WHERE
            DATE_FORMAT(`interview_location`.`start_on`,"%Y-%m-%d")="'.$date_check.'"
        AND
        `interview_schedule`.`type`="physical"
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $address_book_id,
            $connection_id,
            $interviewer_id,
            $start_on,
            $finish_on,
            $address,
            $google_map
            );
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'address_book_id' => $address_book_id,
                'connection_id' => $connection_id,
                'interviewer_id' => $interviewer_id,
                'start_on' => $start_on,
                'finish_on' => $finish_on,
                'address' => $address,
                'google_map' => $google_map
            );
        }
        $stmt->close();
        return $out;
    }

    public function getDataInterviewOnlineReminder($date_check) {
        $out = [];
        $sql = ' SELECT 
                `job_application`.`address_book_id`,
                `address_book_connection`.`connection_id`,
                `interview_schedule`.`interviewer_id`,
                `interview_online`.`schedule_on`,
                `interview_online`.`timezone`,
                `interview_online`.`google_meet_code`
        FROM
        `interview_schedule`
        LEFT JOIN `job_application` on `interview_schedule`.`job_application_id` = `job_application`.`job_application_id`
        LEFT JOIN `address_book_connection` on `job_application`.`address_book_id` = `address_book_connection`.`address_book_id` and `address_book_connection`.`connection_type`="lp"
        LEFT JOIN `interview_online` on `interview_schedule`.`schedule_id` = `interview_online`.`schedule_id`
        WHERE
            DATE_FORMAT(`interview_online`.`schedule_on`,"%Y-%m-%d")="'.$date_check.'"
        AND
            `interview_schedule`.`type`="online"
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result(
            $address_book_id,
            $connection_id,
            $interviewer_id,
            $schedule_on,
            $timezone,
            $google_meet_code
            );
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'address_book_id' => $address_book_id,
                'connection_id' => $connection_id,
                'interviewer_id' => $interviewer_id,
                'schedule_on' => $schedule_on,
                'timezone' => $timezone,
                'google_meet_code' => $google_meet_code
            );
        }
        $stmt->close();
        return $out;
    }

    public function getDetailJobApplication($job_application_id) {
        $out = array();
        $sql = "SELECT 
                    job_speedy_code
				FROM 
					`job_application`
                WHERE 
                    `job_application_id` = ?
			";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",$job_application_id);
        $stmt->bind_result($job_speedy_code);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'job_speedy_code' => $job_speedy_code
            );
        }
        $stmt->close();

        return $out;
    }

}
?>