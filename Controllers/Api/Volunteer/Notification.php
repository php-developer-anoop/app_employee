<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Notification extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $page_no = !empty($post["page_no"]) ? $post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? $post["per_page_limit"] : 10;
        if (empty($volunteer_id)) {
            $response["status"] = false;
            $response["message"] = "Volunteer Id is Blank";
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
        $where['status'] = 'Active';
        $where['user_type'] = 'volunteer';
        $where['employee_volunteer_id'] = $volunteer_id;
        //$where['notification_id != '] = '';
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = 'id,title,description,image_path';
        $notifications = $this->model->getAllData('notification_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($notifications)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($notifications as $key => $value) {
            $return['id'] = (string)$value['id'];
            $return['title'] = (string)$value['title'];
            $return['description'] = (string)$value['description'];
            $return['image_path'] = (string)$value['image_path'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }
    public function delete_notification() {
        $response = [];
        $post = checkPayload();
        $id = !empty($post['id']) ? $post['id'] : "";
        $type = !empty($post['type']) ? $post['type'] : "";
        $volunteer_id = !empty($post['volunteer_id']) ? $post['volunteer_id'] : "";
        if (!in_array($type, ['single', 'multiple'])) {
            $response["status"] = false;
            $response["message"] = "Type is Invalid";
            echo json_encode($response);
            exit();
        } elseif ($type == "single" && empty($id)) {
            $response["status"] = false;
            $response["message"] = "Id is Blank";
            echo json_encode($response);
            exit();
        } elseif ($type == "multiple" && empty($volunteer_id)) {
            $response["status"] = false;
            $response["message"] = "Volunteer Id is Blank";
            echo json_encode($response);
            exit();
        }
        $where = [];
        if ($type == "single") {
            $where['id'] = $id;
        } else if ($type == "multiple") {
            $where['employee_volunteer_id'] = $volunteer_id;
            $where['notification_id !='] = '';
        }
        $where['user_type'] = 'volunteer';
        $this->model->deleteRecords("notification_list", $where);
        $response['status'] = true;
        $response['message'] = 'Notification Deleted Successfully';
        echo json_encode($response);
        exit;
    }
}
