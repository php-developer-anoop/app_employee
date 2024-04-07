<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Cms extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $page_type = !empty($post['page_type']) ? trim($post['page_type']) : '';
        if (empty($page_type)) {
            $response['status'] = false;
            $response['message'] = 'Page type is Empty!';
            echo json_encode($response);
            exit;
        }
        if($page_type=="privacy_policy"){
            $title='Privacy Policy For Volunteer App';
        }else if ($page_type=="about_us"){
             $title='About Us For Volunteer App';
        }else{
            $title='Terms And Conditions';
        }
        
        $cms = $this->model->getSingle('cms_list', 'title,description', ['status' => 'Active', 'page_type' => $page_type,'title'=>$title]);
        if (empty($cms)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        $returnData['title'] = (string)$cms['title'];
        $returnData['description'] = (string)$cms['description'];
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }
    public function banner(){
        $response = [];
        checkHeaders();
        $where=[];
        $where['status']='Active';
        $where['app_type']='volunteer';
        $path=base_url(UPLOADS);
        $record=$this->model->getSingle("banner_master","CONCAT('".$path."',banner) as banner",$where);
        if(empty($record)){
        $response['status'] = false;
        $response['message'] = "No Record Found!";
        echo json_encode($response);
        exit;
        }
        $response['status'] = TRUE;
        $response['banner'] = $record['banner'];
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }
}
