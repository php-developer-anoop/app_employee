<?php
use CodeIgniter\Router\RouteCollection;
$routes->get('/', 'Home::index');
$routes->get("privacy-policy-employee", "Home::privacy_policy_employee", ["namespace" => "App\Controllers"]);
$routes->get("privacy-policy-volunteer", "Home::privacy_policy_volunteer", ["namespace" => "App\Controllers"]);
$routes->get("delete-account", "Home::delete_account", ["namespace" => "App\Controllers"]);

$routes->get(ADMINPATH . "login", "Login::index", ["namespace" => "App\Controllers\Admin"]);
$routes->get(ADMINPATH . "logout", "Logout::index", ["namespace" => "App\Controllers\Admin"]);
$routes->get(ADMINPATH . "dashboard", "Dashboard::index", ["filter" => "auth", "namespace" => "App\Controllers\Admin"]);
$routes->match(["get", "post"], ADMINPATH . "authenticate", "Login::authenticate", ["namespace" => "App\Controllers\Admin"]);
$routes->group("admin", ["filter" => 'auth', "namespace" => "App\Controllers\Admin"], function ($routes) {

    $routes->match(["get", "post"], "deleteRecords", "Ajax::index");
    $routes->match(["get", "post"], "getDistrictsFromAjax", "Ajax::getDistrictsFromAjax");
    $routes->match(["get", "post"], "getHospitalFromAjax", "Ajax::getHospitalFromAjax");
    $routes->match(["get", "post"], "getBlockFromAjax", "Ajax::getBlockFromAjax");
    $routes->match(["get", "post"], "getVolunteerFromAjax", "Ajax::getVolunteerFromAjax");
    $routes->match(["get", "post"], "changeStatus", "Ajax::changeStatus");
    $routes->match(["get", "post"], "changeProfileStatus", "Ajax::changeProfileStatus");
    $routes->match(["get", "post"], "changePatientStatus","Ajax::changePatientStatus");
    $routes->match(["get", "post"], "checkDuplicateHospital", "Ajax::checkDuplicateHospital");
    $routes->match(["get", "post"], "checkDuplicateEmployee", "Ajax::checkDuplicateEmployee");
    $routes->match(["get", "post"], "checkDuplicateVolunteer", "Ajax::checkDuplicateVolunteer");
    $routes->match(["get", "post"], "checkDuplicatePatient", "Ajax::checkDuplicatePatient");
    $routes->match(["get", "post"], "getSlug", "Ajax::getSlug");
    $routes->match(["get", "post"], "getCount", "Ajax::getCount");
    $routes->match(["get", "post"], "checkDuplicateUser", "Ajax::checkDuplicateUser");
    $routes->match(["get", "post"], "changePassword", "Ajax::changePassword");
    $routes->match(["get", "post"], "getHospitalName", "Ajax::getHospitalName");
    $routes->match(["get", "post"], "getEmployeeName", "Ajax::getEmployeeName");
    $routes->match(["get", "post"], "assignHospital", "Ajax::assignHospital");
    $routes->match(["get", "post"], "getDistrictsForHospital", "Ajax::getDistrictsForHospital");
    $routes->match(["get", "post"], "getBlockForHospital", "Ajax::getBlockForHospital");
    $routes->match(["get", "post"], "getUsers", "Ajax::getUsers");
    $routes->match(["get", "post"], "getTotalWorkForce", "Ajax::getTotalWorkForce");
    $routes->match(["get", "post"], "getPresentWorkForce", "Ajax::getPresentWorkForce");
    $routes->match(["get", "post"], "getLateWorkForce", "Ajax::getLateWorkForce");
    $routes->match(["get", "post"], "getAbsentWorkForce", "Ajax::getAbsentWorkForce");
    
    // Web Setting
    $routes->match(["get", "post"], "save-setting", "Websetting::save_setting");
    $routes->match(["get", "post"], "web-setting", "Websetting::index");
    // Delete Account
    $routes->match(["get", "post"], "remove-account", "Ajax::remove_account");
    //Attendance Dashboard
    $routes->match(["get", "post"], "attendance-dashboard", "Dashboard::attendance_dashboard");
    $routes->match(["get", "post"], "last-present-records", "Dashboard::getRecords");
    //All Attendance
    $routes->match(["get", "post"], "attendance", "Attendance_list::index");
    $routes->match(["get", "post"], "exportTotalAttendance", "Attendance_list::exportTotalAttendance");
    $routes->match(["get", "post"], "exportPresentAttendance", "Attendance_list::exportPresentAttendance");
    $routes->match(["get", "post"], "exportLateAttendance", "Attendance_list::exportLateAttendance");
    $routes->match(["get", "post"], "exportAbsentAttendance", "Attendance_list::exportAbsentAttendance");
    // State Master
    $routes->match(["get", "post"], "save-state", "State::save_state");
    $routes->match(["get", "post"], "state-list", "State::index");
    $routes->match(["get", "post"], "add-state", "State::add_state");
    $routes->match(["get", "post"], "state-data", "State::getRecords");
    // Project Master
    $routes->match(["get", "post"], "save-project", "Project::save_project");
    $routes->match(["get", "post"], "project-list", "Project::index");
    $routes->match(["get", "post"], "add-project", "Project::add_project");
    $routes->match(["get", "post"], "project-data", "Project::getRecords");
    // District Master
    $routes->match(["get", "post"], "save-district", "District::save_district");
    $routes->match(["get", "post"], "district-list", "District::index");
    $routes->match(["get", "post"], "add-district", "District::add_district");
    $routes->match(["get", "post"], "district-data", "District::getRecords");
    // Block Master
    $routes->match(["get", "post"], "save-block", "Block::save_block");
    $routes->match(["get", "post"], "block-list", "Block::index");
    $routes->match(["get", "post"], "add-block", "Block::add_block");
    $routes->match(["get", "post"], "block-data", "Block::getRecords");
    // Hospital Master
    $routes->match(["get", "post"], "save-hospital", "Hospital::save_hospital");
    $routes->match(["get", "post"], "hospital-list", "Hospital::index");
    $routes->match(["get", "post"], "add-hospital", "Hospital::add_hospital");
    $routes->match(["get", "post"], "hospital-data", "Hospital::getRecords");
    // Employee Master
    $routes->match(["get", "post"], "save-employee", "Employee::save_employee");
    $routes->match(["get", "post"], "employee-list", "Employee::index");
    $routes->match(["get", "post"], "add-employee", "Employee::add_employee");
    $routes->match(["get", "post"], "employee-data", "Employee::getRecords");
    $routes->match(["get", "post"], "assign-hospital", "Employee::assign_hospital");
    $routes->match(["get", "post"], "employee-hospitals-list", "Employee::employee_hospitals_list");
    $routes->match(["get", "post"], "employee-hospital-data", "Employee::getEmpHosRecords");
    // Patient Master
    $routes->match(["get", "post"], "save-volunteer", "Volunteer::save_volunteer");
    $routes->match(["get", "post"], "volunteer-list", "Volunteer::index");
    $routes->match(["get", "post"], "add-volunteer", "Volunteer::add_volunteer");
    $routes->match(["get", "post"], "volunteer-data", "Volunteer::getRecords");
    // Program Master
    $routes->match(["get", "post"], "save-program", "Program::save_program");
    $routes->match(["get", "post"], "program-list", "Program::index");
    $routes->match(["get", "post"], "add-program", "Program::add_program");
    $routes->match(["get", "post"], "program-data", "Program::getRecords");
    // Category Master
    $routes->match(["get", "post"], "save-category", "Category::save_category");
    $routes->match(["get", "post"], "category-list", "Category::index");
    $routes->match(["get", "post"], "add-category", "Category::add_category");
    $routes->match(["get", "post"], "category-data", "Category::getRecords");
    // CMS Master
    $routes->match(["get", "post"], "save-cms", "Cms::save_cms");
    $routes->match(["get", "post"], "cms-list", "Cms::index");
    $routes->match(["get", "post"], "add-cms", "Cms::add_cms");
    $routes->match(["get", "post"], "cms-data", "Cms::getRecords");
    // Task Master
    $routes->match(["get", "post"], "save-task", "Task::save_task");
    $routes->match(["get", "post"], "task-list", "Task::index");
    $routes->match(["get", "post"], "add-task", "Task::add_task");
    $routes->match(["get", "post"], "task-data", "Task::getRecords");
    // Disease Master
    $routes->match(["get", "post"], "save-disease", "Disease::save_disease");
    $routes->match(["get", "post"], "speciality-list", "Disease::index");
    $routes->match(["get", "post"], "add-speciality", "Disease::add_disease");
    $routes->match(["get", "post"], "disease-data", "Disease::getRecords");
      // Disease Master
    $routes->match(["get", "post"], "save-service", "Scope_of_services::save_service");
    $routes->match(["get", "post"], "services-list", "Scope_of_services::index");
    $routes->match(["get", "post"], "add-service", "Scope_of_services::add_service");
    $routes->match(["get", "post"], "service-data", "Scope_of_services::getRecords");
    // Reason Master
    $routes->match(["get", "post"], "save-reason", "Reason::save_reason");
    $routes->match(["get", "post"], "reason-list", "Reason::index");
    $routes->match(["get", "post"], "add-reason", "Reason::add_reason");
    $routes->match(["get", "post"], "reason-data", "Reason::getRecords");
    // Leaves List
    $routes->match(["get", "post"], "leaves-list", "Leaves::index");
    $routes->match(["get", "post"], "leaves-data", "Leaves::getRecords");
    $routes->match(["get", "post"], "approve_reject_leave", "Leaves::approve_reject_leave");
    // Patient Master
    $routes->match(["get", "post"], "save-patient", "Patient::save_patient");
    $routes->match(["get", "post"], "patient-list", "Patient::index");
    $routes->match(["get", "post"], "add-patient", "Patient::add_patient");
    $routes->match(["get", "post"], "patient-data", "Patient::getRecords");
    $routes->match(["get", "post"], "convert-patient", "Patient::convert_patient");
    // Notifcation Master
    $routes->match(["get", "post"], "save-notification", "Notification::save_notification");
    $routes->match(["get", "post"], "notification-list", "Notification::index");
    $routes->match(["get", "post"], "add-notification", "Notification::add_notification");
    $routes->match(["get", "post"], "notification-data", "Notification::getRecords");
    $routes->match(["get", "post"], "push-notification", "Notification::pushNotification");
    //Converted Patient List
    $routes->match(["get", "post"], "case-history", "Patient::converted_patient_list");
    $routes->match(["get", "post"], "converted-patient-data", "Patient::getConvertedRecords");
    // Banner Master
    $routes->match(["get", "post"], "save-banner", "Banner::save_banner");
    $routes->match(["get", "post"], "banner-list", "Banner::index");
    $routes->match(["get", "post"], "add-banner", "Banner::add_banner");
    $routes->match(["get", "post"], "banner-data", "Banner::getRecords");
    // Query List
    $routes->match(["get", "post"], "query-list", "Query::index");
    $routes->match(["get", "post"], "query-data", "Query::getRecords");
    // Attendance List
    $routes->match(["get", "post"], "attendance-list", "Attendance::index");
    $routes->match(["get", "post"], "attendance-data", "Attendance::getRecords");
    $routes->match(["get", "post"], "exportFullAttendance", "Attendance::exportFullAttendance");
    // Menu Master
    $routes->match(["get", "post"], "save-menu", "Menu::save_menu");
    $routes->match(["get", "post"], "menu-list", "Menu::index");
    $routes->match(["get", "post"], "add-menu", "Menu::add_menu");
    $routes->match(["get", "post"], "menu-data", "Menu::getRecords");
    $routes->match(["get", "post"], "assign-menu", "Menu::assign_menu");
    // Department Master
    $routes->match(["get", "post"], "save-department", "Department::save_department");
    $routes->match(["get", "post"], "department-list", "Department::index");
    $routes->match(["get", "post"], "add-department", "Department::add_department");
    $routes->match(["get", "post"], "department-data", "Department::getRecords");
    $routes->match(["get", "post"], "assign_menus", "Department::assign_menus");
    // Role User Master
    $routes->match(["get", "post"], "save-role-user", "Role_user_registration::save_role_user");
    $routes->match(["get", "post"], "role-user-list", "Role_user_registration::index");
    $routes->match(["get", "post"], "add-role-user", "Role_user_registration::add_role_user");
    $routes->match(["get", "post"], "role-user-data", "Role_user_registration::getRecords");

    $routes->match(["get", "post"], "change-password", "Profile::index");
});



//Hospital Panel Routes
$routes->get(HOSPITALPATH . "login", "Authentication::index", ["namespace" => "App\Controllers\Hospital"]);
$routes->get(HOSPITALPATH . "forgot-password", "Authentication::forgot_password", ["namespace" => "App\Controllers\Hospital"]);
$routes->match(["get", "post"], HOSPITALPATH . "sendNewPassword", "Authentication::sendNewPassword", ["namespace" => "App\Controllers\Hospital"]);
$routes->get(HOSPITALPATH . "logout", "Authentication::logout", ["namespace" => "App\Controllers\Hospital"]);
$routes->get(HOSPITALPATH . "dashboard", "Dashboard::index", ["filter" => "hospitalauth", "namespace" => "App\Controllers\Hospital"]);
$routes->match(["get", "post"], HOSPITALPATH . "authenticate", "Authentication::authenticate", ["namespace" => "App\Controllers\Hospital"]);
$routes->group("hospital", ["filter" => 'hospitalauth', "namespace" => "App\Controllers\Hospital"], function ($routes) {

$routes->match(["get", "post"], "changePassword", "Ajax::index");
$routes->match(["get", "post"], "getCount", "Ajax::getCount");

$routes->match(["get", "post"], "case-history", "Casehistory::index");
$routes->match(["get", "post"], "case-history-data", "Casehistory::getRecords");

$routes->match(["get", "post"], "change-password", "Profile::change_password");


});



/*************************** Employe App API's Section ***********************************/
$routes->group("api/employee", ["namespace" => "App\Controllers\Api\Employee"], function ($routes) {
    $routes->match(["get", "post"], 'login', 'Login::index');
    $routes->match(["get", "post"], 'forgot_password', 'Login::forgot_password');
    $routes->match(["get", "post"], 'autologin', 'Autologin::index');
    $routes->match(["get", "post"], 'logout', 'Logout::index');

    $routes->match(["get", "post"], 'edit_profile', 'Edit_profile::index');

    $routes->match(["get", "post"], 'state_list', 'State_list::index');
    $routes->match(["get", "post"], 'district_list', 'District_list::index');
    $routes->match(["get", "post"], 'block_list', 'Block_list::index');

    $routes->match(["get", "post"], 'hospital_list', 'Hospital_list::index');
    $routes->match(["get", "post"], 'hospitals', 'Hospital_list::hospitals');
    $routes->match(["get", "post"], 'volunteer_list', 'Volunteer_list::index');
    $routes->match(["get", "post"], 'my_team', 'Volunteer_list::my_team');
    $routes->match(["get", "post"], 'task_list', 'Task_list::index');

    $routes->match(["get", "post"], 'manager_list', 'Manager_list::index');
    $routes->match(["get", "post"], 'project_list', 'Manager_list::project_list');
    
    $routes->match(["get", "post"], 'patient_list', 'Patient_list::index');
    $routes->match(["get", "post"], 'patient_detail', 'Patient_list::patient_detail');

    $routes->match(["get", "post"], 'program_master_list', 'Program_master_list::index');
    $routes->match(["get", "post"], 'disease_list', 'Disease_list::index');
    
    $routes->match(["get", "post"], 'service_list', 'Disease_list::service_list');
    
    $routes->match(["get", "post"], 'category_list', 'Add_hospital::category_list');
    
    $routes->match(["get", "post"], 'add_hospital', 'Add_hospital::index');
    $routes->match(["get", "post"], 'add_patient', 'Add_patient::index');
    $routes->match(["get", "post"], 'add_volunteer_task', 'Add_volunteer_task::index');
    
    $routes->match(["get", "post"], 'volunteer_task_list', 'Volunteer_task_list::index');
    $routes->match(["get", "post"], 'volunteer_today_task_list', 'Volunteer_task_list::today_task_list');
    $routes->match(["get", "post"], 'task_detail', 'Volunteer_task_list::task_detail');

    $routes->match(["get", "post"], 'add_program', 'Add_program::index');
    $routes->match(["get", "post"], 'upload_program_image', 'Add_program::upload_program_image');
    $routes->match(["get", "post"], 'program_list', 'Program_list::index');
    $routes->match(["get", "post"], 'program_detail', 'Program_list::program_detail');
    $routes->match(["get", "post"], 'change_program_status', 'Program_list::change_program_status');

    $routes->match(["get", "post"], 'case_history_list', 'Case_history_list::index');
    $routes->match(["get", "post"], 'case_history_detail', 'Case_history_list::case_history_detail');
    $routes->match(["get", "post"], 'reason_list', 'Reason_list::index');

    $routes->match(["get", "post"], 'apply_leave', 'Apply_leave::index');
    $routes->match(["get", "post"], 'leave_list', 'Apply_leave::leave_list');
    $routes->match(["get", "post"], 'volunteer_approval_list', 'Apply_leave::volunteer_approval_list');
    $routes->match(["get", "post"], 'employee_approval_list', 'Apply_leave::employee_approval_list');
    $routes->match(["get", "post"], 'approve_reject_leave', 'Apply_leave::approve_reject_leave');

    $routes->match(["get", "post"], 'notification_list', 'Notification::index');
    $routes->match(["get", "post"], 'delete_notification', 'Notification::delete_notification');
   
    $routes->match(["get", "post"], 'duty_on', 'Duty_on::index');
    $routes->match(["get", "post"], 'duty_off', 'Duty_on::duty_off');
    $routes->match(["get", "post"], 'attendance_list', 'Duty_on::attendance_list');

    $routes->match(["get", "post"], 'update_profile_image', 'Update_profile_image::index');
    $routes->match(["get", "post"], 'upload_patient_image', 'Upload_patient_image::index');

    $routes->match(["get", "post"], 'cms_page', 'Cms::index');
    $routes->match(["get", "post"], 'banner', 'Cms::banner');

    $routes->match(["get", "post"], 'write_to_us', 'Write_to_us::index');
    $routes->match(["get", "post"], 'support', 'Support::index');
});



//Volunteer APP API Section
$routes->group("api/volunteer", ["namespace" => "App\Controllers\Api\Volunteer"], function ($routes) {
    $routes->match(["get", "post"], 'login', 'Login::index');
    $routes->match(["get", "post"], 'forgot_password', 'Login::forgot_password');
    $routes->match(["get", "post"], 'autologin', 'Autologin::index');
    $routes->match(["get", "post"], 'logout', 'Login::logout');

    $routes->match(["get", "post"], 'add_patient', 'Add_patient::index');
    $routes->match(["get", "post"], 'today_registered_patients', 'Add_patient::today_registered_patients');
    $routes->match(["get", "post"], 'total_registered_patients', 'Add_patient::total_registered_patients');
    $routes->match(["get", "post"], 'patient_detail', 'Add_patient::patient_detail');
    $routes->match(["get", "post"], 'upload_patient_image', 'Upload_patient_image::index');

    $routes->match(["get", "post"], 'case_history_list', 'Case_history_list::index');
    $routes->match(["get", "post"], 'case_history_detail', 'Case_history_list::case_history_detail');
    
    $routes->match(["get", "post"], 'apply_leave', 'Leave::index');
    $routes->match(["get", "post"], 'leave_list', 'Leave::leave_list'); 
    $routes->match(["get", "post"], 'reason_list', 'Leave::reason_list'); 
    
    $routes->match(["get", "post"], 'upcoming_programs', 'Upcoming_programs::index');
    $routes->match(["get", "post"], 'change_program_status', 'Upcoming_programs::change_program_status');
    $routes->match(["get", "post"], 'completed_programs', 'Upcoming_programs::completed_programs');

    $routes->match(["get", "post"], 'notification_list', 'Notification::index');
    $routes->match(["get", "post"], 'delete_notification', 'Notification::delete_notification');

    $routes->match(["get", "post"], 'hospital_list', 'Hospital_list::index');
    $routes->match(["get", "post"], 'hospitals', 'Hospital_list::hospitals');
    
    $routes->match(["get", "post"], 'service_list', 'Disease_list::service_list');
    $routes->match(["get", "post"], 'category_list', 'Disease_list::category_list');

    $routes->match(["get", "post"], 'write_to_us', 'Write_to_us::index');

    //Location
    $routes->match(["get", "post"], 'state_list', 'Location::index');
    $routes->match(["get", "post"], 'block_list', 'Location::block_list');
    $routes->match(["get", "post"], 'district_list', 'Location::district_list');

    $routes->match(["get", "post"], 'cms_page', 'Cms::index');
    $routes->match(["get", "post"], 'banner', 'Cms::banner');

    $routes->match(["get", "post"], 'edit_profile', 'Edit_profile::index');
    $routes->match(["get", "post"], 'update_profile_image', 'Edit_profile::update_profile_image');

    $routes->match(["get", "post"], 'support', 'Support::index');
    $routes->match(["get", "post"], 'disease_list', 'Disease_list::index');

    $routes->match(["get", "post"], 'duty_on', 'Duty_on::index');
    $routes->match(["get", "post"], 'duty_off', 'Duty_on::duty_off');
    $routes->match(["get", "post"], 'attendance_list', 'Duty_on::attendance_list');
    
    $routes->match(["get", "post"], 'task_list', 'Task_list::index');
});



if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
