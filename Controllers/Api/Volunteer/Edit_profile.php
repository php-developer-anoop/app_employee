<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Edit_profile extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $volunteer_id = !empty($post['volunteer_id']) ? trim($post['volunteer_id']) : '';
        $volunteer_name = !empty($post['volunteer_name']) ? trim($post['volunteer_name']) : '';
        $date_of_birth = !empty($post['date_of_birth']) ? trim($post['date_of_birth']) : '';
        $address = !empty($post['address']) ? trim($post['address']) : '';
        $latitude = !empty($post['latitude']) ? trim($post['latitude']) : '';
        $longitude = !empty($post['longitude']) ? trim($post['longitude']) : '';
        $mobile_no = !empty($post['mobile_no']) ? trim($post['mobile_no']) : '';
        $email_id = !empty($post['email_id']) ? trim($post['email_id']) : '';
        $state_id = !empty($post['state_id']) ? trim($post['state_id']) : '';
        $state_name = !empty($post['state_name']) ? trim($post['state_name']) : '';
        $district_id = !empty($post['district_id']) ? trim($post['district_id']) : '';
        $district_name = !empty($post['district_name']) ? trim($post['district_name']) : '';
        $block_id = !empty($post['block_id']) ? trim($post['block_id']) : '';
        $block_name = !empty($post['block_name']) ? trim($post['block_name']) : '';
        $pincode = !empty($post['pincode']) ? trim($post['pincode']) : '';
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
        if (empty($date_of_birth)) {
            $response['status'] = false;
            $response['message'] = 'Date Of Birth is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($email_id)) {
            $response['status'] = false;
            $response['message'] = 'Email Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($address)) {
            $response['status'] = false;
            $response['message'] = 'Address is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($mobile_no)) {
            $response['status'] = false;
            $response['message'] = 'Mobile Number is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($state_id)) {
            $response['status'] = false;
            $response['message'] = 'State Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($state_name)) {
            $response['status'] = false;
            $response['message'] = 'State Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_id)) {
            $response['status'] = false;
            $response['message'] = 'District Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_name)) {
            $response['status'] = false;
            $response['message'] = 'District Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($block_id)) {
            $response['status'] = false;
            $response['message'] = 'Block Id is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($block_name)) {
            $response['status'] = false;
            $response['message'] = 'Block Name is empty!';
            echo json_encode($response);
            exit;
        }
        if (empty($pincode)) {
            $response['status'] = false;
            $response['message'] = 'Pincode is empty!';
            echo json_encode($response);
            exit;
        }
        $data = ['full_name' => trim($volunteer_name), 'date_of_birth' => date('Y-m-d', strtotime(trim($date_of_birth))), 'mobile_no' => trim($mobile_no), 'email_id' => trim($email_id), 'state_id' => trim($state_id), 'state_name' => trim($state_name), 'district_id' => trim($district_id), 'district_name' => trim($district_name), 'block_id' => trim($block_id), 'block_name' => trim($block_name), 'latitude' => trim($latitude), 'longitude' => trim($longitude), 'pincode' => trim($pincode), 'address' => trim($address), 'update_date' => trim(date('Y-m-d H:i:s')) ];
        $this->model->updateRecords("volunteer_list", $data, ['id' => $volunteer_id]);
        $response['status'] = TRUE;
        $response['message'] = "Profile Updated Successfully!";
        echo json_encode($response);
        exit;
    }
    public function update_profile_image() {
        $response = [];
        $post = checkFormPayload($this->request->getVar());
        $volunteer_id = !empty($post['volunteer_id']) ? $post['volunteer_id'] : "";
        if (empty($volunteer_id)) {
            $response['status'] = false;
            $response['message'] = 'Volunteer Id is empty!';
            echo json_encode($response);
            exit;
        };
        $old_profile_image = $this->model->getSingle("volunteer_list", "profile_image", ['profile_status' => 'Active', 'id' => $volunteer_id]);
        if ($file = $this->request->getFile('profile_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                if (is_file(ROOTPATH . '/uploads/' . $old_profile_image['profile_image']) && file_exists(ROOTPATH . '/uploads/' . $old_profile_image['profile_image'])) {
                    @unlink(ROOTPATH . '/uploads/' . $old_profile_image['profile_image']);
                }
                $profile_image = $file->getRandomName();
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $profile_image);
                $data['profile_image'] = $profile_image;
                $this->model->updateRecords("volunteer_list", $data, ['profile_status' => 'Active', 'id' => $volunteer_id]);
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
