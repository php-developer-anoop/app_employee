<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Duty_on extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $address = !empty($post['address']) ? trim($post['address']) : '';
        $latitude = !empty($post['latitude']) ? trim($post['latitude']) : '';
        $longitude = !empty($post['longitude']) ? trim($post['longitude']) : '';
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($address)) {
            $response['status'] = false;
            $response['message'] = 'Address is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($latitude)) {
            $response['status'] = false;
            $response['message'] = 'Latitude is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($longitude)) {
            $response['status'] = false;
            $response['message'] = 'Longitude is empty!';
            echo json_encode($response);
            exit;
        }
    
        $duty = $this->model->getSingle("volunteer_list", 'duty_status,last_duty_id', ['profile_status' => 'Active', 'id' => $volunteer_id]);
        if (empty($duty)) {
            $response['status'] = false;
            $response['message'] = 'No Record Found';
            echo json_encode($response);
            exit;
        }
        else if ($duty['duty_status'] == "on" || !empty($duty['last_duty_id'])) {
            $response['status'] = false;
            $response['message'] = 'You Are Already On Duty!';
            echo json_encode($response);
            exit;
        }
        $data = [];
        $data['employee_volunteer_id'] = $volunteer_id;
        $data['user_type'] = 'volunteer';
        $data['login_address'] = $address;
        $data['login_coords'] = $latitude . ',' . $longitude;
        $data['login_date_time'] = date('Y-m-d H:i:s');
        $data['add_date'] = date('Y-m-d H:i:s');
        $last_id = $this->model->insertRecords("duty_list", $data);
        if ($last_id) {
            $this->model->updateRecords("volunteer_list", ['last_duty_id' => $last_id, 'duty_status' => 'on'], ['id' => $volunteer_id, 'profile_status' => 'Active']);
            $response['status'] = TRUE;
            $response['message'] = "Duty Status Changes To On!";
            echo json_encode($response);
            exit;
        }
    }
    public function duty_off() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $address = !empty($post['address']) ? trim($post['address']) : '';
        $latitude = !empty($post['latitude']) ? trim($post['latitude']) : '';
        $longitude = !empty($post['longitude']) ? trim($post['longitude']) : '';
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($address)) {
            $response['status'] = false;
            $response['message'] = 'Address is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($latitude)) {
            $response['status'] = false;
            $response['message'] = 'Latitude is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($longitude)) {
            $response['status'] = false;
            $response['message'] = 'Longitude is empty!';
            echo json_encode($response);
            exit;
        }
        $duty = $this->model->getSingle("volunteer_list", 'duty_status,last_duty_id', ['profile_status' => 'Active', 'id' => $volunteer_id]);
        if (empty($duty)) {
            $response['status'] = false;
            $response['message'] = 'No Record Found';
            echo json_encode($response);
            exit;
        }
        else if ($duty['duty_status'] == "off" || $duty['last_duty_id'] == 0) {
            $response['status'] = false;
            $response['message'] = 'You Are Already Off Duty!';
            echo json_encode($response);
            exit;
        }
        $login_date_time = $this->model->getSingle("duty_list", 'login_date_time', ['id' => $duty['last_duty_id']]);
        $data = [];
        $data['logout_address'] = $address;
        $data['logout_coords'] = $latitude . ',' . $longitude;
        $data['logout_date_time'] = date('Y-m-d H:i:s');
        $data['total_work_time'] = getTimeInterval($login_date_time['login_date_time']);
        $this->model->updateRecords("duty_list", $data, ['id' => $duty['last_duty_id']]);
        $this->model->updateRecords("volunteer_list", ['duty_status' => 'off', 'last_duty_id' => ''], ['id' => $volunteer_id]);
        $response['status'] = TRUE;
        $response['message'] = "Duty Status Changes To Off!";
        echo json_encode($response);
        exit;
    }
    public function attendance_list() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $page_no = !empty($post["page_no"]) ? $post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? $post["per_page_limit"] : 10;
        $start_date = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date = !empty($post['end_date']) ? trim($post['end_date']) : '';
        if (empty($volunteer_id)) {
            $response["status"] = false;
            $response["message"] = "Volunteer Id is Blank";
            echo json_encode($response);
            exit();
        }
        $where = [];
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $where['employee_volunteer_id'] = $volunteer_id;
        $where['user_type'] = 'volunteer';
        if (!empty($start_date) && !empty($end_date)) {
            $where["DATE(add_date) >="] = date('Y-m-d',strtotime($start_date));
            $where["DATE(add_date) <="] = date('Y-m-d',strtotime($end_date));
        } 
        $records = $this->model->getAllData("duty_list", '*', $where, $limit, $start,'DESC','id');
        if (empty($records)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit();
        }
        $returnData = [];
        foreach ($records as $key => $value) {
        $return['attendance_date']   = (string)date('d/m/Y', strtotime($value['login_date_time']));
        $return['attendance_time']   = (string)date('H:i A', strtotime($value['login_date_time']));
        $return['recorded_location'] = (string)$value['login_address'];
        $return['logout_date']       = (string)(!is_null($value['logout_date_time'])) ? date('d/m/Y', strtotime($value['logout_date_time'])) : "";
        $return['logout_time']       = (string)(!is_null($value['logout_date_time'])) ? date('H:i A', strtotime($value['logout_date_time'])) : "";
        $return['logout_location']   = (string)$value['logout_address'];
        $return['total_work_time']   = (string)$value['total_work_time'] . " hours";
        array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
