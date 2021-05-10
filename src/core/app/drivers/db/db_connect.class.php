<?php
namespace core\app\drivers\db;

/**
 * Final db_connect class.
 * 
 * @final
 * @package 	db
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
final class db_connect {
	
	private $_connection;

	public function __construct($loc)
	{
        if(is_readable(DIR_SECURE_INI.'/db_config.ini'))
        {
	        $db_ini_a = parse_ini_file(DIR_SECURE_INI.'/db_config.ini',true);
	        
	        if( isset($db_ini_a[$loc]) )
	        {
		        //load the ini file
	            $server = $db_ini_a[$loc]['SERV'];
	            $user = $db_ini_a[$loc]['USER'];
	            $password = $db_ini_a[$loc]['PASS'];
	            $database = $db_ini_a[$loc]['DATA'];
	            
	            unset($db_ini_a);
	        } else {
		         $msg = "The database configuration information file (secure/ini/db_config.ini) does not have information for {$$loc}!";
				 throw new \RuntimeException($msg);
	        }
            
        } else {
            $msg = 'The database configuration information file (secure/ini/db_config.ini) can not be read!';
            throw new \RuntimeException($msg);
        }
        
		//Connect to the database suppress the error (if any)
		$this->_connection = @new \mysqli($server,$user,$password,$database);
    
		//Check for issues connecting to the database and throw exception if ther is
		if ($this->_connection->connect_error) 
		{
			$msg = "The database configuration information is incorrect - check the INI file!";
			throw new \RuntimeException($msg);
		}
		
		return;
	}

	public function __destruct()
	{
		$this->_connection->close();
	}

	public function __clone()
	{
		$msg = 'Cloning instances of this class is forbidden.';
        trigger_error($msg, E_USER_ERROR);
	}

	public function prepare($sql)
    {
		if(!$stmt = $this->_connection->prepare($sql))
		{
		    $msg = "Prepare failed: {$this->_connection->errno} - {$this->_connection->error}";
		    throw new \RuntimeException($msg);
		}
		return $stmt;
	}
	
	public function query($qry)
    {
		if(!$result = $this->_connection->query($qry))
		{
		    $msg = "Query failed: {$this->_connection->errno} - {$this->_connection->error}";
		    throw new \RuntimeException($msg);
		}
		return $result;
	}
	
	public function query_array($qry)
    {
		if(!$result = $this->_connection->query($qry))
		{
		    $msg = "Query array failed: {$this->_connection->errno} - {$this->_connection->error}";
		    throw new \RuntimeException($msg);
		}
		$out = [];
		while($row = $result->fetch_assoc())
		{
			$out[] = $row;
		}
		
		$result->close();
		
		return $out;
	}
	
	public function tableExists($table)
	{
		$out = false;
		if(mysqli_num_rows($this->query("SHOW TABLES LIKE '".$table."' "))==1)
		{
			$out = true;
		}
		return $out;
	}
	
	public function character_set_name()
	{
		return $this->_connection->character_set_name();
	}
	
	public function set_charset($charset)
	{
		$acceptable_charsets = array('latin1','utf8');
		if(in_array($charset, $acceptable_charsets))
		{
			return $this->_connection->set_charset($charset);
		} else {
			$msg = "Not a listed Character Set ... please add to the db driver";
			throw new \RuntimeException($msg);
		}		
	}
		
	public function affected_rows()
	{
		return $this->_connection->affected_rows;
	}
	
	public function insert_id()
	{
		return $this->_connection->insert_id;
	}
	
	public function commitOff()
	{
		$this->_connection->autocommit(false);
		return;
	}
	
	public function commit()
	{
		$this->_connection->commit();
		return;
	}
	
	public function rollback()
	{
		$this->_connection->rollback();
		return;
	}
	
	public function commitOn()
	{
		$this->_connection->autocommit(true);
		return;
	}
	
	public function info()
	{
		return $this->_connection->info;
	}
		
}
?>