<?php

namespace core\app\classes\middleware;

class authmiddleware {

    private static $instance = null;
    private $user_id, $user;

    public function __construct()
    {
        if (isset($_SESSION['user_id'])) {
            # code...
            $this->user_id = $_SESSION['user_id'];
            
            if (!isset($_SESSION['permissions']) || empty($_SESSION['permissions'])) {
                $this->initPermission();
            }

        }

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getAuthenticatedUser()
    {
        $user_db = new \core\modules\user\models\common\user_db();
        $user_role = $user_db->getUserRole($this->user_id);

        if ($user_role) {
            # code...
            $this->user = $user_role;
            $this->permission = json_decode($user_role['permission'], true);
        }
    }

    public function checkPermission($ability)
    {
        if (isset($_SESSION['permissions'][$ability]) && $_SESSION['permissions'][$ability] === 'not_allow') {
            return false;
        }

        return true;
    }

    public function can($ability)
    {
        if (!$this->checkPermission($ability)) {
            $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
		    $menu_register = $menu_register_ns::getInstance();

            $security_link = $menu_register->getModuleLink('security');
            $page_info_ns = NS_APP_CLASSES.'\\page_info\\page_info';
            $page_info = $page_info_ns::getInstance();
            
            //!SECURITY CHECKS NOW as we have MENU and INFO
            
            $link = $page_info->getLink();

            //check if the link is real and if not 404
		    $menu_register->checkLink($link);
            
            header('HTTP/1.1 403 Forbidden');
            $_SESSION['system_security_redirect'] = 1;
            $_SESSION['system_security_point'] = 'Core Processing';
            $_SESSION['system_security_reason'] = 'You do not have the required permission for this page.';
            
            $_SESSION['system_original_page_info_link'] = $link;
            $_SESSION['system_original_page_info_options'] = $page_info->getOptions();
            $_SESSION['system_original_page_info_home'] = $page_info->getHome();
            
            header('Location: /'.$security_link);
            exit();
        }
    }

    public function initPermission()
    {
        $this->getAuthenticatedUser();
        $_SESSION['user_role'] = $this->user['role_name'];
        $_SESSION['role_id'] = $this->user['role_id'];
        $_SESSION['permissions'] = $this->permission;
    }

}