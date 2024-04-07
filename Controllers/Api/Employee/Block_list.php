<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Block_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $state_id    = !empty($post['state_id']) ? trim($post['state_id']) : '';
        $district_id = !empty($post['district_id']) ? trim($post['district_id']) : '';
        
        if (empty($state_id)) {
            $response['status'] = false;
            $response['message'] = 'State Id is Blank!';
            echo json_encode($response);
            exit;
        }
        if (empty($district_id)) {
            $response['status'] = false;
            $response['message'] = 'District Id is Blank!';
            echo json_encode($response);
            exit;
        }
        $where = [];
        $where['status'] = 'Active';
        $where['state_id'] = $state_id;
        $where['district_id'] = $district_id;
        $limit = null;
        $start = null;
        $orderByKey = 'priority';
        $orderBy = 'ASC';
        $selectKeys = "id,block_name";
        $blocks = $this->model->getAllData('block', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($blocks)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($blocks as $key => $value) {
            $return['block_id']   = (string)$value['id'];
            $return['block_name'] = (string)$value['block_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }
}
