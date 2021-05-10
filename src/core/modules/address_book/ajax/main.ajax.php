<?php

namespace core\modules\address_book\ajax;

/**
 * Final addmain class.
 *
 * @final
 * @extends        module_ajax
 * @package    address_book
 * @author        Martin O'Dee<martin@iow.com.au>
 * @copyright    Martin O'Deemodule_admin
 */
final class main extends \core\app\classes\module_base\module_ajax
{

    protected $optionRequired = true; //we must have an option to work

    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('main');
        $out = null;
        $user_common = new \core\modules\user\models\common\user_common;

        if ($this->option == 'mainEmailCheck') {
            $main_email = $_POST['main_email'];
            $type = $_POST['type'];

            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->checkMainEmail($type, $main_email);
        } elseif ($this->option == 'adminEmailCheck') {
            $admin_email = $_POST['admin_email'];

            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->checkAdminEmail($admin_email);
        } elseif ($this->option == 'userNameCheck') {
            $type = $_POST['type'];
            $entity_family_name = $_POST['entity_family_name'];
            $number_given_name = $_POST['number_given_name'];
            $middle_names = $_POST['middle_names'];
            $dob = $_POST['dob'];
            $sex = $_POST['sex'];
            $address_book_id = isset($_POST['address_book_id']) ? $_POST['address_book_id'] : 0;
            $main_email = $_POST['main_email'];

            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->checkMainData($type, $entity_family_name, $number_given_name, $middle_names, $dob, $sex, $address_book_id, $main_email);
        } elseif ($this->option == 'listAddressBook') {
            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->getListAddressBookDatatable();
        } elseif ($this->option == 'getAddressBook') {
            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            if(empty($this->page_options[1])){
                $msg = "Empty address book id";
                throw new \RuntimeException($msg);
            }
            $out = $address_book_common->getAddressBookMainDetails($this->page_options[1]);
        }elseif ($this->option == 'contactEmailCheck') {
            $email = $_POST['email'];
            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->checkContactEmail($email);
        }
        elseif ($this->option == 'linkAddressBookEntity') {
            $address_book_ent_id = $_POST['address_book_ent_id'];
            $address_book_per_id = $_POST['address_book_per_id'];
            $person_type = $_POST['person_type'];
            $security_level_id = $_POST['security_level_id'];
            $role_id = $_POST['role_id'];
            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->linkAddressBookEntity($address_book_ent_id,$address_book_per_id, $person_type, $security_level_id);

            $address_book_details = $address_book_common->getAddressBookMainDetails($address_book_per_id);

            if ($address_book_details) {
                $user_common->detachUserFromRole($address_book_details['user_id']);
                $user_common->assignRoleToUser($address_book_details['user_id'], $role_id);
            }
        }
        elseif ($this->option == 'deleteAddressBookAdminLink') {
            $address_book_ent_id = $_POST['address_book_ent_id'];
            $address_book_per_id = $_POST['address_book_per_id'];
            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $out = $address_book_common->deleteAddressBookAdminLink($address_book_ent_id,$address_book_per_id);

            $address_book_details = $address_book_common->getAddressBookMainDetails($address_book_per_id);

            if ($address_book_details) {
                $user_common->detachUserFromRole($address_book_details['user_id']);
                $user_common->assignRoleNameToUser($address_book_details['user_id'],'member');
            }
        }
        elseif ($this->option == 'addAddressBookAdminLink') {
            $valid = true;
            $address_book_ent_id = $_POST['address_book_ent_id'];
            $title = $_POST['title'];
            $family_name = $_POST['family_name'];
            $given_name = $_POST['given_name'];
            $middle_names = $_POST['middle_names'];
            $dob = $_POST['dob'];
            $sex = $_POST['sex'];
            $person_type = $_POST['person_type'];
            $security_level_id = $_POST['security_level_id'];
            $contact_allowed = $_POST['contact_allowed'];
            $add_new_user = $_POST['add_new_user'];
            $send_new_user_email = $_POST['send_new_user_email'];
            $email = $_POST['email'];
            $role_id = $_POST['role_id'];

            if($title == ''){
                $valid = false;
                $out['error'][] = [
                    'title' => 'Title required'
                ];
            }
            if($family_name == ''){
                $valid = false;
                $out['error'][] = [
                    'family_name' => 'Family name required'
                ];
            }
            if($given_name == ''){
                $valid = false;
                $out['error'][] = [
                    'given_name' => 'Given name required'
                ];
            }
            if($dob == ''){
                $valid = false;
                $out['error'][] = [
                    'dob' => 'Dob required'
                ];
            }
            if($sex == ''){
                $valid = false;
                $out['error'][] = [
                    'sex' => 'Sex required'
                ];
            }
            if(empty($role_id) && $role_id <= 0){
                $valid = false;
                $out['error'][] = [
                    'role' => 'Role required'
                ];
            }

            if($valid){
                $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

                //add ab
                $key_contact_id = $address_book_common->address_book_db->addMainAddressBookEntry($email,'per',$family_name,$given_name,$contact_allowed);

                if($key_contact_id > 0)
                {
                    //add the extra info
                    $affected_rows = $address_book_common->address_book_db->insertAddressBookPer($key_contact_id,$title,$middle_names,$dob,$sex);
                    $out = $address_book_common->addAddressBookAdminLink($address_book_ent_id,$key_contact_id,$person_type, $security_level_id);
                    if($affected_rows != 1)
                    {
                        $msg = "There was a major issue with adding extra info to address id {$key_contact_id}. Affected was {$affected_rows}";
                        throw new \RuntimeException($msg);
                    }

                } else {
                    $msg = 'Failed to add New Address Entry.  Address book id was empty!';
                    throw new \RuntimeException($msg);
                }

                if($add_new_user)
                {
                    if(empty($email))
                    {
                        $msg = 'Told to add new ent admin email to users but no email exists in ent admin or main!';
                        throw new \RuntimeException($msg);
                    }
                    $add_core = \core\modules\address_book\models\common\add\core::getInstance();
                    $user_id = $add_core->addNewUser($given_name,$family_name,$email,$send_new_user_email);

                    if ($user_id && $user_id > 0) {
                        $user_common->detachUserFromRole($user_id);
                        $user_common->assignRoleToUser($user_id, $role_id);
                    }
                }

            }
        }





        if (!empty($out)) {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        } else {
            return;
        }
    }

}

?>