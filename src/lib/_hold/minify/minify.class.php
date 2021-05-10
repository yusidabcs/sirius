<?php
namespace iow\lib\minify;

class minify {
	
	public function __construct()
	{	
		return;
	}

	public function makeFile($input_filename, $output_filename, $type)
	{
		//check the input file it is readable
		if(!is_readable($input_filename))
		{
			$msg = "File name {$input_filename} is not readable!";
			throw new \RuntimeException($msg);
		}

		//check the type of minify is in the list
		$acceptable_types = array('php','js','css');
		
		if(!in_array($type, $acceptable_types))
		{
			$msg = 'Bad minify type it must be php, js or css';
			throw new \RuntimeException($msg);
		}
		
		//check the output file can be written
		$path_parts = pathinfo($output_filename);
		
		if( is_dir($path_parts['dirname']) && is_writable($path_parts['dirname']) )
		{
			$minifyFunction = 'minify_'.$type;
			
			$output = $this->$minifyFunction($input_filename);
			
		    if (!$handle = fopen($output_filename, 'w')) 
		    {
		         $msg = "Cannot open file ($output_filename)";
		         throw new \RuntimeException($msg);
		    }
		
		    
		    if (fwrite($handle, $output) === FALSE) 
		    {
		        $msg = "Cannot write to file ($output_filename)";
		        throw new \RuntimeException($msg);
		    }
		    
		    fclose($handle);

		} else {
			$msg = "File name {$output_filename} is not writable!";
			throw new \RuntimeException($msg);
		}
		
		return;
	}
	
	private function minify_php($input_filename)
	{
		$buffer = php_strip_whitespace($input_filename);
		return $buffer;
	}
	
	private function minify_js($input_filename)
	{
		$buffer = file_get_contents($input_filename);
		
        /* remove comments */
        $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $buffer);
        /* remove other spaces before/after ) */
        $buffer = preg_replace(array('(( )+\))','(\)( )+)'), ')', $buffer);
        
        return $buffer;
	}
	
	private function minify_css($input_filename)
	{
		$buffer = file_get_contents($input_filename);
		
		 /* remove comments */
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n","\r","\n","\t",'  ','    ','     '), '', $buffer);
        /* remove other spaces before/after ; */
        $buffer = preg_replace(array('(( )+{)','({( )+)'), '{', $buffer);
        $buffer = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $buffer);
        $buffer = preg_replace(array('(;( )+)','(( )+;)'), ';', $buffer);
        return $buffer;
	}
	
}
	
?>