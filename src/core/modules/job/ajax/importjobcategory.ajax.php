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
final class importjobcategory extends \core\app\classes\module_base\module_ajax {


    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('importjobcategory');
        $out = null;
        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $file = $_FILES['file']['tmp_name'];

        if(isset($file) && in_array($_FILES['file']['type'], $file_mimes))
        {
            $db = new  \core\modules\job\models\common\job_category_db();
            require_once (DIR_LIB.'/simplexlsx/SimpleXLSX.php');
            if ( $xlsx = \SimpleXLSX::parse($file) )
            {
                $good = true;
                $error = [];
                $parent = null;
                foreach ($xlsx->rows(1) as $index => $row)
                {
                    if($index > 0 && $row[0] != ''){

                        //insert parent
                        $parent_id = $db->checkName($row[0]);
                        if($parent_id == 0){
                            $parent_id = $db->insert([
                                'name' => $row[0],
                                'parent_id' => 0,
                                'short_description' => ''
                            ]);
                        }
                        if($row[1] != ''){
                            if($db->checkName($row[1]) == 0){
                                $db->insert([
                                    'name' => $row[1],
                                    'parent_id' => $parent_id,
                                    'short_description' => ''
                                ]);
                            }
                        }
                    }

                }

                if ($good)
                {
                    $out = [
                        'success' => true,
                        'message' => 'Successfully import data.'
                    ];

                }else{

                    $out = [
                        'success' => false,
                        'message' => 'Error importing data.'
                    ];

                }

            } else {
                echo SimpleXLSX::parseError();
            }
        }else{
            $out = [
                'error' => true,
                'message' => 'Make sure uploaded file is in excel format.'
            ];
        }

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

}
?>