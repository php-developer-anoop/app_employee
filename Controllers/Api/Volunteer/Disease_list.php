<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Disease_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        checkHeaders();
        $where = [];
        $where['status'] = 'Active';
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,disease_name";
        $diseases = $this->model->getAllData('disease_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($diseases)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($diseases as $key => $value) {
            $return['disease_id'] = (string)$value['id'];
            $return['disease_name'] = (string)$value['disease_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }
    
     public function service_list() {
        $response = [];
        checkHeaders();
        $where = [];
        $where['status'] = 'Active';
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,service_name";
        $diseases = $this->model->getAllData('scope_of_services_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($diseases)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($diseases as $key => $value) {
            $return['service_id']   = (string)$value['id'];
            $return['service_name'] = (string)$value['service_name'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
     public function category_list(){
        $response = [];
        checkHeaders();
        $where = [];
        $where['status'] = 'Active';
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,category_name";
        $categories = $this->model->getAllData('category_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($categories)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($categories as $key => $value) {
            $return['category_id']   = (string)$value['id'];
            $return['category_name'] = (string)$value['category_name'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
