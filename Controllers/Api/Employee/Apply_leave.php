<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Apply_leave extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $manager_id = !empty($post['manager_id']) ? trim($post['manager_id']) : '';
        $fcm_token = !empty($post['fcm_token']) ? trim($post['fcm_token']) : '';
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $employee_name = !empty($post['employee_name']) ? trim($post['employee_name']) : '';
        $leave_from = !empty($post['leave_from']) ? trim($post['leave_from']) : '';
        $leave_upto = !empty($post['leave_upto']) ? trim($post['leave_upto']) : '';
        $leave_reason = !empty($post['leave_reason']) ? trim($post['leave_reason']) : '';
        $description = !empty($post['description']) ? trim($post['description']) : '';
        if (empty($manager_id)) {
            $response['status'] = false;
            $response['message'] = 'Manager Id is empty!';
            echo json_encode($response);
            exit;
        }
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
        $data = [];
        $data['employee_volunteer_id'] = $employee_id;
        $data['employee_volunteer_name'] = $employee_name;
        $data['user_type']  = 'employee';
        $data['leave_from'] = date('Y-m-d', strtotime($leave_from));
        $data['leave_upto'] = date('Y-m-d', strtotime($leave_upto));
        $data['apply_date'] = date('Y-m-d H:i:s');
        $data['leave_reason'] = $leave_reason;
        $data['sender_fcm_token'] = $fcm_token;
        $data['description'] = $description;
        $last_id = $this->model->insertRecords("leaves_list", $data);
        $employee_fcm = $this->model->getSingle("employee_list", 'fcm_token', ['profile_status' => 'Active', 'id' => $manager_id]);
        $serverkey = FIREBASE_API_KEY;
        $msgarray = [];
        $saveRecords = [];
        $saveRecords['title'] = ucwords($leave_reason);
        $saveRecords['description'] = !empty($description) ? $description : $leave_reason;
        $saveRecords['add_date'] = date('Y-m-d H:i:s');
        $saveRecords['leave_id'] = trim($last_id);
        $msgarray['data'] = $saveRecords;
        $saveRecords['employee_volunteer_id'] = trim($manager_id);
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
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $type = !empty($post['type']) ? trim($post['type']) : '';
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
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
        $where['employee_volunteer_id'] = $employee_id;
        $where['user_type'] = 'employee';
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
            $return['leave_from'] = (string)date('d/m/Y', strtotime($value['leave_from']));
            $return['leave_reason'] = (string)$value['leave_reason'];
            $return['leave_upto'] = (string)date('d/m/Y', strtotime($value['leave_upto']));
            $return['action_date'] = (string)$action_date;
            $return['comment'] = (string)$value['comment'];
            $return['description'] = (string)$value['description'];
            $return['status'] = (string)$value['status'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }
    public function volunteer_approval_list() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        $leaves = [];
        $Ids = '';
        $where = 'user_type = \'volunteer\'';
        if (!empty($start_date) && !empty($end_date)) {
            $where.= ' AND DATE(leave_from) >= \'' . date('Y-m-d', strtotime($start_date)) . '\'';
            $where.= ' AND DATE(leave_from) <= \'' . date('Y-m-d', strtotime($end_date)) . '\'';
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $volunteerIds = $this->model->getAllData("volunteer_list", 'GROUP_CONCAT(id SEPARATOR ",") AS ids', ['profile_status' => 'Active', 'employee_id' => $employee_id]);
        if (!empty($volunteerIds[0]['ids'])) {
            $where.= ' AND employee_volunteer_id IN (' . $volunteerIds[0]['ids'] . ')';
            $query = "SELECT * FROM dt_leaves_list WHERE $where ORDER BY id DESC LIMIT $start, $limit";
            $leaves = db()->query($query)->getResultArray();
        }
        if (empty($leaves)) {
            $response['status'] = false;
            $response['message'] = 'No Records Found!';
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($leaves as $key => $value) {
            $action_date = $value['action_date'] == null ? "" : date('d/m/Y', strtotime($value['action_date']));
            $return['id'] = (string)$value['id'];
            $return['leave_from'] = (string)date('d/m/Y', strtotime($value['leave_from']));
            $return['leave_reason'] = (string)$value['leave_reason'];
            $return['leave_upto'] = (string)date('d/m/Y', strtotime($value['leave_upto']));
            $return['action_date'] = (string)$action_date;
            $return['comment'] = (string)$value['comment'];
            $return['user_name'] = (string)$value['employee_volunteer_name'];
            $return['description'] = (string)$value['description'];
            $return['status'] = (string)$value['status'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    public function employee_approval_list() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        $leaves = [];
        $Ids = '';
        $where = 'user_type = \'employee\'';
        if (!empty($start_date) && !empty($end_date)) {
            $where.= ' AND DATE(leave_from) >= \'' . date('Y-m-d', strtotime($start_date)) . '\'';
            $where.= ' AND DATE(leave_from) <= \'' . date('Y-m-d', strtotime($end_date)) . '\'';
        }
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $employeeIds = $this->model->getAllData("employee_list", 'GROUP_CONCAT(id SEPARATOR ",") AS ids', ['profile_status' => 'Active', 'manager_id' => $employee_id]);
        if (!empty($employeeIds[0]['ids'])) {
            $where.= ' AND employee_volunteer_id IN (' . $employeeIds[0]['ids'] . ')';
            $query = "SELECT * FROM dt_leaves_list WHERE $where ORDER BY id DESC LIMIT $start, $limit";
            $leaves = db()->query($query)->getResultArray();
        }
        if (empty($leaves)) {
            $response['status'] = false;
            $response['message'] = 'No Records Found!';
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($leaves as $key => $value) {
            $action_date = $value['action_date'] == null ? "" : date('d/m/Y', strtotime($value['action_date']));
            $return['id'] = (string)$value['id'];
            $return['leave_from'] = (string)date('d/m/Y', strtotime($value['leave_from']));
            $return['leave_reason'] = (string)$value['leave_reason'];
            $return['leave_upto'] = (string)date('d/m/Y', strtotime($value['leave_upto']));
            $return['action_date'] = (string)$action_date;
            $return['comment'] = (string)$value['comment'];
            $return['user_name'] = (string)$value['employee_volunteer_name'];
            $return['description'] = (string)$value['description'];
            $return['status'] = (string)$value['status'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    public function approve_reject_leave() {
        $response = [];
        $msgarray = [];
        $post = checkPayload();
        $id = !empty($post['id']) ? trim($post['id']) : '';
        $leave_status = !empty($post['leave_status']) ? trim($post['leave_status']) : '';
        $comment = !empty($post['comment']) ? trim($post['comment']) : '';
        if (empty($id)) {
            $response = ['status' => false, 'message' => 'Id is empty!'];
            echo json_encode($response);
            exit;
        } else if (empty($leave_status)) {
            $response = ['status' => false, 'message' => 'Leave Status is empty!'];
            echo json_encode($response);
            exit;
        } else if ($leave_status == "reject" && empty($comment)) {
            $response = ['status' => false, 'message' => 'Comment is empty!'];
            echo json_encode($response);
            exit;
        } else {
            $check_status = $this->model->getSingle("leaves_list", 'status', ['id' => $id]);
            if ($check_status['status'] == "approved" || $check_status['status'] == "reject") {
                $response = ['status' => false, 'message' => 'Leave is Already ' . ucfirst($check_status['status']) ];
                echo json_encode($response);
                exit;
            } else {
                $this->model->updateRecords("leaves_list", ['status' => $leave_status, 'comment' => $comment, "action_date" => date('Y-m-d H:i:s') ], ['id' => $id]);
                $response = ['status' => true, 'message' => 'Status Changed Successfully'];
                $fcm_token = $this->model->getSingle("leaves_list", 'sender_fcm_token,user_type,employee_volunteer_id', ['id' => $id]);
                $serverkey = FIREBASE_API_KEY;
                $saveRecords = [];
                $saveRecords['title'] = 'Your Leave is ' . ucwords($leave_status);
                //$saveRecords['description'] = !empty($description) ? $description : $leave_reason;
                $saveRecords['add_date'] = date('Y-m-d H:i:s');
                $saveRecords['leave_id'] = trim($id);
                $msgarray['data'] = $saveRecords;
                $saveRecords['employee_volunteer_id'] = trim($fcm_token['employee_volunteer_id']);
                $saveRecords['user_type'] = $fcm_token['user_type'];
                $saveRecords['status'] = 'Active';
                $this->model->insertRecords("notification_list", $saveRecords);
                $sendnoti = send($fcm_token['sender_fcm_token'], $msgarray, $serverkey);
                echo json_encode($response);
                exit;
            }
        }
    }
}
