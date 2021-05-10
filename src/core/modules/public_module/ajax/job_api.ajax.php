<?php
namespace core\modules\public_module\ajax;

final class job_api extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{		
		$out = null;
		$job_db = new \core\modules\job\models\common\db();
		$public_db = new \core\modules\public_module\models\common\db();
		$category_db = new \core\modules\job\models\common\job_category_db();
		$core_db = new \core\app\classes\core_db\core_db();
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
 		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type");
		switch($this->option) 
		{	
			case 'get-job-demand' :
				$data = $_POST;
				if(!is_array($data)) {
					$data = json_decode(file_get_contents("php://input"), true);
				}

				if(!isset($data['page'])) {
					$page = 1;
				} else {
					$page = $data['page'];
				}
				$page = $page<1?1:$page;

				if(!isset($data['limit'])) {
					$limit = 10;
				} else {
					$limit = $data['limit'];
				}
				$limit = $limit<1?1:$limit;
				$start = ($page-1) * $limit;

				//initial variable pagination
				$total = 0;
				$count = 0;
				$per_page = 1;
				$current_page = 1;
				$total_page = 1;
				$from = 0;
				$to=0;
				$demand = $job_db->getJobWithDemand();
				if(count($demand)>0) {
					$str_demand = implode("','",$demand);
					$str_demand = "'".$str_demand."'";
					$job = $public_db->getAllJobSpeedyByDemand($str_demand,$data);

					$total = count($job);
					
					$per_page = $limit;
					$current_page = $page;
					$total_page = ceil($total / $limit);
					//make sure $page<=$total_page
					if($page>$total_page) {
						$page = $total_page;
						$page = $page<1?1:$page;
						$start = ($page-1) * $limit;
						$current_page = $page;
					}
					$jobs = $public_db->getAllJobSpeedyByDemand($str_demand,$data,$start,$limit);
					$count = count($jobs);
					$from = $start+1;
					$to= $start + $count;
					foreach ($jobs as $key => $value) {
						if($value['cover_image']!='') {
							$cover_image = $value['cover_image'];
							$jobs[$key]['cover_image']=HTTP_TYPE.SITE_WWW.'/fm/image/'.$cover_image;
						} else {
							//check from category
							if($value['job_speedy_category_id']!='') {
								$filename = '';
								$files = $category_db->getFiles($value['job_speedy_category_id']);
								foreach ($files as $file)
								{
									$filename = $file['model_code'] == 'banner' ? $file['filename'] : '';
								}
								if($filename!='') {
									$jobs[$key]['cover_image']=HTTP_TYPE.SITE_WWW.'/ao/show/'.$filename;
								}
							}
						}

						if($value['country']=='' || $value['country']=='ALL') {
							$jobs[$key]['country'] ='ALL';
						} else {
							$countries = $core_db->getAllCountryCodes(json_encode(explode(',',$value['country'])));
							$jobs[$key]['country'] =implode(", ",array_values($countries));
						}
						
					}
					$response['data']=$jobs;
				} else {
					$response['data']=array();
				}
				$response['pagination'] = [
					"total" => $total,
					"count" => $count,
					"per_page" => $per_page,
					"current_page" => $current_page,
					"total_page" => $total_page,
					"from" => $from,
					"to" => $to
				];
				$out = $response;
			break; 
			case 'get-job-speedy-category' :
				$out = $category_db->getAll();
            break; 
			case 'get-detail-job-speedy' :	
				$data = $_POST;
				if(!is_array($data)) {
					$data = json_decode(file_get_contents("php://input"), true);
				}

				if(!isset($data['page'])) {
					$page = 1;
				} else {
					$page = $data['page'];
				}
				$page = $page<1?1:$page;

				if(!isset($data['limit'])) {
					$limit = 10;
				} else {
					$limit = $data['limit'];
				}
				$limit = $limit<1?1:$limit;
				$start = ($page-1) * $limit;

				//initial variable pagination
				$total = 0;
				$count = 0;
				$per_page = 1;
				$current_page = 1;
				$total_page = 1;
				$from = 0;
				$to = 0;

				$demand = $job_db->getJobWithDemand();
				if(count($demand)>0) {
					$str_demand = implode("','",$demand);
					$str_demand = "'".$str_demand."'";


					//get data detail job speedy
					$job_speedy_code='';
					if(isset($data['job_speedy_code'])) {
						$job_speedy_code = $data['job_speedy_code'];
						$job_detail = $public_db->getAllJobSpeedyByDemand($str_demand,$data);
					} else {
						$job_detail = [];
					}

					foreach ($job_detail as $key => $value) {
						if($value['cover_image']!='') {
							$cover_image = $value['cover_image'];
							$job_detail[$key]['cover_image']=HTTP_TYPE.SITE_WWW.'/fm/image/'.$cover_image;
						} else {
							//check from category
							if($value['job_speedy_category_id']!='') {
								$filename = '';
								$files = $category_db->getFiles($value['job_speedy_category_id']);
								foreach ($files as $file)
								{
									$filename = $file['model_code'] == 'banner' ? $file['filename'] : '';
								}
								if($filename!='') {
									$job_detail[$key]['cover_image']=HTTP_TYPE.SITE_WWW.'/ao/show/'.$filename;
								}
							}
						}

						if($value['country']=='' || $value['country']=='ALL') {
							$job_detail[$key]['country'] ='ALL';
						} else {
							$countries = $core_db->getAllCountryCodes(json_encode(explode(',',$value['country'])));
							$job_detail[$key]['country'] =implode(", ",array_values($countries));
						}
						
					}

					if(count($job_detail)>0) {
						$data_detail['data']=(object) $job_detail[0];
					} else {
						$data_detail['data']=null;
					}

					// end detail job speedy

					// show job speedy except job code parameter
					$job = $public_db->getAllJobSpeedyByDemand($str_demand,[],false,false,$job_speedy_code);
					$total = count($job);
					
					$per_page = $limit;
					$current_page = $page;
					$total_page = ceil($total / $limit);
					//make sure $page<=$total_page
					if($page>$total_page) {
						$page = $total_page;
						$page = $page<1?1:$page;
						$start = ($page-1) * $limit;
						$current_page = $page;
					}
					$jobs = $public_db->getAllJobSpeedyByDemand($str_demand,[],$start,$limit,$job_speedy_code);
					$count = count($jobs);
					$from = $start+1;
					$to= $start + $count;
					foreach ($jobs as $key => $value) {
						if($value['cover_image']!='') {
							$cover_image = $value['cover_image'];
							$jobs[$key]['cover_image']=HTTP_TYPE.SITE_WWW.'/fm/image/'.$cover_image;
						} else {
							//check from category
							if($value['job_speedy_category_id']!='') {
								$filename = '';
								$files = $category_db->getFiles($value['job_speedy_category_id']);
								foreach ($files as $file)
								{
									$filename = $file['model_code'] == 'banner' ? $file['filename'] : '';
								}
								if($filename!='') {
									$jobs[$key]['cover_image']=HTTP_TYPE.SITE_WWW.'/ao/show/'.$filename;
								}
							}
						}

						if($value['country']=='' || $value['country']=='ALL') {
							$jobs[$key]['country'] ='ALL';
						} else {
							$countries = $core_db->getAllCountryCodes(json_encode(explode(',',$value['country'])));
							$jobs[$key]['country'] =implode(", ",array_values($countries));
						}
						
					}
					$data_detail['other']=$jobs;
					// end show
				} else {
					$data_detail['data']=null;
					$data_detail['other']=array();
				}
				$data_detail['pagination'] = [
					"total" => $total,
					"count" => $count,
					"per_page" => $per_page,
					"current_page" => $current_page,
					"total_page" => $total_page,
					"from" => $from,
					"to" => $to
				];
				$out = $data_detail;
			break; 
		}
		

		header('Content-Type: application/json; charset=utf-8');
		return json_encode($out);
		
    }
}	