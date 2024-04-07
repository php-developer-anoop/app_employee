<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Volunteer_task_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id Is Blank';
            echo json_encode($response);
            exit;
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
        $where["status"] = 'Active';
        $where["employee_id"] = $employee_id;
       
        if(!empty($start_date) && !empty($end_date)){
            $where["DATE(task_start_date_time) >="] = date('Y-m-d',strtotime($start_date));
            $where["DATE(task_start_date_time) <="] = date('Y-m-d',strtotime($end_date));
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $keys = '*';
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $task_list = $this->model->getAllData('volunteer_task_list', $keys, $where, $limit, $start,$orderBy, $orderByKey);
        if (empty($task_list)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($task_list as $key => $value) {
            
        $return['id']              = (string)$value['id'];
        $return['task_id']         = (string)$value['task_id'];
        $return['task_name']       = (string)$value['task_name'];
        $return['task_start_date'] = (string)date('d/m/Y', strtotime($value['task_start_date_time']));
        $return['task_start_time'] = (string)date('h:i A', strtotime($value['task_start_date_time']));
        $return['task_end_date']   = (string)date('d/m/Y', strtotime($value['task_end_date_time']));
        $return['task_end_time']   = (string)date('h:i A', strtotime($value['task_end_date_time']));
        $return['volunteer_id']    = (string)$value['volunteer_id'];
        $return['volunteer_name']  = (string)$value['volunteer_name'];
        $return['employee_id']     = (string)$value['employee_id'];
        $return['employee_name']   = (string)$value['employee_name'];
        $return['task_location']   = (string)$value['task_location'];
        array_push($returnData, $return);
            
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    
    public function today_task_list() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id Is Blank';
            echo json_encode($response);
            exit;
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
        $where["status"] = 'Active';
        $where["employee_id"] = $employee_id;
        
        if(!empty($start_date) && !empty($end_date)){
            $where["DATE(task_start_date_time) >="] = date('Y-m-d',strtotime($start_date));
            $where["DATE(task_start_date_time) <="] = date('Y-m-d',strtotime($end_date));
        }
        $where["DATE(add_date)"] = date('Y-m-d');
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $keys = '*';
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $task_list = $this->model->getAllData('volunteer_task_list', $keys, $where, $limit, $start,$orderBy,$orderByKey);
        if (empty($task_list)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($task_list as $key => $value) {
            $return['id']              = (string)$value['id'];
            $return['task_id']         = (string)$value['task_id'];
            $return['task_name']       = (string)$value['task_name'];
            $return['task_start_date'] = (string)date('d/m/Y', strtotime($value['task_start_date_time']));
            $return['task_start_time'] = (string)date('h:i A', strtotime($value['task_start_date_time']));
            $return['task_end_date']   = (string)date('d/m/Y', strtotime($value['task_end_date_time']));
            $return['task_end_time']   = (string)date('h:i A', strtotime($value['task_end_date_time']));
            $return['volunteer_id']    = (string)$value['volunteer_id'];
            $return['volunteer_name']  = (string)$value['volunteer_name'];
            $return['employee_id']     = (string)$value['employee_id'];
            $return['employee_name']   = (string)$value['employee_name'];
            $return['task_location']   = (string)$value['task_location'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    
    public function task_detail() {
        $response = [];
        $post = checkPayload();
        $id = !empty($post["id"]) ? trim($post["id"]) : "";
        if (empty($id)) {
            $response['status'] = false;
            $response['message'] = "Id Is Blank";
            echo json_encode($response);
            exit;
        }
        $data = $this->model->getSingle("volunteer_task_list", '*', ['status' => 'Active', 'id' => $id]);
        if (empty($data)) {
            $response['status'] = false;
            $response['message'] = "No Record Found";
            echo json_encode($response);
            exit;
        }
        $return = [];
        $return['task_id']          = (string)$data['task_id'];
        $return['task_name']        = (string)$data['task_name'];
        $return['task_start_date']  = (string)date('d/m/Y', strtotime($data['task_start_date_time']));
        $return['task_start_time']  = (string)date('h:i A', strtotime($data['task_start_date_time']));
        $return['task_end_date']    = (string)date('d/m/Y', strtotime($data['task_end_date_time']));
        $return['task_end_time']    = (string)date('h:i A', strtotime($data['task_end_date_time']));
        $return['volunteer_id']     = (string)$data['volunteer_id'];
        $return['volunteer_name']   = (string)$data['volunteer_name'];
        $return['employee_id']      = (string)$data['employee_id'];
        $return['employee_name']    = (string)$data['employee_name'];
        $return['task_location']    = (string)$data['task_location'];
        
        $response['status'] = true;
        $response['data'] = $return;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
