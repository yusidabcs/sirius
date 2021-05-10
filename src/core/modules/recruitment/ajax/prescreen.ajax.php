<?php

namespace core\modules\recruitment\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends        module_ajax
 * @package    recruitment
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class prescreen extends \core\app\classes\module_base\module_ajax
{

    protected $optionRequired = false;

    public function run()
    {
        $this->authorizeAjax('prescreen');
        switch ($this->option) {
            case 'insert':

                $data = $_POST;
                $this->db = new \core\modules\recruitment\models\common\db();
                $errors = [];
                // check type, jobapplication id
                if (empty($data['job_application_id']))
                    $errors['Job Application Id'] = 'Cannot be empty';

                if (empty($data['type']))
                    $errors['Type'] = 'Cannot be empty';

                //check bulk data
                foreach ($data['pre_screen'] as $key => $answers) {
                    if (isset($answers['parent_id'])) {
                        $answer_parent = $data['pre_screen'][$answers['parent_id']]['answer'] == 'yes' ? 1 : 0;

                        if ($answers['show_child'] == $answer_parent) {

                            if (empty($answers['answer']) && empty($answers['text'])){
                                $errors[$answers['question_text']] = 'Details cannot be empty';
                            }

                        }

                    } else {
                        if (isset($answers['text'])) {
                            $answers['text'] = trim($answers['text']);
                        } else {
                            $answers['text'] = '';
                        }
                        if (isset($answers['more'])) {
                            if (!isset($answers['answer'])) {
                                $errors[$answers['question_text']] = 'Must select one of the options';
                                $data['pre_screen'][$key]['answer'] = '';
                            } else {
                                //true false question
                                $bool_answer = ($answers['answer'] == 'yes') ? 1 : 0;
                                //if need more answer // ignore if has child
                                if ($answers ['more'] == $bool_answer && (isset($answers['child']) && $answers['child'] == 0)) {
                                    //insert to answer text
                                    if (empty($answers['text']))
                                        $errors[$answers['question_text']] = 'Details cannot be empty';
                                }
                            }

                        } else {
                            //insert plain answer text for short answer question
                            if (empty($answers['text']))
                                $errors[$answers['question_text']] = 'Details cannot be empty';

                        }
                    }


                }

                if (count($errors) > 0) {
                    return $this->response($errors, 400);
                }


                //insert or get job prescreen
                $job_prescreen = $this->db->getJobPrescreen($data['job_application_id']);
                if (!$job_prescreen) {
                    $data['hash'] = md5($data['job_application_id'] . date('Y-m-d h:i:s'));
                    $this->db->insertJobPrescreen($data);
                    $job_prescreen = $this->db->getJobPrescreen($data['job_application_id']);
                }

                //if everything check, insert to database
                foreach ($data['pre_screen'] as $key => $answers) {

                    if (isset($answers['parent_id'])) {
                        $answer_parent = $data['pre_screen'][$answers['parent_id']]['answer'] == 'yes' ? 1 : 0;

                        if ($answers['show_child'] != $answer_parent) {
                            continue;
                        }
                    }

                    // init data default template
                    $insertData = array(
                        'job_application_id' => $data['job_application_id'],
                        'type' => $data['type'],
                        'question_id' => $key
                    );
                    if (isset($answers['text'])) {
                        $answers['text'] = trim($answers['text']);
                    } else {
                        $answers['text'] = '';
                    }
                    //check if has more options
                    if (isset($answers['more'])) {
                        $answers['answer'] = trim($answers['answer']);
                        //true false question
                        $bool_answer = ($answers['answer'] == 'yes') ? 1 : 0;
                        $insertData['answer'] = $answers['answer'];
                        $this->db->insertJobApplicationInterviewAnswer($insertData, 'prescreen');

                        //if need more answer
                        if ($answers ['more'] == $bool_answer) {
                            //insert to answer text
                            $insertData['text'] = $answers['text'];
                            $this->db->insertJobApplicationInterviewAnswerText($insertData, 'prescreen');
                        } else {
                            //delete previous more answer if not needed;
                            $this->db->deleteJobApplicationInterviewAnswerText($key,$data['job_application_id'],'prescreen');
                        }

                    } else {
                        //insert plain answer text for short answer question
                        $insertData['text'] = $answers['text'];
                        $this->db->insertJobApplicationInterviewAnswerText($insertData, 'prescreen');
                    }
                }

                return $this->response($job_prescreen, 200);
                break;

            case 'get':
                $data = $_POST;
                $this->core_db = new \core\app\classes\core_db\core_db;
                $this->db = new \core\modules\recruitment\models\common\db();
                //get job app
                $job_application_id = $this->page_options[1];

                //get prescreen
                $job_prescreen = $this->db->getJobPrescreen($job_application_id);
                if (!$job_prescreen) {
                    return;
                }

                $questions = $this->core_db->getPreIntreviewQuestion();
                $answers = $this->db->getJobApplicationInterviewAnswer($job_application_id, 'prescreen');

                $rs = [];
                foreach ($questions as $index => $item) {
                    $item['childs'] = [];
                    if ($item['parent_id'] == 0) {

                        foreach ($questions as $index2 => $item2) {
                            if ($item2['parent_id'] == $item['question_id']) {
                                if(isset($answers[$item2['question_id']])){

                                    $item['childs'][] = $item2;
                                }
                            }
                        }
                        $rs[] = $item;
                    }
                }


                $this->core_db = new \core\app\classes\core_db\core_db;
                $this->recruitment_db = new \core\modules\recruitment\models\common\db();
                $this->job_db = new \core\modules\job\models\common\db();
                $this->generic = \core\app\classes\generic\generic::getInstance();
                $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
                $this->personal_db = new \core\modules\personal\models\common\db;

                $job_application_id = $this->page_options[1];
                //check if job application valid
                if (!$job_application = $this->job_db->getJobApplication($job_application_id)) {
                    die('No job application');
                    exit();
                }
                $address_book_id = $job_application['address_book_id'];
                $prescreener_main = $this->address_book_db->getAddressBookMainDetails($_SESSION['address_book_id']);
                $main = $this->address_book_db->getAddressBookMainDetails($address_book_id);
                $partner_main = $this->personal_db->getLocalPartnerDataByAddressBookId($address_book_id);

                $applicant['job_position'] = $job_application['job_title'];
                $applicant['job_status'] = $job_application['status'];
                $applicant['full_name'] = $this->generic->getName('per', $main['entity_family_name'], $main['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $applicant['prescreener_full_name'] = $this->generic->getName('per', $prescreener_main['entity_family_name'], $prescreener_main['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $applicant['partner_name'] = $partner_main['entity_family_name'];
                $applicant['email'] = $main['main_email'];


                $data = [
                    'job_prescreen' => $job_prescreen,
                    'questions' => $rs,
                    'answers' => $answers,
                    'applicant' => $applicant,
                ];
                return $this->response($data);

                break;

            case 'check':
                $this->db = new \core\modules\recruitment\models\common\db();
                //get job app
                $job_application_id = $this->page_options[1];

                //get prescreen
                $job_prescreen = $this->db->getJobPrescreen($job_application_id);
                if (!$job_prescreen) {
                    return $this->response([
                        'result' => false
                    ]);
                }
                return $this->response($job_prescreen);
                break;

            case 'sendemail':
                $data = $_POST;
                $this->core_db = new \core\app\classes\core_db\core_db;
                $this->recruitment_db = new \core\modules\recruitment\models\common\db();
                $this->job_db = new \core\modules\job\models\common\db();
                $this->generic = \core\app\classes\generic\generic::getInstance();
                $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
                $this->personal_db = new \core\modules\personal\models\common\db;

                $job_application_id = $this->page_options[1];
                //check if job application valid
                if (!$job_application = $this->job_db->getJobApplication($job_application_id)) {
                    die('No job application');
                    exit();
                }
                $address_book_id = $job_application['address_book_id'];
                $prescreener_main = $this->address_book_db->getAddressBookMainDetails($_SESSION['address_book_id']);
                $main = $this->address_book_db->getAddressBookMainDetails($address_book_id);
                $partner_main = $this->personal_db->getLocalPartnerDataByAddressBookId($address_book_id);

                $applicant['job_position'] = $job_application['job_title'];
                $applicant['full_name'] = $this->generic->getName('per', $main['entity_family_name'], $main['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $applicant['prescreener_full_name'] = $this->generic->getName('per', $prescreener_main['entity_family_name'], $prescreener_main['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $applicant['partner_name'] = $partner_main['entity_family_name'];
                $applicant['email'] = $main['main_email'];


                //get prescreen
                $job_prescreen = $this->recruitment_db->getJobPrescreen($job_application_id);
                if (!$job_prescreen) {
                    die('No prescreen');
                    exit();
                }

                $questions = $this->core_db->getPreIntreviewQuestion();
                $answers = $this->recruitment_db->getJobApplicationInterviewAnswer($job_application_id, 'prescreen');
                
                $rs = [];
                foreach ($questions as $index => $item) {
                    $item['childs'] = [];
                    if ($item['parent_id'] == 0) {

                        foreach ($questions as $index2 => $item2) {
                            if ($item2['parent_id'] == $item['question_id']) {
                                if(isset($answers[$item2['question_id']])){

                                    $item['childs'][] = $item2;
                                }
                            }
                        }
                        $rs[] = $item;
                    }
                }
                $questions = $rs;

                $menu_register_ns = NS_APP_CLASSES . '\\menu_register\\menu_register';
                $menu_register = $menu_register_ns::getInstance();

                $job_application_link = $menu_register->getModuleLink('job_application');
                $accept_link = HTTP_TYPE . SITE_WWW . '/' . $job_application_link . '/prescreen/' . $job_prescreen['hash'] . '/accepted';
                $revision_link = HTTP_TYPE . SITE_WWW . '/' . $job_application_link . '/prescreen/' . $job_prescreen['hash'] . '/revision';

                $_system_ini_a = parse_ini_file(DIR_SECURE_INI.'/system_config.ini');
                if ($data['by_pass'] == 1 && $_system_ini_a['BYPASS_USER_PROCESS'] == 1) {

                    $this->recruitment_db->acceptJobPrescreen($job_application_id);
                    return $this->response([
                        'message' => 'Prescreen was completed.'
                    ]);

                    break;
                } else {

                    ob_start();
                    include(DIR_MODULES . '/recruitment/views/prescreen_form/email.php');
                    $emailMessage = ob_get_contents();
                    ob_end_clean();
    
                    $this->_sendEmailToCandidate($applicant['full_name'], $main['main_email'], $emailMessage);
                    $this->recruitment_db->sendingJobPrescreen($job_application_id);
                }

                return $this->response([
                    'message' => 'Successfully send mail to candidate.'
                ]);
                break;

            default:
                $this->db = new \core\modules\recruitment\models\common\db();
                if ($this->useEntity)
                    $job_prescreen = $this->db->getJobPrescreenDatatable($this->entity['address_book_ent_id']);
                else
                    $job_prescreen = $this->db->getJobPrescreenDatatable();

                return $this->response($job_prescreen);
                break;

        }
    }

    private function _sendEmailToCandidate($to_name, $main_email, $message)
    {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;

        $to_email = $main_email;

        //from the system info
        $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $template = $mailing_common->renderEmailTemplate('prescreen_result_preview', [
            'content' => $message
        ]);

        if ($template) {
            $subject = $template['subject'];
        } else {
            $subject = 'Pre-Screen Result Preview : ' . SITE_WWW;
        }

        $message = $template['html'];

        //cc
        $cc = '';

        $bcc = '';

        //html
        $html = true;
        $fullhtml = true;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $this->generic->sendEmail($to_name, $to_email, $from_name, $from_email, $subject, $message, $cc, $bcc, $html, $fullhtml, $unsubscribelink);

        return;
    }


}

?>