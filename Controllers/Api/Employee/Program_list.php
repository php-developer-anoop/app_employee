<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Program_list extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {

        $response = [];
        $post = checkPayload();

        $employee_id    = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $page_no        = !empty($post["page_no"]) ? (int)$post["page_no"] : 1;
        $per_page_limit = !empty($post["per_page_limit"]) ? (int)$post["per_page_limit"] : 10;
        $start_date     = !empty($post['start_date']) ? trim($post['start_date']) : '';
        $end_date       = !empty($post['end_date']) ? trim($post['end_date']) : '';
        $status         = !empty($post['status']) ? trim($post['status']) : '';

        if (empty($employee_id)) {
            $response["status"] = false;
            $response["message"] = "Employee Id is Blank";
            echo json_encode($response);
            exit();
        }
        else if (empty($page_no)) {
            $response['status'] = false;
            $response['message'] = 'Page No. is Blank';
            echo json_encode($response);
            exit;
        }
        else if (empty($per_page_limit)) {
            $response['status'] = false;
            $response['message'] = 'Per Page Limit is Blank';
            echo json_encode($response);
            exit;
        }
        else if ( !in_array( $status, ['today','total','upcoming'])) {
            $response['status'] = false;
            $response['message'] = 'Program Status is Invalid';
            echo json_encode($response);
            exit;
        }

        $where = [];
        $where['employee_id'] = $employee_id;

        if($status == 'today' ){
            $where['status !='] = 'cancel';
            $where['DATE(program_start_date_time)'] = date('Y-m-d');
        }
        else if($status == 'upcoming' ){
            $where['status !='] = 'cancel';
            $where['DATE(program_start_date_time) >'] = date('Y-m-d');
        }  

        //DATE FILTER
        if(!empty($start_date) && !empty($end_date)){
            $where["DATE(program_start_date_time) >="] = date('Y-m-d',strtotime($start_date));
            $where["DATE(program_start_date_time) <="] = date('Y-m-d',strtotime($end_date));
        }

        $limit = $per_page_limit;
        $start = ($page_no - 1) * $per_page_limit;
        $keys  = '*';
        $program_list = $this->model->getAllData('program_list', $keys, $where, $limit, $start,'DESC','id' );

        if ( empty($program_list) ) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }

        $returnData = [];
        foreach ($program_list as $key => $value) {
        $return['id']                 = (string)$value['id'];
        $return['program_id']         = (string)$value['program_id'];
        $return['program_name']       = (string)$value['program_name'];
        $return['program_start_date'] = (string)date('d/m/Y', strtotime($value['program_start_date_time']));
        $return['program_start_time'] = (string)date("h:i A", strtotime($value['program_start_date_time']));
        $return['program_end_date']   = (string)date('d/m/Y', strtotime($value['program_end_date_time']));
        $return['program_end_time']   = (string)date("h:i A", strtotime($value['program_end_date_time']));
        $return['image']              = (string)!empty($value['image']) ? base_url(UPLOADS) . $value['image'] : "";
        $return['volunteer_id']       = (string)$value['volunteer_id'];
        $return['volunteer_name']     = (string)$value['volunteer_name'];
        $return['employee_id']        = (string)$value['employee_id'];
        $return['employee_name']      = (string)$value['employee_name'];
        $return['program_location']   = (string)$value['program_location'];
        $return['purpose']            = (string)$value['purpose']; 
        $return['status']             = (string)$value['status'];
        $return['start_button_status']= ((strtotime($value['program_start_date_time']) <= strtotime(date('Y-m-d H:i:s'))) || ($value['status'] == 'start') ) ?  true : false ;
        $return['end_button_status']  = $value['status'] == 'start' ? true : false ;
        
        array_push($returnData, $return);
        }
        $response['status']  = TRUE;
        $response['data']    = $returnData;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }
    

    public function program_detail() {

        $response = [];
        $post = checkPayload();

        $id = !empty($post["id"]) ? trim($post["id"]) : "";

        if (empty($id)) {
            $response['status'] = false;
            $response['message'] = "Id Is Blank";
            echo json_encode($response);
            exit;
        }

        $data = $this->model->getSingle("program_list", '*', ['status' => 'pending', 'id' => $id]);

        if (empty($data)) {
            $response['status'] = false;
            $response['message'] = "No Record Found";
            echo json_encode($response);
            exit;
        }

        $return = [];
        $return['id']                 = (string)$data['id'];
        $return['program_id']         = (string)$data['program_id'];
        $return['program_name']       = (string)$data['program_name'];
        $return['program_start_date'] = (string)date('d/m/Y', strtotime($data['program_start_date_time']));
        $return['program_start_time'] = (string)date("h:i A", strtotime($data['program_start_date_time']));
        $return['program_end_date']   = (string)date('d/m/Y', strtotime($data['program_end_date_time']));
        $return['program_end_time']   = (string)date("h:i A", strtotime($data['program_end_date_time']));
        $return['volunteer_id']       = (string)$data['volunteer_id'];
        $return['volunteer_name']     = (string)$data['volunteer_name'];
        $return['employee_id']        = (string)$data['employee_id'];
        $return['employee_name']      = (string)$data['employee_name'];
        $return['program_location']   = (string)$data['program_location'];
        $return['purpose']            = (string)$data['purpose'];
        $return['status']             = (string)$data['status'];

        $response['status'] = TRUE;
        $response['data'] = $return;
        $response['message'] = "API Accessed Successfully!";
        echo json_encode($response);
        exit;
    }


    public function change_program_status() {

        $response = [];
        $post = checkPayload();
        
        $id = !empty($post['id']) ? $post['id'] : "";
        $status = !empty($post['status']) ? $post['status'] : "";

        if (empty($id)) {
            $response['status'] = false;
            $response['message'] = "Program Id is Blank!";
            echo json_encode($response);
            exit;
        }
        else if (empty($status)) {
            $response['status'] = false;
            $response['message'] = "Status is Blank!";
            echo json_encode($response);
            exit;
        }

        $where = [];
        $where['id'] = $id;

        $record = $this->model->getSingle("program_list", 'status', $where ); 

        if (empty($record)) {
            $response['status'] = false;
            $response['message'] = "NO Record Found!";
            echo json_encode($response);
            exit;
        } 
        else if (!empty($record) && ( $status == $record['status'] )) {
            $response['status'] = false;
            $response['message'] = "Already Started!";
            echo json_encode($response);
            exit;
        } 

        $changeStatus = '';

        if ($record['status'] == 'pending') {
            $changeStatus = 'start';
        }
        else if ($record['status'] == 'start') {
            $changeStatus = 'end';
        }
        
        if (!empty($changeStatus)) {
        $updateData = ['status' => $changeStatus];
        $this->model->updateRecords("program_list", $updateData, $where);

        $response['status'] = true;
        $response['button'] = $status == "start" ? "Started" : "Ended";
        $response['message'] = "Status Changed To " . $response['button'];
        echo json_encode($response);
        exit;
        }

    }
}