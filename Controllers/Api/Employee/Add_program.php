<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Add_program extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $data = [];
        $post = checkPayload();
        $id = !empty($post['id']) ? trim($post['id']) : '';
        $employee_id = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $employee_name = !empty($post['employee_name']) ? trim($post['employee_name']) : '';
        $program_id = !empty($post['program_id']) ? trim($post['program_id']) : '';
        $program_name = !empty($post['program_name']) ? trim($post['program_name']) : '';
        $program_start_date = !empty($post['program_start_date']) ? trim($post['program_start_date']) : '';
        $program_start_time = !empty($post['program_start_time']) ? trim($post['program_start_time']) : '';
        $program_location = !empty($post['program_location']) ? trim($post['program_location']) : '';
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $volunteer_name = !empty($post['volunteer_name']) ? trim($post['volunteer_name']) : '';
        $program_end_date = !empty($post['program_end_date']) ? trim($post['program_end_date']) : '';
        $program_end_time = !empty($post['program_end_time']) ? trim($post['program_end_time']) : '';
        $purpose = !empty($post['purpose']) ? trim($post['purpose']) : '';
        
        if (empty($program_id)) {
            $response['status'] = false;
            $response['message'] = 'Program Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($program_name)) {
            $response['status'] = false;
            $response['message'] = 'Program Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($program_start_date)) {
            $response['status'] = false;
            $response['message'] = 'Program Start Date is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($program_start_time)) {
            $response['status'] = false;
            $response['message'] = 'Program Start Time is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($program_location)) {
            $response['status'] = false;
            $response['message'] = 'Program Location is empty!';
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
        if (empty($program_end_date)) {
            $response['status'] = false;
            $response['message'] = 'Program End Date is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($program_end_time)) {
            $response['status'] = false;
            $response['message'] = 'Program End Time is empty!';
            echo json_encode($response);
            exit;
        }
        // if (empty($id)) {
        //     $checkDuplicate = $this->model->getSingle("program_list", 'id', ['program_id' => $program_id, 'program_name' => $program_name]);
        //     if ($checkDuplicate) {
        //         $response['status'] = false;
        //         $response['message'] = "Duplicate Entry!";
        //         echo json_encode($response);
        //         exit;
        //     }
        // }
        $data['program_id'] = trim($program_id);
        $data['program_name'] = trim($program_name);
        $data['program_start_date_time'] = date('Y-m-d', strtotime($program_start_date)) . ' ' . date('H:i:s', strtotime($program_start_time));
        $data['program_end_date_time'] = date('Y-m-d', strtotime($program_end_date)) . ' ' . date('H:i:s', strtotime($program_end_time));
        $data['volunteer_id'] = trim($volunteer_id);
        $data['volunteer_name'] = trim($volunteer_name);
        $data['employee_id'] = trim($employee_id);
        $data['employee_name'] = trim($employee_name);
        $data['program_location'] = trim($program_location);
        $data['purpose'] = trim($purpose);
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $res = $this->model->insertRecords("program_list", $data);
            if ($res) {
                $response['status'] = true;
                $response['id'] = (string)$res;
                $response['message'] = "Program Added Successfully!";
                echo json_encode($response);
                exit;
            }
        } else {
            //$data['update_date'] = date('Y-m-d H:i:s');
            $this->model->updateRecords("program_list", $data, ['id' => $id]);
            $response['status'] = TRUE;
            $response['message'] = "Program Updated Successfully!";
            echo json_encode($response);
            exit;
        }
    }
    
    public function upload_program_image() {
        $response = [];
        $data = [];
        $post = checkFormPayload($this->request->getVar());
        $id=!empty($post['id'])?trim($post['id']):"";
        if ($file = $this->request->getFile('image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $image = $file->getRandomName();
                $imageservice = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $image);
                $data['image'] = $image;
            } else {
                $response['status'] = false;
                $response['message'] = 'Please Select Image';
                echo json_encode($response);
                exit;
            }
        }
        $this->model->updateRecords("program_list", $data, ['id' => $id]);
        $response['status'] = true;
        $response['message'] = 'Image Added Successfully!';
        echo json_encode($response);
        exit;
    }
}
