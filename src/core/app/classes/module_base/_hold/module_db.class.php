<?php
namespace iow\app\classes\module_base;

/**
 * Abstract module_db class.
 * 
 * @abstract
 * @package 	module_base
 *Ê@author		Martin O'DeeÊ<martin@iow.com.au>
 *Ê@copyright	Martin O'DeeÊ30 January 2015
 */
abstract class module_db {
	
	protected $db;
	protected $user_id; //the id of the user
	
	public function __construct($location)
	{	
		//instanciate the correct database
		$this->db = \iow\app\drivers\db\db_mysql::getInstance($location);
		$this->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ;
		return;
	}
	
	//!autocommit function
	
	public function commitOff()
	{
		$this->db->commitOff();
		return;
	}
	
	public function commit()
	{
		$this->db->commit();
		return;
	}
	
	public function rollback()
	{
		$this->db->rollback();
		return;
	}
	
	public function commitOn()
	{
		$this->db->commitOn();
		return;
	}

}
?>