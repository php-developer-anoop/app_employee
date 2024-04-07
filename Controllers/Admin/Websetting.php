<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Websetting extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_websetting";
    }
    public function index() {
        $data = [];
        $data['title'] = 'Web Setting';
        $data['web'] = $this->c_model->getSingle($this->table, '*');
        adminview('websetting', $data);
    }
    public function save_setting() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        if ($file = $this->request->getFile('logo_img')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                if (is_file('uploads/' . $post['old_logo_jpg']) && file_exists(ROOTPATH . 'uploads/' . $post['old_logo_jpg'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_logo_jpg']);
                }
                if (is_file('uploads/' . $post['old_logo_webp']) && file_exists(ROOTPATH . 'uploads/' . $post['old_logo_webp'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_logo_webp']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $filename);
                $webp_file = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                $logo_webp = convertImageInToWebp('uploads', $filename, $webp_file);
                $data['logo_jpg'] = $filename;
                $data['logo_webp'] = $logo_webp;
               
            }
        }
        if ($file = $this->request->getFile('favicon_img')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                if (is_file('uploads/' . $post['old_favicon']) && file_exists(ROOTPATH . 'uploads/' . $post['old_favicon'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_favicon']);
                }
               
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $filename);
                $data['favicon'] = $filename;
            }
        }
        $data['logo_alt'] = trim($post['logo_alt']);
        $data['company_name'] = trim($post['company_name']);
        $data['care_whatsapp_no'] = trim($post['care_whatsapp_no']);
        $data['care_email_id'] = trim($post['care_email_id']);
        $data['care_mobile_no'] = trim($post['care_mobile_no']);
        $data['map_script'] = trim($post['map_script']);
        $data['copyright'] = trim($post['copyright']);
        $data['office_address'] = trim($post['office_address']);
        
        $object = $this->c_model;
        if (empty($id)) {
            $object->insertRecords($this->table, $data);
            $this->session->setFlashdata('success', 'Data Added Successfully ');
        } else {
            $object->updateRecords($this->table, $data, ['id' => $id]);
            $this->session->setFlashdata('success', 'Data Updated Successfully');
        }
        return redirect()->to(base_url(ADMINPATH . 'web-setting'));
    }
    
}
?>