<?php
namespace core\modules\user\ajax;

/**
 * Final pass class.
 * 
 * An ajax extension that allows ADMIN users to change passwords of
 * any other user.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 December 2015
 */
final class pass extends \core\app\classes\module_base\module_ajax {
	
	public function run()
	{
		if(isset($_POST['user_id']))
		{
			$user_common_common_ns = NS_MODULES.'\\user\\models\\common\\user_common';
			$user_common = new $user_common_common_ns();
			
			$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
			$user_db = new $user_db_common_ns();
			
			//there should be 2 values nam (name of the field) and val (value of the field)
			$user_id = trim($_POST['user_id']);
			$new_password = trim($_POST['new_pas']);
			
			if($user_common->valueOk('password',$new_password))
			{
				//get the site salt
			    if(is_file(DIR_SECURE_INI.'/site_config.ini'))
			    {
			    	$site_ini_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');     
			    } elseif (is_file(DIR_APP_INI.'/site_config.ini')) {
			    	$site_ini_a = parse_ini_file(DIR_APP_INI.'/site_config.ini');     
			    } else {
			    	die('WOW is this really screwed up. No Site Config File at all!');
			    }
				$salt = $site_ini_a['SALT'];
				//make the md5
				$md5_new = md5($new_password.$salt);
				//update
				if($user_db->updateUser($user_id,'password',$md5_new))
				{
					//return back the good news
					$out['good'] = true;
					$out['reply'] = $md5_new;
                    $out['message'] = 'Successfully update user password';
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
			$out = 'Hmmm, very strange';
		}
		return $out;
	}
}
?>