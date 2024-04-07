<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;
class Support extends BaseController {
    protected $model;
    public function __construct() {
        $this->model = new Api_model();
    }
    public function index() {
        $response = [];
        checkHeaders();
        $record=$this->model->getSingle("websetting",'*',['id'=>'1']);
        if (empty($record)) {
            $response['status'] = false;
            $response['message'] = "No Records Found";
            echo json_encode($response);
            exit;
        }
        $returnData=[];
        $returnData['company_name']=(string)$record['company_name'];
        $returnData['care_mobile_no']=(string)'+'.$record['care_mobile_no'];
        $returnData['care_whatsapp_no']=(string)'+'.$record['care_whatsapp_no'];
        $returnData['care_email_id']=(string)$record['care_email_id'];
        $returnData['map_script']=(string)$record['map_script'];
        $returnData['office_address']=(string)$record['office_address'];
        $returnData['facebook_link']=(string)$record['facebook_link'];
        $returnData['twitter_link']=(string)$record['twitter_link'];
        $returnData['youtube_link']=(string)$record['youtube_link'];
        $returnData['linkedin_link']=(string)$record['linkedin_link'];
        $returnData['instagram_link']=(string)$record['instagram_link'];
        $returnData['logo']=(string)(!empty($record['logo_jpg']))?base_url(UPLOADS).$record['logo_jpg']:"";
        $returnData['copyright']=(string)$record['copyright'];
        $returnData['favicon']=(string)(!empty($record['favicon']))?base_url(UPLOADS).$record['favicon']:"";

        $response['status'] = TRUE;
        $response['data'] = $returnData;
        $response['message'] = "Data Fetched Successfully!";
        echo json_encode($response);
        exit;
    }

}