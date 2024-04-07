<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Logout extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee ID is Blank!';
            echo json_encode($response);
            exit;
        }
        $userData = $this->model->getSingle('employee_list', '*', ['id' => $employee_id]);
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
        $this->model->updateRecords('employee_list', $data, ['id' => $employee_id]);
        $response['status'] = true;
        $response['message'] = 'Logout successfully!';
        echo json_encode($response);
        exit;
    }
}
