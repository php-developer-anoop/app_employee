<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Notification extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_notification_master";
    }
    function index() {
        $data = [];
        $data["title"] = "Notification Master";
        adminview('view-notification', $data);
    }
    function add_notification() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data["title_name"] = !empty($id) ? "Edit Notification" : "Add Notification";
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['app_type'] = !empty($savedData['app_type']) ? $savedData['app_type'] : '';
        $data['title'] = !empty($savedData['title']) ? $savedData['title'] : '';
        $data['description'] = !empty($savedData['description']) ? $savedData['description'] : '';
        $data['image'] = !empty($savedData['image']) ? $savedData['image'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-notification', $data);
    }
    public function save_notification() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data['title'] = (trim($post['title']));
        $data['app_type'] = (trim($post['app_type']));
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && empty($id)) {
            $this->session->setFlashdata('failed', 'Duplicate Entry');
            return redirect()->to(base_url(ADMINPATH . 'notification-list'));
        }
        if ($file = $this->request->getFile('image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $image_file = $file->getRandomName();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_image']) && file_exists(ROOTPATH . 'uploads/' . $post['old_image'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_image']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $image_file);
                $data['image'] = $image_file;
            }
        }
        $data['description'] = trim($post['description']);
        $data['status'] = trim($post['status']);
        $last_id = '';
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $last_id = $this->c_model->insertRecords($this->table, $data);
            $this->session->setFlashdata('success', 'Data Added Successfully ');
        } else {
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $last_id = $id;
            $this->session->setFlashdata('success', 'Data Updated Successfully');
        }
        return redirect()->to(base_url(ADMINPATH . 'notification-list'));
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
            $where[" title LIKE '%" . $searchString . "%'"] = null;
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
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date';
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
    public function pushNotification() {
        $post = $this->request->getVar();
        $id = $post['id'];
        $start = $post['start'];
        $limit = $post['limit'];
        if (!$id) {
            echo 'id is blank';
            exit;
        }
        $notificationData = $this->c_model->getSingle('notification_master', '*', ['id' => $id]);
        $app_type = $notificationData['app_type'];
        $data = [];
        $data['status'] = false;
        $data['id'] = (string)$id;
        $data['start'] = (string)($start + 1);
        $data['limit'] = (string)$limit;
        $data['ids'] = '';
        $serverkey = FIREBASE_API_KEY;
        $keys = '';
        if ($app_type == 'employee') {
            $tablename = 'dt_employee_list';
        } else if ($app_type == 'volunteer') {
            $tablename = 'dt_volunteer_list';
        }
        $where = [];
        $where['fcm_token !='] = '';
        $where['profile_status'] = 'Active';
        $keys = 'id,fcm_token';
        $limits = $limit;
        $start = $start * $limit;
        $orderby = 'ASC';
        $orderbykey = 'id';
        $getorcount = 'get';
        $res = $this->c_model->getfilter($tablename, $where, $limits, $start, $orderby, $orderbykey, $getorcount, $keys);
        $msgarray = [];
        $msgarray['title'] = ucwords($notificationData['title']);
        $msgarray['message'] = !empty($notificationData['description']) ? $notificationData['description'] : $notificationData['title'];
        $msgarray['image'] = !empty($notificationData['image']) ? base_url(UPLOADS) . $notificationData['image'] : "";
        if (!empty($res)) {
            $data['status'] = true;
            $saveRecords = [];
            $fcm_token = '';
            foreach ($res as $key => $value) {
                if (!empty($value['fcm_token']) && (strlen($value['fcm_token']) > 30)) {
                    $fcm_token.= $value['fcm_token'] . ',';
                    $save = [];
                    $save['employee_volunteer_id'] = $value['id'];
                    $save['user_type'] = $app_type;
                    $save['notification_id'] = $id;
                    $save['title'] = $msgarray['title'];
                    $save['description'] = $msgarray['message'];
                    $save['image_path'] = $msgarray['image'] ? $msgarray['image'] : '';
                    $save['add_date'] = date('Y-m-d H:i:s');
                    $saveRecords[] = $save;
                }
            }
            if (!empty($saveRecords)) {
                $this->c_model->insertBatchItems("notification_list", $saveRecords);
            }
            $firebaseids = rtrim($fcm_token, ',');
            $sendnoti = pushnotifications($firebaseids, $msgarray, $serverkey);
        }
        echo json_encode($data);
        exit;
    }
}
