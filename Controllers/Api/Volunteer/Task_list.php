<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Task_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();

        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        $status = !empty($post['status']) ? trim($post['status']) : '';
        
        if (empty($employee_id)) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        }
        else if (empty($volunteer_id)) {
            $response["status"] = false;
            $response["message"] = "Volunteer Id is Blank";
            echo json_encode($response);
            exit();
        }
        else if (empty($page_no)) {
            $response['status'] = false;
            $response['message'] = 'Page No. is Blank';
            echo json_encode($response);
            exit;
        }
        else if (empty($per_page_limit)) {
            $response['status'] = false;
            $response['message'] = 'Per Page Limit is Blank';
            echo json_encode($response);
            exit;
        }
        else if ( !in_array( $status, ['today','total'])) {
            $response['status'] = false;
            $response['message'] = 'Task Status is Invalid';
            echo json_encode($response);
            exit;
        }
        $where = [];
        $where['employee_id'] = $employee_id;
        $where['volunteer_id'] = $volunteer_id;
        $where['status'] = 'Active';
        
        if($status == 'today' ){
            $where['DATE(task_start_date_time)'] = date('Y-m-d');
        }
        if(!empty($start_date) && !empty($end_date)){
            $where["DATE(task_start_date_time) >="] = date('Y-m-d',strtotime($start_date));
            $where["DATE(task_start_date_time) <="] = date('Y-m-d',strtotime($end_date));
        }
        
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $keys = '*';
        $task_list = $this->model->getAllData('volunteer_task_list', $keys, $where, $limit, $start,'DESC','id');
        if ( empty($task_list) ) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($task_list as $key => $value) {
            // $return['id'] = (string)$value['id'];
            // $return['task_id'] = (string)$value['task_id'];
            $return['task_name'] = (string)$value['task_name'];
            $return['task_start_date'] = (string)date('d/m/Y', strtotime($value['task_start_date_time']));
            $return['task_start_time'] = (string)date("h:i A", strtotime($value['task_start_date_time']));
            $return['task_end_date'] = (string)date('d/m/Y', strtotime($value['task_end_date_time']));
            $return['task_end_time'] = (string)date("h:i A", strtotime($value['task_end_date_time']));
            $return['volunteer_id'] = (string)$value['volunteer_id'];
            $return['volunteer_name'] = (string)$value['volunteer_name'];
            $return['employee_id'] = (string)$value['employee_id'];
            $return['employee_name'] = (string)$value['employee_name'];
            $return['event_location'] = (string)$value['task_location'];
            
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }

}