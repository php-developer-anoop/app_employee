<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Autologin extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $device_id = !empty($post['device_id']) ? trim($post['device_id']) : '';
        $fcm_token = !empty($post['fcm_token']) ? trim($post['fcm_token']) : '';
        if (empty($device_id)) {
            $response['status'] = false;
            $response['message'] = 'Device Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($fcm_token)) {
            $response['status'] = false;
            $response['message'] = 'FCM Token is empty!';
            echo json_encode($response);
            exit;
        }
        $where = [];
        $where['device_id'] = $device_id;
        $where['fcm_token'] = $fcm_token;
        $path = base_url(UPLOADS);
        $userData = $this->model->getSingle('employee_list', "*,CONCAT('" . $path . "',profile_image) as profile_image", $where);
        if (empty($userData)) {
            $response['status'] = false;
            $response['message'] = 'User Not Found!';
            echo json_encode($response);
            exit;
        }
        if ($userData['profile_status'] == "Blocked") {
            $response['status'] = false;
            $response['message'] = 'Your Profile is Blocked Please Contact To Admin !';
            echo json_encode($response);
            exit;
        }
        if ($userData['profile_status'] == "Inactive") {
            $response['status'] = false;
            $response['message'] = 'Your Profile is Currently Inactive Please Contact To Admin !';
            echo json_encode($response);
            exit;
        }
        $return = [];
        $return['employee_id']            = (string)$userData['id'];
        $return['employee_name']          = (string)$userData['full_name'];
        $return['employee_mobile_number'] = (string)$userData['mobile_no'];
        $return['employee_email']         = (string)$userData['email_id'];
        $return['employee_doj']           = (string)$userData['date_of_joining'];
        $return['profile_image']          = (string)$userData['profile_image'];
        $return['profile_status']         = (string)$userData['profile_status'];
        $return['duty_status']            = (string)$userData['duty_status'];
        $return['state_id']               = (string)$userData['state_id'];
        $return['state_name']             = (string)$userData['state_name'];
        $return['district_id']            = (string)$userData['district_id'];
        $return['district_name']          = (string)$userData['district_name'];
        $return['project_duration']       = (string)$userData['project_duration'];
        $return['payroll_type']           = (string)$userData['payroll_type'];
        $return['designation']            = (string)$userData['designation'];
        $return['manager_id']             = (string)$userData['manager_id'];
        $return['manager_name']           = (string)$userData['manager_name'];
        $return['project_id']             = (string)$userData['project_id'];
        $return['project_name']           = (string)$userData['project_name'];
        $return['latitude']               = (string)$userData['latitude'];
        $return['longitude']              = (string)$userData['longitude'];
        $return['device_id']              = (string)$userData['device_id'];
        $return['fcm_token']              = (string)$userData['fcm_token'];
        
        if (!empty($return)) {
            $response['status'] = true;
            $response['data'] = $return;
            $response['message'] = 'Login Successfully !';
            echo json_encode($response);
            exit;
        }
    }
}
