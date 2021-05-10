<?php
namespace core\modules\cv\ajax;

use Dompdf\Dompdf;
use Dompdf\Options;
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$this->authorizeAjax('main');	
		$out = null;
		
		//we always need a Session
		switch($this->option) 
		{	
			case 'export-public-cv' : 
				$personal_db = new \core\modules\personal\models\common\db;
				$cv_db = new \core\modules\cv\models\common\db;

				$address_book_id = 0;
				$hash_cv = $this->page_options[1];
				$data_personal_cv = $cv_db->getIDHashPersonalCV($hash_cv);
				if(count($data_personal_cv)>0) {
					$address_book_id = $data_personal_cv[0]['address_book_id'];
				}
				$user_verification = $personal_db->checkVerification($address_book_id);
				if(isset($user_verification['status']) && $user_verification['status']=='verified') {
					$data_cv = $personal_db->getCurriculumVitae($address_book_id);
					echo $this->generatePublicCV($data_cv,$data_personal_cv[0]['template']);
				}
			break;
			case 'generate-cv':
				$template = 'template1';
				if(isset($this->page_options[1])) {
					$template = $this->page_options[1];
				}
				$personal_db = new \core\modules\personal\models\common\db;
				$address_book_id = $_SESSION['address_book_id'];
				$data_cv = $personal_db->getCurriculumVitae($address_book_id);
				//print_r($data_cv);
				$out = $this->generateCV($data_cv,'html',$template);
			break;
			case 'generate-cv-pdf':
				$template = 'blue';
				if(isset($this->page_options[1])) {
					$template = $this->page_options[1];
				}
				$personal_db = new \core\modules\personal\models\common\db;
				$address_book_id = $_SESSION['address_book_id'];
				$data_cv = $personal_db->getCurriculumVitae($address_book_id);
				$cv = $this->generateCV($data_cv,'pdf',$template);

				// instantiate and use the dompdf class  
				$dompdf = new Dompdf();
				$dompdf->loadHtml($cv);

				// (Optional) Setup the paper size and orientation
				$dompdf->setPaper('A4', 'portrait');

				// Render the HTML as PDF
				$dompdf->render();

				// Output the generated PDF to Browser
				$dompdf->stream($data_cv['name'].'-cv.pdf',array("Attachment" => false));
				
			break;
			case 'checkCVHash' :
				//check hash personal cv
				$hash_cv ='123';
				$address_book_id = $_SESSION['address_book_id'];
				$cv_db = new \core\modules\cv\models\common\db;
				$data_hash_cv = $cv_db->checkHashPersonalCV($address_book_id);
				if(count($data_hash_cv)>0) {
					$hash_cv = $data_hash_cv[0]['hash'];
					$data_to_db = [
						'address_book_id' => $address_book_id,
						'template' => $_POST['template']
					];
					$cv_db->updateHashPersonalCV($data_to_db);
				} else {
					$unix = false;
					do {
						$random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
						$hash_cv = md5($random_string);
		
						//check hash unix
						$data_personal_cv = $cv_db->getIDHashPersonalCV($hash_cv);
						if(count($data_personal_cv)>0) {
							$unix = true;
						}
					} while($unix);

					$data_to_db = [
						'address_book_id' => $address_book_id,
						'hash' => $hash_cv,
						'template' => $_POST['template']
					];
					$cv_db->insertHashPersonalCV($data_to_db);
				}
				//update status file address book
				$cv_db->updateAddressBookFileCV($address_book_id);

				$out = $hash_cv;
			break;
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
		}

		if(!empty($out))
		{
				header('Content-Type: application/json; charset=utf-8');
				return json_encode($out);
		} else {
			return ;
		}				
	}

	public function generateCV($data_cv,$type='html',$template) {
		$out='';
		$img = "/ab/show/".$data_cv['full_image'];
		if($type=='pdf') {
			$path = "local/uploads/address_book/".$data_cv['address_book_id']."/".$data_cv['full_image'];
			$imageData = base64_encode(file_get_contents($path));
			$img = 'data:'.mime_content_type($path).';base64,'.$imageData;
		}
		//$template = "template4";
		include DIR_MODULES."/cv/views/template/".$template.".php";
	 	return $out;
    }

	public function generatePublicCV($data_cv,$template) {
		$out='';
        //$img = "/local/uploads/address_book/".$data_cv['address_book_id']."/".$data_cv['full_image'];
        $img = '/ao/show/'.$data_cv['full_image'];
        $file_template = DIR_MODULES."/cv/views/template/".$template."-pub.php";
        if (file_exists($file_template)) {
            include $file_template;
        } else {
            $html_ns = NS_HTML.'\\htmlpage';
	    	$htmlpage = new $html_ns(404);
			exit();
        }
	 	return $out;
    }
}