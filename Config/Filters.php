<?php

namespace Config;

use App\Filters\HospitalAuth;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\Auth;
class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, string>
     * @phpstan-var array<string, class-string>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => Auth::class,
        'hospitalauth'  => HospitalAuth::class
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, array<string>>
     * @phpstan-var array<string, list<string>>|array<string, array<string, array<string, string>>>
     */
    public array $globals = [
    'before' => [
      'csrf' => ['except' => [
        //Admin Filters
        'admin/authenticate','admin/state-data','admin/project-data','admin/block-data','admin/district-data','admin/cms-data','admin/hospital-data','admin/employee-data','admin/volunteer-data','admin/task-data','admin/notification-data','admin/push-notification','admin/leaves-data','admin/patient-data',
        'admin/converted-patient-data','admin/program-data','admin/reason-data','admin/disease-data','admin/deleteRecords','admin/changeStatus','admin/changeProfileStatus',
        'admin/getDistrictsFromAjax','admin/getBlockFromAjax','admin/getHospitalFromAjax','admin/checkDuplicateHospital','admin/checkDuplicateEmployee',
        'admin/changePatientStatus','admin/getVolunteerFromAjax','admin/approve_reject_leave','admin/banner-data','admin/query-data','admin/menu-data',
        'admin/getSlug','admin/assign_menus','admin/getCount','admin/role-user-data','admin/checkDuplicateUser','admin/checkDuplicateVolunteer',
        'admin/getHospitalName','admin/employee-hospital-data','admin/service-data',
        
        //Employee Api Filters
        'api/employee/login','api/employee/autologin','api/employee/logout','api/employee/state_list','api/employee/district_list','api/employee/block_list'
        ,'api/employee/edit_profile','api/employee/cms_page','api/employee/hospital_list','api/employee/volunteer_list','api/employee/add_hospital',
        'api/employee/update_profile_image','api/employee/task_list','api/employee/add_volunteer_task','api/employee/volunteer_task_list',
        'api/employee/volunteer_today_task_list','api/employee/disease_list','api/employee/add_patient','api/employee/upload_patient_image',
        'api/employee/program_master_list','api/employee/patient_list','api/employee/add_program','api/employee/program_list',
        'api/employee/task_detail','api/employee/program_detail','api/employee/change_program_status','api/employee/category_list',
        'api/employee/reason_list','api/employee/apply_leave','api/employee/leave_list','api/employee/duty_on','api/employee/duty_off','api/employee/attendance_list'
        ,'api/employee/patient_detail','api/employee/notification_list','api/employee/delete_notification','api/employee/case_history_list',
        'api/employee/forgot_password','api/employee/write_to_us','api/employee/banner','api/employee/support','api/employee/hospitals',
        'api/employee/upload_program_image','api/employee/case_history_detail','api/employee/my_team','api/employee/manager_list',
        'api/employee/project_list','api/employee/volunteer_approval_list','api/employee/employee_approval_list','api/employee/approve_reject_leave',
        'api/employee/service_list',
        //Volunteer Api Filters
        'api/volunteer/login','api/volunteer/forgot_password','api/volunteer/autologin','api/volunteer/logout','api/volunteer/add_patient',
        'api/volunteer/upload_patient_image','api/volunteer/today_registered_patients','api/volunteer/total_registered_patients','api/volunteer/patient_detail',
        'api/volunteer/case_history_list','api/volunteer/upcoming_programs','api/volunteer/notification_list','api/volunteer/delete_notification',
        'api/volunteer/hospital_list','api/volunteer/write_to_us','api/volunteer/state_list','api/volunteer/district_list','api/volunteer/block_list',
        'api/volunteer/cms_page','api/volunteer/edit_profile','api/volunteer/banner','api/volunteer/update_profile_image','api/volunteer/support',
        'api/volunteer/disease_list','api/volunteer/change_program_status','api/volunteer/duty_on','api/volunteer/duty_off','api/volunteer/attendance_list'
        ,'api/volunteer/hospitals','api/volunteer/task_list','api/volunteer/completed_programs','api/volunteer/case_history_detail',
        'api/volunteer/apply_leave','api/volunteer/leave_list','api/volunteer/reason_list','api/volunteer/service_list','api/volunteer/category_list',

        //Hospital Panel Filters
        'hospital/authenticate','hospital/sendNewPassword','hospital/case-history-data',
        ]],
    ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [];
}
