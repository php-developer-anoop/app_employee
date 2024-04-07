<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Leaves extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_leaves_list";
    }
    function index() {
        $data = [];
        $data["title"] = "Leaves List";
        adminview('view-leaves', $data);
    }
    public function getRecords() {
        $post = $this->request->getVar();
        $get = $this->request->getVar();
        $limit = (int)(!empty($get["length"]) ? $get["length"] : 1);
        $start = (int)!empty($get["start"]) ? $get["start"] : 0;
        $is_count = !empty($post["is_count"]) ? $post["is_count"] : "";
        $totalRecords = !empty($get["recordstotal"]) ? $get["recordstotal"] : 0;
        $orderby = "DESC";
        $where = [];
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where["employee_volunteer_name LIKE '%" . $searchString . "%' OR user_type LIKE '%" . $searchString . "%'"] = null;
            $limit = 100;
            $start = 0;
        }
        $countData = $this->c_model->countRecords($this->table, $where, 'id');
        if ($is_count == "yes") {
            echo (int)(!empty($countData) ? sizeof($countData) : 0);
            exit();
        }
        if (!empty($get["showRecords"])) {
            $limit = $get["showRecords"];
            $orderby = "DESC";
        }
        $select = '*,DATE_FORMAT(apply_date , "%d-%m-%Y %r") AS apply_date,DATE_FORMAT(action_date , "%d-%m-%Y %r") AS action_date';
        $listData = $this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby);
        $result = [];
        if (!empty($listData)) {
            $i = $start + 1;
            foreach ($listData as $key => $value) {
                $push = [];
                $push = $value;
                $push["sr_no"] = $i;
                array_push($result, $push);
                $i++;
            }
        }
        $json_data = [];
        if (!empty($get["search"]["value"])) {
            $countItems = !empty($result) ? count($result) : 0;
            $json_data["draw"] = intval($get["draw"]);
            $json_data["recordsTotal"] = intval($countItems);
            $json_data["recordsFiltered"] = intval($countItems);
            $json_data["data"] = !empty($result) ? $result : [];
        } else {
            $json_data["draw"] = intval($get["draw"]);
            $json_data["recordsTotal"] = intval($totalRecords);
            $json_data["recordsFiltered"] = intval($totalRecords);
            $json_data["data"] = !empty($result) ? $result : [];
        }
        echo json_encode($json_data);
    }

    public function approve_reject_leave(){
        $post=$this->request->getVar();
        $leave_type=!empty($post['leave_type'])?$post['leave_type']:"";
        $id=!empty($post['id'])?$post['id']:"";
        $reason=!empty($post['reason'])?$post['reason']:"";

        $message='';
        $data=[];
        if($leave_type=="approved"){
        $data['comment']=$reason;
        $message="Leave Approved";
        }else if($leave_type=="reject"){
        $data['reject_reason']=$reason;
        $message="Leave Reject";
        }
        $data['action_date']=date('Y-m-d H:i:s');
        $data['status']=$leave_type;
        $this->c_model->updateRecords($this->table,$data,['id'=>$id]);
        $this->session->setFlashdata('success', $message);
        return redirect()->to(base_url(ADMINPATH . 'leaves-list'));
    }
}
