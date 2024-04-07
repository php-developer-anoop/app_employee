<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Volunteer extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_volunteer_list";
    }
    function index() {
        $data = [];
        $data["title"] = "Volunteer Master";
        adminview('view-volunteer', $data);
    }
    function add_volunteer() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data["title"] = !empty($id) ? "Edit Volunteer" : "Add Volunteer";
        $data["state_list"] = $this->c_model->getAllData('state', 'id,state_name', ['status' => 'Active']);
        $data["employee_list"] = $this->c_model->getAllData('employee_list', 'id,full_name', ['profile_status' => 'Active']);
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['full_name'] = !empty($savedData['full_name']) ? $savedData['full_name'] : '';
        $data['mobile_no'] = !empty($savedData['mobile_no']) ? $savedData['mobile_no'] : '';
        $data['email_id'] = !empty($savedData['email_id']) ? $savedData['email_id'] : '';
        $data['date_of_birth'] = !empty($savedData['date_of_birth']) ? date('m/d/Y',strtotime($savedData['date_of_birth'])) : '';
        $data['address'] = !empty($savedData['address']) ? $savedData['address'] : '';
        $data['pincode'] = !empty($savedData['pincode']) ? $savedData['pincode'] : '';
        $data['gender'] = !empty($savedData['gender']) ? $savedData['gender'] : '';
        $data['profile_image'] = !empty($savedData['profile_image']) ? $savedData['profile_image'] : '';
        $data['latitude'] = !empty($savedData['latitude']) ? $savedData['latitude'] : '';
        $data['longitude'] = !empty($savedData['longitude']) ? $savedData['longitude'] : '';
        $data['block_id'] = !empty($savedData['block_id']) ? $savedData['block_id'] : '';
        $data['block_name'] = !empty($savedData['block_name']) ? $savedData['block_name'] : '';
        $data['district_id'] = !empty($savedData['district_id']) ? $savedData['district_id'] : '';
        $data['district_name'] = !empty($savedData['district_name']) ? $savedData['district_name'] : '';
        $data['state_id'] = !empty($savedData['state_id']) ? $savedData['state_id'] : '';
        $data['employee_id'] = !empty($savedData['employee_id']) ? $savedData['employee_id'] : '';
        $data['profile_status'] = !empty($savedData['profile_status']) ? $savedData['profile_status'] : 'Active';
        adminview('add-volunteer', $data);
    }
    public function save_volunteer() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        
        $data = [];
        $state = !empty($post['state']) ? explode(',', $post['state']) : [];
        $block = !empty($post['block']) ? explode(',', $post['block']) : [];
        $district = !empty($post['district']) ? explode(',', $post['district']) : [];
        $employee = !empty($post['employee']) ? explode(',', $post['employee']) : [];
        $data['mobile_no'] = trim($post['mobile_no']);
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && empty($id)) {
            $this->session->setFlashdata('failed', 'Duplicate Entry');
            return redirect()->to(base_url(ADMINPATH . 'volunteer-list'));
        }
        if ($file = $this->request->getFile('profile_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $profile_image = $file->getRandomName();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_profile_image']) && file_exists(ROOTPATH . 'uploads/' . $post['old_profile_image'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_profile_image']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $profile_image);
                $data['profile_image'] = $profile_image;  
            }
        }
        $data['state_id'] = $state[0]??'';
        $data['state_name'] = $state[1]??'';
        $data['block_id'] = $block[0]??'';
        $data['block_name'] = $block[1]??'';
        $data['district_id'] = $district[0]??'';
        $data['district_name'] = $district[1]??'';
        $data['employee_id'] = $employee[0]??'';
        $data['employee_name'] = $employee[1]??'';
        $data['full_name'] = ucwords(trim($post['full_name']));
        
        $data['email_id'] = trim($post['email_id']);
        $data['date_of_birth'] = date('Y-m-d',strtotime(trim($post['date_of_birth'])));
        if(empty($id)){
            $password=generate_password(10);
            $data['enc_password'] = md5($password);
            sendEmail($data['email_id'],$data['email_id'],$password);
        }
        $data['gender'] = trim($post['gender']);
        $data['address'] = trim($post['address']);
        $data['pincode'] = trim($post['pincode']);
        $data['latitude'] = trim($post['latitude']);
        $data['longitude'] = trim($post['longitude']);
        $data['profile_status'] = trim($post['profile_status']);
        $last_id = '';
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $last_id = $this->c_model->insertRecords($this->table, $data);
            $this->session->setFlashdata('success', 'Data Added Successfully ');
        } else {
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $last_id = $id;
            $this->session->setFlashdata('success', 'Data Updated Successfully');
        }
        return redirect()->to(base_url(ADMINPATH . 'volunteer-list'));
    }
    public function getRecords() {
        $post = $this->request->getVar();
        $get = $this->request->getVar();
        $limit = (int)(!empty($get["length"]) ? $get["length"] : 1);
        $start = (int)!empty($get["start"]) ? $get["start"] : 0;
        $is_count = !empty($post["is_count"]) ? $post["is_count"] : "";
        $totalRecords = !empty($get["recordstotal"]) ? $get["recordstotal"] : 0;
        $orderby = "DESC";
        $where = [];
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where[" full_name LIKE '%" . $searchString . "%'  OR employee_name LIKE '%" . $searchString . "%' OR state_name LIKE '%" . $searchString . "%' OR district_name LIKE '%" . $searchString . "%' OR block_name LIKE '%" . $searchString . "%' OR mobile_no LIKE '%" . $searchString . "%' OR email_id LIKE '%" . $searchString . "%' OR date_of_birth LIKE '%" . $searchString . "%' OR pincode LIKE '%" . $searchString . "%' OR employee_name LIKE '%" . $searchString . "%'"] = null;
            $limit = 100;
            $start = 0;
        }
        $countData = $this->c_model->countRecords($this->table, $where, 'id');
        if ($is_count == "yes") {
            echo (int)(!empty($countData) ? sizeof($countData) : 0);
            exit();
        }
        if (!empty($get["showRecords"])) {
            $limit = $get["showRecords"];
            $orderby = "DESC";
        }
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date';
        $listData = $this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby);
        $result = [];
        if (!empty($listData)) {
            $i = $start + 1;
            foreach ($listData as $key => $value) {
                $push = [];
                $push = $value;
                $push["sr_no"] = $i;
                array_push($result, $push);
                $i++;
            }
        }
        $json_data = [];
        if (!empty($get["search"]["value"])) {
            $countItems = !empty($result) ? count($result) : 0;
            $json_data["draw"] = intval($get["draw"]);
            $json_data["recordsTotal"] = intval($countItems);
            $json_data["recordsFiltered"] = intval($countItems);
            $json_data["data"] = !empty($result) ? $result : [];
        } else {
            $json_data["draw"] = intval($get["draw"]);
            $json_data["recordsTotal"] = intval($totalRecords);
            $json_data["recordsFiltered"] = intval($totalRecords);
            $json_data["data"] = !empty($result) ? $result : [];
        }
        echo json_encode($json_data);
    }
}
