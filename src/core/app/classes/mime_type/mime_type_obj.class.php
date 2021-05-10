<?php
namespace core\app\classes\mime_type;

/**
 * mime_type_obj class.
 *
 * This is CACHED if you change it then it needs to be re-cached!
 * If there are operational websites you will have to redo every cache!
 *
 */
class mime_type_obj {
	
	private $_mime_data_a = array();

	/*
	 * Constructor
	 * load all the mime information from mime_type.csv
	 */
	public function __construct()
	{
		//read in mime_type.csv
		$row = 1;
		if (($handle = fopen(DIR_APP_CLASSES.'/mime_type/mime_type.csv', "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		    {
			    $this->_mime_data_a[] = array( 'name' => $data[0], 'mime_type' => $data[1], 'extension' => $data[2]);
		    }
		    fclose($handle);
		}
		return;
	}	
	
	//get name from type
	public function getNameFromMimeType($mime_type)
	{
		$out = '';
		foreach($this->_mime_data_a as $mime)
		{
			if($mime['mime_type'] == $mime_type)
			{
				$out = $mime['name'];
			}
		}
		return $out;
	}
	
	//get ext from type
	public function getExtFromMimeType($mime_type)
	{
		$out = '';
		foreach($this->_mime_data_a as $mime)
		{
			if($mime['mime_type'] == $mime_type)
			{
				if($mime['extension'] == 'jpeg')
				{
					$out = 'jpg';
				} else {
					$out = $mime['extension'];
				}
			}
		}
		return $out;
	}
	
	//get type from ext
	public function getMimeTypeFromExtension($extension)
	{
		$out = '';
		foreach($this->_mime_data_a as $mime)
		{
			if($mime['extension'] == $extension)
			{
				$out = $mime['mime_type'];
			}
		}
		return $out;
	}
	
}

?>