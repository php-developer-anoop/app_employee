<?php
namespace App\Controllers\Hospital;
use App\Controllers\BaseController;
use App\Models\Hospital_model;
class Authentication extends BaseController {
    protected $hospital_model;
    protected $session;
    public function __construct() {
        $this->session = session();
        $this->hospital_model = new Hospital_model();
    }
    public function index() {
        $data["meta_title"] = "Hospital Panel";
        echo view("hospital/login_form", $data);
    }
    public function authenticate() {
        $post = $this->request->getVar();
        $email = !empty($post["email"]) ? $post["email"] : "";
        $password = !empty($post["password"]) ? $post["password"] : "";
        if (empty($email)) {
            $this->session->setFlashdata("failed", "Failed! Email Can not be empty ");
            return redirect()->to(base_url(HOSPITALPATH . "login"));
        }
        if (empty($password)) {
            $this->session->setFlashdata("failed", "Failed! Password Can not be empty ");
            return redirect()->to(base_url(HOSPITALPATH . "login"));
        }
        $where = [];
        $select = "*";
        $where["email_id"] = $email;
        $where["enc_password"] = md5($password);
        $user = $this->hospital_model->getSingle($select, $where);
        if (empty($user)) {
            $this->session->setFlashdata("failed", "Invalid Email Or Password ");
            return redirect()->to(base_url(HOSPITALPATH . "login"));
        } elseif (!empty($user)) {
            $sess_data = [
                    "hospital_id" => $user["id"],
                    "hospital_email" => $user["email_id"],
                    "hospital_phone" => $user["mobile_no"],
                    'hospital_name' => $user['hospital_name'],
                    'last_login' => date('Y-m-d H:i:s'),
                    'login_time' => date('H:i:s'),
                    'login_time_format' => date('H:i:s A'),
                    'loggedIn' => true
                ];
            $this->hospital_model->updateRecords(['last_login' => date('Y-m-d H:i:s') ], ['id' => $user["id"]]);
            $this->session->set('hospital_login_data', $sess_data);
            return redirect()->to(HOSPITALPATH . "dashboard");
        }
    }

    public function forgot_password() {
        $data["meta_title"] = "Hospital Panel";
        echo view("hospital/forgot_password_form", $data);
    }

    public function sendNewPassword(){
        $email=!empty($this->request->getVar('email'))?$this->request->getVar('email'):"";
        if(empty($email)){
            echo "Please Enter Email";
            exit;
        }
        $valid=$this->hospital_model->getSingle('id',['email_id'=>$email]);
        if(empty($valid)){
            echo "This Email Id Is Not Registered";
            exit;
        }
        $password=generate_password(10);
        sendEmailForgotPassword($email,$email,$password);
        $this->hospital_model->updateRecords(['enc_password'=>md5($password)],['email_id'=>$email]);
        echo "success";
    }

    public function logout(){
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to(base_url(HOSPITALPATH . "login"));
    }
}
?>
