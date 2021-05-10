<?php
namespace core\modules\user\ajax;

/**
 * Final test class.
 * 
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 07 February 2015
 */
final class test extends \core\app\classes\module_base\module_ajax {

	public function run()
	{
		if(isset($_POST['nam']))
		{
			$user_common_common_ns = NS_MODULES.'\\user\\models\\common\\user_common';
			$user_common = new $user_common_common_ns();
			
			$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
			$user_db = new $user_db_common_ns();
			
			//there should be 2 values nam (name of the field) and val (value of the field)
			$fieldName = trim($_POST['nam']);
			$value = trim($_POST['val']);
			
			if($fieldName == 'username')
			{
				if($user_common->valueOk('username',$value))
				{
					if($user_db->checkUserNameInUse($value))
					{
						$out['good'] = false;
						$out['note'] = 'Duplicate Name!';
					} else {
						$out['good'] = true;
						$out['reply'] = $value;
					}
				} else {
					$out['good'] = false;
					$out['note'] = 'Name can not be used!';
				}
				return json_encode($out);
			} 
			
			if($fieldName == 'email')
			{				
				if($user_common->valueOk('email',$value))
				{
					if($user_db->checkEmailInUse($value))
					{
						$out['good'] = false;
						$out['note'] = 'Duplicate Name!';
					} else {
						$out['good'] = true;
						$out['reply'] = $value;	
					}
					
				} else {
					$out['good'] = false;
					$out['note'] = 'Bad Email Address!';

				}
				
				return json_encode($out);
			}
			
			if($fieldName == 'password')
			{
				if($user_common->valueOk('password',$value))
				{
					$out['good'] = true;
					$out['reply'] = $value;	
				} else {
					$out['good'] = false;
					$out['note'] = 'Password must have 6 or more characters';
				} 
				
				return json_encode($out);
			} 
			
			if(empty($out))
			{
				$out = 'Singing in the rain';
			}
			
		} else {
		
			if( empty($_POST) )
			{
				$out = 'Hello World';
			} else {
				$out = 'Hmmm, very strange';
			}
		}
		
		return $out;
	}
		
}
?>