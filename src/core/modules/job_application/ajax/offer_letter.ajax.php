<?php
namespace core\modules\job_application\ajax;

final class offer_letter extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = false;

    public function run()
    {
        $this->authorizeAjax('offer_letter');
        $this->offer_letter_db = new \core\modules\offer_letter\models\common\db();
        $this->job_db = new \core\modules\job\models\common\db();

        $data = $_POST;
        $rule = [
            'job_application_id' => 'required',
        ];
        $validator = new \core\app\classes\validator\validator($data, $rule);
        if ($validator->hasErrors()) {
            return $this->response($validator->getValidationErrors(), 400);
        }
        if (empty($_FILES['offer_letter_file'])) {
            return $this->response([
                'errors' => [
                    'File is empty.'
                ]
            ], 400);
        }
        $this->tracker = $this->offer_letter_db->getOfferLetterTracker($data['job_application_id']);
        $this->_uploadOfferLetter($data);
        return $this->response([
            'message' => 'Successfully upload offer letter.'
        ]);
    }

    private function _uploadOfferLetter($data)
    {
        $file = file_get_contents($_FILES['offer_letter_file']['tmp_name']);
        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
        $secure_db = new \core\modules\interview\models\common\secure_file();
        $job_application = $this->job_db->getJobApplication($data['job_application_id']);
        if ($this->tracker['offer_letter_file'] != '') {
            $filename = $this->tracker['offer_letter_file'];
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($filename, $job_application['address_book_id']);
            $affected_rows = $secure_db->deleteSecureFile($filename);

            if ($affected_rows != 1) {
                $msg = "There was a major issue with addInfo in passport for address id {$job_application['address_book_id']}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        $filename = $address_book_common->storeAddressBookFileData($file, $job_application['address_book_id'], true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        //insert also saves the image in the address book folder
        $affected_rows = $address_book_db->insertAddressBookFile($filename, $job_application['address_book_id'], 'offer_letter', 0, $job_application['job_application_id']);

        if ($affected_rows != 1) {

            $msg = "There was a major issue with addInfo in passport for address id {$job_application_id}. Affected was {$affected_rows}";
            throw new \RuntimeException($msg);
        }

        $this->offer_letter_db->updateTrackerOfferLetterFile([
            'offer_letter_file' => $filename,
            'job_application_id' => $data['job_application_id'],
        ]);

        $hash = md5($filename . date('Y-m-d H:i:s'));
        //insert hash
        if ($secure_db->checkDuplicateSecureFileHash($hash)) {
            $hash = md5($filename . date('Y-m-d H:i:s'));
        }
        $secure_db->insertSecureFile([
            'hash' => $hash,
            'file_id' => $filename,
            'type' => 'ab'
        ]);
    }

}
?>