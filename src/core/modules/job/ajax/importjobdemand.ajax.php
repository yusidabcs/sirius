<?php
namespace core\modules\job\ajax;

/**
 * Final importjobdemand class.
 *
 * @final
 * @extends		module_ajax
 * @package 	job
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee module_admin
 */
final class importjobdemand extends \core\app\classes\module_base\module_ajax {


    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('importjobdemand');
        $out = null;
        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $file = $_FILES['file']['tmp_name'];

        if(isset($file) && in_array($_FILES['file']['type'], $file_mimes)) {
            $month = $_POST['month'];
            $year = $_POST['year'];
            $expiry_on = $_POST['expiry_on'];
            $db = new  \core\modules\job\models\common\db();
            $principal_db = new  \core\modules\principal\models\common\db();
            require_once (DIR_LIB.'/simplexlsx/SimpleXLSX.php');
            if ( $xlsx = \SimpleXLSX::parse($file) ) {

                foreach ($xlsx->rows() as $index => $row) {

                    if($row[3] != ''){
                        $principal_brand = $principal_db->getBrandCodeByName($row[0]);
                        if($principal_brand){
                            $job_master = $db->getJobMasterByCode($row[3], $principal_brand['principal_code'], $principal_brand['brand_code']);
                            $data = [];
                            $sex = $row[7] == 'Male' ? 'male' : ( $row[7] == 'Female' ? 'female' : 'both');
                            $data['job_master_id'] = $job_master['job_master_id'];
                            $data['month'] = $month;
                            $data['year'] = $year;
                            $data['demand'] = $row[8];
                            $data['sex'] = $sex;
                            $data['reason'] = '';
                            $data['expiry_on'] = date('Y-m-d', strtotime($expiry_on));
                            $rs = $db->insertJobDemand($data);
                        }
                    }
                }
                $out = [
                    'success' => true,
                    'message' => 'Successfully import data.'
                ];
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
