<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\LoginModel;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // cek login
        if (session()->has('user_id')) {

            $loginModel = new LoginModel();

            $user = $loginModel
                ->where('id', session()->get('user_id'))
                ->first();

            if (!$user) {

                session()->destroy();

                header('Location: ' . site_url('login'));
                exit;
            }
        }
    }
}
