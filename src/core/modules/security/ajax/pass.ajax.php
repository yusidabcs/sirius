<?php
namespace iow\modules\security\ajax;

/**
 * Final pass class.
 * 
 * An ajax extension that allows ADMIN users to change passwords of
 * any other user.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 11 December 2015
 */
final class pass extends \core\app\classes\module_base\module_ajax {
	
	public function run()
	{
		if(isset($_POST['user_id']))
		{
			//include common classes
			$user_common = new \iow\modules\user\models\common\user_common;
			
			//there should be 2 values nam (name of the field) and val (value of the field)
			$user_id = trim($_POST['user_id']);
			$new_password = trim($_POST['new_pas']);
			
			if($user_common->valueOk('password',$new_password))
			{
				//need the system register for salt and user db for update
				$user_db = new \iow\modules\user\models\common\user_db;
				
				//make the md5
				$md5_new = md5($new_password.$this->system_register->salt());
				
				//update
				if($user_db->updateUser($user_id,'password',$md5_new))
				{
					//return back the good news
					$out['good'] = true;
					$out['reply'] = $md5_new;
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
			$out = 'Hmmm, very strange hey';
		}
		return $out;
	}
}
?>