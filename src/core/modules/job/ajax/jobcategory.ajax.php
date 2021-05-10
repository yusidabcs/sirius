<?php
namespace core\modules\job\ajax;

final class jobcategory extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('jobcategory');    
        $this->job_db = new \core\modules\job\models\common\job_category_db;
            
        $type = $this->option;

        
        if ( $type == 'list' )
        {
            $rs = $this->job_db->getAll();
            $out = [];
            foreach ($rs as $key => $item){
                if($item['parent_id'] == 0){

                    $childs = array_filter($rs, function ($var) use ($item) {
                        return ($var['parent_id'] == $item['job_speedy_category_id']);
                    });


                    usort($childs, function($a, $b) {
                        return $a['sequence'] <=> $b['sequence'];
                    });

                    $item['childs'] = $childs;
                    $out[] = $item;
                }
            }

        }else if ( $type == 'get' ){
            
            $id = $this->page_options[1];

            $out = $this->job_db->get($id);

            $out['files'] = $this->job_db->getFiles($out['job_speedy_category_id']);

        }else if ( $type == 'insert' ){

            $data = $_POST;

            $rule = [
                'name' => 'required|max:100',
                'parent_id' => 'required',
                'short_description' => 'required|max:255',
            ];
            $validator = new \core\app\classes\validator\validator($data, $rule);
            if($validator->hasErrors()){
                return $this->response($validator->getValidationErrors(),400);
            }

            $category_id = $this->job_db->insert($data);
            //insert or update the image
            if(!empty($_POST['banner_base64']))
            {
                $banner_current = empty($_POST['banner_current']) ? false : $_POST['banner_current'];
                $this->_processImage($category_id,$banner_current,$_POST['banner_base64'],'banner','job_category');
            }
            if(!empty($_POST['avatar_base64']))
            {
                $banner_current = empty($_POST['avatar_current']) ? false : $_POST['avatar_current'];
                $this->_processImage($category_id,$banner_current,$_POST['avatar_base64'],'avatar','job_category');
            }

            if($category_id){
                $out = [
                    'success' => true,
                    'message' => 'Successfully insert item.'
                ];
            }

        }else if ( $type == 'update' ){

            $id = $this->page_options[1];

            $data = $_POST;

            $rule = [
                'name' => 'required|max:100',
                'parent_id' => 'required|int',
                'short_description' => 'required|max:255',
            ];
            $validator = new \core\app\classes\validator\validator($data, $rule);
            if($validator->hasErrors()){
                return $this->response($validator->getValidationErrors(),400);
            }

            $rs = $this->job_db->update($id, $data);
            //insert or update the image
            if(!empty($_POST['banner_base64']))
            {
                $banner_current = empty($_POST['banner_current']) ? false : $_POST['banner_current'];
                $this->_processImage($id,$banner_current,$_POST['banner_base64'],'banner','job_category');
            }
            if(!empty($_POST['avatar_base64']))
            {
                $banner_current = empty($_POST['avatar_current']) ? false : $_POST['avatar_current'];
                $this->_processImage($id,$banner_current,$_POST['avatar_base64'],'avatar','job_category');
            }

            if($rs){
                $out = [
                    'success' => true,
                    'message' => 'Successfully update item'
                ];
            }

        }else if ( $type == 'delete' ){


            $id = $this->page_options[1];

            $rs = $this->job_db->delete($id);
            if($rs){
                $out = [
                    'success' => true,
                    'message' => 'Successfully delete item.'
                ];
            }

        }
        else if ( $type == 'updateSequence' ){

            $data = $_POST;
            $rule = [
                'id' => 'required|int',
                'index' => 'required|int',
                'parent_id' => 'required|int',
            ];
            $validator = new \core\app\classes\validator\validator($data, $rule);
            if($validator->hasErrors()){
                return $this->response($validator->getValidationErrors(),400);
            }

            $rs = $this->job_db->updateSequence($data['id'],$data['index'],$data['parent_id']);
            if($rs){
                $out = [
                    'success' => true,
                    'message' => 'Successfully delete item.'
                ];
            }

        }

        return $this->response($out);
    }

    private function _processImage($address_book_id,$banner_current,$banner_base64, $model_code, $model_sub_code)
    {
        $filename = 'none';

        //decode
        $data = $banner_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($banner_current)
        {
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($banner_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,$model_code,0,$model_sub_code,1);

            if($affected_rows != 1)
            {
                $msg = "1There was a major issue with addInfo in banner for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,$model_code,0,$model_sub_code,1);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in banner for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }

}
?>