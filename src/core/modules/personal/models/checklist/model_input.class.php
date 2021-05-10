<?php
namespace core\modules\personal\models\checklist;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'checklist';
	
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
		//if Session Resume Address_book_id is not set then we should not be here
		if(!isset($_SESSION['personal']['address_book_id']))
		{
			header('Location: '.$this->baseURL);
			exit();
		} else {
			$personal_id = $_SESSION['personal']['address_book_id'];
		}
		
		foreach($_POST as $checklist_type => $answer)
		{
						
			foreach($answer as $question_id => $value)
			{	
				$answer = isset($value['answer']) ? $value['answer'] : 'not specified' ;
				$text = empty($value['text']) || $value['answer'] != "yes" ? false : $value['text'];
				
				$answer_array[$question_id] = array( 'answer' => $answer, 'text' => $text );
			}
		}
		
		//stick the answers in the db
		$personal_db = new \core\modules\personal\models\common\db;
		$personal_db->putChecklist($personal_id,$checklist_type,$answer_array);
		
		$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'checks';
		return;
	}
}
?>