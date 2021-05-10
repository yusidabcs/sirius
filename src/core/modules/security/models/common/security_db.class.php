<?php
namespace core\modules\security\models\common;

/**
 * Final security_db class.
 * 
 * @final
 */
final class security_db extends \core\app\classes\module_base\module_db {
	
	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}

    /**
     * set new reset password code to database
     *
     * @param $resetCode
     * @param $email
     */
    public function setResetCode($resetCode, $email)
	{
		//delete anything > 4 hours
		$this->_deleteOldResetCodes();
		
		//insert this one in
		$qry = "INSERT INTO  
					`security_reset`  
				SET  
					`reset_code` =  ?,
					`email` = ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$resetCode,$email);
		$stmt->execute();
		$stmt->close();
		
		return;
	}

    /**
     * return email address by reset code
     * @param $resetCode
     * @return bool
     */
    public function getEmailFromResetCode($resetCode)
	{
		$email = false;
		
		//delete anything > 4 hours
		$this->_deleteOldResetCodes();
		
		//insert this one in
		$qry = "SELECT
					`email`
				FROM  
					`security_reset`  
				WHERE  
					`reset_code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$resetCode);
		$stmt->bind_result($email);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		return $email;
	}

    /**
     * return email address by checksum
     * @param $checksum
     * @return bool
     */
    public function getEmailFromCheckSum($checksum)
	{
		$email = false;
		
		//delete anything > 4 hours
		$this->_deleteOldResetCodes();
		
		//insert this one in
		$qry = "SELECT
					`email`
				FROM  
					`security_reset`  
				WHERE  
					`checksum` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("s",$checksum);
		$stmt->bind_result($email);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		return $email;
	}

    /**
     * update checksum by reset code
     *
     * @param $resetCode
     * @return string
     */
    public function setCheckSum($resetCode)
	{
		$out = '';
		
		$random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
		$checksum = md5($random_string);
		
		$qry = "UPDATE
					`security_reset`
				SET
					`checksum` = ?
				WHERE  
					`reset_code` =  ?
				";
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$checksum,$resetCode);
		$stmt->execute();
		if($stmt->affected_rows == 1)
		{
			$out = $checksum;
		}
		$stmt->close();
		
		return $out;
	}

    /**
     * delete old reset code from database by checksum
     * @param $checksum
     */
    public function deleteOldResetCodes($checksum)
	{
		$sql = "DELETE FROM
					`security_reset`
				WHERE
					`checksum` = '{$checksum}'
				";
		$this->db->query($sql);
		return;
	}
	
	private function _deleteOldResetCodes()
	{
		$sql = "DELETE FROM
					`security_reset`
				WHERE
					`created_on` < DATE_SUB(NOW(),INTERVAL 4 HOUR)
				";
		$this->db->query($sql);
		return;
	}
	
}
?>