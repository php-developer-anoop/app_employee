<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Login_model;
class Login extends BaseController {
    protected $login_model;
    protected $session;
    public function __construct() {
        $this->session = session();
        $this->login_model = new Login_model();
    }
    public function index() {
        $data["meta_title"] = "Admin";
        echo view("admin/login_form", $data);
    }
    public function authenticate() {
        $post = $this->request->getVar();
        $email = !empty($post["email"]) ? trim($post["email"]) : "";
        $password = !empty($post["password"]) ? trim($post["password"])  : "";
        if (empty($email)) {
            $this->session->setFlashdata("failed", "Failed! Email Can not be empty ");
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        if (empty($password)) {
            $this->session->setFlashdata("failed", "Failed! Password Can not be empty ");
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        $where = [];
        $select = "*";
        $where["user_email"] = $email;
        $where["enc_password"] = md5($password);
        $user = $this->login_model->getSingle($select, $where);
        if (empty($user)) {
            $this->session->setFlashdata("failed", "Invalid Email Or Password ");
            return redirect()->to(base_url(ADMINPATH . "login"));
        } else if (!empty($user)) {
            $sess_data = [
                        "role_id"           => $user["id"],
                        "user_type"         => $user["user_type"],
                        "role_user_email"   => $user["user_email"],
                        "role_user_phone"   => $user["user_phone"],
                        'role_user_name'    => $user['user_name'],
                        'role'              => ($user['department_name']),
                        'department'        => $user['department_id'],
                        'last_login'        => date('Y-m-d H:i:s'),
                        'login_time'        => date('H:i:s'),
                        'login_time_format' => date('H:i:s A'),
                        'loggedIn'          => true
                         ];
                
            $this->login_model->updateRecords(['last_login' => date('Y-m-d H:i:s') ], ['id' => $user["id"]]);
            $this->session->set('login_data', $sess_data);
            return redirect()->to(ADMINPATH . "dashboard");
        }
    }
}
?>
