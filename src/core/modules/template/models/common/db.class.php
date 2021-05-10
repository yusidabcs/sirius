<?php
namespace iow\modules\xMODULEx\models\common;

/**
 * Final xMODULEx db class.
 *
 * @final
 * @package		xMODULEx
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee xDATEx
 */
final class db extends \iow\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
	
}