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
final class importjobspeedy extends \core\app\classes\module_base\module_ajax {


    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('importjobspeedy');
        $out = null;
        $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $file = $_FILES['file']['tmp_name'];

        if(isset($file) && in_array($_FILES['file']['type'], $file_mimes))
        {
            $db = new  \core\modules\job\models\common\job_category_db();
            $job_db = new  \core\modules\job\models\common\db();
            require_once (DIR_LIB.'/simplexlsx/SimpleXLSX.php');
            if ( $xlsx = \SimpleXLSX::parse($file) )
            {
                $good = true;
                $parent = null;
                foreach ($xlsx->rows(1) as $index => $row)
                {
                    if($index > 0){

                        if($row[1] == ''){
                            $job_speedy_category_id = $db->checkName($row[0]);
                        }else{
                            $job_speedy_category_id = $db->checkName($row[1]);
                        }

                        //check if job title exist
                        if(!$job_db->checkJobTitleSpeedyExist($row[3])){
                            $rs = $job_db->insertJobSpeedy([
                                'job_speedy_code' => $row[4] == '' ? rand() : $row[4],
                                'job_title' => $row[3],
                                'short_description' => '',
                                'min_requirement' => '0',
                                'min_experience' => '0',
                                'education_req' => '1',
                                'min_english_experience' => '',
                                'min_salary' => '',
                                'max_salary' => '',
                                'job_speedy_category_id' => $job_speedy_category_id,
                            ]);
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