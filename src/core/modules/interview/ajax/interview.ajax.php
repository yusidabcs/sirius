<?php
namespace core\modules\interview\ajax;
use Dompdf\Dompdf;
use Dompdf\Options;
final class interview extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;
    protected $interview_db;

    public function run()
    {
        $this->authorizeAjax('interview');
        if ($this->option)
        {

            $this->interview_db = new \core\modules\interview\models\common\db();
            $type = $this->option;

            if ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'answer' => 'required',
                    'communication_level_skill' => 'required',
                    'status' => 'required',
                    'job_application_id' => 'required',
                    'schedule_id' => 'required',
                    'interviewer_id' => 'required',
                    'type' => 'required',
                ];
                if($data['change_job_speedy'] == 1){
                    $rule['job_speedy_code'] = 'required';
                }
                if($data['job_master_prefer'] == 1){
                    $rule['job_master_id'] = 'required';
                    $rule['prefer_type'] = 'required';
                    $rule['fixed'] = 'required';
                    $rule['reason'] = 'required';
                }
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $arr_answer = array_keys($data['answer']);
                //print_r($arr_answer);

                foreach ($arr_answer as $index => $item){
                    $item_question = $item;
                    $type = key($data['answer'][$item]);
                    $str_aswer = $data['answer'][$item][$type];
                    //echo "<br>question : ".$item_question.", type : ".$type.", answer : ".$str_aswer;
                    $question = $this->interview_db->getQuestion($item_question);
                    $question_id = isset($question['question_id'])?$question['question_id']:'';
                    if($question == false){
                        $type = str_replace("'","",$type);
                        $question_id = $this->interview_db->insertIntreviewQuestion([
                            'question' => $item_question,
                            'type' => $type,
                            'help' => '',
                            'answer_heading' => '',
                            'status' => 1,
                            'locked' => 1
                        ]);
                        
                        if($type=='specific') {
                            
                            if($data['change_job_speedy']){
                                $job_code =  $data['job_speedy_code'];
                            } else {
                                $job_application = $this->interview_db->getDetailJobApplication($data['job_application_id']);
                                $job_code = $job_application[0]['job_speedy_code'];
                            }
                            $this->interview_db->insertQuestionJob([
                                'question_id' => $question_id,
                                'job_speedy_code' => $job_code
                            ]);
                        }
                    }

                    
                    $rs = $this->interview_db->saveQuestionAnswer([
                        "job_application_id" => $data['job_application_id'],
                        "question_id" => $question_id,
                        "text" => $str_aswer
                    ]);
                    if($rs > 0){
                        $this->interview_db->lockIntreviewQuestion($question_id);
                    }
                    
                }
                /*foreach ($data['answer'] as $index => $item){
                    
                    $question = $this->interview_db->getQuestion($index);
                    $question_id = @$question['question_id'];
                    if($question == false){
                        $question_id = $this->interview_db->insertIntreviewQuestion([
                            'question' => $index,
                            'type' => 'general',
                            'help' => '',
                            'answer_heading' => '',
                            'status' => 1,
                            'locked' => 1
                        ]);
                    }
                    $rs = $this->interview_db->saveQuestionAnswer([
                        "job_application_id" => $data['job_application_id'],
                        "question_id" => $question_id,
                        "text" => $item
                    ]);
                    if($rs > 0){
                        $this->interview_db->lockIntreviewQuestion($question_id);
                    }
                }*/

                $rs = $this->interview_db->saveIntreviewResult($data);
                if($rs >= 0){

                    if($data['change_job_speedy']){
                        //update job application job speedy
                        $this->job_db = new \core\modules\job\models\common\db();
                        $this->job_db->changeJobApplicationJobSpeedy($data['job_application_id'], $data['job_speedy_code']);
                    }

                    if($data['job_master_prefer']){
                        //update job application job speedy
                        $this->interview_db->saveIntreviewResultPrefer($data);
                    }

                    //update job application
                    $this->job_db = new \core\modules\job\models\common\db();
                    if($data['status'] == 'hire'){

                        $this->job_db->hireJobApplication($data['job_application_id']);

                    }else if($data['status'] == 'not hire'){
                        $this->job_db->notHireJobApplication($data['job_application_id']);
                    } else if($data['status'] == 'pending'){
                        $this->job_db->pendingJobApplication($data['job_application_id']);
                    }

                    return $this->response([
                        'message' => 'Successfully insert interview result.'
                    ]);
                }

                return $this->response([
                    'errors' => ['Unsuccessfully insert interview result.']
                ],400);

            }

            elseif ( $type == 'result' ){

                $result = $this->interview_db->getIntreviewResult($this->page_options[1]);
                $answer = $this->interview_db->getIntreviewAnswer($this->page_options[1]);

                return $this->response([
                    'result' => $result,
                    'answer' => $answer,
                ]);

            }
            elseif ( $type == 'list_result' ){
                $ent_id = false;
                if($this->useEntity) {
                    $ent_id = $this->entity['address_book_ent_id'];
                }
                $result = $this->interview_db->getIntreviewResultDatatable($ent_id);

                return $this->response($result);

            }
            elseif($type == 'change-status'){
                $out=[];

                $this->job_db = new \core\modules\job\models\common\db();

                $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
					if ($contentType === "application/json") {
						$content = trim(file_get_contents("php://input"));
						$decoded = json_decode($content, true);
						$job_application_id = $decoded['id'];
						$status = $decoded['status'];
						if($job_application_id!='') {
							if($status == 'hired'){

                                $this->job_db->hireJobApplication($job_application_id);
            
                            }else if($status == 'not_hired'){
                                $this->job_db->notHireJobApplication($job_application_id);
                            } else if($status == 'pending'){
                                $this->job_db->pendingJobApplication($job_application_id);
                            }
                            $status = 'ok';
                            $message = 'Status updated succesfully';
						} else {
                            $status = 'no';
                            $message = 'Failed to update status';
						}
					} else {$status = 'no';$message = 'Failed to update status';}
                
                return $this->response([
                    'status' => $status,
                    'message' => $message
                ]);
            }
            elseif ( $type == 'calendar-interview' ){
                $type_interview = $_POST['type'];
                $ent = false;
                if($this->useEntity) {
                    $ent = $this->entity['address_book_ent_id'];
                }

                if($type_interview=='online') {
                    $data_interview = $this->interview_db->getTotalInterviewOnlinePerDay($ent);
                } else {
                    $data_interview = $this->interview_db->getTotalInterviewLocationPerDay($ent);
                    //print_r($data_interview);
                }

                $result=[];
                foreach ($data_interview as $item) {
                    $result[] = [
                        "title"=> $item['total_interview'],
                        "start"=> $item['start_interview'],
                        "end"=> $item['finish_interview'],
                        "type"=>$type_interview,
                        "id"=>$type_interview."_".$item['start_interview']
                    ];
                }

                return $this->response($result);
            } elseif ( $type == 'calendar-event' ){
                $generic = \core\app\classes\generic\generic::getInstance();
                //$type_interview = $_POST['type'];
                $ent = false;
                if($this->useEntity) {
                    $ent = $this->entity['address_book_ent_id'];
                }
                
                $result = [];
                $data = $_POST;
                $id = list($type,$date) = explode("_",$data['id']);

                if($data['type']=='online') {
                    $interview_online = $this->interview_db->getOnlineInterviewList($ent,$date);
                    //print_r($interview_online);
                    $html ='
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Job</th>
                                    <th>Schedule On</th>
                                    <th>Interviewer</th>
                                    <th>Partner</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                    ';
                    if(count($interview_online)>0) {
                        $html .='
                            <tbody>';
                        foreach ($interview_online as $item) {
                            $candidate_name = $generic->getName('per', $item['entity_family_name'], $item['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                            $lp_name = $generic->getName('per', $item['partner_entity_family_name'], $item['partner_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                            $interviewer_name = '-';
                            if($item['interviewer_id']>0) {
                                $interviewer_name = $generic->getName('per', $item['interviewer_entity_family_name'], $item['interviewer_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                            }

                            $html .='
                            <tr>
                                <td>'.$candidate_name.'<br>'.$item['main_email'].'</td>
                                <td>'.$item['job_title'].'</td>
                                <td>'.date('d M Y H:i',strtotime($item['schedule_on'])).'<br>('.$item['timezone'].')</td>
                                <td>'.$interviewer_name.'</td>
                                <td>'.$lp_name.'</td>
                                <td>';

                                if($item['interview_result_id']==null) {
                                    $html .='
                                    <a  class="btn-sm btn-info btn-set-interview" href="#" data-schedule-id="'.$item['schedule_id'].'" data-interviewer-id="'.$item['interviewer_id'].'"><i class="fa fa-user" title="Select Interviewer"></i></a>
                                    ';
                                }

                                if($item['interviewer_id']!=0 && $item['status']=='interview') {
                                    $html .='
                                        <a  class="btn-sm btn-success btn-interview" href="/interview/interview/'.$item['schedule_id'].'"  ><i class="fa fa-comment" title="Do Interview"></i></a>
                                    ';
                                }
                                $html .= '</td>
                            </tr>
                            ';
                        }   
                        $html .=' </tbody>
                            </table>
                        ';
                    } else {
                        $html .='
                            <tbody>
                                <tr><td colspan="6">Data interview not found!</td></tr>
                            </tbody>
                            </table>
                        ';
                    }
                } else {
                    $ongoing_location = $this->interview_db->getOnGoingInterviewLocation($ent,$date);
                    $html ='
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Organizer</th>
                                    <th>Address</th>
                                    <th>Date</th>
                                    <th>Total Candidate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                    ';
                    if(count($ongoing_location)>0) {
                        $html .='
                            <tbody>';
                        foreach ($ongoing_location as $item) {
                            $html .='
                            <tr>
                                <td>'.$item['organizer'].'</td>
                                <td>'.$item['address'].'</td>
                                <td>'.date('d M Y H:i',strtotime($item['start_on'])).'</td>
                                <td>'.$item['total_candidate'].'</td>
                                <td>
                                    <a class="btn-sm btn-info text-white" href="/interview/detail_location/'.$item['interview_location_id'].'" title=""><i class="fas fa-users"></i></a>
                                </td>
                            </tr>
                            ';
                        }   
                        $html .=' </tbody>
                            </table>
                        ';
                    } else {
                        $html .='
                            <tbody>
                                <tr><td colspan="4">Data interview not found!</td></tr>
                            </tbody>
                            </table>
                        ';
                    }
                }
                $result = [
                    'type' => $data['type']=='online'?'Online Interview':'Physical Interview',
                    'date' => date('d M Y', strtotime($id[1])),
                    'modal_body' => $html
                ];
                return $this->response($result);
            } elseif ( $type == 'pdf-interview'){ 
                $generic = \core\app\classes\generic\generic::getInstance();

                if(!isset($this->page_options[1])||$this->page_options[1]==''){
                    $html_ns = NS_HTML.'\\htmlpage';
                    $htmlpage = new $html_ns(404);
                    exit();
                }
                $job_application_id =  $this->page_options[1];
                $result = $this->interview_db->getIntreviewResult($job_application_id);
                $answer = $this->interview_db->getIntreviewAnswer($job_application_id);

                $candidate_name = $generic->getName('per', $result['candidate_entity_family_name'], $result['candidate_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $interviewer_name = $generic->getName('per', $result['interviewer_entity_family_name'], $result['interviewer_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $html = '
                    <title>Interview Result</title>
                    <style type="text/css">
                        .container {
                            font-family: "arial", sans-serif;
                            margin : 0 auto;
                            
                            padding-top : 50px;
                        }
                        table {
                            border-collapse: collapse;
                            width:100%;
                            font-size : 12px;
                            table-layout:fixed;
                          }
                          table, td {
                            border : 1px solid black;
                          }
                        table td {
                            padding : 10px;
                        }
                        table td.heading-table {
                            font-weight : bold;
                            background-color : #d6d6d6;
                            text-align : center
                        }  
                        .title {
                            text-align: center;
                        }
                        .content-result {
                            margin-top : 10px;
                        }
                    </style>
                    <div class="container">
                          <h2 class="title">INTERVIEW RESULT</h2>
                        <div class="content-result">
                            <table width="100%">
                                <tr><td colspan="2" class="heading-table">REQUIRED INTERVIEW INFORMATION</td></tr>
                                <tr><td colspan="2">Candidate Name:<br> <b>'.$candidate_name.'</b></td></tr>
                                <tr><td colspan="2">Interviewer Name:<br> <b>'.$interviewer_name.'</b></td></tr>
                                <tr><td colspan="2">Date of Interview:<br> <b>'.date('F j, Y',strtotime($result['created_on'])).'</b></td></tr>
                                <tr><td colspan="2">Position Interviewed For:<br> <b>'.$result['job_title'].'</b></td></tr>
                                <tr><td colspan="2">Interview Type:<br> <b>'.$result['type'].'</b></td></tr>

                                <tr>
                                    <td colspan="2" style="text-align:center">
                                        <span style="color : #f01313";">
                                        Thank you for taking time to apply and interview with me today for '.$result['job_title'].' POSITION. We want to be sure to give
                                        you the opportunity to demonstrate how you will contribute to our organization and also how being a part of our organization is a journey that will shape your life
                                        </span>
                                        <br>
                                        <span style="font-style: italic;">
                                        <b>Remember that you are representing our company and the candidate should be treated as a guest – your role as the recruiter is to
                                        assess if the candidate would be a good fit for our organization and they share our values and vision:</b>~ Service with a friendly greeting and a smile ~ Anticipate the needs of our guests and make all efforts to exceed our clients’ expectations ~ All of us take ownership of any problem that is brought to our attention ~ We engage in conduct that enhances our corporate $resultreputation and
                                        employee morale ~ We are committed to act in the highest ethical manner and respect the rights and dignity of others ~ We are loyal to our brands and strive for continuous improvement in everything we do
                                        </span>
                                        <br>
                                        <span style="font-style: italic;">
                                        <b>Advise the candidate that the interview will take between 15-30 minutes and that you will ask questions and that you will be taking notes. Let them know there will be time at the end for them to ask questions</b>
                                        </span>

                                    </td>
                                </tr>
                                <tr><td colspan="2" class="heading-table">PRE-SCREEN QUESTIONS: General</td></tr>';
                                $i=0;
                                foreach ($answer as $item_answer) {
                                    if($item_answer['type']=='general') {
                                        $i++;
                                        $html .='
                                            <tr>
                                                <td width="10%">'.$i.'</td>
                                                <td>'.$item_answer['question'].'<br><b>'.$item_answer['text'].'</b></td>
                                            </tr>
                                        ';
                                        
                                    }
                                    
                                }
                                if($i==0) {
                                    $html .='
                                            <tr>
                                                <td style="text-align:center" colspan="2">Data interview (General) not found!</td>
                                            </tr>
                                        ';
                                }
                                $html .='<tr><td colspan="2" class="heading-table">PRE-SCREEN QUESTIONS: Spesific</td></tr>';
                                $i=0;
                                foreach ($answer as $item_answer) {
                                    if($item_answer['type']=='specific') {
                                        $i++;
                                        $html .='
                                            <tr>
                                                <td width="10%">'.$i.'</td>
                                                <td>'.$item_answer['question'].'<br><b>'.$item_answer['text'].'</b></td>
                                            </tr>
                                        ';
                                    }
                                    
                                }
                                if($i==0) {
                                    $html .='
                                            <tr>
                                                <td style="text-align:center" colspan="2">Data interview (Specific) not found!</td>
                                            </tr>
                                        ';
                                }
                                $comment = $result['interview_comment']!=''?$result['interview_comment']:'-';
                                $arr_scale = ['Highly Unfavorable','Unfavorable','Acceptable','Favorable','Excellent'];
                                $pos = array_search($result['communication_level_skill'], $arr_scale);
                                $pos = $pos+1;

                                $style = 'font-weight:bold;font-size:16px';
                                $active1 = $pos==1?$style:'';
                                $active2 = $pos==2?$style:'';
                                $active3 = $pos==3?$style:'';
                                $active4 = $pos==4?$style:'';
                                $active5 = $pos==5?$style:'';    

                                $html .= '<tr><td colspan="2"><b>Comment:</b><br>'.$comment.'</td></tr>
                                <tr><td colspan="2"><b>COMMUNICATION RATING SCALE: '.$pos.'</b></td></tr>
                                <tr><td colspan="2">
                                    <table>
                                        <tr>
                                            <td style="text-align:center;'.$active5.'">5</td>
                                            <td style="text-align:center;'.$active4.'">4</td>
                                            <td style="text-align:center;'.$active3.'">3</td>
                                            <td style="text-align:center;'.$active2.'">2</td>
                                            <td style="text-align:center;'.$active1.'">1</td>
                                        </tr>
                                        <tr>
                                            <td style="'.$active5.'" class="heading-table">Excellent</td>
                                            <td style="'.$active4.'" class="heading-table">Favorable</td>
                                            <td style="'.$active3.'" class="heading-table">Acceptable</td>
                                            <td style="'.$active2.'" class="heading-table">Unfavorable</td>
                                            <td style="'.$active1.'" class="heading-table">Highly Unfavorable</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;vertical-align:top">
                                            Clearly spoke throughout the
                                            interview; Answered questions
                                            clearly; indicated he/she was
                                            engagement throughout the
                                            interview; Provided focused
                                            responses.<br><br>
                                            First language may not be English,
                                            has a non-native accent, a lack of
                                            native slang or expressions, a
                                            limited control of deep cultural
                                            language and/or an occasional
                                            isolated language error may still be
                                            present at this level
                                            </td>

                                            <td></td>

                                            <td style="text-align:center;vertical-align:top">
                                            Clearly spoke and in an
                                            engaged manner for most of
                                            the interview; Provided clear
                                            responses, the questions
                                            were handled concretely and
                                            described appropriately using
                                            the time frames of past,
                                            present, and future. Spoke in
                                            well-constructed paragraphs
                                            responding to questions
                                            directly and succinctly.
                                            He/She is easily understood
                                            by native English language
                                            speakers, including those
                                            unaccustomed to non-native
                                            speech.
                                            </td>
                                            <td></td>

                                            <td style="text-align:center;vertical-align:top">
                                            Did not articulate
                                            responses; Lost focus
                                            throughout interview
                                            and did not provide
                                            logical explanations;
                                            was only able to
                                            communicate in short
                                            sentences; primarily
                                            used isolated words
                                            and phrases.
                                            </td>
                                        </tr>
                                
                                    </table>
                                </td></tr>
                            </table>
                        </div>
                    </div>
                ';
                //echo $html;
                // instantiate and use the dompdf class  
				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);

				// (Optional) Setup the paper size and orientation
				$dompdf->setPaper('A4', 'portrait');

				// Render the HTML as PDF
				$dompdf->render();

				// Output the generated PDF to Browser
				$dompdf->stream('interviewResult.pdf',array("Attachment" => false));
            }
        }
    }


}
?>