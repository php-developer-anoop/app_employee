<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Department extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_department";
    }
    function index() {
        $data = [];
        $data["title"] = "Department Master";
        adminview('view-department', $data);
    }
    function add_department() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data["title"] = !empty($id)?"Edit Department":"Add Department";
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['department_name'] = !empty($savedData['department_name']) ? $savedData['department_name'] : '';
        $data['priority'] = !empty($savedData['priority']) ? $savedData['priority'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-department', $data);
    }
    public function save_department() {
        $post = $this->request->getVar();
        
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data['department_name'] = ucwords(trim($post['department_name']));
        $data['priority'] = trim($post['priority']);
        $data['status'] = trim($post['status']);

        $duplicate=$this->c_model->getSingle($this->table,'id',$data);
        if($duplicate && empty($id)){
            $this->session->setFlashdata('failed', 'Duplicate Entry'); 
            return redirect()->to(base_url(ADMINPATH . 'department-list')); 
        }
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
        return redirect()->to(base_url(ADMINPATH . 'department-list'));
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
            $where[" department_name LIKE '%" . $searchString . "%'"] = null;
            $limit = 100;
            $start = 0;
        }
        $countData =$this->c_model->countRecords($this->table, $where, 'id');
        if ($is_count == "yes") {
            echo (int)(!empty($countData) ? sizeof($countData) : 0);
            exit();
        }
        if (!empty($get["showRecords"])) {
            $limit = $get["showRecords"];
            $orderby = "DESC";
        }
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date';
        $listData =$this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby);
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

   
public function assign_menus(){

    $response = [];

    if ($this->request->getMethod() != "post") {
    $response['status'] = false;
    $response['message'] = 'Invalid Request ';
    echo json_encode($response); exit;
    }
    $post = $this->request->getVar();
    $id = $post['department'] ? $post['department'] : '';
    if(empty($post['department'])){
    $response['status'] = false;
    $response['message'] = "Please Choose A Designation ";
    echo json_encode($response); exit;
    }
    
    $data =[];
    if(!empty($post['read'])){
        $data['read_menu_ids'] = implode(",",$post['read']);
    }
    if(!empty($post['write'])){
        $data['write_menu_ids'] = implode(",",$post['write']);
    }
    
    $this->c_model->updateRecords($this->table,$data,['id'=>$id]);
   
    $response['status'] = true;
    $response['message'] = "Menu Assigned Successfully";
    echo json_encode($response); exit;
    
}

}
?>
