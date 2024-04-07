<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Attendance extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_duty_list";
    }
    function index() {
        $data = [];
        $data["title"] = "Attendance List";
        $data["user_type"] = !empty($this->request->getVar('user_type')) ? $this->request->getVar('user_type') : '';
        $data["user_id"] = !empty($this->request->getVar('user_id')) ? $this->request->getVar('user_id') : '';
        $data["from_date"] = !empty($this->request->getVar('from_date')) ? $this->request->getVar('from_date') : '';
        $data["to_date"] = !empty($this->request->getVar('to_date')) ? $this->request->getVar('to_date') : '';
        adminview('view-attendance', $data);
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
        if (!empty($post['user_type'])) {
            $where["user_type"] = $post['user_type'];
        }
        if (!empty($post['user_id'])) {
            $where["employee_volunteer_id"] = $post['user_id'];
        }
        if (!empty($post['from_date']) && !empty($post['to_date'])) {
            $where["DATE(login_date_time) >="] = $post['from_date'];
            $where["DATE(login_date_time) <="] = $post['to_date'];
        }
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where["user_type LIKE '%" . $searchString . "%' OR login_address LIKE '%" . $searchString . "%' OR logout_address LIKE '%" . $searchString . "%'"] = null;
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
        $select = '*,DATE_FORMAT(login_date_time, "%d-%m-%Y") AS login_date,DATE_FORMAT(login_date_time, "%h:%i:%s %p") AS login_time,DATE_FORMAT(logout_date_time, "%d-%m-%Y") AS logout_date,DATE_FORMAT(logout_date_time, "%h:%i:%s %p") AS logout_time';
        $listData = $this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby);
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
    public function exportFullAttendance() {
        $get=$this->request->getVar();
        
        $users = db()->query('SELECT id ,"employee" as user_type FROM dt_employee_list UNION SELECT id ,"volunteer" as user_type FROM dt_volunteer_list')->getResultArray();
        // Assuming $get['month'] contains a string in the format 'YYYY-MM'
        $exploded = explode('-', $get['month']);
        $year = $exploded[0];
        $month = $exploded[1];
        
        // Determine if the month is in the past
        if ($month < date('m') || ($month == date('m') && $year < date('Y'))) {
            // Month is in the past, so set current day to the last day of the month
            $currentDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        } else {
            // Month is current or in the future, so set current day to today's day
            $currentDay = date('d');
        }

        
        
        $numDays =  $currentDay;
        $headerLabels = ['Employee Name', 'Type'];
        for ($day = 1;$day <= $numDays;$day++) {
            $headerLabels[] = sprintf('%02d', $day);
        }
        $csvData = [];
        $csvData[] = $headerLabels;
        foreach ($users as $key => $value) {
            $name = getName($value['id'], $value['user_type']);
            if ($name) {
                $attendance = [];
                for ($day = 1;$day <= $numDays;$day++) {
                    $attendance[$day - 1] = 'A';
                    $attendanceData = $this->c_model->getAllData('duty_list', 'login_date_time', ['MONTH(login_date_time)' => $month,'employee_volunteer_id'=>$value['id'],'user_type'=>$value['user_type'],'YEAR(login_date_time)' => $year, 'DAY(login_date_time)' => $day]);
                    if (!empty($attendanceData)) {
                        $attendance[$day - 1] = 'P';
                    }
                }
                $csvData[] = array_merge([$name, $value['user_type']], $attendance);
            }
        }
        $file = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' .$get['month'].'.csv');
        foreach ($csvData as $data) {
            fputcsv($file, $data);
        }
        fclose($file);
        exit();
    }
}
