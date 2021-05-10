<?php
namespace core\modules\pages\ajax;

/**
 * Final contentsort class.
 *
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class filesort extends \core\app\classes\module_base\module_ajax {

    //set by me

    public function run()
    {
        //!NEEDED check post values
        if($this->_processPostValues())
        {
            //should be good to go with uploading
            $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
            $file_manager = $file_manager_ns::getInstance();

            if($file_manager->file_manager_db->updateSequence($this->_file_manager_id,$this->_new_sequence, $this->_old_sequence, $this->_link_id, $this->_model, $this->_model_id))
            {
                //json to send back
                $out['response'] = 'OK';
                $out['message'] = 'Sort order updated on Server';
            } else {
                $out['response'] = 'Failed';
                $out['message'] = 'Sort order update failed on Server';
            }
        } else {
            $out['response'] = 'Failed';
            $out['message'] = 'Hmm, I do not know what is up with this!';
        }

        if(!empty($out))
        {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        } else {
            return ;
        }
    }

    private function _processPostValues()
    {
        $this->_file_manager_id = $_POST['file_manager_id'];
        $this->_model = $_POST['model'];
        $this->_model_id = $_POST['model_id'];
        $this->_link_id = $_POST['link_id'];
        $this->_new_sequence = $_POST['new_sequence'];
        $this->_old_sequence = $_POST['old_sequence'];

        return true;
    }

}
?>