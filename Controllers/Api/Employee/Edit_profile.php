<?php
namespace App\Controllers\Api\Employee;
use App\Controllers\BaseController;
use App\Models\Api_model;

class Edit_profile extends BaseController
{
    protected $model;
    public function __construct()
	{
        $this->model = new Api_model();
	}

    public function index(){
        $response = [];
        $post             = checkPayload();
        $employee_id      = !empty($post['employee_id']) ? trim($post['employee_id']) : '';
        $employee_name    = !empty($post['employee_name']) ? trim($post['employee_name']) : '';
        $date_of_joining  = !empty($post['date_of_joining']) ? trim($post['date_of_joining']) : '';
        $project_duration = !empty($post['project_duration']) ? trim($post['project_duration']) : '';
        $latitude         = !empty($post['latitude']) ? trim($post['latitude']) : '';
        $longitude        = !empty($post['longitude']) ? trim($post['longitude']) : '';
        $mobile_no        = !empty($post['mobile_no']) ? trim($post['mobile_no']) : '';
        $email_id         = !empty($post['email_id']) ? trim($post['email_id']) : '';
        $state_id         = !empty($post['state_id']) ? trim($post['state_id']) : '';
        $state_name       = !empty($post['state_name']) ? trim($post['state_name']) : '';
        $district_id      = !empty($post['district_id']) ? trim($post['district_id']) : '';
        $district_name    = !empty($post['district_name']) ? trim($post['district_name']) : '';
        $designation      = !empty($post['designation']) ? trim($post['designation']) : '';
        $manager_id       = !empty($post['manager_id']) ? trim($post['manager_id']) : '';
        $manager_name     = !empty($post['manager_name']) ? trim($post['manager_name']) : '';
        $project_id       = !empty($post['project_id']) ? trim($post['project_id']) : '';
        $project_name     = !empty($post['project_name']) ? trim($post['project_name']) : '';
        $payroll_type     = !empty($post['payroll_type']) ? trim($post['payroll_type']) : '';

        if(empty($employee_id)){
            $response['status'] = false;
            $response['message'] = 'Employee Id is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($employee_name)){
            $response['status'] = false;
            $response['message'] = 'Employee Name is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($date_of_joining)){
            $response['status'] = false;
            $response['message'] = 'Date Of Joining is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($email_id)){
            $response['status'] = false;
            $response['message'] = 'Email Id is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($project_duration)){
            $response['status'] = false;
            $response['message'] = 'Project Duration is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($mobile_no)){
            $response['status'] = false;
            $response['message'] = 'Mobile Number is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($state_id)){
            $response['status'] = false;
            $response['message'] = 'State Id is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($state_name)){
            $response['status'] = false;
            $response['message'] = 'State Name is empty!';
            echo json_encode($response);
            exit;
        }
        if(empty($district_id)){
            $response['status'] = false;
            $response['message'] = 'District Id is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($district_name)){
            $response['status'] = false;
            $response['message'] = 'District Name is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($designation)){
            $response['status'] = false;
            $response['message'] = 'Designation is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($project_id)){
            $response['status'] = false;
            $response['message'] = 'Project Id is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($project_name)){
            $response['status'] = false;
            $response['message'] = 'Project Name is empty!';
            echo json_encode($response);
            exit;
        }
        else if(empty($payroll_type)){
            $response['status'] = false;
            $response['message'] = 'Project Name is empty!';
            echo json_encode($response);
            exit;
        }

        $data=[
            'full_name'         => trim($employee_name),
            'date_of_joining'   => date('Y-m-d',strtotime(trim($date_of_joining))),
            'mobile_no'         => trim($mobile_no),
            'email_id'          => trim($email_id),
            'state_id'          => trim($state_id),
            'state_name'        => trim($state_name),
            'district_id'       => trim($district_id),
            'district_name'     => trim($district_name),
            'project_id'        => trim($project_id),
            'project_name'      => trim($project_name),
            'project_duration'  => trim($project_duration),
            'manager_id'        => trim($manager_id),
            'manager_name'      => trim($manager_name),
            'latitude'          => trim($latitude),
            'longitude'         => trim($longitude),
            'payroll_type'      => trim($payroll_type),
            'designation'       => trim($designation),
            'update_date'       => trim(date('Y-m-d H:i:s'))
        ];
         $this->model->updateRecords("employee_list",$data,['id'=>$employee_id]);
        
        $response['status'] = TRUE;
        $response['message'] = "Profile Updated Successfully!";
        echo json_encode($response);
        exit;
       
    }
}