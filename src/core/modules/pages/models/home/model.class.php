<?php
namespace core\modules\pages\models\home;

/**
 * Final model class.
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = false;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		if($this->link_id == 'home-page' || $this->link_id == 'member-area'){
			if( isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0)
			{
				$profile_link = $this->menu_register->getModuleLink('profile');
				header('Location: /'.$profile_link);
		    	exit();
			}
		}
		
		//get all the text content
		$pages_common_ns = NS_MODULES.'\\pages\\models\\common\\common';
		$pages_common = new $pages_common_ns();
		$this->page_contents = $pages_common->getAllPageContentInfo($this->link_id);
		
		//need to know the latest content value to get the next one
		if(!empty($this->page_contents['page_contents'])) 
		{
			$this->last_content_id = max(array_keys($this->page_contents['page_contents']));
		} else {
			$this->last_content_id = 0;
		}

		//process images and files
		$this->page_view_files = $this->pageContentsFileView();		

		return;
	}
	
	private function pageContentsFileView()
	{
		$out = array();
		$x = 1;
		//run over the images and files and put them in the right array
		foreach( $this->page_contents['file_manager'] as $fileName => $value )
		{	
			if($value['status'])
			{
				$content_id = ltrim($value['model'],"entry-");
				$type = $value['model_id'];
				
				$out[$content_id][$type][$x]['title'] = $value['title'];
				$out[$content_id][$type][$x]['sdesc'] = $value['sdesc'];
				$out[$content_id][$type][$x]['image_prefix'] = $value['image_prefix'];
				$out[$content_id][$type][$x]['file_prefix'] = $value['file_prefix'];
				$out[$content_id][$type][$x]['file_name'] = $value['file_name'];
				
				$x++;
			}
		}
				
		return $out;
	}
	
	//required function
	protected function setViewVariables()
	{
		//default view
		$this->view_variables_obj->setViewTemplate('home');

        //use sweet alert
		$this->view_variables_obj->useSweetAlert();
		
		//gallery needed in case there is one
		//$this->view_variables_obj->useEkkoLightBox();
		
		$this->view_variables_obj->addViewVariables('isAdmin',$this->isAdmin);
		$this->view_variables_obj->addViewVariables('page_contents',$this->page_contents);
		$this->view_variables_obj->addViewVariables('edit_link',$this->baseURL.'/edit');
		$this->view_variables_obj->addViewVariables('order_link',$this->baseURL.'/order');
		$this->view_variables_obj->addViewVariables('feedback_post',$this->baseURL.'/feedback');
		$this->view_variables_obj->addViewVariables('link_id',$this->link_id);

		$menu_db_ns = NS_MODULES.'\\menu\\models\\common\\db';
		$menu_db = $menu_db_ns::getInstance();

		$current_menu = $menu_db->getMenuItem($this->link_id);
		
		$this->view_variables_obj->addViewVariables('template_name', $current_menu['template_name']);
		
		//!only want to do this IF there is a contact page!
		$this->content_id = 1;
		
		//firstly get the information (if it is set)
		$pages_db_ns = NS_MODULES.'\\pages\\models\\common\\db';
		$pages_db = new $pages_db_ns();
		
		$submit_array = $pages_db->getContactFormInfo($this->link_id,$this->content_id);
		$submitted_heading = empty($submit_array['submitted_heading']) ? $this->system_register->site_term('FEEDBACK_DEFAULT_HEADING') : $submit_array['submitted_heading'];
		$submitted_sdesc = empty($submit_array['submitted_sdesc']) ? $this->system_register->site_term('FEEDBACK_DEFAULT_SDESC') : $submit_array['submitted_sdesc'];
		$submitted_content = empty($submit_array['submitted_content']) ? $this->system_register->site_term('FEEDBACK_DEFAULT_CONTENT') : $submit_array['submitted_content'];
		
		$this->view_variables_obj->addViewVariables('submitted_heading',$submitted_heading);
		$this->view_variables_obj->addViewVariables('submitted_sdesc',$submitted_sdesc);
		$this->view_variables_obj->addViewVariables('submitted_content',$submitted_content);
				
		//page information
		if(empty($this->page_contents['page_info']))
		{
			$this->view_variables_obj->addViewVariables('show_heading','');
			$this->view_variables_obj->addViewVariables('page_heading','');
			$this->view_variables_obj->addViewVariables('page_sdesc','');
			$this->view_variables_obj->addViewVariables('page_keywords','');
			$this->view_variables_obj->addViewVariables('page_text','');
			$this->view_variables_obj->addViewVariables('show_anchors','');
			$this->view_variables_obj->addViewVariables('created_on','');
			$this->view_variables_obj->addViewVariables('created_by','');
			$this->view_variables_obj->addViewVariables('modified_on','');
			$this->view_variables_obj->addViewVariables('modified_by','');
		} else {
			$this->view_variables_obj->addViewVariables('show_heading',$this->page_contents['page_info']['show_heading']);
			$this->view_variables_obj->addViewVariables('page_heading',$this->page_contents['page_info']['page_heading']);
			$this->view_variables_obj->addViewVariables('page_sdesc',$this->page_contents['page_info']['page_sdesc']);
			$this->view_variables_obj->addViewVariables('page_keywords',$this->page_contents['page_info']['page_keywords']);
			$this->view_variables_obj->addViewVariables('page_text',$this->page_contents['page_info']['page_text']);
			$this->view_variables_obj->addViewVariables('show_anchors',$this->page_contents['page_info']['show_anchors']);
			$this->view_variables_obj->addViewVariables('created_on',$this->page_contents['page_info']['created_on']);
			$this->view_variables_obj->addViewVariables('created_by',$this->page_contents['page_info']['created_by']);
			$this->view_variables_obj->addViewVariables('modified_on',$this->page_contents['page_info']['modified_on']);
			$this->view_variables_obj->addViewVariables('modified_by',$this->page_contents['page_info']['modified_by']);
		}
		
		//set up the contents
		if(!empty($this->page_contents['page_contents']))
		{

            $pageContentAnchors = array();
			foreach($this->page_contents['page_contents'] as $content_id => $value)
			{
				$pageContentInfoArray[$content_id] = array(
															'content_type' => $value['content_type'],
															'show_heading' => $value['show_heading'],
															'heading' => $value['heading'],
															'sdesc' => $value['sdesc'],
															'content' => $value['content'],
															'sequence' => $value['sequence'],
															'image_position' => $value['image_position'],
															'created_on' => $value['created_on'],
															'created_by' => $value['created_by'],
															'modified_on' => $value['modified_on'],
															'modified_by' => $value['modified_by']
														);
				
				//setup anchors if they are needed
				if($value['show_heading'] == 1 && $value['content_type'] != 'banner_top')
				{
					$pageContentAnchors[$content_id] = htmlentities( $value['heading'] );
				}
			}
		} else {
			$pageContentInfoArray = array();
			$pageContentAnchors = array();
		}
		$this->view_variables_obj->addViewVariables('pageContentInfoArray',$pageContentInfoArray);
		$this->view_variables_obj->addViewVariables('pageContentAnchors',$pageContentAnchors);
		
		//set up the files and images data
		$this->view_variables_obj->addViewVariables('pageContentFileViewArray',$this->page_view_files);
			
		//next entry
		$this->view_variables_obj->addViewVariables('next_content_id', $this->last_content_id + 1);

        if(isset($_SESSION['user_email']))
        {
            $this->view_variables_obj->addViewVariables('use_captcha',false);
            $this->view_variables_obj->addViewVariables('feedback_name',$_SESSION['user_name']);
            $this->view_variables_obj->addViewVariables('feedback_email',$_SESSION['user_email']);
        } else {
            $this->view_variables_obj->addViewVariables('use_captcha',true);
            $this->view_variables_obj->addViewVariables('feedback_name','');
            $this->view_variables_obj->addViewVariables('feedback_email','');
        }
		
		return;
	}
		
}
?>