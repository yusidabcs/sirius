<?php
namespace core\modules\user\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 January 2015
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'user';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
	protected function runSpecialSetup()
	{
		$log = '';
			    
		//sticks in the system administrator user and password if it is not already set.
		$msg = '';
		
		$db = \core\app\drivers\db\db_mysql::getInstance('local');
		$system_register = \core\app\classes\system_register\system_register::getInstance();
		
		$qry =	"SELECT `username` FROM `user` WHERE `user_id` = 1";
		$stmt = $db->prepare($qry);
		$stmt->bind_result($username);
		$stmt->execute();
		if($stmt->fetch() != 1)
		{
			$qry =	"INSERT INTO `user`
						SET 
							`user_id` = 1,
							`username` = 'System Administrator', 
							`email` = 'sysadmin@iow.com.au', 
							`password` = ?, 
							`security_level_id` ='SYSADMIN', 
							`group_id` = 'IOW', 
							`created_on` = CURRENT_TIMESTAMP, 
							`created_by` = 1, 
							`modified_on`= CURRENT_TIMESTAMP, 
							`modified_by`= 1, 
							`status` = 1
					";
					
			$stmt2 = $db->prepare($qry);
			$password = $system_register->site_info('PASSWORD');
			$stmt2->bind_param("s",$password);
			
			$stmt2->execute();
			if($stmt2->affected_rows != 1)
			{
				$msg = 'Could not add SYSADMIN!';
			}
			$stmt2->close();
			
			$log .= "<h2>Setting up System Administrator in User Table</h2>\n";
			$log .= "<p>The System Administrator user was set up in the user table with the same password as sysadmin.</p>\n";

		}
		$stmt->close();
		
		if($msg)
		{
			throw new \RuntimeException($msg); 
		}
		
		return $log;
	}
	
}
?>