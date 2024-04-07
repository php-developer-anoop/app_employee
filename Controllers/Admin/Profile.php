<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Profile extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
    }
    function index() {
        $data = [];
        $data["title"] = "Change Password";
        adminview('change-password', $data);
    }

}