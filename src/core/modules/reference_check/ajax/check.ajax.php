<?php
namespace core\modules\reference_check\ajax;

/**
 * Final default class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 29 January 2017
 */
final class check extends \core\app\classes\module_base\module_ajax {
	
	public function __construct($fileOptions)
	{
		parent::__construct($fileOptions);
		return;	
	}
	
	public function run()
	{	
		$data = $_POST;

		$personal_db = new \core\modules\personal\models\common\db();
        $data['questions'] = $personal_db->getReferenceQuestions($data['question_type']);

		return $this->response($data);
	}
	
}
?>