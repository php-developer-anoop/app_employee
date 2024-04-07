<?php
namespace App\Controllers\Api\Volunteer;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Hospital_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        $post = checkPayload();
        $page_no = !empty($post["page_no"]) ? $post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? $post["per_page_limit"] : 10;
        $service_ids    = !empty($post['service_ids']) ? trim($post['service_ids']) :'';
        $category_id    = !empty($post['category_id']) ? trim($post['category_id']) : 0;
        
        if (empty($page_no)) {
            $response['status'] = false;
            $response['message'] = 'Page No. is Blank';
            echo json_encode($response);
            exit;
        } else if (empty($per_page_limit)) {
            $response['status'] = false;
            $response['message'] = 'Per Page Limit is Blank';
            echo json_encode($response);
            exit;
        }
        $where ='';
        $where .= "profile_status = 'Active'"; 
        if($category_id != 0){
            $where .= " AND category = " . $category_id;
        }
        if (!empty($service_ids)) {
            $service_ids = implode(',', array_map('intval', explode(',', $service_ids)));
            $where .= " AND disease_ids IN (" . $service_ids . ")";
        }
        
        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $keys = 'id,hospital_name,district_name,category';
        $orderByKey = 'id';
        $orderBy = 'DESC';
        
        $query = "SELECT $keys FROM dt_hospital_list WHERE $where ORDER BY $orderByKey $orderBy LIMIT $start, $limit";
        $hospitals = db()->query($query)->getResultArray();
    //    echo db()->getLastQuery();exit;
        if (empty($hospitals)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($hospitals as $key => $value) {
            $return['hospital_id'] = (string)$value['id'];
            $return['hospital_name'] = (string)$value['hospital_name'].' ('.(string)$value['district_name'].')';
            $return['category_name'] = (string) !is_null($value['category']) ? getCategoryName($value['category']) : '';
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }

    public function hospitals(){
        $response = [];
        $post = checkPayload();
        $search_hospital = !empty($post['search_hospital']) ? trim($post['search_hospital']) : '';
        $where = [];
        $where['profile_status'] = 'Active';
        if (!empty($search_hospital)){
            $where["hospital_name LIKE '%".$search_hospital."%'"]=null;
        }
        $limit = null;
        $start = null;
        $orderByKey = 'id';
        $orderBy = 'DESC';
        $selectKeys = "id,hospital_name";
        
        $hospitals = $this->model->getAllData('hospital_list', $selectKeys, $where, $limit, $start, $orderBy, $orderByKey);
       // echo $this->model->getLastQuery();die;
        if (empty($hospitals)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData = [];
        foreach ($hospitals as $key => $value) {
            $return['hospital_id'] = (string)$value['id'];
            $return['hospital_name'] = (string)$value['hospital_name'];
            array_push($returnData, $return);
        }
        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
}
