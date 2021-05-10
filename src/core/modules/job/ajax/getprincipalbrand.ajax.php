<?php namespace core\modules\job\ajax;
/**
 * Final importjobmaster class.
 *
 * @final
 * @extends		module_ajax
 * @package 	job
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee module_admin
 */
final class getprincipalbrand extends \core\app\classes\module_base\module_ajax {


    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('getprincipalbrand');
        $out = null;

        $principal_db = new \core\modules\principal\models\common\db();

        $out = $principal_db->getBrandByPrincipalCode($this->option);

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

}
?>