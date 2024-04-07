<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Patient_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post           = checkPayload();
        $employee_id    = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no        = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $type           = !empty($post["type"]) ? $post["type"] : '';
        
        if (empty($employee_id)) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        } else if (empty($page_no)) {
            $response['status'] = false;
            $response['message'] = 'Page No. is Blank';
            echo json_encode($response);
            exit;
        } else if (empty($per_page_limit)) {
            $response['status'] = false;
            $response['message'] = 'Per Page Limit is Blank';
            echo json_encode($response);
            exit;
        }
        $where = [];
        
        if($type!="total"){
            $where['employee_id'] = $employee_id;
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,full_name,mobile_no,occupation,address,gender,dob,status,added_by_username";
        
        $patients = $this->model->getAllData('patient_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($patients)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($patients as $key => $value) {
            $return['patient_id']    = (string)$value['id'];
            $return['patient_name']  = (string)$value['full_name'];
            $return['mobile_no']     = (string)$value['mobile_no'];
            $return['occupation']    = (string)$value['occupation'];
            $return['gender']        = (string)$value['gender'];
            $return['address']       = (string)$value['address'];
            $return['status']        = (string)$value['status'];
            $return['added_by']      = (string)$value['added_by_username'];
            $return['date_of_birth'] = (string)date('d-m-Y', strtotime($value['dob']));
            $return['age']           = date_diff(date_create($value['dob']), date_create(date('Y-m-d')))->y;
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
        $patient_id = !empty($post['patient_id']) ? trim($post['patient_id']) : '';
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

        $returnData['patient_id']       = (string)$record['id'];
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
        $returnData['aadhaar_front_image']= (string)!empty($record['aadhaar_front_image'])?base_url(UPLOADS).$record['aadhaar_front_image']:"";
        $returnData['aadhaar_back_image']= (string)!empty($record['aadhaar_back_image'])?base_url(UPLOADS).$record['aadhaar_back_image']:"";
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
        $returnData['profile_image']    = (string)!empty($record['profile_image'])?base_url(UPLOADS).$record['profile_image']:"";
        $returnData['status']           = (string)$record['status'];

        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
