<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Patient extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_patient_list";
    }
    function index() {
        $data = [];
        $data["title"] = "Patient List";
        $data['from_date'] = !empty($this->request->getVar('from_date')) ? $this->request->getVar('from_date') : "";
        $data['to_date'] = !empty($this->request->getVar('to_date')) ? $this->request->getVar('to_date') : "";
        adminview('view-patient', $data);
    }
    function add_patient() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data["title"] = !empty($id) ? "Edit Patient" : "Add Patient";
        $data["state_list"] = $this->c_model->getAllData('state', 'id,state_name', ['status' => 'Active']);
        $data["disease_list"] = $this->c_model->getAllData('disease_list', 'id,disease_name', ['status' => 'Active']);
        $data["employee_list"] = $this->c_model->getAllData('employee_list', 'id,full_name', ['profile_status' => 'Active']);
        $data["hospital_list"] = $this->c_model->getAllData('hospital_list', 'id,hospital_name', ['profile_status' => 'Active']);
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        if(empty($savedData) && !empty($id)){
          $this->session->setFlashdata('failed', 'No Record Found');
            return redirect()->to(base_url(ADMINPATH . 'patient-list'));
        }
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['full_name'] = !empty($savedData['full_name']) ? $savedData['full_name'] : '';
        $data['mobile_no'] = !empty($savedData['mobile_no']) ? $savedData['mobile_no'] : '';
        $data['gender'] = !empty($savedData['gender']) ? $savedData['gender'] : '';
        $data['occupation'] = !empty($savedData['occupation']) ? $savedData['occupation'] : '';
        $data['date_of_birth'] = !empty($savedData['dob']) ? date('m/d/Y', strtotime($savedData['dob'])) : '';
        $data['address'] = !empty($savedData['address']) ? $savedData['address'] : '';
        $data['aadhaar_number'] = !empty($savedData['aadhaar_no']) ? $savedData['aadhaar_no'] : '';
        $data['pincode'] = !empty($savedData['pincode']) ? $savedData['pincode'] : '';
        $data['profile_image'] = !empty($savedData['profile_image']) ? $savedData['profile_image'] : '';
        $data['aadhaar_front_image'] = !empty($savedData['aadhaar_front_image']) ? $savedData['aadhaar_front_image'] : '';
        $data['aadhaar_back_image'] = !empty($savedData['aadhaar_back_image']) ? $savedData['aadhaar_back_image'] : '';
        $data['employee_id'] = !empty($savedData['employee_id']) ? $savedData['employee_id'] : '';
        $data['employee_name'] = !empty($savedData['employee_name']) ? $savedData['employee_name'] : '';
        $data['block_id'] = !empty($savedData['block_id']) ? $savedData['block_id'] : '';
        $data['block_name'] = !empty($savedData['block_name']) ? $savedData['block_name'] : '';
        $data['volunteer_id'] = !empty($savedData['volunteer_id']) ? $savedData['volunteer_id'] : '';
        $data['volunteer_name'] = !empty($savedData['volunteer_name']) ? $savedData['volunteer_name'] : '';
        $data['district_id'] = !empty($savedData['district_id']) ? $savedData['district_id'] : '';
        $data['district_name'] = !empty($savedData['district_name']) ? $savedData['district_name'] : '';
        $data['state_id'] = !empty($savedData['state_id']) ? $savedData['state_id'] : '';
        $data['disease_id'] = !empty($savedData['disease_id']) ? $savedData['disease_id'] : '';
        $data['hospital_id'] = !empty($savedData['hospital_id']) ? $savedData['hospital_id'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'add';
        adminview('add-patient', $data);
    }
    public function save_patient() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $state = !empty($post['state']) ? explode(',', $post['state']) : [];
        $block = !empty($post['block']) ? explode(',', $post['block']) : [];
        $district = !empty($post['district']) ? explode(',', $post['district']) : [];
        $hospital = !empty($post['hospital']) ? explode(',', $post['hospital']) : [];
        $employee = !empty($post['employee']) ? explode(',', $post['employee']) : [];
        $volunteer = !empty($post['volunteer']) ? explode(',', $post['volunteer']) : [];
        $disease = !empty($post['disease']) ? explode(',', $post['disease']) : [];
        $data['mobile_no'] = trim($post['mobile_no']);
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && empty($id)) {
            $this->session->setFlashdata('failed', 'Duplicate Entry');
            return redirect()->to(base_url(ADMINPATH . 'patient-list'));
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
        if ($file = $this->request->getFile('aadhaar_front_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $aadhaar_front_image = $file->getRandomName();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_aadhaar_front_image']) && file_exists(ROOTPATH . 'uploads/' . $post['old_aadhaar_front_image'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_aadhaar_front_image']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $aadhaar_front_image);
                $data['aadhaar_front_image'] = $aadhaar_front_image;
            }
        }
        if ($file = $this->request->getFile('aadhaar_back_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $aadhaar_back_image = $file->getRandomName();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_aadhaar_back_image']) && file_exists(ROOTPATH . 'uploads/' . $post['old_aadhaar_back_image'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_aadhaar_back_image']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $aadhaar_back_image);
                $data['aadhaar_back_image'] = $aadhaar_back_image;
            }
        }
        $data['disease_id'] = $disease[0]??'';
        $data['disease_name'] = $disease[1]??'';
        $data['hospital_id'] = $hospital[0]??'';
        $data['hospital_name'] = $hospital[1]??'';
        $data['employee_id'] = $employee[0]??'';
        $data['employee_name'] = $employee[1]??'';
        $data['volunteer_id'] = $volunteer[0]??'';
        $data['volunteer_name'] = $volunteer[1]??'';
        $data['state_id'] = $state[0]??'';
        $data['state_name'] = $state[1]??'';
        $data['block_id'] = $block[0]??'';
        $data['block_name'] = $block[1]??'';
        $data['district_id'] = $district[0]??'';
        $data['district_name'] = $district[1]??'';
        $data['full_name'] = ucwords(trim($post['full_name']));
        $data['dob'] = date('Y-m-d', strtotime(trim($post['date_of_birth'])));
        $data['gender'] = trim($post['gender']);
        $data['occupation'] = trim($post['occupation']);
        $data['address'] = trim($post['address']);
        $data['aadhaar_no'] = trim($post['aadhaar_number']);
        $data['pincode'] = trim($post['pincode']);
        $data['added_by'] = 'Admin';
        $data['added_by_username'] = !empty($_SESSION['login_data']['role_user_name'])?$_SESSION['login_data']['role_user_name']:"";
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
        return redirect()->to(base_url(ADMINPATH . 'patient-list'));
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
        $where['status'] = 'add';
        if (!empty($post['from_date']) && !empty($post['to_date'])) {
            $where["DATE(add_date) >="] = $post['from_date'];
            $where["DATE(add_date) <="] = $post['to_date'];
        }
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where[" full_name LIKE '%" . $searchString . "%' OR aadhaar_no LIKE '%" . $searchString . "%' OR state_name LIKE '%" . $searchString . "%' OR district_name LIKE '%" . $searchString . "%' OR block_name LIKE '%" . $searchString . "%' OR hospital_name LIKE '%" . $searchString . "%' OR employee_name LIKE '%" . $searchString . "%' OR volunteer_name LIKE '%" . $searchString . "%' OR mobile_no LIKE '%" . $searchString . "%' OR dob LIKE '%" . $searchString . "%' OR pincode LIKE '%" . $searchString . "%'"] = null;
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
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date,DATE_FORMAT(dob , "%d/%m/%Y") AS dob';
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
    public function convert_patient() {
        $post = $this->request->getVar();
        $id = !empty($post['id']) ? $post['id'] : "";
        $patient_data = $this->c_model->getSingle("patient_list", '*', ['id' => $id]);
        // echo "<pre>";
        // print_r($post);die;
        $data = [];
        $converted_date_time = date('Y-m-d', strtotime(!empty($post['converted_date']) ? $post['converted_date'] : "")) . ' ' . date('H:i:s', strtotime(!empty($post['converted_time']) ? $post['converted_time'] : ""));
        $data['attended_by_doctor_name'] = !empty($post['doctor_name']) ? $post['doctor_name'] : "";
        $data['admit_date'] = !empty($converted_date_time) ? $converted_date_time : "";
        $data['hospital_id'] = !empty($post['hospital_id']) ? $post['hospital_id'] : "";
        $data['hospital_name'] = !empty($post['hospital_name']) ? $post['hospital_name'] : "";
        $data['employee_id'] = !empty($patient_data['employee_id']) ? $patient_data['employee_id'] : "";
        $data['employee_name'] = !empty($patient_data['employee_name']) ? $patient_data['employee_name'] : "";
        $data['volunteer_id'] = !empty($patient_data['volunteer_id']) ? $patient_data['volunteer_id'] : "";
        $data['volunteer_name'] = !empty($patient_data['volunteer_name']) ? $patient_data['volunteer_name'] : "";
        $data['patient_id'] = !empty($patient_data['id']) ? $patient_data['id'] : "";
        $data['patient_full_name'] = !empty($patient_data['full_name']) ? $patient_data['full_name'] : "";
        $data['patient_mobile_no'] = !empty($patient_data['mobile_no']) ? $patient_data['mobile_no'] : "";
        $data['dob'] = !empty($patient_data['dob']) ? $patient_data['dob'] : "";
        $data['gender'] = !empty($patient_data['gender']) ? $patient_data['gender'] : "";
        $data['occupation'] = !empty($patient_data['occupation']) ? $patient_data['occupation'] : "";
        $data['address'] = !empty($patient_data['address']) ? $patient_data['address'] : "";
        $data['aadhaar_front_image'] = !empty($patient_data['aadhaar_front_image']) ? $patient_data['aadhaar_front_image'] : "";
        $data['aadhaar_back_image'] = !empty($patient_data['aadhaar_back_image']) ? $patient_data['aadhaar_back_image'] : "";
        $data['profile_image'] = !empty($patient_data['profile_image']) ? $patient_data['profile_image'] : "";
        $data['aadhaar_no'] = !empty($patient_data['aadhaar_no']) ? $patient_data['aadhaar_no'] : "";
        $data['state_id'] = !empty($patient_data['state_id']) ? $patient_data['state_id'] : "";
        $data['state_name'] = !empty($patient_data['state_name']) ? $patient_data['state_name'] : "";
        $data['district_id'] = !empty($patient_data['district_id']) ? $patient_data['district_id'] : "";
        $data['district_name'] = !empty($patient_data['district_name']) ? $patient_data['district_name'] : "";
        $data['block_id'] = !empty($patient_data['block_id']) ? $patient_data['block_id'] : "";
        $data['block_name'] = !empty($patient_data['block_name']) ? $patient_data['block_name'] : "";
        $data['pincode'] = !empty($patient_data['pincode']) ? $patient_data['pincode'] : "";
        $data['disease_id'] = !empty($patient_data['disease_id']) ? $patient_data['disease_id'] : "";
        $data['disease_name'] = !empty($patient_data['disease_name']) ? $patient_data['disease_name'] : "";
        $data['add_date'] = date('Y-m-d H:i:s');
        $data['status'] = 'convert';
        //   echo "<pre>";
        // print_r($data);die;
        $last_id = $this->c_model->insertRecords("case_history", $data);
        if ($last_id) {
            $this->c_model->updateRecords("patient_list", ['status' => 'convert'], ['id' => $id]);
        }
        $this->session->setFlashdata('success', 'Patient Converted Successfully');
        return redirect()->to(base_url(ADMINPATH . 'case-history'));
    }
    public function converted_patient_list() {
        $data = [];
        $data["title"] = "Case History";
        $data['from_date'] = !empty($this->request->getVar('from_date')) ? $this->request->getVar('from_date') : "";
        $data['to_date'] = !empty($this->request->getVar('to_date')) ? $this->request->getVar('to_date') : "";
        adminview('view-converted-patient', $data);
    }
    public function getConvertedRecords() {
        $post = $this->request->getVar();
        $get = $this->request->getVar();
        $limit = (int)(!empty($get["length"]) ? $get["length"] : 1);
        $start = (int)!empty($get["start"]) ? $get["start"] : 0;
        $is_count = !empty($post["is_count"]) ? $post["is_count"] : "";
        $totalRecords = !empty($get["recordstotal"]) ? $get["recordstotal"] : 0;
        $orderby = "DESC";
        $where = [];
        if (!empty($post['from_date']) && !empty($post['to_date'])) {
            $where["DATE(admit_date) >="] = $post['from_date'];
            $where["DATE(admit_date) <="] = $post['to_date'];
        }
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where[" hospital_name LIKE '%" . $searchString . "%' OR attended_by_doctor_name LIKE '%" . $searchString . "%' OR patient_full_name LIKE '%" . $searchString . "%' OR aadhaar_no LIKE '%" . $searchString . "%' OR state_name LIKE '%" . $searchString . "%' OR district_name LIKE '%" . $searchString . "%' OR block_name LIKE '%" . $searchString . "%' OR hospital_name LIKE '%" . $searchString . "%' OR employee_name LIKE '%" . $searchString . "%' OR volunteer_name LIKE '%" . $searchString . "%' OR patient_mobile_no LIKE '%" . $searchString . "%' OR dob LIKE '%" . $searchString . "%' OR pincode LIKE '%" . $searchString . "%'"] = null;
            $limit = 100;
            $start = 0;
        }
        $countData = $this->c_model->countRecords("case_history", $where, 'id');
        // echo $this->c_model->getLastQuery();die;
        if ($is_count == "yes") {
            echo (int)(!empty($countData) ? sizeof($countData) : 0);
            exit();
        }
        if (!empty($get["showRecords"])) {
            $limit = $get["showRecords"];
            $orderby = "DESC";
        }
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(admit_date , "%d-%m-%Y %r") AS admit_date,DATE_FORMAT(dob , "%d/%m/%Y") AS dob';
        $listData = $this->c_model->getAllData("case_history", $select, $where, $limit, $start, $orderby);
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
