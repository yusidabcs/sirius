<?php
namespace core\modules\send_email\models\edit_template;

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

	protected $model_name = 'edit_template';
	
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
		$this->authorize();
		//save the new email template
		if(!empty($_POST['template_content']))
		{
			file_put_contents(DIR_MODULES.'/send_email/views/template/' . $_POST['template_name'] . '.html', $_POST['template_content']);
        }
        
        if($_POST['next'] == 'home')
        {
            $this->redirect = $this->baseURL.'/send-email/edit_template';
        } else {
            $this->redirect = $this->baseURL.'/edit_template/' . $_POST['template_name'];
        }
		
		return;
	}
}
?>