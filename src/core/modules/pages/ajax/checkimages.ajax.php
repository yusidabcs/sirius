<?php
namespace core\modules\pages\ajax;

/**
 * Final checkpageinfo class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class checkimages extends \core\app\classes\module_base\module_ajax {
    
    private $file_manager;
    private $_link_id;
    private $_model;

	public function run()
    {	
        if ($this->_processPostValues()) {
            $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
            $this->file_manager = $file_manager_ns::getInstance();
    
            $data = $this->file_manager->getFilesArray($this->_link_id, $this->_model);
    
            if(!empty($data))
            {		
                //json to send back
                $out['message'] = 'exist';
                $out['data'] = $data;
                $out['data_count'] = count($data);
            } else {
                $out['message'] = 'not exist';
                $out['data_count'] = 0;
            }
        }else {
            $out['message'] = 'Post values not sended!';
        }


		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
    }
    
    public function _processPostValues()
    {
        $this->_link_id = $_POST['link_id'];
        $this->_model = $_POST['model'];

        return true;
    }
}
?>