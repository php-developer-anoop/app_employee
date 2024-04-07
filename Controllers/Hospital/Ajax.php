<?php
namespace App\Controllers\Hospital;
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
        $password = !empty($this->request->getVar("password")) ? $this->request->getVar("password") : "";
        $session = session();
        $loginData = $session->get('hospital_login_data');
        $data = $this->c_model->updateData("hospital_list", ['enc_password' => md5($password) ], ['email_id' => $loginData['hospital_email']]);
        sendEmailForgotPassword($loginData['hospital_email'], $loginData['hospital_email'], $password);
        if ($data == true) {
            echo "success";
        } else {
            echo "failed";
        }
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
}
