<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Manager_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post           = checkPayload();
        $email_id     = !empty($post['email_id']) ? trim($post['email_id']) : '';
        
        if (empty($email_id)) {
            $response["status"] = false;
            $response["message"] = "Email Id is Blank";
            echo json_encode($response);
            exit();
        } 
        $where = [];
        $where['email_id !=']=$email_id;
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,full_name";
        
        $managers = $this->model->getAllData('employee_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($managers)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($managers as $key => $value) {
            $return['manager_id']    = (string)$value['id'];
            $return['manager_name']  = (string)$value['full_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    
    public function project_list() {
        $response = [];
        checkHeaders();
        
        $where = [];
        $where['status']='Active';
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,project_name";
        
        $projects = $this->model->getAllData('project_master', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($projects)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($projects as $key => $value) {
            $return['project_id']    = (string)$value['id'];
            $return['project_name']  = (string)$value['project_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
   
}
