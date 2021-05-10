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
final class model2 extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'edit';
	protected $processPost = false;
		
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
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
		$this->page_files = $this->page_contents_fm();		
		
		$this->defaultView();
		return;
	}
	
	private function page_contents_fm()
	{
		$out = array();
		//run over the images and files and put them in the right array
		foreach( $this->page_contents['file_manager'] as $fileName => $value )
		{	
			$content_id = ltrim($value['model'],"entry-");
			
			$type = $value['model_id'];
			
			$out[$content_id][$type]['initialPreview'][] = '\'<img src="'.$value['image_prefix'].'/thumb">\',';
					    		
			$out[$content_id][$type]['initialPreviewConfig'][] =<<<EOO
{
	caption: '$fileName', 
	width: '120px',
	url: '/ajax/pages/filedelete/$fileName',
	key: '{$value['title']}'
},						
EOO;

			$checked = $value['status'] == 1 ? 'checked="checked"' : '';
			
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < 10; $i++) 
		    {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
    		}
			
			$out[$content_id][$type]['initialPreviewThumbTags'][] = "{'{TAG_TITLE}': '".$value['title']."', '{TAG_DESCRIPTION}': '".$value['sdesc']."', '{TAG_CHECKED}': '".$checked."', '{TAG_VIS_ID}': '".$randomString."'},";
		}
		
		return $out;
	}
		
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('edit');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//we want summernote for the textareas and bootstrap-fileinput for the fileinput
		//$this->view_variables_obj->useBSFileInput();
		//$this->view_variables_obj->useSummerNote();
		
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
															'content_type' => $value['content_type'],
															'show_heading' => $value['show_heading'],
															'heading' => $value['heading'],
															'sdesc' => $value['sdesc'],
															'content' => $value['content'],
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
		
		//page info for the page itself
		$this->view_variables_obj->addViewVariables('pageContentInfoArray',$pageContentInfoArray);
		
		//footer script builder that puts the images and file in the correct containers and runs tinyMCE if needed
		$this->_buildFooterScript();

		//next entry
		$this->view_variables_obj->addViewVariables('next_content_id', $this->last_content_id + 1);

		//over write and handle submit
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
			}
			
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
		}
		
		return;
	}
	
	private function _buildFooterScript()
	{
		//PAGE Script
		$script = '$(document).ready(function(){';
			
		//set the defaults
		$initialPreview_image = '';
		$initialPreviewConfig_image = '';
		$initialPreviewThumbTags_image = '';
		$initialPreview_file = '';
		$initialPreviewConfig_file = '';
		$initialPreviewThumbTags_file = '';
		
		//setup the variables
		if(isset($this->page_files['page']['image']))
		{
			$initialPreview_image = implode("\n", $this->page_files['page']['image']['initialPreview']);
			$initialPreviewConfig_image = implode("\n", $this->page_files['page']['image']['initialPreviewConfig']);
			$initialPreviewThumbTags_image = implode("\n", $this->page_files['page']['image']['initialPreviewThumbTags']);
		}
		
		if(isset($this->page_files['page']['file']))
		{
			$initialPreview_file = implode("\n", $this->page_files['page']['file']['initialPreview']);
			$initialPreviewConfig_file = implode("\n", $this->page_files['page']['file']['initialPreviewConfig']);
			$initialPreviewThumbTags_file = implode("\n", $this->page_files['page']['file']['initialPreviewThumbTags']);
		}
		
		$script .= <<<EOS
		
			var link_id = "{$this->link_id}",
				initialPreview_image = [{$initialPreview_image}],
				initialPreviewConfig_image = [{$initialPreviewConfig_image}],
				initialPreviewThumbTags_image = [{$initialPreviewThumbTags_image}],
				initialPreview_file = [{$initialPreview_file}],
				initialPreviewConfig_file = [{$initialPreviewConfig_file}],
				initialPreviewThumbTags_file = [{$initialPreviewThumbTags_file}];
				
			runPageImages(link_id,initialPreview_image,initialPreviewConfig_image,initialPreviewThumbTags_image);
			runPageFiles(link_id,initialPreview_file,initialPreviewConfig_file,initialPreviewThumbTags_file);
			runPageShowUpload();
			
EOS;
		
		if(!empty($this->page_contents['page_contents']) )
		{
			foreach($this->page_contents['page_contents'] as $key => $value)
			{
				//set the defaults
				$initialPreview_image = '';
				$initialPreviewConfig_image = '';
				$initialPreviewThumbTags_image = '';
				$initialPreview_file = '';
				$initialPreviewConfig_file = '';
				$initialPreviewThumbTags_file = '';
				
				//need to trigger the sections to load ... tiny, images and files!
				if(isset($this->page_files[$key]['image']))
				{
					$initialPreview_image = implode("\n", $this->page_files[$key]['image']['initialPreview']);
					$initialPreviewConfig_image = implode("\n", $this->page_files[$key]['image']['initialPreviewConfig']);
					$initialPreviewThumbTags_image = implode("\n", $this->page_files[$key]['image']['initialPreviewThumbTags']);
				}
				
				if(isset($this->page_files[$key]['file']))
				{
					$initialPreview_file = implode("\n", $this->page_files[$key]['file']['initialPreview']);
					$initialPreviewConfig_file = implode("\n", $this->page_files[$key]['file']['initialPreviewConfig']);
					$initialPreviewThumbTags_file = implode("\n", $this->page_files[$key]['file']['initialPreviewThumbTags']);
				}
	
				$script .= <<<EOS
				
				var number = {$key},
					initialPreview_image = [{$initialPreview_image}],
					initialPreviewConfig_image = [{$initialPreviewConfig_image}],
					initialPreviewThumbTags_image = [{$initialPreviewThumbTags_image}],
					initialPreview_file = [{$initialPreview_file}],
					initialPreviewConfig_file = [{$initialPreviewConfig_file}],
					initialPreviewThumbTags_file = [{$initialPreviewThumbTags_file}];
				
				//apply summer note to content the textarea
				$('#content-entry-{$key}').summernote();
				
				//run content setup stuff
				runContentImages(link_id,number,initialPreview_image,initialPreviewConfig_image,initialPreviewThumbTags_image);
				runContentFiles(link_id,number,initialPreview_file,initialPreviewConfig_file,initialPreviewThumbTags_file);
				runContentShowUpload();
				runContentSubmit(number);
				runContentDelete(number);
			
EOS;
				
				
			}
		}
		
		$script .= '});';
		
		$this->view_variables_obj->addFootScript(900,$script);
		
		return;
	}
		
}
?>