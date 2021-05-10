<?php
namespace core\app\classes\xml_mysql;

/**
 * mysql2xml class.
 *
 * A class that takes a mysql table and converts it to an XML file for installing or updating
 * on the client sites
 * 
 * @abstract
 * @package 	xml_mysql
 * @author	Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 August 2019
 */

class mysql2xml {

	//private variables
	private $db; //the current database connection
	private $db_location; //the current database location
	private $db_table; //the current table we are working on
	private $filename; //full description of the file to save xml too

	//constructor
	public function __construct()
	{
		return;
	}
	
	public function generateXMLfile($db_location,$db_table,$full_filename)
	{
		//set all the variables
		$this->setDatabaseLocation($db_location);
		$this->setTableId($db_table);
		$this->setFilename($full_filename);
		
		//writeteh XML file
		$this->writeXMLfile();
		
		return;
	}
	
	private function setDatabaseLocation($db_location)
	{
		$acceptable_locations = array('local','remote');
		
		if (!in_array($db_location,$acceptable_locations) )
		{
			$msg = "Unable to set database location to $db_location in mysql2xml class";
			throw new \RuntimeException($msg);
			exit;
		}
		
		if($this->db_location != $db_location)
		{
			$this->db = \iow\app\drivers\db\db_mysql::getInstance($db_location);
			$this->db_location = $db_location;
		}
		return;
	}
	
	private function setTableId($db_table)
	{
		if($this->db->tableExists($db_table))
		{
			$this->db_table = $db_table;
			return;
		} else {
			$msg = "Unable to set the table to $db_table on {$this->db_location} in mysql2xml class";
			throw new \RuntimeException($msg);
			exit;
		}
	}
	
	private function setFilename($full_filename)
	{
		//check to see if the file can be written
		if(!is_writable(dirname($full_filename)))
		{
			$msg = "File path given for $full_filename is not writable!";
			throw new \RuntimeException($msg);
			exit;
		}
		
		$this->filename = $full_filename;
		return;
	}
	
	private function writeXMLfile()
	{
		$file = fopen($this->filename,'w');
		fwrite($file,$this->schema2XML());
		fclose($file);
		return;
	}
	
	private function schema2XML()
	{
		$xml = null;
		
		//database information
		$db_info_res = $this->db->query("SHOW VARIABLES WHERE Variable_name LIKE 'character_set_database' OR Variable_name LIKE 'default_storage_engine' ");
		$db_row[0] = mysqli_fetch_assoc($db_info_res);
		$db_row[1] = mysqli_fetch_assoc($db_info_res);
		$char_set = $db_row[0]['Value'];
		$engine = $db_row[1]['Value'];
		
		//get the structure of the table
		$res = $this->db->query("SHOW CREATE TABLE ".$this->db_table);
  		if($res && (mysqli_num_rows($res)>0)){
  			$struct_row = mysqli_fetch_row($res);
  			$tbl_structure = $struct_row[1];
  		}
  		
		//start XML
		$xml = '<?xml version="1.0"?>'."\n";

		//set the charset and engine from the db information
		$xml .= '<SCHEMA CHARSET="'.$char_set.'" ENGINE="'.$engine.'">'."\n";
		
		//table name and location
		$xml .= '<TABLE NAME="'.$this->db_table.'" LOCATION="'.$this->db_location.'">'."\n";
		
		//use the full structure if the table does not exist
		$xml .= '<STRUCTURE>'.$tbl_structure.'</STRUCTURE>'."\n";
		
		//now turn the fields into XML
		$fld_result_set = $this->db->query("SHOW FULL COLUMNS FROM `$this->db_table` ");
		if($fld_result_set && (mysqli_num_rows($fld_result_set)>0))
		{
			while ($fld_row = mysqli_fetch_assoc($fld_result_set))
			{
				$xml .= '<FIELD NAME="'.$fld_row['Field'].'">'."\n";
				
					if($fld_row['Type'])
					{
						$xml .= '<TYPE>'.$fld_row['Type'].'</TYPE>'."\n";
					}
					
					if($fld_row['Collation'])
					{
						$xml .= '<COLLATION>'.$fld_row['Collation'].'</COLLATION>'."\n";
					}
					
					if($fld_row['Null'])
					{
						$xml .= '<NULL>'.$fld_row['Null'].'</NULL>'."\n";
					}
					
					if($fld_row['Key'])
					{
						$xml .='<KEY>'.$fld_row['Key'].'</KEY>'."\n";
					}
					
					if($fld_row['Default'] or ($fld_row['Default']=='0'))
					{
						$xml .= '<DEFAULT>'.$fld_row['Default'].'</DEFAULT>'."\n";
					}

					if($fld_row['Extra'])
					{
						$xml .=	'<EXTRA>'.$fld_row['Extra'].'</EXTRA>'."\n";
					}
					
					if($fld_row['Privileges'])
					{
						$xml .=	'<PRIVALEGES>'.$fld_row['Privileges'].'</PRIVALEGES>'."\n";
					}
					
					if($fld_row['Comment'])
					{
						$xml .=	'<COMMENT>'.$fld_row['Comment'].'</COMMENT>'."\n";
					}
					
				$xml .= '</FIELD>'."\n";
			}
		}
		$xml .= '</TABLE>'."\n";
		
		$xml .= '</SCHEMA>'."\n";
		
		return $xml;
	}
		
}
?>
