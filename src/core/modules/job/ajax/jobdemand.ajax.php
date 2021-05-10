<?php
namespace core\modules\job\ajax;

final class jobdemand extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('jobdemand');    
        if ($_SESSION['user_id']){// restrict access only to logged in user
            if ($this->option){
                $this->job_db = new \core\modules\job\models\common\db;
                $this->demand_db = new \core\modules\job\models\common\demand_db();
                if($this->option == 'list'){


                    $rs = $this->demand_db->getJobSpeedyDemandDatatable();

                    return $this->response($rs);
                }

                if($this->option == 'update_demand'){

                    $id = $this->page_options[1];

                    $data = $_POST;

                    $rule = [
                        'demand' => 'required',
                        'reason' => 'required|max:255',
                        'expiry_on' => 'required',
                    ];
                    $validator = new \core\app\classes\validator\validator($data, $rule);
                    if($validator->hasErrors()){
                        return $this->response($validator->getValidationErrors(),400);
                    }
                    $data = [
                        'job_master_id' => $this->page_options[1],
                        'month' => date('m'),
                        'year' =>  date('Y'),
                        'demand' => $data['demand'],
                        'sex' => 'both',
                        'reason' => $data['reason'],
                        'expiry_on' => date('Y-m-d', strtotime($data['expiry_on'])),
                    ];
                    $rs = $this->job_db->insertJobDemand($data);
                    
                    return $this->response([
                        'message' => 'Successfuly update job demand.'
                    ]);
                }
                elseif( $this->option == 'calculatedemand' )
                {
                    if (!isset($this->page_options[1]) )
                        return $this->response(['message' => 'Job code parameter not set'],400);

                    $job_code = $this->page_options[1];
                    $affected_rows = $this->job_db->calculateJobCurrentDemand($job_code);
                    return ($affected_rows != -1 )
                        ? $this->response(
                            ['message' => 'Successfuly update job current demand.']
                        )
                        : $this->response(
                            ['message' => 'Update job current demand failed.',400]
                        )
                    ;

                }

                $job_code = $this->option; //check type of job that wanted to be accessed
                header('Content-Type: application/json; charset=utf-8');
                return json_encode(
                        $this->job_db->getJobDemandByCode($job_code)
                    );
                
            }
        }
    }

}
?>