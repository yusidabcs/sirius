<?php
namespace core\modules\workflow\models\common;

/**
 * Final workflow db class.
 *
 * @final
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class db extends \core\app\classes\module_base\module_db {

	private $now;

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		$this->now = date('Y-m-d H:i:s');
		return;
	}

	public function getCountTracker($workflow, $col,$not_in)
	{
		$out = [];
		$sql = "SELECT
					count(*) as all_level, 
					count(if(`level`='1',".$col." ,NULL))  as normal, 
					count(if(`level`='2',".$col." ,NULL))  as soft_warning,
					count(if(`level`='3',".$col." ,NULL))  as hard_warning, 
					count(if(`level`='4',".$col." ,NULL))  as deadline 
				FROM  `workflow_".$workflow."_tracker` 
				WHERE `status` NOT IN ".$not_in;
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($all_level, $normal, $soft_warning, $hard_warning, $deadline);
		$stmt->execute();
		if ($stmt->fetch()) {
			$out = [
				'all_level' => $all_level,
				'normal' => $normal,
				'soft_warning' => $soft_warning,
				'hard_warning' => $hard_warning,
				'deadline' => $deadline
			];
		}

		$stmt->close();

		return $out;
	}

	public function insertPersonalReferenceTracker($reference_check_id,$address_book_id)
	{
		
		$sql = "INSERT INTO 
					`workflow_personal_reference_tracker`(
						`reference_check_id`,
						`address_book_id`,
						`created_on`,
						`created_by`,
						`request_on`,
						`request_by`,
						`completed_on`,
						`completed_by`,
						`accepted_on`,
						`accepted_by`,
						`rejected_on`,
						`rejected_by`,
						`notes`,
						`status`
					)
				VALUES(?, ?, ?, ?, ?, ?, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 'requesting to person who referenced to', 'request')";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iisisi', $reference_check_id, $address_book_id, $this->now, $_SESSION['user_id'], $this->now, $_SESSION['user_id']);
		
		$stmt->execute();
		$out = $stmt->affected_rows;

		if ($stmt->error) {
			echo $stmt->error;
			exit(0);
		}

		$stmt->close();

		return $out;
	}

	public function insertInterviewTracker($address_book_id)
	{
		$sql = "INSERT INTO 
		`workflow_interview_ready_tracker`(
			`address_book_id`,
			`created_on`,
			`created_by`,
			`accepted_on`,
			`accepted_by`,
			`rejected_on`,
			`rejected_by`,
			`notes`,
			`status`,
			`level`
		)
				VALUES(?, ?, ?, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 'wating for schedule', 'request_schedule', 1)";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isi', $address_book_id, $this->now, $_SESSION['user_id']);

		$stmt->execute();
		$out = $stmt->affected_rows;

		if ($stmt->error) {
			echo $stmt->error;
			exit(0);
		}

		$stmt->close();

		return $out;
	}

	public function insertProfessionalReferenceTracker($reference_check_id, $address_book_id)
	{
		
		$sql = "INSERT INTO 
					`workflow_profesional_reference_tracker`(
						`reference_check_id`,
						`address_book_id`,
						`created_on`,
						`created_by`,
						`request_on`,
						`request_by`,
						`completed_on`,
						`completed_by`,
						`accepted_on`,
						`accepted_by`,
						`rejected_on`,
						`rejected_by`,
						`notes`,
						`status`
					)
				VALUES(?, ?, ?, ?, ?, ?, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 'requesting to person who referenced to', 'request')";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iisisi', $reference_check_id, $address_book_id, $this->now, $_SESSION['user_id'], $this->now, $_SESSION['user_id']);
		
		$stmt->execute();
		$out = $stmt->affected_rows;

		if ($stmt->error) {
			echo $stmt->error;
			exit(0);
		}

		$stmt->close();

		return $out;
	}

	public function insertEnglishTracker($address_book_id)
	{
		$sql = "INSERT INTO 
					`workflow_english_test_tracker`(
						`address_book_id`,
						`created_on`,
						`created_by`,
						`request_file_on`,
						`request_file_by`,
						`uploaded_file_on`,
						`uploaded_file_by`,
						`accepted_on`,
						`accepted_by`,
						`rejected_on`,
						`rejected_by`,
						`notes`,
						`status`
					)
				VALUES(?, ?, ?, ?, ?, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 'requesting file to candidate', 'request_file')";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isisi', $address_book_id, $this->now, $_SESSION['user_id'], $this->now, $_SESSION['user_id']);
		
		$stmt->execute();
		$out = $stmt->affected_rows;

		if ($stmt->error) {
			echo $stmt->error;
			exit(0);
		}

		$stmt->close();

		return $out;
	}

	public function insertPremiumServiceTracker($address_book_id)
	{
		$sql = "INSERT INTO 
					`workflow_premium_service_tracker`(
						`address_book_id`,
						`created_on`,
						`created_by`,
						`request_psf_on`,
						`request_psf_by`,
						`psf_verified_on`,
						`psf_verified_by`,
						`psf_confirmed_on`,
						`psf_confirmed_by`,
						`notes`,
						`status`
					)
				VALUES(?, ?, ?, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 'requesting psf to candidate', 'request_psf')";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isi', $address_book_id, $this->now, $_SESSION['user_id']);
		
		$stmt->execute();
		$out = $stmt->affected_rows;

		if ($stmt->error) {
			echo $stmt->error;
			exit(0);
		}

		$stmt->close();

		return $out;
	}

	public function updateTrackers($tablename, $address_book_id, $data, $field_type='',$value_type='')
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$where = "";
		if($field_type!='') {
			$where = " AND ".$field_type."='".$value_type."'";
		}

		$sql = "UPDATE $tablename
				SET $set WHERE address_book_id = ?
				AND `status` NOT IN ('accepted')
				".$where;

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $address_book_id);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function updateReferenceTracker($tablename, $reference_check_id, $data)
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$sql = "UPDATE $tablename
				SET $set WHERE reference_check_id = ?
				AND `status` NOT IN ('accepted')
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $reference_check_id);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function updateStcwTrackers($stcw_type, $address_book_id, $data)
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$sql = "UPDATE `workflow_stcw_tracker`
				SET $set WHERE address_book_id = ?
				AND `status` NOT IN ('accepted')
				AND `stcw_type` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $stcw_type);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function updateOktbTrackers($oktb_type, $address_book_id, $data)
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$sql = "UPDATE `workflow_oktb_tracker`
				SET $set WHERE address_book_id = ?
				AND `status` NOT IN ('accepted')
				AND `oktb_type` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $oktb_type);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function updateVisaTrackers($visa_type, $address_book_id, $data)
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$sql = "UPDATE `workflow_visa_tracker`
				SET $set WHERE address_book_id = ?
				AND `status` NOT IN ('accepted')
				AND `visa_type` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $visa_type);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function updateMedicalTrackers($medical_type, $address_book_id, $data)
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$sql = "UPDATE `workflow_medical_tracker`
				SET $set WHERE address_book_id = ?
				AND `status` NOT IN ('accepted')
				AND `medical_type` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $medical_type);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function updateVaccineTrackers($vaccine_type, $address_book_id, $data)
	{
		$set = '';
		
		foreach ($data as $key => $value) {
			$set .= " $key = '$value',";
		}
		$set = rtrim($set, ',');

		$sql = "UPDATE `workflow_vaccination_tracker`
				SET $set WHERE address_book_id = ?
				AND `status` NOT IN ('accepted')
				AND `vaccine_type` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $vaccine_type);
		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = $stmt->affected_rows;
		}else {
			$out = $stmt->error;
		}

		$stmt->close();

		return $out;
	}

	public function initializeAllApplicationTrackers($address_book_id)
	{
		$english_test = $this->insertEnglishTracker($address_book_id);
		
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		$address_book = $address_book_db->getAddressBookMainDetails($address_book_id);
		
		if ($address_book) {
			$mailing_common = new \core\modules\send_email\models\common\common;
			$mailing_common->putEmailToCollection($address_book['main_email'], $address_book['entity_family_name'] . ' ' . $address_book['number_given_name'], 'english_test');
		}

		$psf_tracker = $this->insertPremiumServiceTracker($address_book_id);

		$all_trues = [$english_test, $psf_tracker];

		for ($i=0; $i < count($all_trues); $i++) { 
			if ($all_trues[$i] !== 1) {
				return false;
			}
		}

		return true;
	}

	public function getActiveWorkflow($table, $primary_key, $primary_id, $additional = array()){
        $out = false;
	    $sql = "SELECT $primary_key from $table
				WHERE $primary_key = ? AND `status` not in ('accepted')
				";

		if (count($additional) > 0) {

			foreach ($additional as $key => $value) {
				$sql .= " AND $key = '$value'";
			}
		}

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $primary_id);
        $stmt->bind_result($id);
        $stmt->execute();
        while($stmt->fetch()){
            $out = $id;
        }
        return $id;
	}
	
	public function getTrackerDatatable($tablename, $cols = array(),$ent = false)
	{
		$this->generic = \core\app\classes\generic\generic::getInstance();
		
		$request = $_POST;
        $this->validateRequest($request, ['status', 'level','startDate','endDate','address_book']);
		$primaryKey = $tablename.'.address_book_id';

        $columns = array(
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.created_on', 'dt' => 'created_on'),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
			array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
			array( 'db' => 'user.user_id', 'dt' => 'user_id'),
			array( 'db' => 'address_book.entity_family_name', 'dt' => 'fullname', 'formatter' => function ($d, $row) {
                return $this->generic->getName('per', $row['entity_family_name'], $row['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            })
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
        $where = $this->filter($request, $columns, $bindings);

		$join = "LEFT JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
		$join .= " LEFT JOIN `user` on address_book.main_email = user.email";

		

        if (isset($request['status']) && !empty($request['status'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "$tablename.`status` = '".$request['status']."'";
        }

        if (isset($request['level'])) {
            if ($request['level'] > 0) {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .= "$tablename.`level` = ".$request['level'];
            }
		}
		
		//filter by date
		if ((isset($request['startDate']) && !empty($request['startDate']))) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`created_on`) >= '".date('Y-m-d',strtotime($request['startDate']))."'";
		}
		if (isset($request['endDate']) && !empty($request['endDate'])) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`created_on`) <= '".date('Y-m-d',strtotime($request['endDate']))."'";
		}

		if (isset($request['address_book'])) {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .= "$tablename.`address_book_id` = ".$request['address_book'];
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

	public function getReferenceTrackerDatatable($tablename, $cols = array(),$ent = false,$table_workflow='')
	{
		$request = $_POST;
        $this->validateRequest($request, ['status', 'level','startDate','endDate']);
		$primaryKey = $tablename.'.reference_check_id';

        $columns = array(
            array( 'db' => $tablename.'.`reference_check_id`', 'dt' => 'reference_check_id' ),
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.created_on', 'dt' => 'created_on'),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
			array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
			array( 'db' => 'user.user_id', 'dt' => 'user_id')
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
		$where = $this->filter($request, $columns, $bindings);
		$group = " GROUP BY $tablename.`address_book_id`";

		$join = "LEFT JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
		$join .= ' LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';
		$join .= " LEFT JOIN `user` on address_book.main_email = user.email";
		if($table_workflow!='') {
			$columns[]=array( 'db' => $table_workflow.'.short_description', 'dt' => 'short_description');
			$join .= ' LEFT JOIN `'.$table_workflow.'` ON '.$tablename.'.`status` = `'.$table_workflow.'`.`milestone` ';
		}

		$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .= "$tablename.status not in ('accepted','rejected')";

        if (isset($request['status']) && !empty($request['status'])) {
            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "$tablename.`status` = '".$request['status']."'";
        }

        if (isset($request['level'])) {
            if ($request['level'] > 0) {
                $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
                $where .= "$tablename.`level` = ".$request['level'];
            }
		}
		
		//filter by date
		if ((isset($request['startDate']) && !empty($request['startDate']))) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`created_on`) >= '".date('Y-m-d',strtotime($request['startDate']))."'";
		}
		if (isset($request['endDate']) && !empty($request['endDate'])) {
			$where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= "DATE(".$tablename.".`created_on`) <= '".date('Y-m-d',strtotime($request['endDate']))."'";
		}

		if ($ent !== false) {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
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
			  $join $where
			 ";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'] ?? 0;

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM  `".$tablename."` $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'] ?? 0;

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

	public function getTrackerDatatableFor($address_book_id, $tablename, $cols = array())
	{
		$data = [];
		$primaryKey = $tablename.'.address_book_id';

        $columns = array(
            array( 'db' => $tablename.'.`address_book_id`', 'dt' => 'address_book_id' ),
            array( 'db' => $tablename.'.level', 'dt' => 'level' ),
			array( 'db' => $tablename.'.status', 'dt' => 'status' ),
			array( 'db' => $tablename.'.created_on', 'dt' => 'created_on'),
            array( 'db' => 'address_book.address_book_id', 'dt' => 'address_book_id' ),
            array( 'db' => 'address_book.number_given_name', 'dt' => 'number_given_name' ),
            array( 'db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name' ),
			array( 'db' => 'address_book.main_email', 'dt' => 'main_email' ),
			array( 'db' => 'user.user_id', 'dt' => 'user_id')
		);
		
		if (count($cols) > 0) {
			foreach($cols as $key => $col) {
				$columns[] = array('db' => $tablename.'.'.$col, 'dt' => $col);
			}
		}

		$join = "LEFT JOIN `address_book` on $tablename.`address_book_id` = address_book.address_book_id";
		$join .= " LEFT JOIN `user` on address_book.main_email = user.email";

		$where = " WHERE $primaryKey = $address_book_id";

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `".$tablename."`
			 $join
			 $where";

        $stmt = $this->db->query($qry1);

		while ($row = $stmt->fetch_assoc()) {
			$data[] = $row;
		}
        /*
         * Output
         */
        return $data;
	}

	public function getTracker($tablename, $address_book_id, $cols = array(), $adds = array())
	{
		$select = array('address_book_id', 'created_on', 'created_by', 'notes', 'status');
		$where = '';

		if (count($cols) > 0) {
			$select = array_merge($select, $cols);
		}

		if (count($adds) > 0) {
			foreach ($adds as $key => $value) {
				$where .= " AND $key = '$value' ";
			}
		}

		$columns = implode(',', $select);
		chop($columns, ',');

		$sql = "SELECT $columns from $tablename WHERE address_book_id = $address_book_id $where";
		$stmt = $this->db->query($sql);

		if ($data = $stmt->fetch_assoc()) {
			$stmt->close();
			return $data;
		}

		return false;
	}

	public function getOfferLetterTracker($job_application_id)
	{
		$out = [];

		$sql = "SELECT
					`job_application_id`,
					`created_on`,
					`request_offer_letter_on`,
					`offer_letter_file_on`,
					`status`
				FROM
					`workflow_offer_letter_tracker`
				WHERE
					`job_application_id` = ?
				AND
					`status` = 'offer_letter'
				ORDER BY `created_on` DESC LIMIT 1";

		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $job_application_id);
		$stmt->bind_result($job_application_id, $created_on, $request_offer_letter_on, $offer_letter_file_on, $status);

		$stmt->execute();

		if ($stmt->fetch()) {
			$out = [
				'job_application_id' => $job_application_id,
				'request_offer_letter_on' => $request_offer_letter_on,
				'offer_letter_file_on' => $offer_letter_file_on,
				'created_on' => $created_on,
				'status' => $status
			];
		}

		return $out;
	}

	public function putVisaDocApplication($address_book_id, $booking_number, $payment_receipt, $visa_type, $docs_application_date)
	{
		$out = false;

		$sql = "INSERT INTO
					`workflow_visa_docs_application`(address_book_id, booking_number, payment_receipt, status, visa_type, docs_application_date)
				VALUES(?,?,?,'pending',?)";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isss', $address_book_id, $booking_number, $payment_receipt, $visa_type);

		$stmt->execute();
		$stmt->close();
		if ($stmt->affected_rows >= 1) {
			$out = true;
		}

		return $out;
	}

	public function getVisaDocApplication($address_book_id, $visa_type)
	{
		$out = false;

		$sql = "SELECT
					`address_book_id`
					`booking_number`
					`docs_application_date`
				FROM
					`workflow_visa_docs_application`
				WHERE
					`address_book_id` = ?
				AND
					`visa_type` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('is', $address_book_id, $visa_type);

		$stmt->execute();
		$stmt->bind_result($addr_book_id, $booking_number, $docs_application_date);

		if ($stmt->fetch()) {
			$out = [
				'address_book_id' => $addr_book_id,
				'booking_number' => $booking_number,
				'docs_application_date' => $docs_application_date
			];
		}

		$stmt->close();

		return $out;

	}

	public function insertTrackerGeneral($table, $primary_field, $primary_value)
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`$table`(
						$primary_field,
						deployment_date,
						created_on,
						created_by
					)
				VALUES(?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ss', $primary_value, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows === 1) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function insertVisaTracker($address_book_id, $deployment_date, $visa_type, $country_code = '000')
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`workflow_visa_tracker`(
						address_book_id,
						country_code,
						deployment_date,
						visa_type,
						created_on,
						created_by
					)
				VALUES(?,?,?,?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('issss', $address_book_id, $country_code, $deployment_date, $visa_type, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function insertOktbTracker($address_book_id, $deployment_date, $oktb_type)
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`workflow_oktb_tracker`(
						address_book_id,
						deployment_date,
						oktb_type,
						created_on,
						created_by
					)
				VALUES(?,?,?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isss', $address_book_id, $deployment_date, $oktb_type, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function insertVaccineTracker($address_book_id, $deployment_date, $vaccine_type)
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`workflow_vaccination_tracker`(
						address_book_id,
						deployment_date,
						vaccination_type,
						created_on,
						created_by
					)
				VALUES(?,?,?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isss', $address_book_id, $deployment_date, $vaccine_type, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function insertMedicalTracker($address_book_id, $deployment_date, $medical_type)
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`workflow_medical_tracker`(
						address_book_id,
						deployment_date,
						medical_type,
						created_on,
						created_by
					)
				VALUES(?,?,?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isss', $address_book_id, $deployment_date, $medical_type, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function insertStcwTracker($address_book_id, $deployment_date, $stcw_type)
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`workflow_stcw_tracker`(
						address_book_id,
						deployment_date,
						stcw_type,
						created_on,
						created_by
					)
				VALUES(?,?,?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('isss', $address_book_id, $deployment_date, $stcw_type, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function insertDeploymentTracker($table, $primary_field, $primary_value, $deployment_date)
	{
		$now = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					`$table`(
						$primary_field,
						deployment_date,
						created_on,
						created_by
					)
				VALUES(?,?,?,'0')";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iss', $primary_value, $deployment_date, $now);

		$stmt->execute();
		
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}

		$stmt->close();

		return false;
	}

	public function putDeploymentMaster($address_book_id, $job_demand_master_id, $loe_date, $deploy_date_start, $deploy_date_end)
	{
		$out = false;

		$current_timestamp = date('Y-m-d H:i:s');
		$sql = "INSERT INTO
						`deployment_master`(
							`address_book_id`,
							`job_demand_master_id`,
							`loe_date`,
							`deploy_date`,
							`deploy_date_end`,
							`created_on`
						)
					VALUES(?,?,?,?,?,?)";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('iissss', $address_book_id, $job_demand_master_id, $loe_date, $deploy_date_start, $deploy_date_end, $current_timestamp);

		$stmt->execute();

		if ($stmt->affected_rows >= 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function updateLoeFile($address_book_id, $job_demand_master_id, $filename)
	{
		$out = false;

		$sql = " UPDATE
					`deployment_master`
				SET
					`loe_file` = ?
				WHERE
					`address_book_id` = ?
				AND
					`job_demand_master_id` = ? ";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sii', $filename, $address_book_id, $job_demand_master_id);

		$stmt->execute();

		if ($stmt->affected_rows >= 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function getVisaTypes()
    {
        $out = [];

        $sql = "SELECT
                    `visa_type`
                FROM
                    `workflow_visa_type`";
		$stmt = $this->db->query($sql);

        while ($data = $stmt->fetch_assoc()) {
            $out[] = $data;
        }

        $stmt->close();

        return $out;
	}
	
	public function getOktbTypes()
	{
		$out = [];

        $sql = "SELECT
                    `oktb_type`
                FROM
                    `workflow_oktb_type`";
		$stmt = $this->db->query($sql);

        while ($data = $stmt->fetch_assoc()) {
            $out[] = $data;
        }

        $stmt->close();

        return $out;
	}

	public function getDeploymentMaster($address_book_id, $job_demand_master_id)
	{
		$out = false;

		$sql = "SELECT
					*
				FROM
					`deployment_master`
				WHERE
					`address_book_id` = ?
				AND
					`job_demand_master_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ii', $address_book_id, $job_demand_master_id);
		$stmt->bind_result($_address_book_id, $_job_demand_master_id, $loe_date, $loe_file, $deploy_date_start, $deploy_date_end, $status, $created_on);

		$stmt->execute();

		if ($stmt->fetch()) {
			$out = [
				'address_book_id' => $_address_book_id,
				'job_demand_master_id' => $_job_demand_master_id,
				'loe_date' => $loe_date,
				'loe_file' => $loe_file,
				'deploy_date_start' => $deploy_date_start,
				'deploy_date_end' => $deploy_date_end,
				'status' => $status,
				'created_on' => $created_on
			];
		}

		$stmt->close();

		return $out;
	}

	public function insertToQueue($primary_field, $primary_value, $tracker_table, $deployment_type, $deployment_date, $reserved_at, $data = array())
	{
		$out = false;
		$oktb_types = '';
		$visa_types = '';
		$stcw_types = '';
		$medical_types = '';
		$vaccine_types = '';

		if (isset($data['oktb_types'])) {
			$oktb_types = implode(',', $data['oktb_types']);
		}

		if (isset($data['visa_types'])) {
			$visa_types = implode(',', $data['visa_types']);
		}

		if (isset($data['stcw_types'])) {
			$stcw_types = implode(',', $data['stcw_types']);
		}

		if (isset($data['medical_types'])) {
			$medical_types = implode(',', $data['medical_types']);
		}

		if (isset($data['vaccine_types'])) {
			$vaccine_types = implode(',', $data['vaccine_types']);
		}

		$sql = "INSERT 
					INTO
						`workflow_tracker_queue`(
							`primary_field`,
							`primary_value`,
							`tracker_table`,
							`deployment_type`,
							`deployment_date`,
							`reserved_at`,
							`oktb_type`,
							`visa_type`,
							`stcw_type`,
							`medical_type`,
							`vaccine_type`,
							`created_on`
						)
					VALUES(?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('sisssssssss', $primary_field, $primary_value, $tracker_table, $deployment_type, $deployment_date, $reserved_at, $oktb_types, $visa_types, $stcw_types, $medical_types, $vaccine_types);

		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function isQueueActive($tracker_table, $primary_field, $primary_value, $adds = array())
	{
		$where = '';
		$out = false;
		$sql = "SELECT *
					FROM
						`workflow_tracker_queue`
					WHERE
						`tracker_table` = ?
					AND
						`primary_field` = ?
					AND
						`primary_value` = ?
					AND
						`status` = 'pending'";
		
		if (count($adds) > 0) {
			foreach ($adds as $key => $value) {
				$where .= " AND $key = '$value'";
			}
		}

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('ssi', $tracker_table, $primary_field, $primary_value);
		$stmt->execute();

		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function updateReferenceTrackerLevel($workflow){
        $out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`request_on`,
					`workflow_".$workflow."_tracker`.`completed_on`,
					`workflow_".$workflow."_tracker`.`rejected_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                
				JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status`
				WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')";

        $data = $this->db->query_array($sql);
        $this->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

	public function updateEnglistTestTrackerLevel($workflow) {
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`rejected_on`,
					`workflow_".$workflow."_tracker`.`uploaded_file_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                
				JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status`
				WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')";

        $data = $this->db->query_array($sql);

		if ($workflow === 'english_test') {
			$mailing_common = new \core\modules\send_email\models\common\common;
			$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();	

			foreach ($data as $key => $value) {
				$address_book_detail = $address_book_db->getAddressBookMainDetails($value['address_book_id']);

				if ($address_book_detail) {
					$mailing_common->removeEmailFromCollection($address_book_detail['main_email'], 'english_test');
					if ($value['status'] === 'request_file') {
						# code...
						$mailing_common->putEmailToCollection($address_book_detail['main_email'], $address_book_detail['entity_family_name'] .' '. $address_book_detail['number_given_name'], 'english_test');
					}
				}
			}
		}

        $this->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

	public function updatePremiumServiceTrackerLevel($workflow) {
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`request_psf_on`,
					`workflow_".$workflow."_tracker`.`psf_verified_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                
				JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status`
				WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')";

        $data = $this->db->query_array($sql);
        $this->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

	public function updateInterviewReadyTrackerLevel($workflow) {
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                
				JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status`

				WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted', 'rejected')";

        $data = $this->db->query_array($sql);
        $this->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}
	
	public function updateAllWorkflowTrackerLevel($workflow,$data) {
        $now = time();
        foreach ($data as $index => $item){
            //calculate warning level
            if($item['reference_milestone'] == ''){
                $data[$index]['level'] = 'normal';
                continue;
            }

            $reference_date = $item[$item['reference_milestone']];
            
            if($item['reference_direction'] == 'after')
            {
                if( $now > strtotime($reference_date.' + '.$item['deadline'].' days') )
                {
                    $level = '4';
                } else if( $now > strtotime($reference_date.' + '.$item['hard_warning'].' days') ) {
                    $level = '3';
                } else if( $now > strtotime($reference_date.' + '.$item['soft_warning'].' days') ) {
                    $level = '2';
                } else {
                    $level = '1';
                }

            } else {

                if( $now > strtotime($reference_date.' - '.$item['deadline'].' days') )
                {
                    $level = '4';
                } else if( $now > strtotime($reference_date.' - '.$item['hard_warning'].' days') ) {
                    $level = '3';
                } else if( $now > strtotime($reference_date.' - '.$item['soft_warning'].' days') ) {
                    $level = '2';
                } else {
                    $level = '1';
                }
            }

            $this->updateWorkflowTrackerLevel($workflow,$item['address_book_id'], $level);

        }
    }

	public function getTotalTrackerByLevel($workflow){
        $sql =  "SELECT 
                    COUNT(`workflow_".$workflow."_tracker`.`address_book_id`) as total,
                    `workflow_".$workflow."_tracker`.`level`
                FROM 
                    `workflow_".$workflow."_tracker`
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted', 'rejected')
                	
       			GROUP BY `workflow_".$workflow."_tracker`.`level`";
        $data = $this->db->query_array($sql);
        return $data;
	}

	public function getTotalTrackerByLevelPartner($workflow){
        $sql =  "SELECT 
                    COUNT(`workflow_".$workflow."_tracker`.`address_book_id`) as total,
                    `workflow_".$workflow."_tracker`.`level`,`address_book_connection`.`connection_id`
                FROM 
                    `workflow_".$workflow."_tracker`
				LEFT JOIN
					`address_book_connection` ON `workflow_".$workflow."_tracker`.`address_book_id`=`address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`='lp'
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')
                	
       			GROUP BY `workflow_".$workflow."_tracker`.`level`,`address_book_connection`.`connection_id`";
        $data = $this->db->query_array($sql);
        return $data;
	}
	
	public function updateWorkflowTrackerLevel($workflow,$address_book_id, $level){
        $out = null;
        $sql =  "UPDATE
                    `workflow_".$workflow."_tracker`
                SET
                    `level` = {$level}
                WHERE
                    `address_book_id` = {$address_book_id}
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $out = $stmt->affected_rows;
        $stmt->close();
        return $out;
	}
	
	public function updateStcwTrackerLevel($workflow) {
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`deployment_date`,
                    `workflow_".$workflow."_tracker`.`file_uploaded_on`,
                    `workflow_".$workflow."_tracker`.`rejected_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                
				JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status`
				
				WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')";

		$data = $this->db->query_array($sql);
		
        $this->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

	public function updateFlightTrackerLevel($workflow) {
        $tracker_db = new db();
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`deployment_date`,
                    `workflow_".$workflow."_tracker`.`rejected_on`,
                    `workflow_".$workflow."_tracker`.`file_uploaded_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                JOIN `job_application` on `workflow_".$workflow."_tracker`.`address_book_id` = `job_application`.`address_book_id`
                JOIN `interview_result_principal` on `job_application`.`job_application_id` = `interview_result_principal`.`job_application_id`
                
                JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status` AND `workflow_".$workflow."_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')
				AND `job_application`.`status` NOT IN ('not_hired','canceled','reapply','allocated')
				AND `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')";

        $data = $this->db->query_array($sql);
        //var_dump($data);
        $tracker_db->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

	public function updateSeamanTrackerLevel($workflow) {
        $tracker_db = new db();
		$out = [];
        $sql =  "SELECT 
                    `workflow_".$workflow."_tracker`.`address_book_id`,
                    `workflow_".$workflow."_tracker`.`created_on`,
                    `workflow_".$workflow."_tracker`.`deployment_date`,
                    `workflow_".$workflow."_tracker`.`rejected_on`,
                    `workflow_".$workflow."_tracker`.`file_uploaded_on`,
                    `workflow_".$workflow."_tracker`.`status`,  
                    `workflow_".$workflow."_workflow`.`milestone`, 
                    `workflow_".$workflow."_workflow`.`soft_warning`, 
                    `workflow_".$workflow."_workflow`.`hard_warning`, 
                    `workflow_".$workflow."_workflow`.`deadline`, 
                    `workflow_".$workflow."_workflow`.`reference_direction`, 
                    `workflow_".$workflow."_workflow`.`reference_milestone`
                FROM 
                    `workflow_".$workflow."_tracker`
                
                JOIN `job_application` on `workflow_".$workflow."_tracker`.`address_book_id` = `job_application`.`address_book_id`
                JOIN `interview_result_principal` on `job_application`.`job_application_id` = `interview_result_principal`.`job_application_id`
                
                JOIN `workflow_".$workflow."_workflow` on `workflow_".$workflow."_workflow`.`milestone` = `workflow_".$workflow."_tracker`.`status` AND `workflow_".$workflow."_workflow`.`principal_code` = `interview_result_principal`.`principal_code`
                
                WHERE `workflow_".$workflow."_tracker`.`status` NOT IN ('accepted')
				AND `job_application`.`status` NOT IN ('not_hired','canceled','reapply','allocated')";

        $data = $this->db->query_array($sql);
        //var_dump($data);
        $tracker_db->updateAllWorkflowTrackerLevel($workflow,$data);
        return $out;
	}

	public function deleteTracker($table,$key_field,$key_value)
	{
		$out = 0;
		$sql = "DELETE FROM
				`".$table."`
				WHERE `".$key_field."` = ?
				";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $key_value);
		$stmt->execute();
		$out = $stmt->affected_rows;

		$stmt->close();

		return $out;
	}

	
}