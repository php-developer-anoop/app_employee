<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class HospitalAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get("hospital_login_data")) {
            return redirect()->to(base_url(HOSPITALPATH . "login"));
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}