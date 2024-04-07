<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class State_list extends BaseController {
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
        $orderByKey = 'priority';
        $orderBy = 'ASC';
        $selectKeys = "id,state_name";
        $states = $this->model->getAllData('state', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($states)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($states as $key => $value) {
            $return['state_id']   = (string)$value['id'];
            $return['state_name'] = (string)$value['state_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
