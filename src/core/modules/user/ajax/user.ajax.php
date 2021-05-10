<?php
namespace core\modules\user\ajax;

final class user extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        if ($this->option)
        {
            $user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
            $this->user_db = new $user_db_common_ns();

            $type = $this->option;

            if ( $type == 'list' ){
                $out = $this->user_db->getUsers();

            }
            else if ( $type == 'update' )
            {
                $data = $_POST;
                $update_option = $this->page_options[1];

                //user/update/group
                if ($update_option == 'group')
                {
                    $affected_rows = $this->user_db->UpdateUser($data['user_id'],'group_id',$data['group_id']);

                    if($affected_rows)
                    {
                        $out['message'] = 'Update user group success';
                    }else{
                        $out['message'] = 'Problem in update user group.';
                        return $this->response($out,500);
                    }
                }
                elseif($update_option == 'role') {
                    $this->user_db->detachUserRole($data['user_id']);
                    $this->user_db->assignRole([$data['user_id']], $data['role_id']);

                    $out['message'] = 'Update user group success';
                }
                elseif ($update_option == 'security_level')
                {
                    $affected_rows = $this->user_db->UpdateUser($data['user_id'],'security_level_id',$data['security_level_id']);

                    if($affected_rows)
                    {
                        $out['message'] = 'Update user security level success';
                    }else{
                        $out['message'] = 'Problem in update user security level.';
                        return $this->response($out,500);
                    }
                }
                elseif ($update_option == 'password')
                {

                }
            } else if ( $type == 'delete' )
            {  
                $out = [];
                $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
                if ($contentType === "application/json") {
                    $content = trim(file_get_contents("php://input"));
                    $decoded = json_decode($content, true);

                    $user_id = $decoded['user_id'];
                    $email = $decoded['email'];
                    $check_user = $decoded['check_user'];
                    $check_ab = $decoded['check_ab'];

                    if($check_ab && $check_user){
                        //delete data user && address book data
                        $delete_user = $this->_deleteUser($user_id,$email);
                        $delete_ab = $this->_deleteAddressBook($user_id,$email);
                        $out['status'] = $delete_user['status'];
                        $out['message'] = $delete_user['message'].'<br>'.$delete_ab['message'];
                    } else if ($check_user) {
                        //only delete data user
                        $data = $this->_deleteUser($user_id,$email);
                        $out= $data;
                    } else if ($check_ab) {
                        //only delete user address book data
                        $data = $this->_deleteAddressBook($user_id,$email);
                        $out= $data;
                    }
                }
            }
            return $this->response($out);

        }
    }

    private function _deleteUser($user_id,$email) {
        $user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
        $this->user_db = new $user_db_common_ns();
        $data = $this->user_db->deleteUser($user_id,$email);
        return $data;
    }

    private function _deleteAddressBook($user_id,$email) {
        $ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book_id = $ab_db->checkPersonEmail($email);
        
        //set table to delete
        $table = ["`personal_stcw`","`personal_sbk`","`personal_reference`","`personal_police`","`personal_passport`","`personal_oktb`","`personal_medical`","`personal_language`","`personal_idcard`","`personal_general`","`personal_flight`","`personal_english`","`personal_employment`","`personal_education`","`personal_checklist_text`","`personal_checklist`","`personal_tattoo`","`personal_visa`","`personal`","`address_book_address`","`address_book_connection`", "`address_book_coordinates`","`address_book_email`","`address_book_internet`", "`address_book_per`","`address_book_pots`","`address_book_file`","`address_book`"];

        $address_book_id = $address_book_id==''?0:$address_book_id;
        $row_deleted = $ab_db->deleteAllAddressBook($address_book_id,$table);

            //delete folder
            $this->_deleteFileAddressBook($address_book_id);
            return [
                'status'=>'ok',
                'message' => $row_deleted.' rows has been deleted from Address Book!'
            ];

    }

    private function _deleteFileAddressBook($address_book_id) {
        // Folder path to be flushed 
        $folder_path = "local/uploads/address_book/".$address_book_id; 
        // specified folder 
        $files = glob($folder_path.'/*');  
        // Deleting all the files in the list 
        foreach($files as $file) {
            
            if(is_file($file)) { 
                // Delete the given file 
                unlink($file);  
            }
        }

        //delete folder
        if(file_exists($folder_path)) {
            rmdir($folder_path);
        }
        
    }

}
?>