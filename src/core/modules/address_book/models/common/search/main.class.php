<?php
Namespace core\modules\address_book\models\common\search;

final class main extends content
{
	//this name
	protected $contentName = 'main';
	
	protected $link_id = '';
	protected $model_name = '';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array(); //array for view_variables_obj->$viewSwitch()
	
	protected $contentValue = array();
		
	protected function setContent($link_id,$model_name,$page)
	{
		if( isset($_POST['search_type']) && isset($_POST['search_text']) )
		{
			$acceptable_search_types_a = array('starts','contains');
		
			if( in_array($_POST['search_type'], $acceptable_search_types_a) )
			{
				//a search was submitted and change to special characters
				$type = $_POST['search_type'];
				$text = htmlspecialchars(trim($_POST['search_text']));
				
				//reset search
				if(empty($text))
				{
					$type = 'RESET';
					$text = 'RESET-THE-SEARCH';
					$this->contentValue['term_search_title'] = 'term_search_title_contains';
					$this->contentValue['term_search_name'] = 'term_search_name_contains';
				} else {   //new search
					switch ($type) 
					{
					    case 'starts':
					        $this->contentValue['term_search_title'] = 'term_search_title_starts';
					        $this->contentValue['term_search_name'] = 'term_search_name_starts';
					        
					        break;
					    case 'contains':
					        $this->contentValue['term_search_title'] = 'term_search_title_contains';
					        $this->contentValue['term_search_name'] = 'term_search_name_contains';
	
					        break;
					    default:
					    	$type = 'RESET';
					    	$text = 'RESET-THE-SEARCH';
					    	$this->contentValue['term_search_title'] = 'term_search_title_contains';
					    	$this->contentValue['term_search_name'] = 'term_search_name_contains';
					}
				}
			} else {
				$msg = 'Unknown search type!';
				throw new \RuntimeException($msg);
			}
			
		} else {
			$type = '';
			$text = '';
			$this->contentValue['term_search_title'] = 'term_search_title_contains';
			$this->contentValue['term_search_name'] = 'term_search_name_contains';
		}
		
		$this->contentValue['paginationInfo'] = $this->address_book_db->getClientPaginationInfo($link_id,$model_name,$page,$type,$text);
		
		$this->contentValue['address_book_array'] = $this->address_book_db->getAddressBookArray();
		
		$address_book_ids = array_keys($this->contentValue['address_book_array']);
		
		//not the fastest way to do this but it will do for now!
		if(!empty($address_book_ids))
		{
			foreach($address_book_ids as $address_book_id)
			{
				$this->contentValue['address_book_array'][$address_book_id]['main'] = $this->address_book_db->getAddressBookMainDetails($address_book_id);
				
				if($this->contentValue['address_book_array'][$address_book_id]['main']['type'] == 'ent')
				{
					$this->contentValue['address_book_array'][$address_book_id]['main']['ent_admin_details'] = $this->address_book_db->getAddressBookAdminLinks($address_book_id);
				} else {
					$this->contentValue['address_book_array'][$address_book_id]['main']['ent_admin_details'] = array();
				}
				
				$this->contentValue['address_book_array'][$address_book_id]['address'] = $this->address_book_db->getAddressBookAddressDetails($address_book_id);
				$this->contentValue['address_book_array'][$address_book_id]['pots'] = $this->address_book_db->getAddressBookPotsDetails($address_book_id);
				$this->contentValue['address_book_array'][$address_book_id]['internet'] = $this->address_book_db->getAddressBookInternetDetails($address_book_id);
				$this->contentValue['address_book_array'][$address_book_id]['avatar'] = $this->address_book_db->getAddressBookFileArray($address_book_id,'avatar');
			}
		}
		
		return;
	}
	
	public function setVariablesArray()
	{
		$out = array();
		
		//connect to view variables object and variables need for all views
		$view_variables_obj = \core\app\classes\page_view\page_view_variables::getInstance();
		//set the JS script for paginate because it is needed for search
		$view_variables_obj->addFootSrcFile(900,$this->contentValue['paginationInfo']['paginate_JS_File']);

		$this->viewVariables = $this->contentValue;
		return;
	}
	
}
?>