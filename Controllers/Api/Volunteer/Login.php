<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Login extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $email_id = !empty($post['email_id']) ? trim($post['email_id']) : '';
        $password = !empty($post['password']) ? trim($post['password']) : '';
        $device_id = !empty($post['device_id']) ? trim($post['device_id']) : '';
        $fcm_token = !empty($post['fcm_token']) ? trim($post['fcm_token']) : '';
        
        if (empty($email_id)) {
            $response['status'] = false;
            $response['message'] = 'Email is Blank!';
            echo json_encode($response);
            exit;
        }
        if (empty($password)) {
            $response['status'] = false;
            $response['message'] = 'Password is Blank!';
            echo json_encode($response);
            exit;
        }
        if (empty($device_id)) {
            $response['status'] = false;
            $response['message'] = 'Device Id is Blank!';
            echo json_encode($response);
            exit;
        }
        if (empty($fcm_token)) {
            $response['status'] = false;
            $response['message'] = 'FCM Token is Blank!';
            echo json_encode($response);
            exit;
        }
        $keys = "profile_status";
        $userData = $this->model->getSingle('volunteer_list', $keys, ['email_id' => $email_id, 'enc_password' => md5($password)]);
        if (!empty($userData)) {
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
            $data = [];
            $data['device_id'] = $device_id;
            $data['fcm_token'] = $fcm_token;
            $path = base_url(UPLOADS);
            $this->model->updateRecords('volunteer_list', $data, ['email_id' => $email_id, 'enc_password' => md5($password)]);
            $keys = "id,full_name,mobile_no,email_id,date_of_birth,CONCAT('" . $path . "',profile_image) as profile_image,profile_status,duty_status,state_id,state_name,district_id,district_name,block_id,block_name,address,pincode,latitude,longitude,device_id,fcm_token,employee_id,employee_name";
            $userData = $this->model->getSingle('volunteer_list', $keys, ['email_id' => $email_id, 'enc_password' => md5($password)]);
            $return = [];
            $return['volunteer_id'] = (string)$userData['id'];
            $return['volunteer_name'] = (string)$userData['full_name'];
            $return['volunteer_mobile_number'] = (string)$userData['mobile_no'];
            $return['volunteer_email'] = (string)$userData['email_id'];
            $return['volunteer_dob'] = (string)$userData['date_of_birth'];
            $return['profile_image'] = (string)$userData['profile_image'];
            $return['profile_status'] = (string)$userData['profile_status'];
            $return['duty_status'] = (string)$userData['duty_status'];
            $return['state_id'] = (string)$userData['state_id'];
            $return['state_name'] = (string)$userData['state_name'];
            $return['district_id'] = (string)$userData['district_id'];
            $return['district_name'] = (string)$userData['district_name'];
            $return['block_id'] = (string)$userData['block_id'];
            $return['block_name'] = (string)$userData['block_name'];
            $return['address'] = (string)$userData['address'];
            $return['pincode'] = (string)$userData['pincode'];
            $return['latitude'] = (string)$userData['latitude'];
            $return['longitude'] = (string)$userData['longitude'];
            $return['employee_id'] = (string)$userData['employee_id'];
            $return['employee_name'] = (string)$userData['employee_name'];
            $return['device_id'] = (string)$userData['device_id'];
            $return['fcm_token'] = (string)$userData['fcm_token'];
            $response['status'] = true;
            $response['data'] = $return;
            $response['message'] = 'Login Successfully !';
            echo json_encode($response);
            exit;
        } else {
            $response['status'] = false;
            $response['message'] = 'You Are Not Registered';
            echo json_encode($response);
            exit;
        }
    }
    public function forgot_password() {
        $response = [];
        $post = checkPayload();
        $email_id = !empty($post['email_id']) ? trim($post['email_id']) : '';
        if (empty($email_id)) {
            $response['status'] = false;
            $response['message'] = 'Email Id is Blank!';
            echo json_encode($response);
            exit;
        }
        $keys = "profile_status";
        $userData = $this->model->getSingle('volunteer_list', $keys, ['email_id' => $email_id]);
        if (empty($userData)) {
            $response['status'] = false;
            $response['message'] = 'Email Id is not registered !';
            echo json_encode($response);
            exit;
        }
        if (!empty($userData)) {
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
            $enc_password = generate_password(10);
            sendEmailForgotPassword($email_id, $email_id, $enc_password);
            $this->model->updateRecords("volunteer_list", ['enc_password' => md5($enc_password)], ['email_id' => $email_id]);
            $response['status'] = true;
            $response['message'] = 'New Password has been sent on entered Email Id';
            echo json_encode($response);
            exit;
        }
    }
    public function logout(){
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer ID is Blank!';
            echo json_encode($response);
            exit;
        }
        $userData = $this->model->getSingle('volunteer_list', '*', ['id' => $volunteer_id]);
        if (empty($userData)) {
            $response['status'] = false;
            $response['message'] = 'User Does Not Exists!';
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
        $data = [];
        $data['device_id'] = '';
        $data['fcm_token'] = '';
        $this->model->updateRecords('volunteer_list', $data, ['id' => $volunteer_id]);
        $response['status'] = true;
        $response['message'] = 'Logout successfully!';
        echo json_encode($response);
        exit;
    }
}
