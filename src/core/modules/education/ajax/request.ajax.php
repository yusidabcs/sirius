<?php
namespace core\modules\education\ajax;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
final class request extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$out = array();
		if ($this->option)
        {
            $this->education_db = new \core\modules\education\models\common\request;
			$this->education_tracker = new \core\modules\workflow\models\common\education_db;
            $type = $this->option;
			switch ($type) {
				case 'list':
					if($this->useEntity) {
						$out = $this->education_db->getAllRequestEducationCourse($this->entity['address_book_ent_id']);
					} else {
						$out = $this->education_db->getAllRequestEducationCourse();
					}
					
					break;
				case 'list-dashbord':
				if($this->useEntity) {
					$out = $this->education_db->getAllRequestEducationCourseDashboard($this->entity['address_book_ent_id']);
				} else {
					$out = $this->education_db->getAllRequestEducationCourseDashboard();
				}
				
				break;
				case 'count-request-dashborad' :
					$data = $_POST;
					if($this->useEntity) {
						$out = $this->education_db->getCountRequestCourse($data,$this->entity['address_book_ent_id']);
					} else {
						$out = $this->education_db->getCountRequestCourse($data);
					}
					
					break;
				case 'change-status':
					$out = [];
					$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
					if ($contentType === "application/json") {
						$content = trim(file_get_contents("php://input"));
						$decoded = json_decode($content, true);
						$id = $decoded['id'];
						$status_select = $decoded['status_select'];
						$course_request_id = $id;
						if($course_request_id!='') {
							$col='';
							if($status_select=='accepted') {$col='accepted_on';}
							else if($status_select=='enrolled') {$col='enrolled_on';}
							else if($status_select=='finish') {$col='finished_on';}
							else if($status_select=='cancel') {$col='cancelled_on';}
							$update = $this->education_db->updateStatusRequest($course_request_id,$status_select,$col);

							$col_tracker='';
							if($status_select=='accepted') {$col_tracker='accepted';}
							else if($status_select=='enrolled') {$col_tracker='enrolled';}
							
							if($update>0) {
								$message = 'Successfully update item!';
								$status = 'ok';
								$update_tracker = $this->education_tracker->updateEducationTrackerStatus($course_request_id, $status_select,$col_tracker);
							} else {
								$message = 'No Change has made!';
								$status = 'no';
							}
						} else {
							$message = 'Something Error!';
							$status = 'no';
						}
					}
					$out = [
						'message' => $message,
						'status' => $status
					];
				break;
				case 'change-all-status':
					$out = [];
					$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
					if ($contentType === "application/json") {
						$content = trim(file_get_contents("php://input"));
						$decoded = json_decode($content, true);
						$id_checked = $decoded['id'];
						$status_select = $decoded['status_select'];
						$item=0;
						$col='';
						if($status_select=='accepted') {$col='accepted_on';}
						else if($status_select=='enrolled') {$col='enrolled_on';}
						else if($status_select=='finish') {$col='finished_on';}
						else if($status_select=='cancel') {$col='cancelled_on';}

						$col_tracker='';
						if($status_select=='accepted') {$col_tracker='accepted';}
						else if($status_select=='enrolled') {$col_tracker='enrolled';}

						foreach ($id_checked as $key => $value) {
							$course_request_id = $value;
							if($course_request_id!='') {
								$update = $this->education_db->updateStatusRequest($course_request_id,$status_select,$col);
								if($update>0) {
									$item++;
									$update_tracker = $this->education_tracker->updateEducationTrackerStatus($course_request_id, $status_select,$col_tracker);
								}
							}
						}
						$message = 'Successfully update '.$item.' item!';
						$status = 'ok';
					}
					$out = [
						'message' => $message,
						'status' => $status
					];
				break;
				case 'export' :
					ob_start();
					$status = $_POST['status'];
					$partner = $_POST['partner'];
					$start_date = $_POST['start_date'];
					$end_date = $_POST['end_date'];
					
					if($this->useEntity) {
						$data_request = $this->education_db->getDataEducationRequest($status,$partner,$start_date,$end_date,$this->entity['address_book_ent_id']);
					} else {
						$data_request = $this->education_db->getDataEducationRequest($status,$partner,$start_date,$end_date);
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
					$sheet->setCellValue('D'.$row, 'Partner Name');
					$sheet->setCellValue('E'.$row, 'Course');
					$sheet->setCellValue('F'.$row, 'Created On');
					$sheet->setCellValue('G'.$row, 'Status');
					$sheet->getStyle('A'.$row.':G'.$row)->applyFromArray($style_header);

					$sheet->getColumnDimension('A')->setAutoSize(true);
					$sheet->getColumnDimension('B')->setAutoSize(true);
					$sheet->getColumnDimension('C')->setAutoSize(true);
					$sheet->getColumnDimension('D')->setAutoSize(true);
					$sheet->getColumnDimension('E')->setAutoSize(true);
					$sheet->getColumnDimension('F')->setAutoSize(true);
					$sheet->getColumnDimension('G')->setAutoSize(true);

					$i=0;
					foreach ($data_request as $key => $value) {
						$i++;
						$sheet->setCellValue('A'.($row+$i), $i);
						$sheet->setCellValue('B'.($row+$i), $value['fullname']);
						$sheet->setCellValue('C'.($row+$i), $value['main_email']);
						$sheet->setCellValue('D'.($row+$i), $value['partner_name']);
						$sheet->setCellValue('E'.($row+$i), $value['course_name']);
						$sheet->setCellValue('F'.($row+$i), date('d M Y',strtotime($value['created_on'])));
						$sheet->setCellValue('G'.($row+$i), ucwords($value['status']));
						
					}
					$sheet->getStyle('A'.$row.':G'.($row+$i))->applyFromArray($style_border);

					$writer = new Xlsx($spreadsheet);
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="Course_Request_'.date('Y-m-d').'.xlsx"');
					header('Cache-Control: max-age=0');
					ob_end_clean();
					$writer->save('php://output');
					exit();
				break;
				default:
					# code...
					break;
			}
			return $this->response($out);
		}	
	}

	
}
?>