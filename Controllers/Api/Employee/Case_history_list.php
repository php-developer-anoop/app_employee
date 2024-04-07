<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Case_history_list extends BaseController {
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
        $start_date     = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date       = !empty($post['end_date']) ? trim($post['end_date']) : '';

        if (empty($employee_id)) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        }
        if (empty($page_no)) {
            $response['status'] = false;
            $response['message'] = 'Page No. is Blank';
            echo json_encode($response);
            exit;
        } 
        if (empty($per_page_limit)) {
            $response['status'] = false;
            $response['message'] = 'Per Page Limit is Blank';
            echo json_encode($response);
            exit;
        }
        $where = [];
        $where["employee_id"] = $employee_id;
        
        if(!empty($start_date) && !empty($end_date)){
            $where["DATE(admit_date) >="] = date('Y-m-d',strtotime($start_date));
            $where["DATE(admit_date) <="] = date('Y-m-d',strtotime($end_date));
        }
        
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $keys = '*';
        $orderByKey='id';
        $orderBy='DESC';
        $case_history_list = $this->model->getAllData('case_history', $keys, $where, $limit, $start,$orderBy, $orderByKey);
        if (empty($case_history_list)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        
        $returnData = [];
        foreach ($case_history_list as $key => $value) {
        $return['id']                       = (string)$value['id'];
        $return['patient_name']             = (string)$value['patient_full_name'];
        $return['hospital_name']            = (string)$value['hospital_name'];
        $return['disease_name']             = (string)$value['disease_name'];
        $return['attended_by_doctor_name']  = (string)$value['attended_by_doctor_name'];
        $return['admit_date']               = (string)(!is_null($value['admit_date']))?date("d/m/Y", strtotime($value['admit_date'])):"";
        $return['address']                  = (string)$value['address'];
        $return['discharged_date']          = (string)(!is_null($value['discharged_date']))?date("d/m/Y", strtotime($value['discharged_date'])):"";
        array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    
    public function case_history_detail(){
        $response = [];
        $post = checkPayload();
        $id = !empty($post['id']) ? trim($post['id']) : '';
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        if (empty($id)) {
            $response["status"] = false;
            $response["message"] = "Id is Blank";
            echo json_encode($response);
            exit();
        }
        else if (empty($employee_id)) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        }
        
        $value=$this->model->getSingle('case_history','*',['id'=>$id,'employee_id'=>$employee_id]);
        if(empty($value)){
            $response["status"] = false;
            $response["message"] = "No Record Found";
            echo json_encode($response);
            exit();
        }
        
        $returnData=[];
        $return['id']               = (string)$value['id'];
        $return['employee_id']      = (string)$value['employee_id'];
        $return['employee_name']    = (string)$value['employee_name'];
        $return['volunteer_id']     = (string)$value['volunteer_id'];
        $return['volunteer_name']   = (string)$value['volunteer_name'];
        $return['patient_id']       = (string)$value['patient_id'];
        $return['patient_full_name'] = (string)$value['patient_full_name'];
        $return['patient_mobile_no'] = (string)$value['patient_mobile_no'];
        $return['dob']              = (string)date('d/m/Y', strtotime($value['dob']));
        $return['gender']           = (string)$value['gender'];
        $return['occupation']       = (string)$value['occupation'];
        $return['address']          = (string)$value['address'];
        $return['aadhaar_no']       = (string)$value['aadhaar_no'];
        $return['state_id']         = (string)$value['state_id'];
        $return['state_name']       = (string)$value['state_name'];
        $return['district_id']      = (string)$value['district_id'];
        $return['district_name']    = (string)$value['district_name'];
        $return['block_id']         = (string)$value['block_id'];
        $return['block_name']       = (string)$value['block_name'];
        $return['pincode']          = (string)$value['pincode'];
        $return['disease_id']       = (string)$value['disease_id'];
        $return['disease_name']     = (string)$value['disease_name'];
        $return['hospital_id']      = (string)$value['hospital_id'];
        $return['hospital_name']    = (string)$value['hospital_name'];
        $return['attended_by_doctor_name'] = (string)$value['attended_by_doctor_name'];
        $return['profile_image']    = (string)!empty($value['profile_image'])?base_url(UPLOADS).$value['profile_image']:"";
        $return['aadhaar_front_image'] = (string)!empty($value['aadhaar_front_image'])?base_url(UPLOADS).$value['aadhaar_front_image']:"";
        $return['aadhaar_back_image'] = (string)!empty($value['aadhaar_back_image'])?base_url(UPLOADS).$value['aadhaar_back_image']:"";
        $return['admit_date']       = (string)date('d/m/Y', strtotime($value['admit_date']));
        $return['address']          = (string)$value['address'];
        $return['discharged_date']  = (string)(!is_null($value['discharged_date']))?date("d/m/Y", strtotime($value['discharged_date'])):"";
        
        $response["status"] = true;
        $response["data"] =$return;
        $response["message"] = "API Accessed Successfully";
        echo json_encode($response);
        exit();
    }

}
