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
final class addjobmaster extends \core\app\classes\module_base\module_ajax {


    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('addjobmaster');
        $out = null;
        
        $data = $_POST;

        $job_db = new  \core\modules\job\models\common\db;

        if ($data['minimum_salary'] > $data['mid_salary'] || $data['minimum_salary'] > $data['max_salary']) {
            return $this->response([
                'message' => 'The minimum salary must be lowest!',
                'type' => 'warning'
            ], 406);
        } else if($data['mid_salary'] > $data['max_salary']) {
            return $this->response([
                'message' => 'The medium salary must be lower than maximum salary!',
                'type' => 'warning'
            ], 406);
        } else if($data['max_salary'] < $data['mid_salary'] || $data['max_salary'] < $data['minimum_salary']) {
            return $this->response([
                'message' => 'The maximum salary must be greatest!',
                'type' => 'warning'
            ], 406);
        }

        $insert = $job_db->insertJobMaster($data);

        if (isset($data['job_speedy_code'])) {
            $job_db->updateJobMasterfield($data['job_code'],'job_speedy_code',$data['job_speedy_code']);
            $job_db->CalculateMinMaxSalary($data['job_speedy_code']);
        }

        if ($insert === 1) {
            # code...
            $out = [
                'message' => 'Job successfully added!',
                'status' => 'success'
            ];
        } else {
            $out = [
                'message' => 'Failed to add job',
                'status' => 'error'
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