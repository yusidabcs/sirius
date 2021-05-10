<?php
namespace core\modules\personal\models\language;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 3 January 2018
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'language';
	
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
		
		//access db
		
		$resume_db = new \core\modules\personal\models\common\db;
		
		$resume_db->commitOff();	
		
		//process
		if(!empty($_POST['keep']))
		{
			foreach($_POST['keep'] as $languageCode_id)
			{
				$keep_key[$languageCode_id] = 1;
			}
			
			$keep = array_keys($keep_key);

			$resume_db->deleteLanguage($personal_id,$keep);
		}else{
			if(!empty($_POST['def_lang_count'])){
				//delete all
				$resume_db->deleteLanguage($personal_id,'');
			}
		}
		
		if(!empty($_POST['language']))
		{
			$language = $_POST['language'];

			if (is_array($language))
			{
				foreach($language as $key => $languageCode_id)
				{
					$level = $_POST['level'][$key];
					$experience = $_POST['experience'][$key];
					
					if(!empty($level))
					{
						$resume_db->putLanguage($personal_id,$languageCode_id,$level,$experience);
						$keep_key[$languageCode_id] = 1;
					}
				}
			}
		}
		
		
		
		$resume_db->commit();
		$resume_db->commitOn();
		$this->redirect = $this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'lang';

		return;
	}
	
}
?>