<?php
Namespace core\modules\address_book\models\common\view;
	
abstract class content extends \core\modules\address_book\models\common\base_content
{
	public function __construct($address_book_id)
	{
		parent::__construct();
		
		$method = 'getAddressBook'.ucfirst($this->contentName).'Details';
		
		$this->contentValue = $this->address_book_db->$method($address_book_id);
		
		return;
	}
	
}

?>