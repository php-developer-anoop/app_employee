<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class District_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $state_id = !empty($post['state_id']) ? trim($post['state_id']) : '';
        if (empty($state_id)) {
            $response['status'] = false;
            $response['message'] = 'State Id is Blank!';
            echo json_encode($response);
            exit;
        }
        $where = [];
        $where['status'] = 'Active';
        $where['state_id'] = $state_id;
        $limit = null;
        $start = null;
        $orderByKey = 'priority';
        $orderBy = 'ASC';
        $selectKeys = "id,district_name";
        $districts = $this->model->getAllData('district', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($districts)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($districts as $key => $value) {
            $return['district_id']   = (string)$value['id'];
            $return['district_name'] = (string)$value['district_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
