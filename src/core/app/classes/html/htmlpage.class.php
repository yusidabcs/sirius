<?php

namespace core\app\classes\html;

class htmlpage {

    private $_htmlOutput = '';
    private $_trace = false;
	
	public function __construct($htmlNumber = 404)
	{
		//override if there is a local file
        $local_error_file = DIR_LOCAL_HTML_ERROR.'/'.$htmlNumber.'.php';
        
        if(is_file($local_error_file))
        {
	        include $local_error_file; //you just do a php file with $this->_htmlOutput = <<<EDO.... and that is it
        }
        
        if(empty($this->_htmlOutput))
        {
            switch ($htmlNumber)
            {
                case 403:
                    $this->setHtmlOuput_403();
                    break;
                case 404:
                    $this->setHtmlOuput_404();
                    break;
                case 999:
                    $this->setHtmlOuput_999();
                    break;
                default:
                   $this->setHtmlOuput_404();
            }
        }
        
        echo $this->_htmlOutput;
        exit(); //we are done now!
	}
    
    private function setHtmlOuput_403()
    {
        $this->_htmlOutput = <<<EOO
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
    <title>403 Forbidden</title>
</head>
<body>
    <h1>Forbidden</h1>
	<p>The requested URL {$_SERVER['REQUEST_URI']} is protected or private.</p>
</body>
</html>
EOO;
    }
    
    private function setHtmlOuput_404()
    {
        $this->_htmlOutput = <<<EOO
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
    <title>404 Not Found</title>
</head>
<body>
    <h1>Not Found</h1>
	<p>The requested URL {$_SERVER['REQUEST_URI']} was not found on this server.</p>
</body>
</html>
EOO;
    }
    
    private function setHtmlOuput_999()
    {
        $this->_htmlOutput = <<<EOO
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
    <title>Website Offline</title>
</head>
<body>
    <h1>Website Currently Offline</h1>
	<p>This website, <strong>{$_SERVER['SERVER_NAME']}</strong>, is currently offline until further notice.</p>
	<p>Contact <a href="https://www.iow.com.au/">IOW Pty Ltd</a> for more information.</p>
	<p>&nbsp;</p>
	<hr />
	<p><a href="/admin/">Admin</a></p>
</body>
</html>
EOO;
    }
    
    public function getHtmlOutput()
    {
        return $this->_htmlOutput;
    }
    
}
?>