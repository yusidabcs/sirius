<?php
namespace core\modules\menu\ajax;

/**
 * Final default class.
 * 
 * @final
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */
final class main extends \core\app\classes\module_base\module_ajax {
	
	protected $optionRequired = true;
	private $_form_check; //the obj for checking menu fields
		
	public function run()
	{
		//we are going to need
		$form_check_ns = NS_MODULES.'\\menu\\models\\common\\form_check';
		$this->_form_check = new $form_check_ns;
		
		switch ($this->option) 
		{
		    case 'testTitle':
		        $out = $this->_testTitle($_POST['title_new']);
		        break;
		    case 'testPage':
		        $out = $this->_testPage($_POST['page_new']);
		        break;
		}
		
		if( !empty($out) ) 
		{
			$out = json_encode($out);
		} else {
			$out = json_encode("Singing in the rain '{$this->option}'");
		}
		
		return $out;
	}

	/**
	 * _testTitle function.
	 * 
	 * @access private
	 * @param mixed $title
	 * @return void
	 */
	private function _testTitle($title)
	{	
		$out = array();
		$title_menu = trim($title);
		
		//needing to run testing
		if( !empty($title_menu) )
		{
			if($this->_form_check->checkTitle($title_menu))
			{
				//!check unique menu id
				if($this->_form_check->checkMenuUnique($title_menu))
				{
					$out['good'] = true;
					$out['menu'] = $title_menu;
					$out['note'] = $title_menu.' is good.';
				} else {
					$out['good'] = false;
					$out['note'] = 'Duplicate Name!';
				}
			} else {
				$out['good'] = false;
				$out['note'] = 'Bad Characters!';
			}
		}
		
		return $out;
	}
	
	/**
	 * _testPage function.
	 * 
	 * @access private
	 * @param mixed $page
	 * @return void
	 */
	private function _testPage($page)
	{
		$out = array();
		
		$title_page = trim($page);
		
		if($this->_form_check->checkTitle($title_page))
		{
			$link_id = $this->_form_check->makeLinkId($title_page);
			
			if($this->_form_check->checkLinkIdUnique($link_id))
			{
				$out['good'] = true;
				$out['menu'] = $title_page;
				$out['link'] = $link_id;
				$out['note'] = $title_page.' is a good.';
			} else {
				$out['good'] = false;
				$out['note'] = 'Duplicate Page Name!';
			}
		} else {
			$out['good'] = false;
			$out['note'] = 'Bad Characters!';
		}

		return $out;
	}
		
}
?>