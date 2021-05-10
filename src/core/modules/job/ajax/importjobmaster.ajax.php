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
final class importjobmaster extends \core\app\classes\module_base\module_ajax {


    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('importjobmaster');
        $out = null;
        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $file = $_FILES['file']['tmp_name'];

        if(isset($file) && in_array($_FILES['file']['type'], $file_mimes)) 
        {
            $db = new  \core\modules\job\models\common\db();
            $principal_db = new  \core\modules\principal\models\common\db();
            require_once (DIR_LIB.'/simplexlsx/SimpleXLSX.php');
            if ( $xlsx = \SimpleXLSX::parse($file) )
            {
                $good = true;
                $error = [];
                foreach ($xlsx->rows() as $index => $row)
                {
                    if($index !== 0)
                    {
                        $data = [];
                        $brand = $principal_db->getBrandCodeByName($row[0]);
                        if($brand == false){
                            $data['brand_code'] = '';
                            $data['principal_code'] = '';
                        }else{
                            $data['brand_code'] = $brand['brand_code'];
                            $data['principal_code'] = $brand['principal_code'];
                        }
                        $data['cost_center'] = $row[1];
                        $data['job_code'] = $row[2];
                        $data['job_title'] = $row[3];
                        $data['minimum_salary'] = $row[5];
                        $data['mid_salary'] = $row[6];
                        $data['max_salary'] = $row[7];

                        if($row[2] != '')
                        {
                            $affected_row = $db->insertJobMaster($data);
                            if ($affected_row == -1)
                            {
                                $good = false;
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
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        } else {
            return ;
        }
    }

}
?>