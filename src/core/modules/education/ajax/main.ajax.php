<?php
namespace core\modules\education\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 15 Jun 2020
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = false;
	
	public function run()
	{	
		$this->authorizeAjax('main');
		
		$out = array();
		if ($this->option)
        {
			$this->education_db = new \core\modules\education\models\common\master;
			
            $type = $this->option;
			switch ($type) {
				case 'list':
					$out = $this->education_db->getAllEducationCourse();
					break;
				case 'insert':
					$data = $_POST;
					$data['description'] = SUBMITTED_TEXT;
					$rule = [
						'course_name' => 'required',
						'short_description' => 'required|max:255',
					];
					$validator = new \core\app\classes\validator\validator($data, $rule);
					if($validator->hasErrors()){
						return $this->response($validator->getValidationErrors(),400);
					}
					$course_id= $this->education_db->insert($data);
					//insert or update the image
					if(!empty($_POST['image_course_base64']))
					{
						$image_course_current = empty($_POST['image_course_current']) ? false : $_POST['image_course_current'];
						$this->_processImage($course_id,$image_course_current,$_POST['image_course_base64'],'banner','education_course');
					}
					if($course_id){
						$out = [
							'success' => true,
							'message' => 'Successfully insert item.'
						];
					}
				break;
				case 'get' :
					$id = $this->page_options[1];
         			$out = $this->education_db->get($id);
            		$out['files'] = $this->education_db->getFiles($out['course_id']);
				break;
				case 'update':
					$course_id = $this->page_options[1];
					$data = $_POST;
					$data['e_description'] = SUBMITTED_TEXT;
					$rule = [
						'e_course_name' => 'required',
						'e_short_description' => 'required|max:255',
					];
					$validator = new \core\app\classes\validator\validator($data, $rule);
					if($validator->hasErrors()){
						return $this->response($validator->getValidationErrors(),400);
					}
					$this->education_db->update($course_id,$data);
					//insert or update the image
					if(!empty($_POST['e_image_course_base64']))
					{
						$image_course_current = empty($_POST['image_course_current']) ? false : $_POST['image_course_current'];
						$this->_processImage($course_id,$image_course_current,$_POST['e_image_course_base64'],'banner','education_course');
					}
					if($course_id){
						$out = [
							'success' => true,
							'message' => 'Successfully update item.'
						];
					}
				break;
				case 'delete' :
					$course_id = $this->page_options[1];
					$rs = $this->education_db->delete($course_id);
					if($rs){
						$out = [
							'success' => true,
							'message' => 'Successfully delete item.',
							'status' => 'ok'
						];
					}
				break;
				case 'export-tracker' : 
					ob_start();
					$generic = \core\app\classes\generic\generic::getInstance();

					$education_request = new \core\modules\education\models\common\request;
					$period = $_POST['period'];
					$start_date = $_POST['start_date'];
					$end_date = $_POST['end_date'];
					if($this->useEntity) {
						$data_tracker = $education_request->getDataEducationTracker($period,$start_date,$end_date,$this->entity['address_book_ent_id']);
					} else {
						$data_tracker = $education_request->getDataEducationTracker($period,$start_date,$end_date);
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
					$sheet->setCellValue('H'.$row, 'Level');
					$sheet->getStyle('A'.$row.':H'.$row)->applyFromArray($style_header);

					$sheet->getColumnDimension('A')->setAutoSize(true);
					$sheet->getColumnDimension('B')->setAutoSize(true);
					$sheet->getColumnDimension('C')->setAutoSize(true);
					$sheet->getColumnDimension('D')->setAutoSize(true);
					$sheet->getColumnDimension('E')->setAutoSize(true);
					$sheet->getColumnDimension('F')->setAutoSize(true);
					$sheet->getColumnDimension('G')->setAutoSize(true);
					$sheet->getColumnDimension('H')->setAutoSize(true);
					$i=0;
					foreach ($data_tracker as $key => $value) {
						$fullname = $generic->getName('per', $value['entity_family_name'], $value['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

						switch ($value['level']) {
							case 1:
								$level = 'Normal';
								break;
							case 2:
								$level = 'Soft Warning';
								break;
							case 3:
								$level = 'Hard Warning';
								break;
							case 4:
								$level = 'Deadline';
								break;
							
							default:
								$level = '-';
								break;
						}

						$i++;
						$sheet->setCellValue('A'.($row+$i), $i);
						$sheet->setCellValue('B'.($row+$i), $fullname);
						$sheet->setCellValue('C'.($row+$i), $value['main_email']);
						$sheet->setCellValue('D'.($row+$i), $value['partner_name']);
						$sheet->setCellValue('E'.($row+$i), $value['course_name']);
						$sheet->setCellValue('F'.($row+$i), date('d M Y',strtotime($value['created_on'])));
						$sheet->setCellValue('G'.($row+$i), ucwords($value['status']));
						$sheet->setCellValue('H'.($row+$i), $level);
						
					}
					$sheet->getStyle('A'.$row.':H'.($row+$i))->applyFromArray($style_border);

					$writer = new Xlsx($spreadsheet);
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="Course_Tracker_'.date('Y-m-d').'.xlsx"');
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

	private function _processImage($address_book_id,$banner_current,$banner_base64, $model_code, $model_sub_code)
    {
        $filename = 'none';

        //decode
        $data = $banner_base64;
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);

        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        $filename = $address_book_common->storeAddressBookFileData($data,$address_book_id,true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        if($banner_current)
        {
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($banner_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,$model_code,0,$model_sub_code,1);

            if($affected_rows != 1)
            {
                $msg = "1There was a major issue with addInfo in banner for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,$model_code,0,$model_sub_code,1);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with addInfo in banner for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        return $filename;
    }
	
}
?>