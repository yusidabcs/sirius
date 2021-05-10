<?php
namespace core\app\classes\ini;

final class write_ini
{
	
	public function __construct()
	{
		return;
	}
	
	public function write_php_ini($array, $file)
	{
		if(!$this->checkIniFile($file))
		{
			return false;
		}
		
	    $res = array();
	    foreach($array as $key => $val)
	    {
	        if(is_array($val))
	        {
	            $res[] = "[$key]";
	            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
	        }
	        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
	    }
	    $this->safefilerewrite($file, implode("\n", $res));
	    
	    return true;
	}
	
	private function checkIniFile($file)
	{
		if(is_writable($file) || is_writable(dirname($file)))
		{
			return true;
		}
		return false;
	}
	
	private function safefilerewrite($fileName, $dataToSave)
	{   if ($fp = fopen($fileName, 'w'))
	    {
	        $startTime = microtime();
	        do
	        {            
		        $canWrite = flock($fp, LOCK_EX);
		        // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
		        if(!$canWrite) usleep(round(rand(0, 100)*1000));
		        
	        } while ((!$canWrite)and((microtime()-$startTime) < 1000));
	
	        //file was locked so now we can store information
	        if ($canWrite)
	        {            
		        fwrite($fp, $dataToSave);
	            flock($fp, LOCK_UN);
	        }
	        fclose($fp);
	    }
	}
}	
	
?>