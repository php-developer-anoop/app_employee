<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Volunteer_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        // $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        // $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"]: 10;

        if( empty($employee_id) ) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        }
//         else if( empty($page_no) ){
// 			$response['status'] = false;
// 			$response['message'] = 'Page No. is Blank';
// 			echo json_encode($response); 
// 			exit;
// 		}else if( empty($per_page_limit) ){
// 			$response['status'] = false;
// 			$response['message'] = 'Per Page Limit is Blank';
// 			echo json_encode($response); 
// 			exit;
// 		}
        $where = [];
        $where["profile_status"] ='Active';
        $where["employee_id"] = $employee_id;
        $limit = null;
        $start = null;
        $keys='id,full_name';
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $volunteers=$this->model->getAllData('volunteer_list',$keys,$where,$limit,$start,$orderBy,$orderByKey);
        
        if (empty($volunteers)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($volunteers as $key => $value) {
            $return['volunteer_id']   = (string)$value['id'];
            $return['volunteer_name'] = (string)$value['full_name'];
            // $return['mobile_no']      = (string)$value['mobile_no'];
            // $return['address']        = (string)$value['address'];
            // $return['age']            = date_diff(date_create($value['date_of_birth']), date_create(date('Y-m-d')))->y;
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    
     public function my_team() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"]: 10;

        if( empty($employee_id) ) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        }
        else if( empty($page_no) ){
			$response['status'] = false;
			$response['message'] = 'Page No. is Blank';
			echo json_encode($response); 
			exit;
		}else if( empty($per_page_limit) ){
			$response['status'] = false;
			$response['message'] = 'Per Page Limit is Blank';
			echo json_encode($response); 
			exit;
		}
        $where = [];
        $where["profile_status"] ='Active';
        $where["employee_id"] = $employee_id;
        $limit = null;
        $start = null;
        $keys='id,full_name,mobile_no,address,gender,date_of_birth';
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $volunteers=$this->model->getAllData('volunteer_list',$keys,$where,$limit,$start,$orderBy,$orderByKey);
        
        if (empty($volunteers)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($volunteers as $key => $value) {
            $return['volunteer_id']   = (string)$value['id'];
            $return['volunteer_name'] = (string)$value['full_name'];
            $return['mobile_no']      = (string)$value['mobile_no'];
            $return['address']        = (string)$value['address'];
            $return['gender']         = (string)$value['gender'];
            $return['age']            = date_diff(date_create($value['date_of_birth']), date_create(date('Y-m-d')))->y;
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
