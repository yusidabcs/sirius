<?php
namespace core\modules\user\models\common;

/**
 * Final user_db class.
 * 
 * @final
 */
final class user_db extends \core\app\classes\module_base\module_db {
	
	private $system_register;

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		
		//private global variables for this object
		$this->system_register = \core\app\classes\system_register\system_register::getInstance();
	
		return;
	}
	public function addNewUserDb($username,$email,$password,$security_level_id,$group_id,$status)
	{
		//only store the md5 version .. never to be seen again
		$pwMd5 = md5($password.$this->system_register->site_info('SALT'));
		settype($user_id, 'integer');
		
		$qry = "INSERT INTO
					`user`
				SET
					`username` = '{$username}',
					`email` = '{$email}',
					`password` = '{$pwMd5}',
					`security_level_id` = '{$security_level_id}',
					`group_id` = '{$group_id}',
					`status` = {$status},
					`created_on` = CURRENT_TIMESTAMP, 
					`created_by` = {$this->user_id}, 
					`modified_on`= CURRENT_TIMESTAMP, 
					`modified_by`= {$this->user_id} 
				";
		$this->db->query($qry);
		if($this->db->affected_rows() != 1)
		{
			$msg = 'No user added - system error';
			throw new \RuntimeException($msg);
		}
		
		return $this->db->insert_id();
	}
	
	public function checkUserNameInUse($username)
	{
		$sql = "SELECT 
					`user_id`
				FROM
					`user`
				WHERE
					`username` = ?";
					
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$username);
		$stmt->bind_result($user_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $user_id;
		} else {
			$out = false;
		}
		$stmt->close();
		
		return $out;
	}
	
	public function checkEmailInUse($email)
	{
		$qry = "SELECT 
					`user_id`
				FROM
					`user`
				WHERE
					`email` = '{$email}'";
					
		$result = $this->db->query($qry);
		$row = $result->fetch_row();
		if(!empty($row))
		{
			$out = $row[0];
		} else {
			$out = false;
		}
		$result->close();
		return $out;
	}
		
	public function updateUser($user_id,$field,$value)
    {
	    //make absolutly certain the person is logged in
	    if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] < 0)
	    {
		    $msg = 'Security error: you can not update user information.';
		    throw new \RuntimeException($msg);
	    }
				
	    $out = false;
	    
	    $qry = "UPDATE `user` SET `{$field}` = '{$value}', `modified_by` = {$this->user_id}, `modified_on` = CURRENT_TIMESTAMP WHERE `user_id` = '{$user_id}'";
	    $this->db->query($qry);
	    
	    if( $this->db->affected_rows() == 1 )
		{
			$out = true;
		}

	    return $out;
	}

	public function currentPassword($md5_current)
	{
		$sql = "SELECT 
					`user_id`
				FROM
					`user`
				WHERE
					`password` = ?";
					
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$md5_current);
		$stmt->bind_result($user_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $user_id;
		} else {
			$out = false;
		}
		$stmt->close();
		
		return $out;
	}
	
	public function getCurrentPassword($user_id)
	{
		$sql = "SELECT 
					`password`
				FROM
					`user`
				WHERE
					`user_id` = ?";
					
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$user_id);
		$stmt->bind_result($password);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $password;
		} else {
			$out = false;
		}
		$stmt->close();
		
		return $out;
	}
		
	public function selectUserDetails($user_id)
	{	
		$out = array();
		$permitedSecurityLevelIds = $this->system_register->permittedSecurityLevelIds();

		$sql = "SELECT
				`username`,
				`email`,
				`security_level_id`,
				`group_id`,
				`created_on`,
				`created_by`,
				`modified_on`,
				`modified_by`,
				`last_login`,
				`status`
			FROM 
				`user`
			WHERE
				`user_id` = ?
			AND
				`security_level_id` IN ({$permitedSecurityLevelIds})
		";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$user_id);
		$stmt->bind_result($username,$email,$security_level_id,$group_id,$created_on,$created_by,$modified_on,$modified_by,$last_login,$status);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out[$user_id] = array(
				'username' => $username,
				'email' => $email,
				'security_level_id' => $security_level_id,
				'group_id' => $group_id,
				'created_on' => $created_on,
				'created_by' => $created_by,
				'modified_on' => $modified_on,
				'modified_by' => $modified_by,
				'last_login' => $last_login,
				'status' => $status
			);
		} 
		$stmt->close();
		return $out;
	}
	
	public function getPaginationInfo($link_id,$model_name,$page,$ignore_me=false)
	{
		$paginate = new \core\app\classes\paginate\paginate('local','user',false);
		
		//setup pagination
		$paginate->setSessionInfo($link_id,$model_name,'users');
		
		//if page is set in options
		if($page > 0)
		{
			$paginate->setPageOn($page);
		}
		
		//specific sql information
		$permitedSecurityLevelIds = $this->system_register->permittedSecurityLevelIds();
		
		$this->where_statement = "WHERE `security_level_id` IN ({$permitedSecurityLevelIds}) ";
		
		if($ignore_me)
		{
			$this->where_statement .= "AND `user_id` NOT IN ({$_SESSION['user_id']}) ";
		}
		
		$paginate->setWhere($this->where_statement);
		
		$paginateViewArray = $paginate->getPaginationInfo();

		//setInfo for getClientArray
		$this->_paginate_offset = $paginateViewArray['page_start_record'];
		$this->_paginate_rowcount = $paginateViewArray['pagination_number'];

		return $paginateViewArray;
	}
	
	public function selectAllUsers()
	{	
		//error checking
		if(!isset($this->_paginate_offset))
		{
			$msg = "The you need to run getPaginationInfo before you can selectAllUsers.";
			throw new \RuntimeException($msg); 
		}
			
		$out = array();
		
		$permitedSecurityLevelIds = $this->system_register->permittedSecurityLevelIds();
		
		$sql = "SELECT
					`user_id`,
					`username`,
					`email`,
					`security_level_id`,
					`group_id`,
					`last_login`,
					`status`
				FROM 
					`user`
		";

		$sql .= $this->where_statement;
				
		$sql .= "LIMIT
					{$this->_paginate_offset},{$this->_paginate_rowcount}
		";
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($user_id,$username,$email,$security_level_id,$group_id,$last_login,$status);
		$stmt->execute();
		while($stmt->fetch())
		{	
			$out[$user_id] = array(
				'username' => $username,
				'email' => $email,
				'security_level_title' => $this->system_register->getSecurityTitleFromId($security_level_id),
				'group_title' => $this->system_register->getGroupTitle($group_id),
				'last_login' => $last_login,
				'status' => $status
			);
		} 
		$stmt->close();
		return $out;
	}

	public function getEmailFromUsername($username)
	{
		$email = '';
		
		$qry = "SELECT  
					`email` 
				FROM  
					`user` 
				WHERE  
					`username` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$username);
		$stmt->bind_result($email);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		return $email;
	}
	
	public function getUsernameFromEmail($email)
	{
		$email = '';
		
		$qry = "SELECT  
					`username` 
				FROM  
					`user` 
				WHERE  
					`email` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$email);
		$stmt->bind_result($username);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		return $username;
	}
	
	public function getUserInfoFromEmail($email)
	{
		$out = array();
		
		$qry = "SELECT 
					`user_id`,
					`username`,
					`security_level_id`,
					`group_id`,
					`last_login`,
					`status`
				FROM
					`user`
				WHERE
					`email` = '{$email}'";
					
		$stmt = $this->db->prepare($qry);
		$stmt->bind_result($user_id,$username,$security_level_id,$group_id,$last_login,$status);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = array(
				'user_id' => $user_id,
				'username' => $username,
				'email' => $email,
				'security_level_title' => $this->system_register->getSecurityTitleFromId($security_level_id),
				'group_title' => $this->system_register->getGroupTitle($group_id),
				'last_login' => $last_login,
				'status' => $status
			);
		}
		$stmt->close();
		return $out;
	}
	
	public function getUserInfoFromAdressBookId($address_book_id)
	{
		$out = array();
		
		$qry = "SELECT 
					`user_id`,
					`email`,
					`username`,
					`security_level_id`,
					`group_id`,
					`last_login`,
					`status`
				FROM
					`user`
				WHERE
					`email` = 
					(
						SELECT 
							main_email 
						FROM
							address_book
						WHERE 
							address_book_id = '{$address_book_id}'
					)";
					
		$stmt = $this->db->prepare($qry);
		$stmt->bind_result($user_id,$email,$username,$security_level_id,$group_id,$last_login,$status);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = array(
				'user_id' => $user_id,
				'username' => $username,
				'email' => $email,
				'security_level_title' => $this->system_register->getSecurityTitleFromId($security_level_id),
				'group_title' => $this->system_register->getGroupTitle($group_id),
				'last_login' => $last_login,
				'status' => $status
			);
		}
		$stmt->close();
		return $out;
	}
	

	public function updateUserPassword($user_id,$password)
    {
	    $out = false;
	    $qry = "UPDATE 
	    			`user` 
	    		SET 
	    			`password` = '{$password}', 
	    			`modified_by` = {$this->user_id}, 
	    			`modified_on` = CURRENT_TIMESTAMP 
	    		WHERE 
	    			`user_id` = '{$user_id}'
	    		AND
	    			`security_level_id` != 'SYSADMIN'
	    		";
	    		
	    $this->db->query($qry);
	    if( $this->db->affected_rows() == 1 )
		{
			$out = true;
		}
	    return $out;
	}
    
    public function getEmailFromUserID($user_id)
    {
	    $email = false;
	    
	    $sql = "SELECT
					`email`
				FROM 
					`user`
				WHERE
					`user_id` = ?
			";
	
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i',$user_id);
		$stmt->bind_result($email);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		return $email;
    }


    public function getUsers(){
        $request = $_POST;
        $table = 'user';

        $primaryKey = 'user.user_id';

        $columns = array(
            array( 'db' => 'user.username', 'dt' => 'username' ),
            array( 'db' => 'user.email', 'dt' => 'email' ),
            array( 'db' => 'user.security_level_id',  'dt' => 'security_level_id' ),
            array( 'db' => 'user.group_id',  'dt' => 'group_id' ),
            array( 'db' => 'user.created_on',  'dt' => 'created_on' ),
			array( 'db' => 'user.last_login',  'dt' => 'last_login' ),
            array( 'db' => 'user.status',  'dt' => 'status' ),
			array( 'db' => 'user.user_id',  'dt' => 'user_id' ),
			array( 'db' => 'role.role_name', 'dt' => 'role_name' ),
			array( 'db' => 'role.role_id', 'dt' => 'role_id' )
			
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
		$join = ' LEFT JOIN `user_role` ON `user`.`user_id` = `user_role`.`user_id`';
		$join .= ' LEFT JOIN `role` ON `role`.`role_id` = `user_role`.`role_id`';
        $where = $this->filter( $request, $columns,$bindings  );


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

    public function getNonInterviewerArray(){
        $out = array();

        $qry = "SELECT 
					`user`.`user_id`,
					`user`.`email`,
					`user`.`username`,
					`user`.`security_level_id`,
					`user`.`group_id`,
					`user`.`last_login`,
					`user`.`status`,
					`address_book`.`address_book_id`,
					`address_book`.`entity_family_name`,
					`address_book`.`number_given_name`
				FROM
					`user`
				JOIN 
					`address_book` on `address_book`.`main_email` = `user`.`email`	
				WHERE NOT EXISTS (
					  SELECT *
					  FROM interview_interviewer WHERE interview_interviewer.`address_book_id` = `address_book`.`address_book_id`
					)	
				AND
					`security_level_id` != 'NONE' AND `security_level_id` != 'USER'
				";

        $stmt = $this->db->prepare($qry);
        $stmt->bind_result(
        	$user_id,
			$email,
			$username,
			$security_level_id,
			$group_id,
			$last_login,
			$status,
			$address_book_id,
			$entity_family_name,
			$number_given_name
		);
        $stmt->execute();
        while($stmt->fetch())
        {
            $out[] = array(
                'user_id' => $user_id,
                'username' => $username,
                'email' => $email,
                'security_level_title' => $this->system_register->getSecurityTitleFromId($security_level_id),
                'group_title' => $this->system_register->getGroupTitle($group_id),
                'last_login' => $last_login,
                'status' => $status,
                'address_book_id' => $address_book_id,
                'entity_family_name' => $entity_family_name,
                'number_given_name' => $number_given_name
            );
        }
        $stmt->close();
        return $out;
	}
	
	public function deleteUser($user_id,$email) {
		$out = [
			'status'=>'',
			'message' => ''
		];
		$qry = "DELETE FROM 
					`user`
				WHERE
					`email` = '{$email}' AND
					`user_id` = ".$user_id."
					";
					
		$this->db->query($qry);
		if($this->db->affected_rows() >= 1)
		{
			$out['status'] = 'ok';
			$out['message'] = 'User deleted successfully!';
		} else {
			$out['message'] = 'User not found!';
		}
		return $out;
	}

	public function getAllRoles()
	{
		$out = [];

		$sql = "
			SELECT `role_id`,`role_name`,`permission` FROM `role`
		";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($role_id, $role_name, $permission);

		$stmt->execute();

		while($stmt->fetch()) {
			$out[] = [
				'role_id' => $role_id,
				'role_name' => $role_name,
				'permission' => $permission
			];
		}

		$stmt->free_result();

		$stmt->close();

		return $out;
	}

	public function getRole($role_id)
	{
		$out = null;

		$sql = "
			SELECT `role_id`,`role_name`,`permission` FROM `role` WHERE role_id = ?
		";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $role_id);
		$stmt->bind_result($role_id, $role_name, $permission);

		$stmt->execute();

		if($stmt->fetch()) {
			$out = [
				'role_id' => $role_id,
				'role_name' => $role_name,
				'permission' => $permission
			];
		}

		$stmt->free_result();

		$stmt->close();

		return $out;
	}

	public function insertRole($name)
	{
		$out = null;
		$sql = "INSERT INTO `role`(role_name,created_on,modified_on) VALUES(?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s', $name);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function updateRole($role_id, $data)
	{
		$out = false;
		$sql = "UPDATE `role` SET ";

		foreach ($data as $key => $value) {
			$sql .= " `$key` = '$value',";
		}

		$sql .= "modified_on = CURRENT_TIMESTAMP";

		$sql .= " WHERE `role_id` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $role_id);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function updateRolePermission($role_id, $permission)
	{
		$out = false;
		$sql = "UPDATE `role` SET `permission` = ?, `modified_on` = CURRENT_TIMESTAMP WHERE `role_id` = ? ";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('si', $permission, $role_id);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function deleteRole($role_id)
	{
		$out = false;
		$sql = "DELETE FROM `role` WHERE `role_id` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $role_id);

		$stmt->execute();

		if ($stmt->affected_rows === 1) {
			$out = true;
		}

		$stmt->close();

		return $out;
	}

	public function getRoleDatatable()
	{
		$request = $_POST;
        $table = 'role';

        $primaryKey = 'role.role_id';

        $columns = array(
            array( 'db' => "$table.`role_id`", 'dt' => 'role_id' ),
            array( 'db' => "$table.`created_on`", 'dt' => 'created_on' ),
            array( 'db' => "$table.`modified_on`", 'dt' => 'modified_on' ),
            array( 'db' => "$table.`role_name`", 'dt' => 'role_name' ),
            array( 'db' => "$table.`permission`", 'dt' => 'permission' ),
            array( 'db' => "(
				SELECT COUNT(user_role.user_id) AS users FROM `user_role` WHERE `user_role`.`role_id` = `$table`.`role_id` 
			)", 'as' => 'total_users', 'dt' => 'total_users' )
        );


        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );
		$where = $this->filter( $request, $columns, $bindings );

        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM `$table`
			 $where
			 $order
			 $limit";

        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table` ";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table` ";
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

	public function getUserArray()
	{
		$out = [];

		$sql = "SELECT 
					`user`.`user_id`,
					`user`.`username`,
					`user`.`email`,
					`address_book`.`number_given_name`,
					`address_book`.`entity_family_name`
				FROM `user`
				LEFT JOIN `address_book` ON `user`.`email` = `address_book`.`main_email`";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($user_id, $username, $email, $number_given_name, $entity_family_name);

		$stmt->execute();

		while ($stmt->fetch()) {
			$out [] = [
				'user_id' => $user_id,
				'username' => $username,
				'email' => $email,
				'number_given_name' => $number_given_name,
				'entity_family_name' => $entity_family_name
			];
		}

		$stmt->close();

		return $out;
	}

	public function getUser($user_id)
	{
		$out = null;

		$sql = "SELECT 
					`user`.`user_id`,
					`user`.`username`,
					`user`.`email`,
					`address_book`.`address_book_id`,
					`address_book`.`number_given_name`,
					`address_book`.`entity_family_name`
				FROM `user`
				LEFT JOIN `address_book` ON `user`.`email` = `address_book`.`main_email`
				WHERE `user`.`user_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $user_id);
		$stmt->bind_result($user_id, $username, $email, $address_book_id, $number_given_name, $entity_family_name);

		$stmt->execute();

		if ($stmt->fetch()) {
			$out = [
				'user_id' => $user_id,
				'username' => $username,
				'email' => $email,
				'address_book_id' => $address_book_id,
				'number_given_name' => $number_given_name,
				'entity_family_name' => $entity_family_name
			];
		}

		$stmt->close();

		return $out;
	}

	public function getUserRole($user_id)
	{
		$out = null;

		$sql = "SELECT 
					`user_role`.`user_id`,
					`user`.`username`,
					`user`.`email`,
					`user_role`.`role_id`,
					`role`.`role_name`,
					`role`.`permission`
				FROM `user_role`
				LEFT JOIN `role` ON `user_role`.`role_id` = `role`.`role_id`
				LEFT JOIN `user` ON `user_role`.`user_id` = `user`.`user_id`
				WHERE `user_role`.`user_id` = ?";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $user_id);
		$stmt->bind_result($user_id, $username, $email, $role_id, $role_name, $permission);

		$stmt->execute();

		if ($stmt->fetch()) {
			$out = [
				'user_id' => $user_id,
				'username' => $username,
				'email' => $email,
				'role_id' => $role_id,
				'role_name' => $role_name,
				'permission' => $permission
			];
		}

		$stmt->close();

		return $out;
	}

	public function assignRole($user_ids, $role_id)
	{
		$sql = "INSERT INTO `user_role`(`user_id`,`role_id`,`created_at`,`updated_at`) VALUES(?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP) ";
		$stmt = $this->db->prepare($sql);

		foreach($user_ids as $key => $id) {
			$stmt->bind_param('ii', $id, $role_id);
			$stmt->execute();
		}

		if ($stmt->affected_rows >= 1) {
			return true;
		}

		return false;
	}

	public function detachRoleFromUser($user_ids, $role_id)
	{
		$sql = "DELETE FROM `user_role` WHERE `user_id` = ? AND `role_id` = ?";
		$stmt = $this->db->prepare($sql);

		foreach($user_ids as $key => $id) {
			$stmt->bind_param('ii', $id, $role_id);
			$stmt->execute();
		}

		if ($stmt->affected_rows >= 1) {
			return true;
		}

		return false;
	}

	public function detachRole($role_id)
	{
		$sql = "DELETE FROM `user_role` WHERE `role_id` = ?";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $role_id);
		$stmt->execute();

		if ($stmt->affected_rows >= 1) {
			return true;
		}

		return false;
	}

	public function detachUserRole($user_id)
	{
		$sql = "DELETE FROM `user_role` WHERE `user_id` = ?";
		$stmt = $this->db->prepare($sql);

		$stmt->bind_param('i', $user_id);
		$stmt->execute();

		if ($stmt->affected_rows >= 1) {
			return true;
		}

		return false;
	}

	public function getUserByRole($role_id)
	{
		$out = [];

		$sql = "SELECT 
					`user`.`user_id`,
					`user`.`username`,
					`user`.`email`,
					`user_role`.`role_id`
				FROM
					`user_role`
				LEFT JOIN `user` ON `user_role`.`user_id` = `user`.`user_id`
				WHERE
					`user_role`.`role_id` = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('i', $role_id);
		$stmt->bind_result($user_id,$username,$email,$role_id);

		$stmt->execute();

		while ($stmt->fetch()) {
			$out[] = [
				'user_id' => $user_id,
				'username' => $username,
				'email' => $email,
				'role_id' => $role_id
			];
		}

		return $out;
	}

	public function getRoleByName($name)
	{
		$out = null;

		$name = strtolower($name);
		$sql = "SELECT `role_id`,`role_name` FROM `role` WHERE `role_name` = ? LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s', $name);
		$stmt->bind_result($role_id, $role_name);

		$stmt->execute();

		if ($stmt->fetch()) {
			$out = [
				'role_id' => $role_id,
				'role_name' => $role_name
			];
		}

		$stmt->close();

		return $out;
	}
	
}
?>
