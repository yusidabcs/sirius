<?php

namespace core\app\classes\html;

class htmlmsg {

    private $_htmlOutput;
    private $_title = "No Error Message";
    private $_message = "HTML Messages was called for output but output was blank!";
    private $_trace = false;
	
	public function __construct(\Exception $e, $debug)
	{
		//make sure debug is a booleen then set it globally for this function
		settype($debug,'boolean');
		$this->_debug = $debug;
		 
		//different types of exceptions produce different outputs
        if($e instanceof \RuntimeException)
        {
            $this->setRuntimeOutput($e);
        } else {
            $this->setGeneralOutput($e);
        }
        
        $this->setHtmlOuput();
        
        return;
	}
    
    private function setRuntimeOutput($e)
    {
        $this->_title = "Runtime Error";
        $this->_message = $e->getMessage();
        $this->_trace = $e->getTraceAsString();
        return;
    }
    
    private function setGeneralOutput($e)
    {
        $this->_title = "Error";
        $this->_message = $e->getMessage();
		if($this->_debug)
		{
			$this->_trace = $e->getTraceAsString();
		} else {
			$this->_trace = false;
		}
        return;
    }
    
    private function setHtmlOuput()
    {   
        if( $this->_debug && $this->_trace)
        {
                $this->_htmlOutput = <<<EOO
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
    <title>{$this->_title} Error</title>
</head>
<body>
    <h1>{$this->_title} Detected</h1>
    <p>{$this->_message}</p>
    <pre>
{$this->_trace}
    </pre>
</body>
</html>
EOO;
        } else{
            $this->_htmlOutput = <<<EOO
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
    <title>{$this->_title}</title>
</head>
<body>
    <h1>{$this->_title} Detected</h1>
    <p>{$this->_message}</p>
</body>
</html>
EOO;
        }
    }
    
    public function getHtmlOutput()
    {	
        return $this->_htmlOutput;
    }
}
?>