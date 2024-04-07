<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Add_patient extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post           = checkPayload();
        $employee_id    = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $employee_name  = !empty($post['employee_name']) ? trim($post['employee_name']) : '';
        $patient_name   = !empty($post['patient_name']) ? trim($post['patient_name']) : '';
        $mobile_no      = !empty($post['mobile_no']) ? trim($post['mobile_no']) : '';
        $date_of_birth  = !empty($post['date_of_birth']) ? trim($post['date_of_birth']) : '';
        $occupation     = !empty($post['occupation']) ? trim($post['occupation']) : '';
        $address        = !empty($post['address']) ? trim($post['address']) : '';
        $gender         = !empty($post['gender']) ? trim($post['gender']) : '';
        $aadhar_number  = !empty($post['aadhar_number']) ? trim($post['aadhar_number']) : '';
        $state_id       = !empty($post['state_id']) ? trim($post['state_id']) : '';
        $state_name     = !empty($post['state_name']) ? trim($post['state_name']) : '';
        $district_id    = !empty($post['district_id']) ? trim($post['district_id']) : '';
        $district_name  = !empty($post['district_name']) ? trim($post['district_name']) : '';
        $block_id       = !empty($post['block_id']) ? trim($post['block_id']) : '';
        $block_name     = !empty($post['block_name']) ? trim($post['block_name']) : '';
        $pincode        = !empty($post['pincode']) ? trim($post['pincode']) : '';
        $disease_id     = !empty($post['disease_id']) ? trim($post['disease_id']) : '';
        $disease_name   = !empty($post['disease_name']) ? trim($post['disease_name']) : '';
        $hospital_id    = !empty($post['hospital_id']) ? trim($post['hospital_id']) : '';
        $hospital_name  = !empty($post['hospital_name']) ? trim($post['hospital_name']) : '';

        if (empty($employee_id)) {
            $response['status']  = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($employee_name)) {
            $response['status']  = false;
            $response['message'] = 'Employee Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($patient_name)) {
            $response['status']  = false;
            $response['message'] = 'Patient Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($mobile_no)) {
            $response['status']  = false;
            $response['message'] = 'Mobile Number is empty!';
            echo json_encode($response);
            exit;
        }
        if (strlen($mobile_no)>10) {
            $response['status']  = false;
            $response['message'] = 'Please Enter A Valid 10 Digit Mobile Number!';
            echo json_encode($response);
            exit;
        }
        if (empty($date_of_birth)) {
            $response['status']  = false;
            $response['message'] = 'DOB is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($gender)) {
            $response['status']  = false;
            $response['message'] = 'Please Select Gender!';
            echo json_encode($response);
            exit;
        }
        if (empty($occupation)) {
            $response['status']  = false;
            $response['message'] = 'Occupation is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($address)) {
            $response['status']  = false;
            $response['message'] = 'Address is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($aadhar_number)) {
            $response['status']  = false;
            $response['message'] = 'Aadhar Number is empty!';
            echo json_encode($response);
            exit;
        }
        if (strlen($aadhar_number)>12) {
            $response['status']  = false;
            $response['message'] = 'Please Enter A Valid 12 Digit Aadhar Number!';
            echo json_encode($response);
            exit;
        }
        if (empty($state_id)) {
            $response['status']  = false;
            $response['message'] = 'State Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($state_name)) {
            $response['status']  = false;
            $response['message'] = 'State Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_id)) {
            $response['status']  = false;
            $response['message'] = 'District Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_name)) {
            $response['status']  = false;
            $response['message'] = 'District Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($block_id)) {
            $response['status']  = false;
            $response['message'] = 'Block Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($block_name)) {
            $response['status']  = false;
            $response['message'] = 'Block Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($pincode)) {
            $response['status']  = false;
            $response['message'] = 'Pincode is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($disease_id)) {
            $response['status']  = false;
            $response['message'] = 'Disease Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($disease_name)) {
            $response['status']  = false;
            $response['message'] = 'Disease Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($hospital_id)) {
            $response['status']  = false;
            $response['message'] = 'Hospital Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($hospital_name)) {
            $response['status']  = false;
            $response['message'] = 'Hospital Name is empty!';
            echo json_encode($response);
            exit;
        }
        $checkDuplicate = $this->model->getSingle("patient_list", 'id', ['mobile_no' => $mobile_no]);
        if ($checkDuplicate) {
            $response['status'] = false;
            $response['message'] = "Duplicate Entry!";
            echo json_encode($response);
            exit;
        }
       $data=[];
       $data['employee_id']     = $employee_id;
       $data['employee_name']   = $employee_name;
       $data['full_name']       = $patient_name;
       $data['mobile_no']       = $mobile_no;
       $data['dob']             = date('Y-m-d',strtotime($date_of_birth));
       $data['occupation']      = $occupation;
       $data['gender']          = $gender;
       $data['aadhaar_no']      = $aadhar_number;
       $data['address']         = $address;
       $data['state_id']        = $state_id;
       $data['state_name']      = $state_name;
       $data['district_id']     = $district_id;
       $data['district_name']   = $district_name;
       $data['block_id']        = $block_id;
       $data['block_name']      = $block_name;
       $data['pincode']         = $pincode;
       $data['hospital_id']     = $hospital_id;
       $data['hospital_name']   = $hospital_name;
       $data['disease_id']      = $disease_id;
       $data['disease_name']    = $disease_name;
       $data['add_date']        = date('Y-m-d H:i:s');
       $data['added_by']        = 'Employee';
       $data['added_by_username'] = $employee_name;
       
       $lastid=$this->model->insertRecords("patient_list",$data);
       if($lastid){
        $response['status'] = TRUE;
        $response['patient_id'] = $lastid;
        $response['message'] = "Patient Registered Successfully!";
        echo json_encode($response);
        exit;
    }
    }
}
