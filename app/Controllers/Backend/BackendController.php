<?php

namespace App\Controllers\Backend;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoryModel;

class BackendController extends ResourceController
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    protected $helpers = ['response'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        //silahkan load semua model dibawah ini kalau bisa semua kompak disini semua

        $this->model_course = model('CoursesModel');
        $this->category = new CategoryModel();
        $this->model_coupon = model('CouponModel');
        $this->model_users = model('UsersModel');
    }
}
