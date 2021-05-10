<?php
namespace core\modules\pages\models\edit;

/**
 * Final model class.
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'edit';
	protected $processPost = false;
	protected $_page_core;
	protected $_page_common;

	public function __construct()
	{
        $page_core_ns = NS_APP_CLASSES.'\\page_core\\page_core';
        $this->_page_core = $page_core_ns::getInstance();

		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		//get all the text content
		$pages_common_ns = NS_MODULES.'\\pages\\models\\common\\common';
		$this->_page_common = new $pages_common_ns();
		
		$this->page_contents = $this->_page_common->getAllPageContentInfo($this->link_id);

		//need to know the latest content value to get the next one
		if(!empty($this->page_contents['page_contents'])) 
		{
			$this->last_content_id = max(array_keys($this->page_contents['page_contents']));
		} else {
			$this->last_content_id = 0;
		}
		$this->view_variables_obj->setViewTemplate('edit');
		
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//text area wysiwyg
		$this->view_variables_obj->useTrumbowyg();
		
		//use sweet alerts
		$this->view_variables_obj->useSweetAlert();
		
		//sortable
		$this->view_variables_obj->useSortable();
		
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('link_id',$this->link_id);
		
		$this->view_variables_obj->addViewVariables('view_link',$this->baseURL);
		$this->view_variables_obj->addViewVariables('order_link',$this->baseURL.'/order');
		
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
			foreach($this->page_contents['page_contents'] as $content_id => $value)
			{
				$pageContentInfoArray[$content_id] = array(
															'link_id' => $this->link_id,
															'content_type' => $value['content_type'],
															'show_heading' => $value['show_heading'],
															'heading' => $value['heading'],
															'sdesc' => $value['sdesc'],
															'content' => $value['content'],
															'image_position' => $value['image_position'],
															'to_name' => $value['to_name'],
															'to_email' => $value['to_email'],
															'to_subject' => $value['to_subject'],
															'submitted_heading' => $value['submitted_heading'],
															'submitted_sdesc' => $value['submitted_sdesc'],
															'submitted_content' => $value['submitted_content'],
															'sequence' => $value['sequence'],
															'created_on' => $value['created_on'],
															'created_by' => $value['created_by'],
															'modified_on' => $value['modified_on'],
															'modified_by' => $value['modified_by']
														);
			}
		} else {
			$pageContentInfoArray = array();
		}
		
		//page files
		if(isset($this->page_contents['file_manager']))
		{
			$this->view_variables_obj->addViewVariables('files',$this->page_contents['file_manager']);
		} else {
			$this->view_variables_obj->addViewVariables('files',array());
		}
			
		//page info for the page itself
		$this->view_variables_obj->addViewVariables('pageContentInfoArray',$pageContentInfoArray);
		
		//next entry
		$this->view_variables_obj->addViewVariables('next_content_id', $this->last_content_id + 1);

		//get all pages
        $data = $this->_page_core->getAllPageCore();
        $this->view_variables_obj->addViewVariables('list_pages', $data);

        //add page common that needed in standard content
        $this->view_variables_obj->addViewVariables('pages_common',$this->_page_common);

        $this->view_variables_obj->addViewVariables('pageContentFileViewArray',$this->pageContentsFileView());

		return;
	}

    private function pageContentsFileView()
    {
        $out = array();
        $x = 1;
        //run over the images and files and put them in the right array
        foreach( $this->page_contents['file_manager'] as $fileName => $value )
        {
            $content_id = ltrim($value['model'],"entry-");
            $type = $value['model_id'];

            $out[$content_id][$type][$x]['file_manager_id'] = $fileName;
            $out[$content_id][$type][$x]['model'] = $value['model'];
            $out[$content_id][$type][$x]['model_id'] = $value['model_id'];
            $out[$content_id][$type][$x]['status'] = $value['status'];
            $out[$content_id][$type][$x]['title'] = $value['title'];
            $out[$content_id][$type][$x]['sdesc'] = $value['sdesc'];
            $out[$content_id][$type][$x]['image_prefix'] = $value['image_prefix'];
            $out[$content_id][$type][$x]['file_prefix'] = $value['file_prefix'];
			$out[$content_id][$type][$x]['file_name'] = $value['file_name'];
			$out[$content_id][$type][$x]['sequence'] = $value['sequence'];
            $x++;
        }

        return $out;
    }
	
		
}
?>