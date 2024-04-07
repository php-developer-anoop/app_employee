<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Program_master_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        checkHeaders();
        $where = [];
        $where = ''; 
        $date=date('Y-m-d');
        $where .= "status = 'Active'"; 
        $where .= " AND '".$date."' BETWEEN valid_from AND valid_till  ";
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,program_name,valid_from,valid_till";
        $query = "SELECT $selectKeys FROM dt_program_master WHERE $where ORDER BY $orderByKey $orderBy";
        $programs =db()->query($query)->getResultArray();
        // echo $this->model->getLastQuery();die;
        if (empty($programs)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($programs as $key => $value) {
            $return['program_id']   = (string)$value['id'];
            $return['program_name'] = (string)$value['program_name'];
            $return['valid_from']   = (string)date('d-m-Y',strtotime($value['valid_from']));
            $return['valid_till']   = (string)date('d-m-Y',strtotime($value['valid_till']));
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
