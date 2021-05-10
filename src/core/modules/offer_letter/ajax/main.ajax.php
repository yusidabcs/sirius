<?php
namespace core\modules\offer_letter\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 18 May 2020
 */
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$out = null;
		
		switch($this->option) 
		{	
			case 'list_pool':
				$pool_db = new \core\modules\interview\models\common\pool_db();
				$out = $pool_db->getSpeedyPoolWithDemandDatatable();
                return $this->response($out);
				break;
            case 'allocation':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'job_demand_master_id' => 'required',
                    'job_master_id' => 'required',
                    'job_application_id' => 'required',
                    'address_book_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $job_db = new \core\modules\job\models\common\db();
                $offer_letter_db = new \core\modules\offer_letter\models\common\db();

                foreach ($data['address_book_id'] as $index => $ab_id){
                    $job_application_id = $data['job_application_id'][$index];
                    //check if need endorser
                    $endorser = $offer_letter_db->getEndorser($data['job_master_id']);

                    if($endorser){
                        $offer_letter_db->insertOfferLetterTrackerWithEndorsment([
                            'job_application_id' => $job_application_id,
                            'endorsement_expected_on' => date('Y-m-d H:i:s', strtotime('+'.$endorser['allowance_days'].' days')),
                        ]);
                    }else{
                        $offer_letter_db->insertOfferLetterTrackerWithoutEndorsment([
                            'job_application_id' => $job_application_id
                        ]);
                    }

                    $rs = $job_db->addDemandAllocation([
                        'job_demand_master_id' => $data['job_demand_master_id'],
                        'address_book_id' => $ab_id,
                    ]);
                    if($rs > 0){

                        $job_db->allocatedJobApplication($job_application_id);
                    }
                }


                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully add job allocation'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully add job allocation']
                    ],400);
                }

                break;
		
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
		}

	}
	
}
?>