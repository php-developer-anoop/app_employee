<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Add_volunteer_task extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $data = [];
        $post            = checkPayload();
        $id              = !empty($post['id']) ? trim($post['id']) : '';
        $task_id         = !empty($post['task_id']) ? trim($post['task_id']) : '';
        $task_name       = !empty($post['task_name']) ? trim($post['task_name']) : '';
        $task_start_date = !empty($post['task_start_date']) ? trim($post['task_start_date']) : '';
        $task_start_time = !empty($post['task_start_time']) ? trim($post['task_start_time']) : '';
        $task_location   = !empty($post['task_location']) ? trim($post['task_location']) : '';
        $volunteer_id    = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $volunteer_name  = !empty($post['volunteer_name']) ? trim($post['volunteer_name']) : '';
        $employee_id     = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $employee_name   = !empty($post['employee_name']) ? trim($post['employee_name']) : '';
        $task_end_date   = !empty($post['task_end_date']) ? trim($post['task_end_date']) : '';
        $task_end_time   = !empty($post['task_end_time']) ? trim($post['task_end_time']) : '';
        
        if (empty($task_id)) {
            $response['status']  = false;
            $response['message'] = 'Task Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($task_name)) {
            $response['status']  = false;
            $response['message'] = 'Task Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($task_start_date)) {
            $response['status']  = false;
            $response['message'] = 'Task Start Date is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($task_start_time)) {
            $response['status']  = false;
            $response['message'] = 'Task Start Time is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($task_location)) {
            $response['status']  = false;
            $response['message'] = 'Task Location is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($volunteer_id)) {
            $response['status']  = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($volunteer_name)) {
            $response['status']  = false;
            $response['message'] = 'Volunteer Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($employee_id)) {
            $response['status']  = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($employee_name)) {
            $response['status']  = false;
            $response['message'] = 'Employee Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($task_end_date)) {
            $response['status']  = false;
            $response['message'] = 'Task End Date is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($task_end_time)) {
            $response['status']  = false;
            $response['message'] = 'Task End Time is empty!';
            echo json_encode($response);
            exit;
        }
        // if (empty($id)) {
        //     $checkDuplicate = $this->model->getSingle("volunteer_task_list", 'id', ['task_id' => $task_id, 'task_name' => $task_name]);
        //     if ($checkDuplicate) {
        //         $response['status']  = false;
        //         $response['message'] = "Duplicate Entry!";
        //         echo json_encode($response);
        //         exit;
        //     }
        // }
        $data['task_id'] = trim($task_id);
        $data['task_name'] = trim($task_name);
        $data['task_start_date_time'] = date('Y-m-d', strtotime($task_start_date)) . ' ' . date('H:i:s', strtotime($task_start_time));
        $data['task_end_date_time'] = date('Y-m-d', strtotime($task_end_date)) . ' ' . date('H:i:s', strtotime($task_end_time));
        $data['volunteer_id'] = trim($volunteer_id);
        $data['volunteer_name'] = trim($volunteer_name);
        $data['employee_id'] = trim($employee_id);
        $data['employee_name'] = trim($employee_name);
        $data['task_location'] = trim($task_location);
        
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $res = $this->model->insertRecords("volunteer_task_list", $data);
            if ($res) {
                $response['status'] = TRUE;
                $response['message'] = "Task Added Successfully!";
                echo json_encode($response);
                exit;
            }
        } else {
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->model->updateRecords("volunteer_task_list", $data, ['id' => $id]);
            $response['status'] = TRUE;
            $response['message'] = "Task Updated Successfully!";
            echo json_encode($response);
            exit;
        }
    }
}
