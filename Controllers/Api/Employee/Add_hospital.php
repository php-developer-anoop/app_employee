<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;

class Add_hospital extends BaseController
{
    protected $model;
    public function __construct()
	{
        $this->model = new Api_model();
	}

    public function index(){
        $response = [];
        $post           = checkPayload();
        $manager_id     = !empty($post['manager_id']) ? trim($post['manager_id']) : '';
        $manager_name   = !empty($post['manager_name']) ? trim($post['manager_name']) : '';
        $hospital_name  = !empty($post['hospital_name']) ? trim($post['hospital_name']) : '';
        $address        = !empty($post['address']) ? trim($post['address']) : '';
        $latitude       = !empty($post['latitude']) ? trim($post['latitude']) : '';
        $longitude      = !empty($post['longitude']) ? trim($post['longitude']) : '';
        $mobile_no      = !empty($post['mobile_no']) ? trim($post['mobile_no']) : '';
        $email_id       = !empty($post['email_id']) ? trim($post['email_id']) : '';
        $state_id       = !empty($post['state_id']) ? trim($post['state_id']) : '';
        $state_name     = !empty($post['state_name']) ? trim($post['state_name']) : '';
        $district_id    = !empty($post['district_id']) ? trim($post['district_id']) : '';
        $district_name  = !empty($post['district_name']) ? trim($post['district_name']) : '';
        $block_id       = !empty($post['block_id']) ? trim($post['block_id']) : '';
        $block_name     = !empty($post['block_name']) ? trim($post['block_name']) : '';
        $pincode        = !empty($post['pincode']) ? trim($post['pincode']) : '';
        $service_ids    = !empty($post['service_ids']) ? trim($post['service_ids']) : '';
        $category_id    = !empty($post['category_id']) ? trim($post['category_id']) : '';
        $signing_date   = !empty($post['signing_date']) ? trim($post['signing_date']) : '';
        $expiry_date    = !empty($post['expiry_date']) ? trim($post['expiry_date']) : '';
        $date_of_mou    = !empty($post['date_of_mou']) ? trim($post['date_of_mou']) : '';

        if(empty($manager_id)){
            $response['status']  = false;
            $response['message'] = 'Manager Id is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($manager_name)){
            $response['status']  = false;
            $response['message'] = 'Manager Name is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($hospital_name)){
            $response['status']  = false;
            $response['message'] = 'Hospital Name is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($address)){
            $response['status']  = false;
            $response['message'] = 'Address is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($mobile_no)){
            $response['status']  = false;
            $response['message'] = 'Mobile Number is empty!';
            echo json_encode($response);
            exit;
        }
        if (strlen($mobile_no)>10) {
            $response['status']  = false;
            $response['message'] = 'Please Enter A Valid 10 Digit Mobile Number!';
            echo json_encode($response);
            exit;
        }
        if(empty($email_id)){
            $response['status']  = false;
            $response['message'] = 'Email Id is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($state_id)){
            $response['status']  = false;
            $response['message'] = 'State Id is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($state_name)){
            $response['status']  = false;
            $response['message'] = 'State Name is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($district_id)){
            $response['status']  = false;
            $response['message'] = 'District Id is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($district_name)){
            $response['status']  = false;
            $response['message'] = 'District Name is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($block_id)){
            $response['status']  = false;
            $response['message'] = 'Block Id is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($block_name)){
            $response['status']  = false;
            $response['message'] = 'Block Name is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($pincode)){
            $response['status']  = false;
            $response['message'] = 'Pincode is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($service_ids)){
            $response['status']  = false;
            $response['message'] = 'Please Select Services!';
            echo json_encode($response);
            exit;
        }
        if(empty($category_id)){
            $response['status']  = false;
            $response['message'] = 'Category Id is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($signing_date)){
            $response['status']  = false;
            $response['message'] = 'Signing Date is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($expiry_date)){
            $response['status']  = false;
            $response['message'] = 'Expiry Date is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($date_of_mou)){
            $response['status']  = false;
            $response['message'] = 'Date Of MOU is empty!';
            echo json_encode($response);
            exit;
        }
        $checkDuplicate=$this->model->getSingle("hospital_list",'id',['profile_status'=>'Active','email_id'=>$email_id]);
        if($checkDuplicate){
            $response['status']  = false;
            $response['message'] = "Duplicate Entry!";
            echo json_encode($response);
            exit;
        }
            //Send Login Credentials on Email Id
            $password=generate_password(10);
            $enc_password= md5($password);
            sendEmail($email_id,$email_id,$password);
       
        $data=[
            'employee_id'   => $manager_id,
            'employee_name' => $manager_name,
            'hospital_name' => $hospital_name,
            'mobile_no'     => $mobile_no,
            'email_id'      => $email_id,
            'state_id'      => $state_id,
            'state_name'    => $state_name,
            'district_id'   => $district_id,
            'district_name' => $district_name,
            'block_id'      => $block_id,
            'block_name'    => $block_name,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'pincode'       => $pincode,
            'address'       => $address,
            'disease_ids'   => $service_ids,
            'category'      => $category_id,
            'signing_date'  => date('Y-m-d',strtotime($signing_date)),
            'expiry_date'   => date('Y-m-d',strtotime($expiry_date)),
            'date_of_mou'   => date('Y-m-d',strtotime($date_of_mou)),
            'enc_password'  => $enc_password,
            'add_date'      => date('Y-m-d H:i:s')
        ];
        $res=$this->model->insertRecords("hospital_list",$data);
        if($res){
            $response['status'] = true;
            $response['message'] = "Hospital Added Successfully!";
            echo json_encode($response);
            exit;
        }
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