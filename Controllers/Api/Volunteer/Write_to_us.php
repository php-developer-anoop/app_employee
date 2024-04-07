<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Write_to_us extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $volunteer_name = !empty($post['volunteer_name']) ? trim($post['volunteer_name']) : '';
        $email_id = !empty($post['email_id']) ? trim($post['email_id']) : '';
        $mobile_no = !empty($post['mobile_no']) ? trim($post['mobile_no']) : '';
        $query = !empty($post['query']) ? trim($post['query']) : '';
        if (empty($volunteer_id)) {
            $response["status"] = false;
            $response["message"] = "Volunteer Id is Blank";
            echo json_encode($response);
            exit();
        }
        if (empty($volunteer_name)) {
            $response["status"] = false;
            $response["message"] = "Volunteer Name is Blank";
            echo json_encode($response);
            exit();
        }
        if (empty($email_id)) {
            $response["status"] = false;
            $response["message"] = "Email Id is Blank";
            echo json_encode($response);
            exit();
        }
        if (empty($mobile_no)) {
            $response["status"] = false;
            $response["message"] = "Mobile No. is Blank";
            echo json_encode($response);
            exit();
        }
        if (empty($query)) {
            $response["status"] = false;
            $response["message"] = "Query is Blank";
            echo json_encode($response);
            exit();
        }
        $data = [];
        $data['employee_volunteer_id'] = $volunteer_id;
        $data['user_type'] = 'volunteer';
        $data['name'] = $volunteer_name;
        $data['email_id'] = $email_id;
        $data['mobile_no'] = $mobile_no;
        $data['query'] = $query;
        $data['add_date'] = date('Y-m-d H:i:s');
        $last_id = $this->model->insertRecords("contact_query", $data);
        if ($last_id) {
            $response['status'] = true;
            $response['message'] = "Query Posted Successfully ! We will get back to you shortly";
            echo json_encode($response);
            exit;
        }
    }
}
