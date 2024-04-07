<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
class Auth implements FilterInterface {
    // before function
    public function before(RequestInterface $request, $arguments = null) {
        if (!session()->get("login_data")) {
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        $session = session()->get('login_data');
        if ($session['user_type'] == "Role User") {
            $department_id = $session['department'];
            $db = \Config\Database::connect();
            $builder = $db->table('dt_department');
            $query = $builder->select('read_menu_ids')->where('id', $department_id)->get()->getRowArray();
            $assigned_menus = explode(',', $query['read_menu_ids']??'');
            $uri = $request->uri->getSegment(2);
            $menus = $db->table('dt_menus')->select('id')->where('slug', $uri)->get()->getRowArray();
            if (!empty($menus) && !in_array($menus['id'], $assigned_menus)) {
                session()->setFlashdata('failed', 'You are not allowed to view this');
                return redirect()->to(base_url(ADMINPATH . 'dashboard'));
            }
        }
    }
    // after function
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
    }
}
