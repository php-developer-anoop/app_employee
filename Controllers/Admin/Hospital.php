<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Hospital extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_hospital_list";
    }
    function index() {
        $data = [];
        $data["title"] = "Hospital Master";
        $data['category'] = !empty($this->request->getVar('category')) ? $this->request->getVar('category') : "";
        $data['employee'] = !empty($this->request->getVar('employee')) ? $this->request->getVar('employee') : "";
        $data['speciality'] = !empty($this->request->getVar('speciality')) ? $this->request->getVar('speciality') : "";
        $data['state_id'] = !empty($this->request->getVar('state')) ? $this->request->getVar('state') : '';
        $data['district_id'] = !empty($this->request->getVar('district')) ? $this->request->getVar('district') : '';
        $data['block_id'] = !empty($this->request->getVar('block')) ? $this->request->getVar('block') : '';
        $data["disease_list"] = $this->c_model->getAllData('scope_of_services_list', 'id,service_name', ['status' => 'Active']);
        $data["category_list"] = $this->c_model->getAllData('category_list', 'id,category_name', ['status' => 'Active']);
        $data["employee_list"] = $this->c_model->getAllData('employee_list', 'id,full_name', ['profile_status' => 'Active']);
        $data["state_list"] = $this->c_model->getAllData('state', 'id,state_name', ['status' => 'Active']);
        adminview('view-hospital', $data);
    }
    function add_hospital() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data["title"] = !empty($id) ? "Edit Hospital" : "Add Hospital";
        $data["state_list"] = $this->c_model->getAllData('state', 'id,state_name', ['status' => 'Active']);
        $data["service_list"] = $this->c_model->getAllData('scope_of_services_list', 'id,service_name', ['status' => 'Active']);
        $data["employee_list"] = $this->c_model->getAllData('employee_list', 'id,full_name', ['profile_status' => 'Active']);
        $data["category_list"] = $this->c_model->getAllData('category_list', 'id,category_name', ['status' => 'Active']);
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        if (empty($savedData) && !empty($id)) {
            $this->session->setFlashdata('failed', 'No Record Found');
            return redirect()->to(base_url(ADMINPATH . 'hospital-list'));
        }
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['hospital_name'] = !empty($savedData['hospital_name']) ? $savedData['hospital_name'] : '';
        $data['mobile_no'] = !empty($savedData['mobile_no']) ? $savedData['mobile_no'] : '';
        $data['email_id'] = !empty($savedData['email_id']) ? $savedData['email_id'] : '';
        $data['address'] = !empty($savedData['address']) ? $savedData['address'] : '';
        $data['pincode'] = !empty($savedData['pincode']) ? $savedData['pincode'] : '';
        $data['latitude'] = !empty($savedData['latitude']) ? $savedData['latitude'] : '';
        $data['longitude'] = !empty($savedData['longitude']) ? $savedData['longitude'] : '';
        $data['block_id'] = !empty($savedData['block_id']) ? $savedData['block_id'] : '';
        $data['block_name'] = !empty($savedData['block_name']) ? $savedData['block_name'] : '';
        $data['district_id'] = !empty($savedData['district_id']) ? $savedData['district_id'] : '';
        $data['district_name'] = !empty($savedData['district_name']) ? $savedData['district_name'] : '';
        $data['employee_id'] = !empty($savedData['employee_id']) ? $savedData['employee_id'] : '';
        $data['category'] = !empty($savedData['category']) ? $savedData['category'] : '';
        $data['service_ids'] = !empty($savedData['disease_ids']) ? explode(',', $savedData['disease_ids']) : [];
        $data['employee_name'] = !empty($savedData['employee_name']) ? $savedData['employee_name'] : '';
        $data['state_id'] = !empty($savedData['state_id']) ? $savedData['state_id'] : '';
        $data['signing_date'] = !empty($savedData['signing_date']) ? $savedData['signing_date'] : '';
        $data['expiry_date'] = !empty($savedData['expiry_date']) ? $savedData['expiry_date'] : '';
        $data['date_of_mou'] = !empty($savedData['date_of_mou']) ? $savedData['date_of_mou'] : '';
        $data['profile_status'] = !empty($savedData['profile_status']) ? $savedData['profile_status'] : 'Active';
        adminview('add-hospital', $data);
    }
    public function save_hospital() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        
        $state = !empty($post['state']) ? explode(',', $post['state']) : [];
        $block = !empty($post['block']) ? explode(',', $post['block']) : [];
        $district = !empty($post['district']) ? explode(',', $post['district']) : [];
        $data['email_id'] = trim($post['email_id']);
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && empty($id)) {
            $this->session->setFlashdata('failed', 'Duplicate Entry');
            return redirect()->to(base_url(ADMINPATH . 'hospital-list'));
        }
        $employee = !empty($post['employee']) ? explode(',', $post['employee']) : [];
        $data['mobile_no'] = trim($post['mobile_no']);
        $data['hospital_name'] = ucwords(trim($post['hospital_name']));
        if (empty($id)) {
            $password = generate_password(10);
            $data['enc_password'] = md5($password);
            sendEmail($data['email_id'], $data['email_id'], $password);
        }
        $disease_ids = $post['disease_ids']??[];
        $data['disease_ids'] = count($disease_ids) > 1 ? implode(',', $disease_ids) : $disease_ids;
        $data['state_id'] = $state[0]??'';
        $data['state_name'] = $state[1]??'';
        $data['employee_id'] = $employee[0]??'';
        $data['employee_name'] = $employee[1]??'';
        $data['block_id'] = $block[0]??'';
        $data['block_name'] = $block[1]??'';
        $data['district_id'] = $district[0]??'';
        $data['district_name'] = $district[1]??'';
        $data['address'] = trim($post['address']);
        $data['pincode'] = trim($post['pincode']);
        $data['latitude'] = trim($post['latitude']);
        $data['category'] = trim($post['category']);
        $data['signing_date'] = date('Y-m-d', strtotime(trim($post['signing_date'])));
        $data['expiry_date'] = date('Y-m-d', strtotime(trim($post['expiry_date'])));
        $data['date_of_mou'] = date('Y-m-d', strtotime(trim($post['date_of_mou'])));
        $data['profile_status'] = trim($post['profile_status']);
        $last_id = '';
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $last_id = $this->c_model->insertRecords($this->table, $data);
            $this->session->setFlashdata('success', 'Data Added Successfully ');
        } else {
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $this->session->setFlashdata('success', 'Data Updated Successfully');
        }
        return redirect()->to(base_url(ADMINPATH . 'hospital-list'));
    }
    public function getRecords() {
        $post = $this->request->getVar();
        $get = $this->request->getVar();
        $limit = (int)(!empty($get["length"]) ? $get["length"] : 1);
        $start = (int)!empty($get["start"]) ? $get["start"] : 0;
        $is_count = !empty($post["is_count"]) ? $post["is_count"] : "";
        $totalRecords = !empty($get["recordstotal"]) ? $get["recordstotal"] : 0;
        $orderby = "DESC";
        $searchString = null;
        $where = [];

        if (!empty($post['employee'])) {
            $where[$this->table . ".employee_id"] = $post['employee'];
        }
        if (!empty($post['state'])) {
            $where[$this->table . ".state_id"] = $post['state'];
        }
        if (!empty($post['district'])) {
            $where[$this->table . ".district_id"] = $post['district'];
        }
        if (!empty($post['block'])) {
            $where[$this->table . ".block_id"] = $post['block'];
        }
        if (!empty($post['category']) && empty($post['speciality'])) {
            $where[$this->table . ".category"] = $post['category'];
        } elseif (empty($post['category']) && !empty($post['speciality'])) {
            $where["FIND_IN_SET('" . $post['speciality'] . "'," . $this->table . ".disease_ids) <>"] = 0;
        } elseif (!empty($post['category']) && !empty($post['speciality'])) {
            $where[$this->table . ".category"] = $post['category'];
            $where["FIND_IN_SET('" . $post['speciality'] . "'," . $this->table . ".disease_ids) <>"] = 0;
        }

        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where["$this->table.hospital_name LIKE '%" . $searchString . "%' OR $this->table.state_name LIKE '%" . $searchString . "%' OR $this->table.district_name LIKE '%" . $searchString . "%' OR $this->table.block_name LIKE '%" . $searchString . "%' OR $this->table.mobile_no LIKE '%" . $searchString . "%' OR $this->table.email_id LIKE '%" . $searchString . "%' OR $this->table.pincode LIKE '%" . $searchString . "%'"] = null;
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
        $select = $this->table . '.*, category_list.category_name, DATE_FORMAT(' . $this->table . '.add_date, "%d-%m-%Y %r") AS add_date, DATE_FORMAT(' . $this->table . '.update_date, "%d-%m-%Y %r") AS update_date';
        $listData = $this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby, null, null, 'category_list', $this->table . '.category=category_list.id', 'left');
        //echo $this->c_model->getLastQuery();exit;
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
