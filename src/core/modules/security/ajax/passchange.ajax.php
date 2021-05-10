<?php
namespace core\modules\security\ajax;

/**
 * Final pass class.
 * 
 * An ajax extension that allows USERS to change their own passwords.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 11 December 2015
 */
final class passchange extends \core\app\classes\module_base\module_ajax {
	
	public function run()
	{
		$checksum = isset($_POST['checksum']) ? $_POST['checksum'] : false;
		$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : false;
		$password = isset($_POST['new_pas']) ? $_POST['new_pas'] : false;
	
		if($checksum && $user_id && $password)
		{
			//include security db to check the checksum
			$security_db_ns = NS_MODULES.'\\security\\models\\common\\security_db';
			$security_db = new $security_db_ns;

			if($email = $security_db->getEmailFromCheckSum($checksum))
			{
				//get user db to do a double check
                $user_db_ns = NS_MODULES.'\\user\\models\\common\\user_db';
				$user_db = new $user_db_ns;
				$userInfo = $user_db->getUserInfoFromEmail($email);
				
				if($user_id == $userInfo['user_id'])
				{
					//include common classes
                    $user_common_ns = NS_MODULES.'\\user\\models\\common\\user_common';
					$user_common = new $user_common_ns;
					
					if($user_common->valueOk('password',$password))
					{
						//make the md5
						$md5_new = md5($password.$this->system_register->site_info('salt'));
						
						//update
						if($user_db->updateUserPassword($user_id,$md5_new))
						{
							//delete the ability to use this reset code
							$security_db->deleteOldResetCodes($checksum);
							
							//return back the good news
							$out['good'] = true;
						} else {
							//ok so it did not pass muster
							$out['good'] = false;
							$out['note'] = 'System error .. Password update failed.';
						}
					} else {
						//ok so it did not pass muster
						$out['good'] = false;
						$out['note'] = 'Password must have 6 or more characters.';
					} 
					
					return json_encode($out);
				} else {
					$out = 'Truly?';
				}
			} else {
				$out = 'How did that happen?';
			}
		} else {
			$out = 'Hmmm, very strange hey ... but what to do';
		}
		return $out;
	}
}
?>