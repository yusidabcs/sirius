<?php
namespace core\modules\job\ajax;

final class jobspeedy extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('jobspeedy');
        if ($this->option)
        {
            $this->job_db = new \core\modules\job\models\common\db;

            $type = $this->option;

            if ( $type == 'list' )
            {
                $out = $this->job_db->getAllJobSpeedyDatatable();

            }
            elseif ( $type == 'get' )
            {
                $id = $this->page_options[1];
                $out = $this->job_db->getJobSpeedy($id);
            }
            elseif ( $type == 'jobmaster' )
            {
                $code = $this->page_options[1];
                $out = $this->job_db->getJobMasterByJobSpeedyDatatable($code);
            }
            elseif ( $type == 'job_master_list' )
            {
                $code = $this->page_options[1];
                $out = $this->job_db->getJobMasterByJobSpeedy($code);
            }
            elseif ( $type == 'checkjobcode' )
            {
                $out = null;
                $partner_code = $_POST['code'];
                $id = isset($_POST['id']) ? $_POST['id'] : false;
                $out = $this->job_db->checkJobCodeSpeedyExist($partner_code, $id);

                if($out['duplicate']){
                    $out['message'] = 'Job code is already in system. Please use another job code.';
                }else{
                    $out['message'] = 'Good to go.';
                }
            }
            elseif ( $type == 'insert' )
            {
                $data = $_POST;
                $data['min_requirement'] = MIN_REQUIREMENT;
                $rule = [
                    'job_speedy_code' => 'required|min:3|max:4',
                    'job_title' => 'required|max:100',
                    'short_description' => 'required|max:255',
                    'min_requirement' => 'required',
                    'min_experience' => 'required',
                    'min_education' => 'required',
                    'stcw_req' => 'required',
                    'min_english_experience' => 'required|int',
                    'job_speedy_category_id' => 'required|int',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                
                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                if(isset($_POST['countries'])) {
                    $countries = $_POST['countries'];
                    $country = implode(',',$countries);
                } else {
                    $country = 'ALL';
                }
                $data['country'] = $country;
                
                $affected_rows = $this->job_db->insertJobSpeedy($data);

                //upload image
                if($_FILES['cover_image']['name'] != "") { 
                    $link_id = 'job';
                    $type = $_POST['job_speedy_code'];
                    $content_id = 'job_speedy';

                    $title = 'No Title';
                    $sdesc = 'No Description';
                    $security_level_id = 'NONE';
                    $group_id = 'ALL';
                    $status = 1;

                    $fileUpload_a['name'] = $_FILES['cover_image']['name'];
                    $fileUpload_a['type'] = $_FILES['cover_image']['type'];
                    $fileUpload_a['tmp_name'] = $_FILES['cover_image']['tmp_name'];
                    $fileUpload_a['error'] = $_FILES['cover_image']['error'];
                    $fileUpload_a['size'] = $_FILES['cover_image']['size'];

                    $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
                    $file_manager = $file_manager_ns::getInstance();

                    //get latest sequence
                    $sequence = $file_manager->file_manager_db->getLatestSequence($link_id,$content_id,$type);

                    $file_manager->setLinkInfo($link_id,$content_id,$type);

                    $data = $file_manager->addFiles($title, $sdesc, $sequence, $security_level_id, $group_id, $status, $fileUpload_a, false);

                    $cover_image = $data['file_obj']['file_manager_id'];
                    //update filename job speedy
                    $this->job_db->updateCoverImageJobSpeedy($_POST['job_speedy_code'],$cover_image);
                }

                //update to job master if set job master
                $job_masters=[];
                if(isset($_POST['job_masters'])) {
                    $job_masters = $_POST['job_masters'];
                }
                foreach ($job_masters as $key => $code) {
                    $rs = $this->job_db->updateJobMasterfield($code,'job_speedy_code',$_POST['job_speedy_code']);
                }

                if($affected_rows != -1)
                {
                    $out['message'] = 'Add new job speedy success';
                }else{
                    $out['message'] = 'Problem in insert job speedy.';
                    return $this->response($out,500);
                }
            }
			elseif ( $type == 'update' )
            {
                $data = $_POST;
                $data['min_requirement'] = MIN_REQUIREMENT;
                $rule = [
                    'e_job_speedy_code' => 'required|min:3|max:4',
                    'e_job_title' => 'required|max:100',
                    'e_short_description' => 'required|max:255',
                    'min_requirement' => 'required',
                    'e_min_experience' => 'required',
                    'e_min_education' => 'required',
                    'e_stcw_req' => 'required',
                    'e_min_english_experience' => 'required|int',
                    'e_job_speedy_category_id' => 'required|int',
                    'e_min_salary' => 'required|int',
                    'e_max_salary' => 'required|int',
                    'old_job_speedy_code' => 'required|min:3|max:4',
                ];

                $validator = new \core\app\classes\validator\validator($data, $rule);
                
                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                if(isset($_POST['e_countries'])) {
                    $countries = $_POST['e_countries'];
                    $country = implode(',',$countries);
                } else {
                    $country = 'ALL';
                }
                $data['country'] = $country;
                
                //upload image
                if($_FILES['e_cover_image']['name'] != "") { 
                    $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
                    $file_manager = $file_manager_ns::getInstance();
                    if($_POST['image_prev']!='') {
                        //delete file
                        $file_manager->deleteFile($_POST['image_prev']);
                    }
                    $link_id = 'job';
                    $type = $_POST['e_job_speedy_code'];
                    $content_id = 'job_speedy';

                    $title = 'No Title';
                    $sdesc = 'No Description';
                    $security_level_id = 'NONE';
                    $group_id = 'ALL';
                    $status = 1;

                    $fileUpload_a['name'] = $_FILES['e_cover_image']['name'];
                    $fileUpload_a['type'] = $_FILES['e_cover_image']['type'];
                    $fileUpload_a['tmp_name'] = $_FILES['e_cover_image']['tmp_name'];
                    $fileUpload_a['error'] = $_FILES['e_cover_image']['error'];
                    $fileUpload_a['size'] = $_FILES['e_cover_image']['size'];

                    

                    //get latest sequence
                    $sequence = $file_manager->file_manager_db->getLatestSequence($link_id,$content_id,$type);

                    $file_manager->setLinkInfo($link_id,$content_id,$type);

                    $data_file = $file_manager->addFiles($title, $sdesc, $sequence, $security_level_id, $group_id, $status, $fileUpload_a, false);

                    $cover_image = $data_file['file_obj']['file_manager_id'];
                    $data['cover_image'] = $cover_image; 
                } else {
                    $data['cover_image'] = $_POST['image_prev'];
                }


                $affected_rows = $this->job_db->updateJobSpeedy($data);

                //update to job master if set job master
                $job_masters=[];
                if(isset($_POST['e_job_masters'])) {
                    $job_masters = $_POST['e_job_masters'];
                }
                foreach ($job_masters as $key => $code) {
                    $rs = $this->job_db->updateJobMasterfield($code,'job_speedy_code',$_POST['e_job_speedy_code']);
                }

                if($affected_rows != -1)
                {
                    $out['message'] = 'Update job speedy success';
                }else{
                    $out['message'] = 'Problem in update job speedy.';
                    return $this->response($out,500);
                }

            }
			elseif ( $type == 'delete' )
            {
                $data = $_POST;                

                $rule = [
                    'job_speedy_code' => 'required'
                ];

                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

				$affected_rows = $this->job_db->softDeleteJobSpeedy($data['job_speedy_code']);

				if($affected_rows != -1)
				{
                    $out['response'] = 'Success';
                    $out['message'] = 'Success deleting data ';
				}else{
                    $out['message'] = 'Problem in soft delete job speedy with.';
                    return $this->response($out,500);
                }
            }

            elseif ( $type == 'demand' )
            {
                $out = $this->job_db->JobSpeedyDemand();
            }

            elseif ($type == 'updateSequence') {
                $data = $_POST;
                $rule = [
                    'sequence' => 'required|int',
                    'job_speedy_code' => 'required',
                ];

                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }


                $affected_rows = $this->job_db->updateJobSequence($data);

                if($affected_rows != -1)
                {
                    $out['message'] = 'Update premium status success';
                }else{
                    $out['message'] = 'Problem in update premium status.';
                    return $this->response($out,500);
                }
            } else if ($type == 'getJobMaster') {
                $data = $_POST;
                $principal = $_POST['principal'];
                $brand = $_POST['brand'];

                $data_job_master = $this->job_db->getListJobMaster($principal,$brand);

                $out = $data_job_master;
            }

            return $this->response($out);
        }
    }

}
?>