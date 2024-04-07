<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Dashboard extends BaseController {
    protected $c_model;
    
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        
        $this->table = "dt_duty_list";
    }
    public function index() {
        $data['meta_title'] = 'Dashboard';
        adminview('dashboard', $data);
    }
    public function attendance_dashboard() {
        $data['meta_title'] = 'Attendance Dashboard';
        adminview('attendance-dashboard', $data);
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
            $where["user_type LIKE '%" . $searchString . "%' OR login_address LIKE '%" . $searchString . "%' OR logout_address LIKE '%" . $searchString . "%'"] = null;
            $limit = 10;
            $start = 0;
        }
        $countData = $this->c_model->countRecords($this->table, $where, 'id',10);
        if ($is_count == "yes") {
            echo (int)(!empty($countData) ? sizeof($countData) : 0);
            exit();
        }
        if (!empty($get["showRecords"])) {
            $limit = $get["showRecords"];
            $orderby = "DESC";
        }
        $select = '*,DATE_FORMAT(login_date_time, "%d-%m-%Y") AS login_date,DATE_FORMAT(login_date_time, "%h:%i:%s %p") AS login_time,DATE_FORMAT(logout_date_time, "%d-%m-%Y") AS logout_date,DATE_FORMAT(logout_date_time, "%h:%i:%s %p") AS logout_time';
        $listData = $this->c_model->getAllData($this->table, $select, $where, 10, 0, $orderby);
        $result = [];
        if (!empty($listData)) {
            $i = $start + 1;
            foreach ($listData as $key => $value) {
                $push = [];
                $push = $value;
                $push["sr_no"] = $i;
                $push['employee_volunteer_name'] = getName($push['employee_volunteer_id'], $push['user_type']);
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
?>