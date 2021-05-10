<?php
namespace core\modules\recruitment\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$this->authorizeAjax('main');
		$out = null;
        $db_ns = NS_MODULES.'\\recruitment\\models\\common\\db';
        $db = new $db_ns();
		switch($this->option) 
		{	
			case 'dashboard':


                if($this->useEntity)
                    $data = $db->getAllRecruitmentInMonth($this->entity['address_book_ent_id']);
                else
                    $data = $db->getAllRecruitmentInMonth();

                return $this->response($data);

                break;
			case 'export' :
				ob_start();
				$status = $_POST['status'];
				$country = $_POST['country'];
				$partner = $_POST['partner'];
				$job_category = $_POST['job_category'];

				if($this->useEntity) {
					$ent = $this->entity['address_book_ent_id'];
					$partner = '';
                    $data_recruitment = $db->getAllRecruitmentExport($country,$status,$partner,$job_category,$ent);
				} else {
					$data_recruitment = $db->getAllRecruitmentExport($country,$status,$partner,$job_category);
				}

				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				
				$style_border = array(
					
					'borders' => array(
						'allBorders' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => array('rgb' => '000000'),
						),
					),
				);
				$style_header = array(
					'font' => [
						'bold' => true
					],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'startColor' => [
							'rgb' => 'f2f2f2',
						]
					]
				);
					
				$row = 1;
				$sheet->setCellValue('A'.$row, 'No.');
				$sheet->setCellValue('B'.$row, 'Name');
				$sheet->setCellValue('C'.$row, 'Email');
				$sheet->setCellValue('D'.$row, 'Country');
				$sheet->setCellValue('E'.$row, 'Job Category');
				$sheet->setCellValue('F'.$row, 'Partner (LP)');
				$sheet->setCellValue('G'.$row, 'Partner (LEP)');
				$sheet->setCellValue('H'.$row, 'Status');
				$sheet->setCellValue('I'.$row, 'Created');
				$sheet->getStyle('A'.$row.':I'.$row)->applyFromArray($style_header);

				$sheet->getColumnDimension('A')->setAutoSize(true);
				$sheet->getColumnDimension('B')->setAutoSize(true);
				$sheet->getColumnDimension('C')->setAutoSize(true);
				$sheet->getColumnDimension('D')->setAutoSize(true);
				$sheet->getColumnDimension('E')->setAutoSize(true);
				$sheet->getColumnDimension('F')->setAutoSize(true);
				$sheet->getColumnDimension('G')->setAutoSize(true);
				$sheet->getColumnDimension('H')->setAutoSize(true);
				$sheet->getColumnDimension('I')->setAutoSize(true);

				$i=0;
				$generic_obj = \core\app\classes\generic\generic::getInstance();
				foreach ($data_recruitment as $key => $value) {
					$i++;
					$sheet->setCellValue('A'.($row+$i), $i);
					
					$fullname = $generic_obj->getName('per', $value['entity_family_name'], $value['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

					$fullname = $value['title'].' '.$fullname;

					$sheet->setCellValue('B'.($row+$i), $fullname);
					$sheet->setCellValue('C'.($row+$i), $value['main_email']);
					$sheet->setCellValue('D'.($row+$i), $value['country']);
					$partner = $value['partner_name'];
					$partner_lep = $value['partner_lep_name'];

					$partner = ($partner!=null&&$partner!='')?$partner:'No Partner';
					$partner_lep = ($partner_lep!=null&&$partner_lep!='')?$partner_lep:'No Partner';

					$sheet->setCellValue('E'.($row+$i), $value['job_category'] ?? 'No Job Yet');
					$sheet->setCellValue('F'.($row+$i), $partner);
					$sheet->setCellValue('G'.($row+$i), $partner_lep);
					$status = ($value['status']==''||$value['status']==null)?'Unverified':$value['status'];
					$sheet->setCellValue('H'.($row+$i), ucwords($status));
					$sheet->setCellValue('I'.($row+$i), date('d M Y H:i:s',strtotime($value['created_on'])));
				}
				$sheet->getStyle('A'.$row.':I'.($row+$i))->applyFromArray($style_border);

				$writer = new Xlsx($spreadsheet);
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="List_Candidate_'.date('Y-m-d').'.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$writer->save('php://output');
				exit();

			break;
			case 'all-candidate':
				$out['data'] = $db->getAllRecruitmentSelect();
				break;
			case 'request-englist-test':
				
				$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
                if ($contentType === "application/json") {
                    $content = trim(file_get_contents("php://input"));
                    $decoded = json_decode($content, true);
				
					$address_book_id = $decoded['id'];
					$this->_sedEmailRequestFileEnglistTest($address_book_id);

					//update tracker
					$workflow_db = new \core\modules\workflow\models\common\db;
					$workflow = $workflow_db->getActiveWorkflow('workflow_english_test_tracker','address_book_id', $address_book_id);
					if($workflow){
						$workflow = $workflow_db->updateTrackers('workflow_english_test_tracker', $address_book_id, [
							'request_file_on' => date('Y-m-d H:i:s'),
							'request_file_by' => $_SESSION['user_id'],
							'status' => 'request_file',
							'level' => 1,
							'notes' => 'Request english test file'
						]);

					}

					$out = [
						'message' => 'Email sent!',
						'status' => 'ok'
					];
				}
				break;
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
		}
		
		if(!empty($out))
		{
			return $this->response($out);
		} else {
			return ;
		}				
	}

	private function _sedEmailRequestFileEnglistTest($address_book_id) {
		$mailing_common = new \core\modules\send_email\models\common\common;
		$generic = \core\app\classes\generic\generic::getInstance();
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		$address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

		$to_name = $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
		$to_email = $address_book['main_email'];

		//from the system info
		$system_register = \core\app\classes\system_register\system_register::getInstance();
		$from_name = $system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $system_register->site_info('SITE_EMAIL_ADD');

		$template = $mailing_common->renderEmailTemplate('request_englist_test_file', [
			'to_name' => $to_name
			
		]);

		if ($template) {
			$subject = $template['subject'];
		} else {
			$subject = 'Request Englist Test File : ' . SITE_WWW;
		}

		$message = $template['html'];
		
		//cc
		$cc ='';

		//bcc
		if(SYSADMIN_BCC_NEW_USERS)
		{
			$bcc = SYSADMIN_EMAIL;
		} else {
			$bcc = '';
		}

		//html
		$html = true;
		$fullhtml = true;

		//unsubscribe link
		$unsubscribelink = false;

		//generic for the sendmail
		$generic = \core\app\classes\generic\generic::getInstance();
		return $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
	}
	
}
?>