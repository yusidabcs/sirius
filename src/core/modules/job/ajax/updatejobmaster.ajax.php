<?php
namespace core\modules\job\ajax;

final class updatejobmaster extends \core\app\classes\module_base\module_ajax {

    public function run()
    {
        $this->authorizeAjax('updatejobmaster');    
        if ($_SESSION['user_id']) // restrict access only to logged in user
        {
            $this->job_db = new \core\modules\job\models\common\db;
                
            if ($_POST['multi_select'])
            {
                //multi select
                $multi_code = json_decode(html_entity_decode($_POST['job_code']));
                foreach ($multi_code as $code)
                {
                    $rs = $this->job_db->updateJobMasterfield($code,'job_speedy_code',$_POST['job_speedy_code']);
                }
                //calculate min max after insert
                $this->job_db->CalculateMinMaxSalary($_POST['job_speedy_code']);
            }else{
                //single select
                $data = $_POST;
                
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

                $rs = $this->job_db->updateJobMaster($data);

                //calculate min max after insert
                if (isset($data['job_speedy_code'])) {
                    $this->job_db->CalculateMinMaxSalary($_POST['job_speedy_code']);
                }
            }

            header('Content-Type: application/json; charset=utf-8');
            return json_encode(
                    [
                        'success' => $rs,
                        'message' => $rs ? 'Successfully update job' : 'Woops, something wrong!'
                    ]
                );
        }
    }

}
?>