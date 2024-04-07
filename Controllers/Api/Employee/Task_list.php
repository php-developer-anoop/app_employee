<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Task_list extends BaseController {
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
        $selectKeys = "id,task_name";
        $tasks = $this->model->getAllData('task_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
        if (empty($tasks)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($tasks as $key => $value) {
            $return['task_id']   = (string)$value['id'];
            $return['task_name'] = (string)$value['task_name'];
            array_push($returnData, $return);
        }
        $response['status'] = true;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
