<?php
namespace core\modules\pages\ajax;

/**
 * Final fileinput class.
 * 
 * Ajax to allow for files to be uploaded
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class counters extends \core\app\classes\module_base\module_ajax {
		
	public function run()
	{
        $out = [
			"total_candidate" => 0,
			"total_education" => 100,
			"total_job" => 98,
		];
		$this->personal_db = new \core\modules\personal\models\common\db;
		$this->job_db = new \core\modules\job\models\common\db;

		//get verification count to be displayed on dashboard
        $total_candidate = $this->personal_db->getAllCandidateCount();
        $out['total_candidate'] = $total_candidate;
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