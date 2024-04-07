<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Attendance_list extends BaseController {
    protected $c_model;
    protected $session;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
    }
    function index() {
        $data = [];
        $type = !empty($this->request->getVar('type')) ? $this->request->getVar('type') : '';
        $data["title"] = $type . " Workforce List";
        $data['workforce'] = [];
        $data['url'] = 'javascript:void(0)';
        if ($type == "Total") {
            $data['workforce'] = db()->query('SELECT full_name,mobile_no,email_id,district_name,state_name,"Employee" AS user_type FROM dt_employee_list WHERE profile_status="Active" UNION ALL SELECT full_name,mobile_no,email_id,district_name,state_name,"Volunteer" as user_type FROM dt_volunteer_list WHERE profile_status="Active"')->getResultArray();
         //   echo db()->getLastQuery();exit;
            $data['url'] = base_url(ADMINPATH . 'exportTotalAttendance');
        }
        if ($type == "Present") {
            $query = db()->query("SELECT GROUP_CONCAT(employee_volunteer_id) as total FROM dt_duty_list WHERE DATE(login_date_time) = ?", array(date('Y-m-d')))->getRowArray();
            $totalIds = $query['total']??'';
            if (!empty($totalIds)) {
                $data['workforce'] = db()->query("SELECT full_name, mobile_no, email_id, district_name, state_name, 'Employee' AS user_type FROM dt_employee_list WHERE profile_status = 'Active' AND id IN ($totalIds) UNION SELECT full_name, mobile_no, email_id, district_name, state_name, 'Volunteer' AS user_type FROM dt_volunteer_list WHERE profile_status = 'Active' AND id IN ($totalIds)")->getResultArray();
                $data['url'] = base_url(ADMINPATH . 'exportPresentAttendance');
            }
        }
        if ($type == "Absent") {
            $query = db()->query("SELECT GROUP_CONCAT(employee_volunteer_id) AS total FROM dt_duty_list WHERE DATE(login_date_time) = ?", array(date('Y-m-d')))->getRowArray();
            $totalIdsPresent = $query['total']??'';
            if (!empty($totalIdsPresent)) {
                $absentQuery = "SELECT full_name, mobile_no, email_id, district_name, state_name, 'Employee' AS user_type FROM dt_employee_list WHERE profile_status = 'Active' AND id NOT IN ($totalIdsPresent) UNION SELECT full_name, mobile_no, email_id, district_name, state_name, 'Volunteer' AS user_type FROM dt_volunteer_list WHERE profile_status = 'Active' AND id NOT IN ($totalIdsPresent)";
                $data['workforce'] = db()->query($absentQuery)->getResultArray();
            }
            $data['url'] = base_url(ADMINPATH . 'exportAbsentAttendance');
        }
        if ($type == "Late") {
            $lateQuery = db()->query("SELECT GROUP_CONCAT(employee_volunteer_id) AS total FROM dt_duty_list WHERE DATE(login_date_time) = ? AND TIME(login_date_time) > '10:00:00'", array(date('Y-m-d')))->getRowArray();
            $totalIdsLate = $lateQuery['total']??'';
            if (!empty($totalIdsLate)) {
                $lateEntriesQuery = "SELECT full_name, mobile_no, email_id, district_name, state_name, 'Employee' AS user_type FROM dt_employee_list WHERE profile_status = 'Active' AND id IN ($totalIdsLate) UNION SELECT full_name, mobile_no, email_id, district_name, state_name, 'Volunteer' AS user_type FROM dt_volunteer_list WHERE profile_status = 'Active' AND id IN ($totalIdsLate)";
                $data['workforce'] = db()->query($lateEntriesQuery)->getResultArray();
            }
            $data['url'] = base_url(ADMINPATH . 'exportLateAttendance');
        }
        adminview('attendance-list', $data);
    }
    public function exportTotalAttendance() {
        $users = db()->query('SELECT id ,"employee" as user_type FROM dt_employee_list UNION SELECT id ,"volunteer" as user_type FROM dt_volunteer_list')->getResultArray();
        $currentDay = date('d');
        $currentMonth = date('m');
        $currentYear = date('Y');
        $numDays = $currentDay;
        $headerLabels = ['Employee Name', 'Type', 'Login Date', 'Login Time', 'Location', 'Logout Date', 'Logout Time', 'Location', 'Late Arrivals'];
        $csvData = [];
        $csvData[] = $headerLabels;
        foreach ($users as $key => $value) {
            $name = getName($value['id'], $value['user_type']);
            if ($name) {
                $attendance = [];
                for ($day = 1;$day <= $numDays;$day++) {
                    $attendanceData = $this->c_model->getAllData('duty_list', 'login_date_time,logout_date_time,login_address,logout_address', ['DATE(login_date_time)' => date('Y-m-d'), 'employee_volunteer_id' => $value['id'], 'user_type' => $value['user_type']]);
                    if (!empty($attendanceData)) {
                        foreach ($attendanceData as $key => $data) {
                            $attendance['login_date'] = !empty($data['login_date_time']) ? date('Y-m-d', strtotime($data['login_date_time'])) : 'N/A';
                            $attendance['login_time'] = !empty($data['login_date_time']) ? date('H:i:s', strtotime($data['login_date_time'])) : 'N/A'; // Changed to 24-hour format
                            $attendance['login_address'] = !empty($data['login_address']) ? $data['login_address'] : 'N/A';
                            $attendance['logout_date'] = !empty($data['logout_date_time']) ? date('Y-m-d', strtotime($data['logout_date_time'])) : 'N/A';
                            $attendance['logout_time'] = !empty($data['logout_date_time']) ? date('H:i:s', strtotime($data['logout_date_time'])) : 'N/A'; // Changed to 24-hour format
                            $attendance['logout_address'] = !empty($data['logout_address']) ? $data['logout_address'] : 'N/A';
                            $attendance['late_arrivals'] = (date('H:i:s', strtotime($data['login_date_time'])) > '10:00:00') ? 'Yes' : 'No';
                        }
                    }
                }
                $csvData[] = array_merge([$name, $value['user_type']], $attendance);
            }
        }
        $file = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . date('Y-m-d') . '_total_workforce.csv');
        foreach ($csvData as $data) {
            fputcsv($file, $data);
        }
        fclose($file);
        exit();
    }
    public function exportPresentAttendance() {
        $attendanceData = $this->c_model->getAllData('duty_list', 'employee_volunteer_id,user_type,login_date_time,logout_date_time,login_address,logout_address', ['DATE(login_date_time)' => date('Y-m-d') ],null,null,null,null,'employee_volunteer_id');
        $headerLabels = ['Employee Name', 'Type', 'Login Date', 'Login Time', 'Location', 'Logout Date', 'Logout Time', 'Location', 'Late Arrivals'];
        $csvData = [];
        $csvData[] = $headerLabels;
        foreach ($attendanceData as $data) {
            $attendance = [];
            $attendance['name'] = getName($data['employee_volunteer_id'], $data['user_type']);
            $attendance['user_type'] = ucfirst($data['user_type']);
            $attendance['login_date'] = !empty($data['login_date_time']) ? date('Y-m-d', strtotime($data['login_date_time'])) : 'N/A';
            $attendance['login_time'] = !empty($data['login_date_time']) ? date('H:i:s', strtotime($data['login_date_time'])) : 'N/A';
            $attendance['login_address'] = !empty($data['login_address']) ? $data['login_address'] : 'N/A';
            $attendance['logout_date'] = !empty($data['logout_date_time']) ? date('Y-m-d', strtotime($data['logout_date_time'])) : 'N/A';
            $attendance['logout_time'] = !empty($data['logout_date_time']) ? date('H:i:s', strtotime($data['logout_date_time'])) : 'N/A';
            $attendance['logout_address'] = !empty($data['logout_address']) ? $data['logout_address'] : 'N/A';
            $attendance['late_arrivals'] = (strtotime($data['login_date_time']) > strtotime('10:00:00')) ? 'Yes' : 'No';
            $csvData[] = $attendance;
        }
        $file = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . date('Y-m-d') . '_present_workforce.csv');
        foreach ($csvData as $data) {
            fputcsv($file, $data);
        }
        fclose($file);
        exit();
    }
    public function exportLateAttendance() {
        $attendanceData = $this->c_model->getAllData('duty_list', 'employee_volunteer_id,user_type,login_date_time,logout_date_time,login_address,logout_address', ['TIMe(login_date_time) > ' => '10:00:00', 'DATE(login_date_time)' => date('Y-m-d') ],null,null,null,null,'employee_volunteer_id');
        $headerLabels = ['Employee Name', 'Type', 'Login Date', 'Login Time', 'Location', 'Logout Date', 'Logout Time', 'Location', 'Late Arrivals'];
        $csvData = [];
        $csvData[] = $headerLabels;
        foreach ($attendanceData as $data) {
            $attendance = [];
            $attendance['name'] = getName($data['employee_volunteer_id'], $data['user_type']);
            $attendance['user_type'] = ucfirst($data['user_type']);
            $attendance['login_date'] = !empty($data['login_date_time']) ? date('Y-m-d', strtotime($data['login_date_time'])) : 'N/A';
            $attendance['login_time'] = !empty($data['login_date_time']) ? date('H:i:s', strtotime($data['login_date_time'])) : 'N/A';
            $attendance['login_address'] = !empty($data['login_address']) ? $data['login_address'] : 'N/A';
            $attendance['logout_date'] = !empty($data['logout_date_time']) ? date('Y-m-d', strtotime($data['logout_date_time'])) : 'N/A';
            $attendance['logout_time'] = !empty($data['logout_date_time']) ? date('H:i:s', strtotime($data['logout_date_time'])) : 'N/A';
            $attendance['logout_address'] = !empty($data['logout_address']) ? $data['logout_address'] : 'N/A';
            $attendance['late_arrivals'] = (strtotime($data['login_date_time']) > strtotime('10:00:00')) ? 'Yes' : 'No';
            $csvData[] = $attendance;
        }
        $file = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . date('Y-m-d') . '_late_workforce.csv');
        foreach ($csvData as $data) {
            fputcsv($file, $data);
        }
        fclose($file);
        exit();
    }
    public function exportAbsentAttendance() {
        $query = db()->query("SELECT GROUP_CONCAT(employee_volunteer_id) as presentIds FROM dt_duty_list WHERE DATE(login_date_time) = ?", [date('Y-m-d') ])->getRowArray();
        $presentIds = $query['presentIds'];
        if ($presentIds !== null) {
            $query = 'SELECT full_name, "Employee" as user_type FROM dt_employee_list WHERE id NOT IN (' . $presentIds . ')
            UNION 
            SELECT full_name, "Volunteer" as user_type FROM dt_volunteer_list WHERE id NOT IN (' . $presentIds . ') ';
            $absentUser = db()->query($query)->getResultArray();
            $headerLabels = ['Employee Name', 'Type'];
            $csvData = [$headerLabels];
            foreach ($absentUser as $data) {
                $attendance = [];
                $attendance['name'] = $data['full_name'];
                $attendance['user_type'] = ucfirst($data['user_type']);
                // $attendance['login_date'] = '';
                // $attendance['login_time'] = '';
                // $attendance['login_address'] = '';
                // $attendance['logout_date'] = '';
                // $attendance['logout_time'] = '';
                // $attendance['logout_address'] = '';
                // $attendance['late_arrivals'] = '';
                $csvData[] = $attendance;
            }
            $file = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=' . date('Y-m-d') . '_absent_workforce.csv');
            foreach ($csvData as $data) {
                fputcsv($file, $data);
            }
            fclose($file);
            exit();
        }
    }
}
