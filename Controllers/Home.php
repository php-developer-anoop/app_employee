<?php
namespace App\Controllers;
use App\Models\Common_model;
class Home extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_cms_list";
    }
    public function index() {
        return view('welcome_message');
    }
    
    public function privacy_policy_employee() {
        $data=[];
        $savedData        = $this->c_model->getSingle($this->table, 'title,description', ['status' =>'Active','title'=>'Privacy Policy For HLFPPT PMIS App']);
        $data['heading'] = !empty($savedData['title']) ? $savedData['title'] : '';
        $data['description']   = !empty($savedData['description']) ? $savedData['description'] : '';
        return view('privacy_policy',$data);
    }
    
    public function privacy_policy_volunteer() {
        $data=[];
        $savedData        = $this->c_model->getSingle($this->table, 'title,description', ['status' =>'Active','title'=>'Privacy Policy For HLFPPT PMIS Volunteer App']);
        $data['heading'] = !empty($savedData['title']) ? $savedData['title'] : '';
        $data['description']   = !empty($savedData['description']) ? $savedData['description'] : '';
        return view('privacy_policy',$data);
    }
    public function delete_account() {
        $data = [];
        $data['title'] = 'Delete Account';
        $company = db()->table('dt_websetting')->select('favicon')->get()->getRowArray();
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        return view('delete-account', $data);
    }
}
