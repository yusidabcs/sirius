<?php
namespace core\modules\profile\models\common;

/**
 * Final profile db class.
 *
 * @final
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 July 2017
 */
final class db extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
	
}