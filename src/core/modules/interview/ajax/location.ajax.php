<?php

namespace core\modules\interview\ajax;

/**
 * Final default class.
 *
 * @final
 * @package    interview
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright    Martin O'Dee 22 August 2019
 */
final class location extends \core\app\classes\module_base\module_ajax
{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('location');
        //we are going to need
        $interview_db = new \core\modules\interview\models\common\db();


        switch ($this->option) {
            case 'insert':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'interview_title' => 'required',
                    'interview_description' => 'required',
                    'start_on_date' => 'required',
                    'finish_on_date' => 'required',
                    'start_on_time' => 'required',
                    'finish_on_time' => 'required',
                    'countryCode_id' => 'required',
                    'countrySubCode_id' => 'required',
                    'address' => 'required',
                    'google_map' => 'required',
                    'status' => 'required',
                    'visible' => 'required',
                ];
                if(!$this->useEntity) {
                    $rule = ['organizer_id' => 'required'];
                } else {
                    $data['organizer_id'] = $this->entity['address_book_ent_id'];
                }
                $data['start_on'] = date('Y-m-d H:i:s', strtotime($data['start_on_date'].' '.$data['start_on_time']));
                $data['finish_on'] = date('Y-m-d H:i:s', strtotime($data['finish_on_date'].' '.$data['finish_on_time']));
                //check if all data is exist but not it's validity
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $interview_db->insertInterviewLocation($data);
                if($rs >= 0){
                    return $this->response([
                        'message' => 'Successfully insert data'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully insert data'
                    ]);
                }
                break;
            case 'update':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'interview_location_id' => 'required',
                    'interview_title' => 'required',
                    'interview_description' => 'required',
                    'start_on_date' => 'required',
                    'finish_on_date' => 'required',
                    'start_on_time' => 'required',
                    'finish_on_time' => 'required',
                    'countryCode_id' => 'required',
                    'countrySubCode_id' => 'required',
                    'address' => 'required',
                    'google_map' => 'required',
                    'status' => 'required',
                    'visible' => 'required',
                ];
                if(!$this->useEntity) {
                    $rule = ['organizer_id' => 'required'];
                } else {
                    $data['organizer_id'] = $this->entity['address_book_ent_id'];
                }
                $data['start_on'] = date('Y-m-d H:i:s', strtotime($data['start_on_date'].' '.$data['start_on_time']));
                $data['finish_on'] = date('Y-m-d H:i:s', strtotime($data['finish_on_date'].' '.$data['finish_on_time']));
                //check if all data is exist but not it's validity
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                $rs = $interview_db->updateInterviewLocation($data);

                if($rs >= 0){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }

                break;
            case 'delete':

                $id = $this->page_options[1];
                $rs = $interview_db->deleteInterviewLocation($id);

                if($rs){
                    return $this->response([
                        'message' => 'Successfully delete data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully delete data']
                    ],400);
                }
                break;
            case 'list':
                $core_db = new \core\app\classes\core_db\core_db();
                $countries = $core_db->getAllCountryCodes();
                $subCountries = $core_db->getAllSubCountry();
                    
                if($this->useEntity) {
                    $rs = $interview_db->getInterviewLocationDatatable($countries,$subCountries,$this->entity['address_book_ent_id']);
                } else {
                    $rs = $interview_db->getInterviewLocationDatatable($countries,$subCountries);
                }
                return $this->response($rs);

                break;
            case 'polling':
                $rs = $interview_db->getActiveInterviewLocation();

                return $this->response($rs);

                break;

            case 'nonactive':
                $rs = $interview_db->nonActiveInterviewLocation();

                return $this->response($rs);

                break;

            case 'close':

                $schedule_id = $this->page_options[1];

                $rs = $interview_db->closeInterviewLocation($schedule_id);

                return $this->response($rs);

                break;
        }


    }

}

?>