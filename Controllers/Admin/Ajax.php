<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Ajax extends BaseController {
    protected $c_model;
    protected $session;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
    }
    public function index() {
        $id = !empty($this->request->getVar("id")) ? $this->request->getVar("id") : "";
        $table = !empty($this->request->getVar("table")) ? $this->request->getVar("table") : "";
        $records = $this->c_model->getSingle($table, null, ['id' => $id]);
        if (!empty($records)) {
            $result = $this->c_model->deleteRecords($table, ['id' => $id]);
        }
    }
    public function getDistrictsFromAjax() {
        $state_id = !empty($this->request->getVar("state_id")) ? explode(',', $this->request->getVar("state_id")) : "";
        $districts = $this->c_model->getAllData('district', 'id,district_name', ['state_id' => $state_id[0]]);
        $distvalue = !empty($this->request->getVar("district")) ? $this->request->getVar("district") : "";
        $html = '<option value="">Select District</option>';
        if (!empty($districts)) {
            foreach ($districts as $key => $value) {
                if ($value['id'] . ', ' . $value['district_name'] == $distvalue) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $html.= '<option ' . $selected . ' value="' . $value['id'] . ',' . $value['district_name'] . '" >' . $value['district_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function getBlockFromAjax() {
        $district_id = !empty($this->request->getVar("district_id")) ? explode(',', $this->request->getVar("district_id")) : "";
        $blocks = $this->c_model->getAllData('block', 'id,block_name', ['district_id' => $district_id[0]]);
        //echo $this->c_model->getLastQuery();exit;
        $blockvalue = !empty($this->request->getVar("block")) ? $this->request->getVar("block") : "";
        $html = '<option value="">Select Block</option>';
        if (!empty($blocks)) {
            foreach ($blocks as $key => $value) {
                if ($value['id'] . ', ' . $value['block_name'] == $blockvalue) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $html.= '<option ' . $selected . ' value="' . $value['id'] . ',' . $value['block_name'] . '">' . $value['block_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function changeStatus() {
        $id = !empty($this->request->getVar("id")) ? $this->request->getVar("id") : "";
        $table = !empty($this->request->getVar("table")) ? $this->request->getVar("table") : "";
        $records = $this->c_model->getSingle($table, 'status', ['id' => $id]);
        if (!empty($records)) {
            $current_status = $records['status'];
            if ($current_status == "Active") {
                $data['status'] = "Inactive";
            } else {
                $data['status'] = "Active";
            }
            $this->c_model->updateRecords($table, $data, ['id' => $id]);
            echo $data['status'];
        }
    }
    public function changeProfileStatus() {
        $id = !empty($this->request->getVar("id")) ? $this->request->getVar("id") : "";
        $table = !empty($this->request->getVar("table")) ? $this->request->getVar("table") : "";
        $records = $this->c_model->getSingle($table, 'profile_status', ['id' => $id]);
        if (!empty($records)) {
            $current_status = $records['profile_status'];
            if ($current_status == "Active") {
                $data['profile_status'] = "Inactive";
            } else {
                $data['profile_status'] = "Active";
            }
            $this->c_model->updateRecords($table, $data, ['id' => $id]);
        }
    }
    public function changePatientStatus() {
        $id = !empty($this->request->getVar("id")) ? $this->request->getVar("id") : "";
        $table = !empty($this->request->getVar("table")) ? $this->request->getVar("table") : "";
        $records = $this->c_model->getSingle($table, 'status', ['id' => $id]);
        if (!empty($records)) {
            $current_status = $records['status'];
            if ($current_status == "add") {
                $data['status'] = "convert";
            } else {
                $data['status'] = "add";
            }
            $this->c_model->updateRecords($table, $data, ['id' => $id]);
        }
    }
    public function getHospitalFromAjax() {
        $block = !empty($this->request->getVar("block_id")) ? explode(',', $this->request->getVar("block_id")) : "";
        $district = !empty($this->request->getVar("district_id")) ? explode(',', $this->request->getVar("district_id")) : "";
        $state = !empty($this->request->getVar("state_id")) ? explode(',', $this->request->getVar("state_id")) : "";
        $employee_id = !empty($this->request->getVar("employee_id")) ? ($this->request->getVar("employee_id")) : "";
        $where = [];
        if (!empty($block)) {
            $where['block_id'] = $block[0];
        }
        if (!empty($district)) {
            $where['district_id'] = $district[0];
        }
        $where['state_id'] = $state[0]??'';
        $hospitals = $this->c_model->getAllData('hospital_list', 'id,hospital_name', $where);
        $emp_details = $this->c_model->getSingle('employee_list', 'hospital_ids', ['id' => $employee_id, 'profile_status' => 'Active']);
        $html = '';
        $selected = '';
        if (!empty($hospitals)) {
            foreach ($hospitals as $key => $value) {
                if (!empty($emp_details['hospital_ids'])) {
                    $selected = in_array($value['id'], explode(',', $emp_details['hospital_ids'])) ? "selected" : "";
                }
                $html.= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['hospital_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function checkDuplicateHospital() {
        $email_id = !empty($this->request->getVar('email_id')) ? $this->request->getVar('email_id') : "";
        $where = [];
        $where['profile_status'] = 'Active';
        $where['email_id'] = $email_id;
        $data = $this->c_model->getSingle("hospital_list", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function checkDuplicateEmployee() {
        $email_id = !empty($this->request->getVar('email_id')) ? $this->request->getVar('email_id') : "";
        $where = [];
        $where['profile_status'] = 'Active';
        $where['email_id'] = $email_id;
        $data = $this->c_model->getSingle("employee_list", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function checkDuplicateVolunteer() {
        $email_id = !empty($this->request->getVar('email_id')) ? $this->request->getVar('email_id') : "";
        $where = [];
        $where['profile_status'] = 'Active';
        $where['email_id'] = $email_id;
        $data = $this->c_model->getSingle("volunteer_list", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function checkDuplicatePatient() {
        $mobile_no = !empty($this->request->getVar('mobile_no')) ? $this->request->getVar('mobile_no') : "";
        $where = [];
        // $where['profile_status']='Active';
        $where['mobile_no'] = $mobile_no;
        $data = $this->c_model->getSingle("patient_list", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function getVolunteerFromAjax() {
        $employee_id = !empty($this->request->getVar("employee_id")) ? explode(',', $this->request->getVar("employee_id")) : "";
        $volunteers = $this->c_model->getAllData('volunteer_list', 'id,full_name', ['employee_id' => $employee_id[0]]);
        $volunteervalue = !empty($this->request->getVar("volunteer")) ? $this->request->getVar("volunteer") : "";
        $html = '<option value="">Select volunteer</option>';
        if (!empty($volunteers)) {
            foreach ($volunteers as $key => $value) {
                if ($value['id'] . ', ' . $value['full_name'] == $volunteervalue) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $html.= '<option ' . $selected . ' value="' . $value['id'] . ',' . $value['full_name'] . '">' . $value['full_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function getSlug() {
        $keyword = $this->request->getVar("keyword");
        if (empty($keyword)) {
            return '';
        }
        $slug = validate_slug($keyword);
        return $slug;
    }
    public function getCount() {
        $type = $this->request->getVar('type') ??"";
        $table = $this->request->getVar('table') ??"";
        $where = [];
        if (in_array($table, ['employee_list', 'volunteer_list', 'hospital_list'])) {
            $where = ['profile_status' => 'Active'];
        } else if (in_array($table, ['banner_master', 'block', 'district', 'state', 'program_master', 'cms_list', 'task_list', 'disease_list', 'notification_master', 'reason_master', 'menus', 'department'])) {
            $where = ['status' => 'Active'];
        }
        $count = count_data('id', $table, $where);
        echo $count;
    }
    public function checkDuplicateUser() {
        $email_id = !empty($this->request->getVar('email_id')) ? $this->request->getVar('email_id') : "";
        $where = [];
        $where['status'] = 'Active';
        $where['user_email'] = $email_id;
        $data = $this->c_model->getSingle("role_users", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function changePassword() {
        $password = !empty($this->request->getVar("password")) ? $this->request->getVar("password") : "";
        $session = session();
        $loginData = $session->get('login_data');
        $data = $this->c_model->updateData("role_users", ['enc_password' => md5($password) ], ['user_email' => $loginData['role_user_email']]);
        sendEmailForgotPassword($loginData['role_user_email'], $loginData['role_user_email'], $password);
        if ($data == true) {
            echo "success";
        } else {
            echo "failed";
        }
    }
    public function getHospitalName() {
        $val = $this->request->getVar("val");
        $query = "SELECT id, hospital_name FROM dt_hospital_list WHERE hospital_name LIKE ? AND profile_status = 'Active'";
        $hospitals = db()->query($query, ["%" . $val . "%"])->getResultArray();
        if (empty($hospitals)) {
            echo "No Record Found";
            exit;
        }
        foreach ($hospitals as $key => $value) {
            echo "<li value=" . $value['id'] . ">" . $value['hospital_name'] . "</li>";
        }
    }
    public function getEmployeeName() {
        $val = $this->request->getVar("val");
        $query = "SELECT id, full_name FROM dt_employee_list WHERE full_name LIKE ? AND profile_status = 'Active'";
        $employees = db()->query($query, ["%" . $val . "%"])->getResultArray();
        if (empty($employees)) {
            echo "No Record Found";
            exit;
        }
        foreach ($employees as $key => $value) {
            echo "<li value=" . $value['id'] . ">" . $value['full_name'] . "</li>";
        }
    }
    public function assignHospital() {
        $post = $this->request->getVar();
        $employee_id = !empty($post['employee_id']) ? $post['employee_id'] : "";
        $hospitals = !empty($post['hospitals']) ? implode(",", $post['hospitals']) : "";
        $data = $this->c_model->updateData("employee_list", ['hospital_ids' => $hospitals], ['id' => $employee_id]);
        $output = [];
        if ($data == true) {
            $output['status'] = true;
            $output['message'] = "Hospital Assigned Successfully";
            $output['id'] = $employee_id;
        } else {
            $output['status'] = false;
            $output['message'] = "Something Went Wrong";
        }
        echo json_encode($output);
    }
    public function getDistrictsForHospital() {
        $state_id = !empty($this->request->getVar("state_id")) ? $this->request->getVar("state_id") : "";
        $districts = $this->c_model->getAllData('district', 'id,district_name', ['state_id' => $state_id]);
        $distvalue = !empty($this->request->getVar("district")) ? $this->request->getVar("district") : "";
        $html = '<option value="">Select District</option>';
        if (!empty($districts)) {
            foreach ($districts as $key => $value) {
                if ($value['id'] == $distvalue) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $html.= '<option ' . $selected . ' value="' . $value['id'] . '" >' . $value['district_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function getBlockForHospital() {
        $district_id = !empty($this->request->getVar("district_id")) ? $this->request->getVar("district_id") : "";
        $blocks = $this->c_model->getAllData('block', 'id,block_name', ['district_id' => $district_id]);
        //echo $this->c_model->getLastQuery();exit;
        $blockvalue = !empty($this->request->getVar("block")) ? $this->request->getVar("block") : "";
        $html = '<option value="">Select Block</option>';
        if (!empty($blocks)) {
            foreach ($blocks as $key => $value) {
                if ($value['id'] == $blockvalue) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $html.= '<option ' . $selected . ' value="' . $value['id'] . '">' . $value['block_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function getUsers() {
        $val = !empty($this->request->getVar("val")) ? $this->request->getVar("val") : "";
        if ($val == "employee") {
            $users = $this->c_model->getAllData('employee_list', 'id,full_name', ['profile_status' => 'Active']);
        } else {
            $users = $this->c_model->getAllData('volunteer_list', 'id,full_name', ['profile_status' => 'Active']);
        }
        $user_id = !empty($this->request->getVar("user_id")) ? $this->request->getVar("user_id") : "";
        $html = '<option value="">Select User</option>';
        if (!empty($users)) {
            foreach ($users as $key => $value) {
                if ($value['id'] == $user_id) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $html.= '<option ' . $selected . ' value="' . $value['id'] . '" >' . $value['full_name'] . '</option>';
            }
        }
        echo $html;
    }
    public function remove_account() {
        $email = !empty($this->request->getVar('email')) ? $this->request->getVar('email') : '';
        $user_type = !empty($this->request->getVar('user_type')) ? $this->request->getVar('user_type') : '';
        $response = [];
        if (empty($user_type)) {
            $response['status'] = false;
            $response['message'] = "Please Select User Type";
            echo json_encode($response);
            exit;
        }
        if (empty($email)) {
            $response['status'] = false;
            $response['message'] = "Please Enter Email";
            echo json_encode($response);
            exit;
        }
        $table = '';
        if ($user_type == "volunteer") {
            $table = 'volunteer_list';
        } else {
            $table = 'employee_list';
        }
        $check_user = $this->c_model->getSingle($table, 'id', ['email_id' => $email]);
        if (empty($check_user)) {
            $response['status'] = false;
            $response['message'] = "No Record Found";
            echo json_encode($response);
            exit;
        }
        $del = $this->c_model->deleteRecords($table, ['email_id' => $email]);
        if ($del) {
            $response['status'] = true;
            $response['message'] = "Account Deleted Successfully";
            echo json_encode($response);
            exit;
        }
    }
    public function getTotalWorkForce() {
        $query = db()->query("SELECT SUM(total_count) AS total_active_count FROM (
                    SELECT count(id) AS total_count FROM dt_employee_list WHERE profile_status='Active'
                    UNION
                    SELECT count(id) FROM dt_volunteer_list WHERE profile_status='Active' ) AS counts")->getRowArray();
        if (!empty($query['total_active_count'])) {
            echo $query['total_active_count'];
        }
    }
    public function getPresentWorkForce() {
        $query = db()->query("SELECT COUNT(DISTINCT employee_volunteer_id ) as total FROM dt_duty_list WHERE DATE(login_date_time) = ?", array(date('Y-m-d')))->getRowArray();
        if (!empty($query['total'])) {
            echo $query['total'];
        }
    }
    public function getLateWorkForce() {
        $query = db()->query("SELECT COUNT(DISTINCT employee_volunteer_id) as total FROM dt_duty_list WHERE DATE(login_date_time) = '" . date('Y-m-d') . "' AND TIME(login_date_time) > ?", array(date("10:00:00")))->getRowArray();
        if (!empty($query['total'])) {
            echo $query['total'];
        }
    }
    public function getAbsentWorkForce() {
        $activeCountQuery = db()->query("
        SELECT SUM(total_count) AS total_active_count FROM (
            SELECT COUNT(id) AS total_count FROM dt_employee_list WHERE profile_status='Active'
            UNION ALL
            SELECT COUNT(id) FROM dt_volunteer_list WHERE profile_status='Active'
        ) AS counts")->getRowArray();
        $totalActiveCount = !empty($activeCountQuery['total_active_count']) ? $activeCountQuery['total_active_count'] : 0;
        $presentCountQuery = db()->query("
        SELECT COUNT(DISTINCT employee_volunteer_id) AS total FROM dt_duty_list WHERE DATE(login_date_time) = ?", array(date('Y-m-d')))->getRowArray();
        $totalPresentCount = !empty($presentCountQuery['total']) ? $presentCountQuery['total'] : 0;
        echo $totalActiveCount - $totalPresentCount;
    }
}
