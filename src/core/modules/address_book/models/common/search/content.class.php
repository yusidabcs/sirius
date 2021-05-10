<?php
Namespace core\modules\address_book\models\common\search;
	
abstract class content extends \core\modules\address_book\models\common\base_content
{	
	protected $contentName = ''; //the name of the actual model being used
	protected $contentValue; //the array holding the content values
	
	public function __construct($link_id,$model_name,$page)
	{
		parent::__construct();
		$this->setContent($link_id,$model_name,$page);
		return;
	}
	
	abstract protected function setContent($link_id,$model_name,$page);
	
	abstract public function setVariablesArray();

}

?>