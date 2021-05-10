<?php
namespace core\app\classes\robots;

/**
 * robots class.
 * a class that have function to check if robots.txt is writeable and update the robot.txt file
 *
 * @final
 * @package 	robots
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 August 2019
 */
final class robots {
		
	private $_robotFile = null;
	
	public function __construct()
	{
		//set the file
		$robotFile = DIR_BASE.'/sitemaps/robots.txt';

		//check if the file is write able, if not trow error
		if(is_writable($robotFile))
		{
			$this->_robotFile = $robotFile;
		} else {
			$msg = 'The robots file is not writable!';
			throw new \RuntimeException($msg);
		}
		return;
	}
	
	public function updateRobotFile()
	{
		//make the robots text
        $robots = '';
        $robots .= "# /robots.txt for ".HTTP_TYPE.SITE_WWW."\n";
        //$robots .= empty($this->_email) ? "# mail admin@iow.com.au\n" : "# mail $this->_email\n";
        $robots .= "sitemap: ".HTTP_TYPE.SITE_WWW."/sitemap.xml\n";
        $robots .="\n"; //intentionally blank
        
        //all the rest 
        $robots .= <<<EOI
#Allow All Other Crawlers
User-agent: *
Disallow: /admin/
Disallow: /control/
Disallow: /core/
Disallow: /lib/
Disallow: /local/
Disallow: /secure/
Disallow: /security/
Disallow: /sitemaps/

EOI;

        if(file_put_contents($this->_robotFile,$robots))
        {
	        $out = "Robot TXT Updated!";
        } else {
            $msg = 'Unable to output the robots text file!';
			throw new \RuntimeException($msg);
        }
        
        return $out;
	}
	
}
?>