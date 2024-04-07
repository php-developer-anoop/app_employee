<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Upload_patient_image extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $data=[];
        $post = checkFormPayload($this->request->getVar());
        
        $patient_id=!empty($post['patient_id'])?trim($post['patient_id']):"";
        
        if(empty($patient_id)){
            $response['status'] = false;
            $response['message'] = 'Patient Id Is Blank';
            echo json_encode($response);
            exit;
        }
        if ($file = $this->request->getFile('profile_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $profile_image = $file->getRandomName();
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $profile_image);
                $data['profile_image'] = $profile_image;
            } else {
                $response['status'] = false;
                $response['message'] = 'Please Select Profile Image';
                echo json_encode($response);
                exit;
            }
        }
        if ($file = $this->request->getFile('aadhar_front')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $aadhar_front = $file->getRandomName();
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $aadhar_front);
                $data['aadhaar_front_image'] = $aadhar_front;
            } else {
                $response['status'] = false;
                $response['message'] = 'Please Select Aadhar Front';
                echo json_encode($response);
                exit;
            }
        }
        if ($file = $this->request->getFile('aadhar_back')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $aadhar_back = $file->getRandomName();
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $aadhar_back);
                $data['aadhaar_back_image'] = $aadhar_back;
            } else {
                $response['status'] = false;
                $response['message'] = 'Please Select Aadhar Back';
                echo json_encode($response);
                exit;
            }
        }
        $this->model->updateRecords("patient_list", $data,['id'=>$patient_id]);
        $response['status'] = true;
        $response['message'] = 'Image Upload Successfully!';
        echo json_encode($response);
        exit;
    }
}
