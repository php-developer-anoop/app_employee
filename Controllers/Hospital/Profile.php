<?php
namespace App\Controllers\Hospital;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Profile extends BaseController {
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
        $session=session();
        $loginData = $session->get('hospital_login_data');
        
        $data["title"] = "View Profile";
        $data['profile']=$this->c_model->getSingle($this->table,'hospital_name,mobile_no,email_id,address',['email_id'=>$loginData['hospital_email'],'profile_status'=>'Active']);
        hospitalview('view-profile', $data);
    }

    public function change_password(){
        $data = [];
        $data["title"] = "Change Password";
        hospitalview('change-password', $data);
    }
}