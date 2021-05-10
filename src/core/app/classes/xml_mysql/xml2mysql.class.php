<?php
namespace core\app\classes\xml_mysql;

use Exception;

/**
 * xml2sql class.
 *
 * A class that takes an xml file and updates sql database tables by 
 * inserting a new table or alterning the existing one where required.
 * 
 * @abstract
 * @package 	xml_mysql
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */

class xml2mysql {

	//private variables
	private $filename; //full description of the file to save xml too
	private $log = '';

	//constructor
	public function __construct()
	{
		return;
	}
	
	public function processXmlFile($full_filename)
	{
		//set all the variables
		$this->setFilename($full_filename);
		$this->updateDatabase();
		return;
	}
	
	public function getSqlLog()
	{
		$out = $this->log;
		$this->log = '';
		return $out;
	}
	
	private function setFilename($full_filename)
	{
		//check to see if the file can be written
		if(!is_readable($full_filename))
		{
			$msg = "The XML File $full_filename is not readable!";
			throw new \RuntimeException($msg);
			exit;
		}
		
		$this->filename = $full_filename;
		return;
	}

	public function updateDatabase()
	{
		$this->log .= "";
		$queries = array();

		$tbl_fld_temp_data = array();

		//load new XML DOM and setup basic values
		$doc = new \DOMDocument;
		$doc->load($this->filename);
		
		//base xml
		$root = $doc->firstChild;
		$charset = $root->getAttribute("CHARSET");
		$engine = $root->getAttribute("ENGINE");

	    $xml_tbl =  $root->getElementsByTagName("TABLE")->item(0);
	    
		$xml_tbl_comments = "";
		
		$xml_tbl_name = $xml_tbl->getAttribute("NAME");
		
		if($xml_tbl->getAttribute("LOCATION") == 'gin' || $xml_tbl->getAttribute("LOCATION") == 'local')
		{
			$xml_db_location = 'local' ;
		} else {
			$msg = "Unable to set database location in xml2sql class because LOCATION attribute in xml is not acceptable.";
			throw new \RuntimeException($msg);
			exit;
		}
		
		//connect to the database
		$db_ns = NS_APP_DRIVERS.'\\db\\db_mysql';
		$db = $db_ns::getInstance($xml_db_location);
			
		//get the set of field names
		$xml_flds = $xml_tbl->getElementsByTagName( "FIELD" );
		
		//get the SQL to create the table if needed
		$xml_tbl_struct = $xml_tbl->getElementsByTagName("STRUCTURE")->item(0)->nodeValue;
	
		if($db->tableExists($xml_tbl_name))
		{
			//get the fields and info
			$res = $db->query('SHOW FULL COLUMNS FROM `'.$xml_tbl_name.'` ' );
			if($res && (mysqli_num_rows($res)>0))
			{
				while ($fld_row = mysqli_fetch_assoc($res)) 
				{
					$tbl_fields[] = $fld_row;
				}
			} else {
				$tbl_fields[] = array();
			}

			//set position to first
			$pos = 'FIRST';
		
			//Array to hold all xml fields names to compare with the existing db at the end
			//$xml_fld_names = array();
			foreach ($xml_flds as $xml_fld)
			{
				//track what has changed
				$tbl_fld_type_changed = array();
				
				//zero field info
				$xml_fld_key = null;
				$xml_fld_extra = null;
				$xml_fld_nul = null;
				$xml_fld_default = null;
				$xml_fld_collation = null;
				$xml_fld_comments = "";
				$str_fld_pos = $pos;
	
				//setup the xml details
				$xml_fld_name = $xml_fld->getAttribute("NAME");
	
				$xml_fld_type = $xml_fld->getElementsByTagName("TYPE")->item(0)->nodeValue;
	
				if(is_object($xml_fld->getElementsByTagName("COLLATION")) && is_object( $xml_fld->getElementsByTagName("COLLATION")->item(0)))
				{
					$xml_fld_collation = $xml_fld->getElementsByTagName("COLLATION")->item(0)->nodeValue;
				}
			
				if(is_object($xml_fld->getElementsByTagName("NULL")) && is_object( $xml_fld->getElementsByTagName("NULL")->item(0)))
				{
					$xml_fld_nul = $xml_fld->getElementsByTagName("NULL")->item(0)->nodeValue;
				}
				
				if(is_object($xml_fld->getElementsByTagName("KEY")) && is_object( $xml_fld->getElementsByTagName("KEY")->item(0)))
				{
					$xml_fld_key = $xml_fld->getElementsByTagName("KEY")->item(0)->nodeValue;
				}
				
				if(is_object($xml_fld->getElementsByTagName("DEFAULT")) && is_object( $xml_fld->getElementsByTagName("DEFAULT")->item(0)))
				{
					$xml_fld_default=$xml_fld->getElementsByTagName("DEFAULT")->item(0)->nodeValue;
					
					//fix up for CURRENT_TIMESTAMP
					if($xml_fld_default != 'CURRENT_TIMESTAMP')
					{
						$xml_fld_default = "'$xml_fld_default'";
					}
				}
				
				if(is_object($xml_fld->getElementsByTagName("EXTRA")) && is_object( $xml_fld->getElementsByTagName("EXTRA")->item(0)))
				{
					$xml_fld_extra = $xml_fld->getElementsByTagName("EXTRA")->item(0)->nodeValue;
				}
				
				if(is_object($xml_fld->getElementsByTagName("COMMENT")) && is_object( $xml_fld->getElementsByTagName("COMMENT")->item(0)))
				{
					$xml_fld_comments = $xml_fld->getElementsByTagName("COMMENT")->item(0)->nodeValue;
				}
		
				$found = false;
		
				//finding if the field already exists
				foreach ($tbl_fields as $key => $tbl_field)
				{
					if($tbl_field['Field'] == $xml_fld_name)
					{
						//the field is here so set some changes if needed
						
						//if index old and new are the same don't attempt to alter it
						if($tbl_field['Key'] == $xml_fld_key) 
						{
							$str_fld_key = '';
							$str_index_drop = '';
						} else {
							
							//set new index key (only used if different)
							switch ($xml_fld_key)
							{
								case 'PRI':
										$str_fld_key = ", ADD PRIMARY KEY (`$xml_fld_name`)" ;
									break;
					
								case 'UNI':
										$str_fld_key = ", ADD UNIQUE (`$xml_fld_name`)";
									break;
				
								case 'MUL':
										$str_fld_key = ", ADD INDEX (`$xml_fld_name`)";
									break;
					
								default:
										$str_fld_key = '';
									break;
							}
		
							//drop old index (only used if new is different)
							switch ($tbl_field['Key'])
							{
								case 'PRI':
										$str_index_drop = ", DROP PRIMARY KEY";
									break;
					
								case 'UNI':
										$str_index_drop = ", DROP INDEX `$xml_fld_name`";
									break;
					
								case 'MUL':
										$str_index_drop = ", DROP INDEX `$xml_fld_name`";
									break;
					
								default:
										$str_index_drop = '';
									break;
							}
						}
						
						//set nul
						switch ($xml_fld_nul)
						{
							case 'NO':
									$str_fld_def_nul = ($xml_fld_default !== null) ? "NOT NULL DEFAULT $xml_fld_default" : "NOT NULL";
								break;
				
							default:
									$str_fld_def_nul = $xml_fld_default ? "NULL DEFAULT $xml_fld_default" : 'NULL';
								break;
						}
				
						//set up the collation variable if needed
						$str_fld_col = empty($xml_fld_collation) ? '' : "COLLATE $xml_fld_collation";
				
						//set up the comment variable if needed
						$str_fld_com = empty($xml_fld_comments) ? '' : "COMMENT '$xml_fld_comments'";
						
						//ok it was found so now need to go any further
						$found = true;
						
						//remove the field
						unset($tbl_fields[$key]);
						break;
					}
				}
				
				//if this field was found 
				if($found)
				{
					//default values for table field
					if(!isset($tbl_field['Collation']))$tbl_field['Collation']=null;
					if(!isset($tbl_field['Null']))$tbl_field['Null']=null;
					if(!isset($tbl_field['Key']))$tbl_field['Key']=null;
					if(!isset($tbl_field['Default']))$tbl_field['Default']=null;
					if(!isset($tbl_field['Extra']))$tbl_field['Extra']=null;
					if(!isset($tbl_field['Comment']))$tbl_field['Comment']="";
					
					//fix up Default
					if($tbl_field['Default'] != null && $tbl_field['Default'] != 'CURRENT_TIMESTAMP')
					{
						$tbl_field['Default'] = "'{$tbl_field['Default']}'";
					}
		
					//run comparison and if something is different then we have to fix it
					if( ($tbl_field['Type'] != $xml_fld_type) ||
						($tbl_field['Collation'] != $xml_fld_collation) ||
						($tbl_field['Null'] != $xml_fld_nul) ||
						($tbl_field['Key'] != $xml_fld_key) ||
						($tbl_field['Default'] != $xml_fld_default)  ||
						($tbl_field['Extra'] != $xml_fld_extra) ||
						($tbl_field['Comment'] != $xml_fld_comments))
					{
						$this->log .= "\nChanging field $xml_fld_name in table $xml_tbl_name\n";
		
						if($tbl_field['Type'] != $xml_fld_type) $this->log .= "Type - table: {$tbl_field['Type']}  != field: $xml_fld_type \n";
						if($tbl_field['Collation'] != $xml_fld_collation) $this->log .= "Collation - table: {$tbl_field['Collation']}  != field: $xml_fld_collation \n";
						if($tbl_field['Null'] != $xml_fld_nul) $this->log .= "Null - table: {$tbl_field['Null']}  != field: $xml_fld_nul \n";
						if($tbl_field['Key'] != $xml_fld_key) $this->log .= "Key - table: {$tbl_field['Key']}  != field: $xml_fld_key \n";
						if($tbl_field['Default'] != $xml_fld_default) $this->log .= "Default - table: {$tbl_field['Default']}  != field: $xml_fld_default \n";
						if($tbl_field['Extra'] != $xml_fld_extra) $this->log .= "Extra - table: {$tbl_field['Extra']}  != field: $xml_fld_extra \n";
						if($tbl_field['Comment'] != $xml_fld_comments) $this->log .= "Comment - table: {$tbl_field['Comment']}  != field: $xml_fld_comments \n";
		
						//remove key change if not needed
						$query = "ALTER TABLE `$xml_tbl_name` CHANGE `$xml_fld_name` `$xml_fld_name` $xml_fld_type $str_fld_col $str_fld_def_nul $xml_fld_extra $str_fld_com $str_fld_pos $str_index_drop $str_fld_key ;";
						
						$this->log .= "Query: \n".$query."\n\n";
		
						$queries[] = $query;
					}
					
				} else {
	
					$this->log .= "Adding new field $xml_fld_name in table $xml_tbl_name\n";
					
					//if it is an indexed field you need to add it
					switch ($xml_fld_key)
					{
						case 'PRI':
								$str_fld_key = ", ADD PRIMARY KEY (`$xml_fld_name`)" ;
							break;
			
						case 'UNI':
								$str_fld_key = ", ADD UNIQUE (`$xml_fld_name`)";
							break;
		
						case 'MUL':
								$str_fld_key = ", ADD INDEX (`$xml_fld_name`)";
							break;
			
						default:
								$str_fld_key = '';
							break;
					}
					
					//set up the rest of the stuff from the XML file
					
					//set nul
					switch ($xml_fld_nul)
					{
						case 'NO':
								$str_fld_def_nul = ($xml_fld_default !== null) ? "NOT NULL DEFAULT $xml_fld_default" : "NOT NULL";
							break;
			
						default:
								$str_fld_def_nul = $xml_fld_default ? "NULL DEFAULT $xml_fld_default" : 'NULL';
							break;
					}
			
					//set up the collation variable if needed
					$str_fld_col = empty($xml_fld_collation) ? '' : "COLLATE $xml_fld_collation";
			
					//set up the comment variable if needed
					$str_fld_com = empty($xml_fld_comments) ? '' : "COMMENT '$xml_fld_comments'";
					
					//echo "insert as new field in same table ".$xml_fld_name;
					$query = "ALTER TABLE `$xml_tbl_name` ADD `$xml_fld_name` $xml_fld_type $str_fld_col $str_fld_def_nul $xml_fld_extra $str_fld_pos $str_fld_key ;";
					
					$this->log .= "Query: \n".$query."\n\n";
		
					$queries[] = $query;
				}
				
				$pos = "AFTER `$xml_fld_name`";
			}
			
			if(count($tbl_fields)>0)
			{
				foreach($tbl_fields as $tbl_field)
				{
					//delete columns no longer being used
					$this->log .= "Deleting redundant field {$tbl_field['Field']} in table $xml_tbl_name \n";
					$query = "ALTER TABLE `{$xml_tbl_name}` DROP `{$tbl_field['Field']}`";
					$this->log .= "Query: \n".$query."\n\n";
					$queries[] = $query;
				}
			}
			
		} else {
			//create the table because it does not exist
			$this->log .= " \nCreating table ".$xml_tbl_name." \n";
			$this->log .= "Query: \n".$xml_tbl_struct."\n\n";
			$queries[] = $xml_tbl_struct;
			//rest the table specifically for incrimentals
			$queries[] = 'TRUNCATE TABLE  `'.$xml_tbl_name.'` ';
		}
		
		//apply all the changes to the database
	  	if(count($queries)>0)
	  	{
	  		$db->commitOff();
			  
			try {
				foreach ($queries as $qry)
				{
					$db->query($qry);
				}
			}catch(Exception $e){
				var_dump($queries);
				exit();
			}
	  		$db->commitOn();
	  	}

	  	return;
	}
}
?>