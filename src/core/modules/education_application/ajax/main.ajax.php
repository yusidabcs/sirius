<?php
namespace core\modules\education_application\ajax;

final class main extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('main');
        $out = array();
		if ($this->option)
        {
            $this->education_db = new \core\modules\education_application\models\common\db;

            $type = $this->option;
			switch ($type) {
                case 'get':
                    $id = $this->page_options[1];
                    $out = $this->education_db->getDetailCourse($id);
                    //get image course
                    $master_db = new \core\modules\education\models\common\master;
                    $out['image']='';
                    $data_file = $master_db->getFiles($id);
                    if(count($data_file)>0) {
                        $out['image'] = $data_file[0]['filename'];
                    }
                     
                break;
                case 'request':
                    $id = $this->page_options[1];
                    $address_book_id = $_SESSION['address_book_id'];
                    $data_to_db = [
                        'course_id' =>$id,
                        'address_book_id' =>$address_book_id,
                        'status' => 'request'
                    ];
                    $course_request_id = $this->education_db->insertRequestCourse($data_to_db);
                    if($course_request_id>0) {
                        $course = $this->education_db->getDetailCourse($id);
                        
                        $common_db = new \core\modules\education_application\models\common\common;
                        $common_db->senEmailRequestCourse($address_book_id,$course);

                        //insert education tracker
                        $education_workflow_db = new \core\modules\workflow\models\common\education_db();
                        $education_workflow_db->insertEducationTracker($course_request_id,$address_book_id,$id);
                    }
                    $out['message']='Request has been sent!';
                     
                break;
                case 'getMyCourse' :
                    $address_book_id = $_SESSION['address_book_id'];
		            $out = $this->education_db->getAllCourseApp($address_book_id);
                break;
                case 'cancel-course' :
                    $course_request_id = $this->page_options[1];
                    $education_tracker= new \core\modules\workflow\models\common\education_db();
                    $education_db = new \core\modules\education\models\common\request;
                    $update = $education_db->updateStatusRequest($course_request_id,'cancel','cancelled_on');

                    $message = 'No change has made!';
                    $status = '';
                    if($update>0) {
                        $message = 'Successfully update course request status!';
                        $status = 'ok';
                        $update_tracker = $education_tracker->updateEducationTrackerStatus($course_request_id, 'cancel');
                    }
                    $out = [
                        'message' => $message,
                        'status' => $status
                    ];
                break;
                default :
                break;
            }
            return $this->response($out);
        }
    }

}
?>