<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Employee extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_employee_list";
    }
    function index() {
        $data = [];
        $data["title"] = "Employee Master";
        adminview('view-employee', $data);
    }
    function add_employee() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data["title"] = !empty($id) ? "Edit Employee" : "Add Employee";
        $data["state_list"] = $this->c_model->getAllData('state', 'id,state_name', ['status' => 'Active']);
        $data["project_list"] = $this->c_model->getAllData('project_master', 'id,project_name', ['status' => 'Active']);
        $data["manager_list"] = $this->c_model->getAllData('employee_list', 'id,full_name', ['profile_status' => 'Active']);
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['full_name'] = !empty($savedData['full_name']) ? $savedData['full_name'] : '';
        $data['mobile_no'] = !empty($savedData['mobile_no']) ? $savedData['mobile_no'] : '';
        $data['email_id'] = !empty($savedData['email_id']) ? $savedData['email_id'] : '';
        $data['date_of_joining'] = !empty($savedData['date_of_joining']) ? date('m/d/Y',strtotime($savedData['date_of_joining'])) : '';
        $data['project_duration'] = !empty($savedData['project_duration']) ? $savedData['project_duration'] : '';
        $data['payroll_type'] = !empty($savedData['payroll_type']) ? $savedData['payroll_type'] : '';
        $data['profile_image'] = !empty($savedData['profile_image']) ? $savedData['profile_image'] : '';
        $data['designation'] = !empty($savedData['designation']) ? $savedData['designation'] : '';
        $data['latitude'] = !empty($savedData['latitude']) ? $savedData['latitude'] : '';
        $data['longitude'] = !empty($savedData['longitude']) ? $savedData['longitude'] : '';
        $data['block_id'] = !empty($savedData['block_id']) ? $savedData['block_id'] : '';
        $data['block_name'] = !empty($savedData['block_name']) ? $savedData['block_name'] : '';
        $data['district_id'] = !empty($savedData['district_id']) ? $savedData['district_id'] : '';
        $data['district_name'] = !empty($savedData['district_name']) ? $savedData['district_name'] : '';
        $data['state_id'] = !empty($savedData['state_id']) ? $savedData['state_id'] : '';
        $data['manager_id'] = !empty($savedData['manager_id']) ? $savedData['manager_id'] : '';
        $data['project_id'] = !empty($savedData['project_id']) ? $savedData['project_id'] : '';
        $data['hospital_ids'] = !empty($savedData['hospital_ids']) ? $savedData['hospital_ids'] : '';
        $data['profile_status'] = !empty($savedData['profile_status']) ? $savedData['profile_status'] : 'Active';
        adminview('add-employee', $data);
    }
    public function save_employee() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
    
        $data = [];
        $state = !empty($post['state']) ? explode(',', $post['state']) : [];
        $reporting_manager = !empty($post['reporting_manager']) ? explode(',', $post['reporting_manager']) : [];
        $project = !empty($post['project']) ? explode(',', $post['project']) : [];
        $district = !empty($post['district']) ? explode(',', $post['district']) : [];
        $data['mobile_no'] = trim($post['mobile_no']);
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && empty($id)) {
            $this->session->setFlashdata('failed', 'Duplicate Entry');
            return redirect()->to(base_url(ADMINPATH . 'employee-list'));
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
        // if(!empty($post['hospital'])){
        //     $data['hospital_ids']=count($post['hospital'])>1?implode(',',$post['hospital']):$post['hospital'];
        // }else{
        //     $data['hospital_ids']='';
        // }
        $data['state_id'] = $state[0]??'';
        $data['state_name'] = $state[1]??'';
        $data['project_id'] = $project[0]??'';
        $data['project_name'] = $project[1]??'';
        $data['manager_id'] = $reporting_manager[0]??'';
        $data['manager_name'] = $reporting_manager[1]??'';
        $data['district_id'] = $district[0]??'';
        $data['district_name'] = $district[1]??'';
        $data['full_name'] = ucwords(trim($post['full_name']));
        $data['email_id'] = trim($post['email_id']);
        $data['date_of_joining'] = date('Y-m-d',strtotime(trim($post['date_of_joining'])));
        if(empty($id)){
            $password=generate_password(10);
            $data['enc_password'] = md5($password);
            sendEmail($data['email_id'],$data['email_id'],$password);
        }
        // $data['address'] = trim($post['address']);
        // $data['pincode'] = trim($post['pincode']);
        $data['project_duration'] = trim($post['project_duration']);
        $data['payroll_type'] = trim($post['payroll_type']);
        $data['designation'] = trim($post['designation']);
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
        return redirect()->to(base_url(ADMINPATH . 'employee-list'));
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
            $where[" full_name LIKE '%" . $searchString . "%' OR state_name LIKE '%" . $searchString . "%' OR district_name LIKE '%" . $searchString . "%' OR designation LIKE '%" . $searchString . "%' OR email_id LIKE '%" . $searchString . "%' OR mobile_no LIKE '%" . $searchString . "%' OR date_of_joining LIKE '%" . $searchString . "%' OR project_name LIKE '%" . $searchString . "%' OR manager_name LIKE '%" . $searchString . "%'"] = null;
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
        $select = '*,DATE_FORMAT(date_of_joining , "%d-%m-%Y") AS date_of_joining,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date';
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
    
    function assign_hospital() {
    $data = [];
    $data["title"] = "Assign Hospital";
    $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : "";
    $data["hospital_list"] = $this->c_model->getAllData('hospital_list', 'id,hospital_name', ['profile_status' => 'Active']);
    $data['assigned_hospitals'] = [];
    if (!empty($id)) {
        $hospital_ids = $this->c_model->getSingle('employee_list', 'full_name,hospital_ids', ['id' => $id]);
        if (!empty($hospital_ids['hospital_ids'])) {
            $hospitalIdsArray = explode(',', $hospital_ids['hospital_ids']);
            $data['hospitalIdsArray']=$hospitalIdsArray;
            $inStatement = implode(',', array_fill(0, count($hospitalIdsArray), '?'));
            $query = "SELECT hospital_name FROM dt_hospital_list WHERE id IN ($inStatement)";
            $data['assigned_hospitals'] = db()->query($query, $hospitalIdsArray)->getResultArray();
            $data['full_name'] = $hospital_ids['full_name'];
        } else {
            $data['assigned_hospitals'] = [];
        }
    } else {
        $data['assigned_hospitals'] = [];
    }
  
    adminview('assign-hospital', $data);
    }
    
    function employee_hospitals_list() {
        $data = [];
        $data["title"] = "Employee Hospitals";
        adminview('employee-hospitals-list', $data);
    }
    
    public function getEmpHosRecords() {
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
            $where[" full_name LIKE '%" . $searchString . "%' OR state_name LIKE '%" . $searchString . "%' OR district_name LIKE '%" . $searchString . "%' OR designation LIKE '%" . $searchString . "%' OR email_id LIKE '%" . $searchString . "%' OR mobile_no LIKE '%" . $searchString . "%' OR date_of_joining LIKE '%" . $searchString . "%' OR project_name LIKE '%" . $searchString . "%' OR manager_name LIKE '%" . $searchString . "%'"] = null;
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
      
        $select = '*,DATE_FORMAT(date_of_joining , "%d-%m-%Y") AS date_of_joining,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date';
        $listData = $this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby);
     
        $result = [];
        if (!empty($listData)) {
            $i = $start + 1;
            foreach ($listData as $key => $value) {
               
                $push = [];
                $push = $value;
                $push["sr_no"] = $i;
               $hospitalIdsArray = explode(',', $value['hospital_ids']);

if (!empty($hospitalIdsArray)) {
    $inStatement = implode(',', array_fill(0, count($hospitalIdsArray), '?'));

    $query = "SELECT hospital_name FROM dt_hospital_list WHERE id IN ($inStatement)";
    
    $assigned_hospitals = db()->query($query, $hospitalIdsArray)->getResultArray();

    $hospitalNames = [];
    foreach ($assigned_hospitals as $hospital) {
        $hospitalNames[] = $hospital['hospital_name'];
    }

    $push['assigned_hospitals'] = implode(',', $hospitalNames);
} else {
    $push['assigned_hospitals'] = ''; // Or any default value if no hospitals found
}

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
        // echo "<pre>";
        // print_r($json_data);exit;
        echo json_encode($json_data);
    }
}
