<?php
namespace App\Controllers\Hospital;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Casehistory extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_case_history";
    }
    function index() {
        $data = [];
        $data["title"] = "Case History";
        $data['from_date']=!empty($this->request->getVar('from_date'))?$this->request->getVar('from_date'):"";
        $data['to_date']=!empty($this->request->getVar('to_date'))?$this->request->getVar('to_date'):"";
        hospitalview('case-history', $data);
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
        if(!empty($post['from_date']) && !empty($post['to_date'])){
            $where["DATE(admit_date) >="] = $post['from_date'];
            $where["DATE(admit_date) <="] = $post['to_date'];
        }
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where[" hospital_name LIKE '%" . $searchString . "%' OR attended_by_doctor_name LIKE '%" . $searchString . "%' OR patient_full_name LIKE '%" . $searchString . "%' OR aadhaar_no LIKE '%" . $searchString . "%' OR state_name LIKE '%" . $searchString . "%' OR district_name LIKE '%" . $searchString . "%' OR block_name LIKE '%" . $searchString . "%' OR hospital_name LIKE '%" . $searchString . "%' OR employee_name LIKE '%" . $searchString . "%' OR volunteer_name LIKE '%" . $searchString . "%' OR patient_mobile_no LIKE '%" . $searchString . "%' OR dob LIKE '%" . $searchString . "%' OR pincode LIKE '%" . $searchString . "%' OR DATE_FORMAT(admit_date,'%d-%m-%Y') LIKE '%" . $searchString . "%'"] = null;
            $limit = 100;
            $start = 0;
        }
        $countData = $this->c_model->countRecords($this->table, $where, 'id');
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