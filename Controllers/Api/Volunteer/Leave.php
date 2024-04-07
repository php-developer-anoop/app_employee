<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Leave extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $volunteer_name = !empty($post['volunteer_name']) ? trim($post['volunteer_name']) : '';
        $leave_from = !empty($post['leave_from']) ? trim($post['leave_from']) : '';
        $leave_upto = !empty($post['leave_upto']) ? trim($post['leave_upto']) : '';
        $leave_reason = !empty($post['leave_reason']) ? trim($post['leave_reason']) : '';
        $description = !empty($post['description']) ? trim($post['description']) : '';
        $fcm_token = !empty($post['fcm_token']) ? trim($post['fcm_token']) : '';
        
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
        if (empty($volunteer_name)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($leave_from)) {
            $response['status'] = false;
            $response['message'] = 'Leave Date is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($leave_upto)) {
            $response['status'] = false;
            $response['message'] = 'Leave Upto is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($leave_reason)) {
            $response['status'] = false;
            $response['message'] = 'Leave Reason is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($description)) {
            $response['status'] = false;
            $response['message'] = 'Description is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($fcm_token)) {
            $response['status'] = false;
            $response['message'] = 'FCM Token is empty!';
            echo json_encode($response);
            exit;
        }
        $employee_fcm = $this->model->getSingle("employee_list", 'fcm_token', ['profile_status' => 'Active', 'id' => $employee_id]);
        $serverkey = FIREBASE_API_KEY;
        $data = [];
        $data['employee_volunteer_id'] = $volunteer_id;
        $data['employee_volunteer_name'] = $volunteer_name;
        $data['user_type'] = 'volunteer';
        $data['leave_from'] = date('Y-m-d', strtotime($leave_from));
        $data['leave_upto'] = date('Y-m-d', strtotime($leave_upto));
        $data['apply_date'] = date('Y-m-d H:i:s');
        $data['leave_reason'] = $leave_reason;
        $data['sender_fcm_token'] = $fcm_token;
        $data['description'] = $description;
        $last_id = $this->model->insertRecords("leaves_list", $data);
        
        $msgarray = [];
        $saveRecords = [];
        $saveRecords['title'] = ucwords($leave_reason);
        $saveRecords['description'] = !empty($description) ? $description : $leave_reason;
        $saveRecords['add_date'] = date('Y-m-d H:i:s');
        $saveRecords['leave_id'] = trim($last_id);
        $msgarray['data'] = $saveRecords;
        $saveRecords['employee_volunteer_id'] = trim($employee_id);
        $saveRecords['user_type'] = 'employee';
        $saveRecords['status'] = 'Active';
        $this->model->insertRecords("notification_list", $saveRecords);
        $sendnoti = send($employee_fcm['fcm_token'], $msgarray, $serverkey);
        
        if ($last_id) {
            $response['status'] = true;
            $response['message'] = 'Leave Applied Successfully!';
            echo json_encode($response);
            exit;
        }
    }
    public function leave_list() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $type = !empty($post['type']) ? trim($post['type']) : '';
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
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
        $where['employee_volunteer_id'] = $volunteer_id;
        $where['user_type'] = 'volunteer';
        if ($type == "upcoming") {
            $where["DATE(leave_from) >"] = date('Y-m-d');
        }
        // Date Filter
        if (!empty($start_date) && !empty($end_date)) {
            $where["DATE(leave_from) >="] = date('Y-m-d', strtotime($start_date));
            $where["DATE(leave_from) <="] = date('Y-m-d', strtotime($end_date));
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $leaves = $this->model->getAllData("leaves_list", '*', $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($leaves)) {
            $response['status'] = false;
            $response['message'] = 'No Records Found!';
            echo json_encode($response);
            exit; 
        }
        $returnData = [];
        foreach ($leaves as $key => $value) {
            $action_date = $value['action_date'] == null ? "" : date('d/m/Y', strtotime($value['action_date']));
            $return['leave_from']   = (string)date('d/m/Y', strtotime($value['leave_from']));
            $return['leave_reason'] = (string)$value['leave_reason'];
            $return['leave_upto']   = (string)date('d/m/Y', strtotime($value['leave_upto']));
            $return['action_date']  = (string)$action_date;
            $return['comment']      = (string)$value['comment'];
            $return['description']  = (string)$value['description'];
            $return['status']       = (string)$value['status'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    public function reason_list() {
        $response = [];
        checkHeaders();
        $where = [];
        $where['status'] = 'Active';
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,type,reason";
        $reasons = $this->model->getAllData('reason_master', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($reasons)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($reasons as $key => $value) {
            $return['reason_id'] = (string)$value['id'];
            $return['type'] = (string)$value['type'];
            $return['reason'] = (string)$value['reason'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
