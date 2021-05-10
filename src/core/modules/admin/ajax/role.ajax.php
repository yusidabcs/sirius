<?php
namespace core\modules\admin\ajax;

/**
 * Final countrysubcodes class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee module_admin
 */
final class role extends \core\app\classes\module_base\module_ajax {
	
	protected $optionRequired = true; //we must have an option to work
	
	
	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter


	public function run()
	{	
        $this->authorizeAjax('role');
        
		$out = null;
        $user_db = new \core\modules\user\models\common\user_db();
        $user_common = new \core\modules\user\models\common\user_common();
        
        switch ($this->option) {
            case 'list':
                $out = $user_db->getRoleDatatable();
                break;

            case 'add':
                $user_db->insertRole($_POST['role_name']);

                $out = [
                    'message' => 'Role successfully added!',
                    'status' => 'success'
                ];
                break;
            case 'save-permission':

                $permissions = json_encode($_POST['permission']);

                $user_common->assignPermissionToRole($_POST['role_id'], $permissions);

                if (isset($_SESSION['permissions']) && isset($_POST['role_id'])) {

                    if ($_POST['role_id'] == $_SESSION['role_id']) {
                        $this->middleware->initPermission();
                    }
                }

                $out = [
                    'message' => 'Permission saved!',
                    'status' => 'success'
                ];
                break;
            case 'get-role':

                $role = $user_db->getRole($this->page_options[1]);

                $out['data'] = json_decode($role['permission'], true);
                break;
            case 'get-roles':

                $out['data'] = $user_db->getAllRoles();

                break;
            case 'user-role':

                $out['data'] = $user_db->getUserByRole($this->page_options[1]);

                break;
            case 'assign-role':

                $users = $_POST['user_id'];
                $user_db->detachRoleFromUser($users, $_POST['role_id']);
                $user_db->assignRole($users, $_POST['role_id']);

                $out = [
                    'message' => 'Role successfully assigned!',
                    'status' => 'success'
                ];

                break;
            case 'update':

                $user_db->updateRole($this->page_options[1], ['role_name' => $_POST['role_name']]);

                $out = [
                    'message' => 'Role successfully updated!',
                    'status' => 'success'
                ];
                break;

            case 'delete':

                $user_db->detachRole($this->page_options[1]);
                $user_db->deleteRole($this->page_options[1]);

                $out = [
                    'message' => 'Role has been deleted!',
                    'status' => 'success'
                ];
                break;
            case 'edit-permission-value':
                    
                    $role = $user_db->getRole($_POST['role_id']);
                    $permissions = json_decode($role['permission'], true);

                    $permissions[$_POST['permission_key']] = $_POST['permission_value'];

                    $user_db->updateRolePermission($_POST['role_id'], json_encode($permissions));

                    if (isset($_SESSION['permissions']) && isset($_POST['role_id'])) {

                        if ($_POST['role_id'] == $_SESSION['role_id']) {
                            $this->middleware->initPermission();
                        }
                    }

                    $out = [
                        'message' => 'Permission value updated!',
                        'status' => 'success'
                    ];

                break;
            default:
                throw new Exception("Option {$this->option} can\'t be supported", 1);
                break;

        }
		
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>