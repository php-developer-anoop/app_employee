<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Update_profile_image extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkFormPayload($this->request->getVar());
        $employee_id = !empty($post['employee_id']) ? $post['employee_id'] : "";
        if (empty($employee_id)) {
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        };
    
        $old_profile_image = $this->model->getSingle("employee_list", "profile_image", ['profile_status' => 'Active', 'id' => $employee_id]);
        if ($file = $this->request->getFile('profile_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                if (is_file(ROOTPATH . '/uploads/' . $old_profile_image['profile_image']) && file_exists(ROOTPATH . '/uploads/' . $old_profile_image['profile_image'])) {
                    @unlink(ROOTPATH . '/uploads/' . $old_profile_image['profile_image']);
                }
                $profile_image = $file->getRandomName();
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $profile_image);
                $data['profile_image'] = $profile_image;
                $this->model->updateRecords("employee_list", $data, ['profile_status' => 'Active', 'id' => $employee_id]);
                $response['status'] = true;
                $response['message'] = 'Profile Image Updated Successfully!';
                echo json_encode($response);
                exit;
            }
        } 
            $response['status'] = false;
            $response['message'] = 'Please Select A File';
            echo json_encode($response);
            exit;
       
    }
}
