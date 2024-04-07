<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Checkmenu implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('login_data')) {
            $session = session()->get('login_data');

            $department_id = $session['department'];
            $db = \Config\Database::connect();
            $builder = $db->table('dt_department');
            $builder->where('id',$department_id);
            $query   = $builder->get()->getRowArray();
            $readmenu = $query['read_menu'];
            $assigened_menus = explode(',',$query['read_menu']);

            $uri = $request->uri->getSegment(2);
            $builder = $db->table('dt_menus');
            $builder->where('slug',$uri);
            $menus   = $builder->get()->getRowArray();
            if(!empty($menus)){
                $menu_id = $menus['id'];
                if(!in_array($menu_id, $assigened_menus)) {
                    return redirect()->to(base_url('admin'));
                }
            }
           
			// if ($session['role'] == "team") {
			// 	//return $this->response->redirect(ADMIN_URL.'dashboard');
			// }
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}