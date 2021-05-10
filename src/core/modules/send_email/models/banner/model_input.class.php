<?php
namespace core\modules\send_email\models\banner;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'banner';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		//save the banner image
		if(!empty($_POST['banner_base64']))
		{
	        $data = $_POST['banner_base64'];
	       
	        list($type, $data) = explode(';', $data);
	        
	        list(,$data) = explode(',', $data);
	        
	        $data = base64_decode($data);
	        
			if(!empty($data))
			{
				file_put_contents(DIR_LOCAL_UPLOADS.'/send_email/banner.png', $data);
			}
	    }
	    
	    //save the new css
		if(!empty($_POST['css_info']))
		{
			file_put_contents(DIR_LOCAL_UPLOADS.'/send_email/email.css', trim($_POST['css_info']));
		}
		
		return;
	}
}
?>