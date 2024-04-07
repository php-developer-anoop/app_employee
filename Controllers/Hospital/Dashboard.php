<?php
namespace App\Controllers\Hospital;
use App\Controllers\BaseController;


class Dashboard extends BaseController
{
    public function index(){
        $session=session();
        $loginData = $session->get('hospital_login_data');
        $data=[];
        $data['hospital_name']=!empty($loginData['hospital_name'])?'('.$loginData['hospital_name'].')':"";
        $data['meta_title']='Dashboard';
        hospitalview('dashboard',$data);
    }
}
?>