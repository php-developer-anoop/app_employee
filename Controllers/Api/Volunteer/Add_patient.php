<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Add_patient extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $employee_id        = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $employee_name      = !empty($post['employee_name']) ? trim($post['employee_name']) : '';
        $volunteer_id       = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $volunteer_name     = !empty($post['volunteer_name']) ? trim($post['volunteer_name']) : '';
        $patient_name       = !empty($post['patient_name']) ? trim($post['patient_name']) : '';
        $mobile_no          = !empty($post['mobile_no']) ? trim($post['mobile_no']) : '';
        $date_of_birth      = !empty($post['date_of_birth']) ? trim($post['date_of_birth']) : '';
        $occupation         = !empty($post['occupation']) ? trim($post['occupation']) : '';
        $address            = !empty($post['address']) ? trim($post['address']) : ''; 
        $gender             = !empty($post['gender']) ? trim($post['gender']) : '';
        $aadhar_number      = !empty($post['aadhar_number']) ? trim($post['aadhar_number']) : '';
        $state_id           = !empty($post['state_id']) ? trim($post['state_id']) : '';
        $state_name         = !empty($post['state_name']) ? trim($post['state_name']) : '';
        $district_id        = !empty($post['district_id']) ? trim($post['district_id']) : '';
        $district_name      = !empty($post['district_name']) ? trim($post['district_name']) : '';
        $block_id           = !empty($post['block_id']) ? trim($post['block_id']) : '';
        $block_name         = !empty($post['block_name']) ? trim($post['block_name']) : '';
        $pincode            = !empty($post['pincode']) ? trim($post['pincode']) : '';
        $disease_id         = !empty($post['disease_id']) ? trim($post['disease_id']) : '';
        $disease_name       = !empty($post['disease_name']) ? trim($post['disease_name']) : '';
        $hospital_id        = !empty($post['hospital_id']) ? trim($post['hospital_id']) : '';
        $hospital_name      = !empty($post['hospital_name']) ? trim($post['hospital_name']) : '';
        
         if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($employee_name)) {
            $response['status'] = false;
            $response['message'] = 'Employee Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($volunteer_name)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($patient_name)) {
            $response['status'] = false;
            $response['message'] = 'Patient Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($mobile_no)) {
            $response['status'] = false;
            $response['message'] = 'Mobile Number is empty!';
            echo json_encode($response);
            exit;
        }
        if (strlen($mobile_no) > 10) {
            $response['status'] = false;
            $response['message'] = 'Please Enter A Valid 10 Digit Mobile Number!';
            echo json_encode($response);
            exit;
        }
        if (empty($date_of_birth)) {
            $response['status'] = false;
            $response['message'] = 'DOB is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($gender)) {
            $response['status'] = false;
            $response['message'] = 'Please Select Gender!';
            echo json_encode($response);
            exit;
        }
        if (empty($occupation)) {
            $response['status'] = false;
            $response['message'] = 'Occupation is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($address)) {
            $response['status'] = false;
            $response['message'] = 'Address is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($aadhar_number)) {
            $response['status'] = false;
            $response['message'] = 'Aadhar Number is empty!';
            echo json_encode($response);
            exit;
        }
        if (strlen($aadhar_number) > 12) {
            $response['status'] = false;
            $response['message'] = 'Please Enter A Valid 12 Digit Aadhar Number!';
            echo json_encode($response);
            exit;
        }
        if (empty($state_id)) {
            $response['status'] = false;
            $response['message'] = 'State Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($state_name)) {
            $response['status'] = false;
            $response['message'] = 'State Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_id)) {
            $response['status'] = false;
            $response['message'] = 'District Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_name)) {
            $response['status'] = false;
            $response['message'] = 'District Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($block_id)) {
            $response['status'] = false;
            $response['message'] = 'Block Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($block_name)) {
            $response['status'] = false;
            $response['message'] = 'Block Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($pincode)) {
            $response['status'] = false;
            $response['message'] = 'Pincode is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($disease_id)) {
            $response['status'] = false;
            $response['message'] = 'Disease Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($disease_name)) {
            $response['status'] = false;
            $response['message'] = 'Disease Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($hospital_id)) {
            $response['status'] = false;
            $response['message'] = 'Hospital Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($hospital_name)) {
            $response['status'] = false;
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
        $data = [];
        $data['employee_id'] = $employee_id;
        $data['employee_name'] = ucwords($employee_name);
        $data['volunteer_id'] = $volunteer_id;
        $data['volunteer_name'] = ucwords($volunteer_name);
        $data['full_name'] = ucwords($patient_name);
        $data['mobile_no'] = $mobile_no;
        $data['dob'] = date('Y-m-d', strtotime($date_of_birth));
        $data['occupation'] = ucwords($occupation);
        $data['gender'] = $gender;
        $data['aadhaar_no'] = $aadhar_number;
        $data['address'] = ucwords($address);
        $data['state_id'] = $state_id;
        $data['state_name'] = ucwords($state_name);
        $data['district_id'] = $district_id;
        $data['district_name'] = ucwords($district_name);
        $data['block_id'] = $block_id;
        $data['block_name'] = ucwords($block_name);
        $data['pincode'] = $pincode;
        $data['hospital_id'] = $hospital_id;
        $data['hospital_name'] = ucwords($hospital_name);
        $data['disease_id'] = $disease_id;
        $data['disease_name'] = ucwords($disease_name);
        $data['add_date'] = date('Y-m-d H:i:s');
        $data['added_by'] = 'Volunteer';
        $data['added_by_username'] = ucwords($volunteer_name);
        
        $lastid = $this->model->insertRecords("patient_list", $data);
        if ($lastid) {
            $response['status'] = TRUE;
            $response['patient_id'] = $lastid;
            $response['message'] = "Patient Registered Successfully!";
            echo json_encode($response);
            exit;
        }
    }
    public function today_registered_patients() {
        $response = [];
        $post = checkPayload();
        $employee_id    = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $volunteer_id   = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $page_no        = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date     = !empty($post['start_date'])?$post['start_date']:"";
        $end_date       = !empty($post['end_date'])?$post['end_date']:"";
        
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $where = [];
        $where["DATE(add_date)"] = date('Y-m-d');
        $where["volunteer_id"] = $volunteer_id;
        $where["employee_id"] = $employee_id;
        
        if(!empty($start_date) && !empty($end_date)){
            $where['DATE(add_date) >='] = date('Y-m-d',strtotime($start_date));
            $where['DATE(add_date) >='] = date('Y-m-d',strtotime($end_date));
        }
        
        $records = $this->model->getAllData("patient_list", 'id,full_name,mobile_no,gender,address,dob,added_by_username', $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($records)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($records as $key => $value) {
            $return['id']        = (string)$value['id'];
            $return['full_name'] = (string)$value['full_name'];
            $return['mobile_no'] = (string)$value['mobile_no'];
            $return['gender']    = (string)$value['gender'];
            $return['address']   = (string)$value['address'];
            $return['added_by']  = (string)$value['added_by_username'];
            $return['age']       = date_diff(date_create($value['dob']), date_create(date('Y-m-d')))->y;
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }

    public function total_registered_patients() {
        $response = [];
        $post = checkPayload();
        $employee_id    = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $volunteer_id   = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $page_no        = !empty($post["page_no"]) ? $post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? $post["per_page_limit"] : 10;
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $where = [];
        $where["volunteer_id"] = $volunteer_id;
        $where["employee_id"] = $employee_id;
        
        $records = $this->model->getAllData("patient_list", 'id,full_name,mobile_no,gender,address,dob,volunteer_name', $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($records)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($records as $key => $value) {
            $return['id']        = (string)$value['id'];
            $return['full_name'] = (string)$value['full_name'];
            $return['mobile_no'] = (string)$value['mobile_no'];
            $return['gender']    = (string)$value['gender'];
            $return['address']   = (string)$value['address'];
            $return['added_by']  = (string)$value['volunteer_name'];
            $return['age']       = date_diff(date_create($value['dob']), date_create(date('Y-m-d')))->y;
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }

    public function patient_detail(){
        $response = [];
        $post = checkPayload();
        $patient_id = !empty($post['id']) ? trim($post['id']) : '';
        if (empty($patient_id)) {
            $response["status"] = false;
            $response["message"] = "Patient Id is Blank";
            echo json_encode($response);
            exit();
        }

        $record=$this->model->getSingle("patient_list",'*',['id'=>$patient_id]);
        if(empty($record)){
            $response["status"] = false;
            $response["message"] = "No Record Found";
            echo json_encode($response);
            exit();
        }
        $returnData=[];

        //$returnData['patient_id']=(string)$record['id'];
    $returnData['employee_id']      = (string)$record['employee_id'];
    $returnData['employee_name']    = (string)$record['employee_name'];
    $returnData['volunteer_id']     = (string)$record['volunteer_id'];
    $returnData['volunteer_name']   = (string)$record['volunteer_name'];
    $returnData['full_name']        = (string)$record['full_name'];
    $returnData['mobile_no']        = (string)$record['mobile_no'];
    $returnData['dob']              = (string)date('d/m/Y',strtotime($record['dob']));
    $returnData['gender']           = (string)$record['gender'];
    $returnData['occupation']       = (string)$record['occupation'];
    $returnData['address']          = (string)$record['address'];
    $returnData['aadhaar_no']       = (string)$record['aadhaar_no'];
    $returnData['state_id']         = (string)$record['state_id'];
    $returnData['state_name']       = (string)$record['state_name'];
    $returnData['district_id']      = (string)$record['district_id'];
    $returnData['district_name']    = (string)$record['district_name'];
    $returnData['block_id']         = (string)$record['block_id'];
    $returnData['block_name']       = (string)$record['block_name'];
    $returnData['pincode']          = (string)$record['pincode'];
    $returnData['disease_id']       = (string)$record['disease_id'];
    $returnData['disease_name']     = (string)$record['disease_name'];
    $returnData['hospital_id']      = (string)$record['hospital_id'];
    $returnData['hospital_name']    = (string)$record['hospital_name'];
    $returnData['block_name']       = (string)$record['block_name'];
    $returnData['aadhaar_front_image']  = (string)!empty($record['aadhaar_front_image'])?base_url(UPLOADS).$record['aadhaar_front_image']:"";
    $returnData['aadhaar_back_image']   = (string)!empty($record['aadhaar_back_image'])?base_url(UPLOADS).$record['aadhaar_back_image']:"";
    $returnData['profile_image']    = (string)!empty($record['profile_image'])?base_url(UPLOADS).$record['profile_image']:"";
    $returnData['status']           = (string)$record['status'];

    $response['status'] = TRUE;
    $response['data'] = $returnData;
    $response['message'] = "API Accessed Successfully!";
    echo json_encode($response);
    exit;
    }
}
