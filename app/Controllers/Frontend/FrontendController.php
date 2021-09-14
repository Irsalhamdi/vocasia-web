<?php

namespace App\Controllers\Frontend;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class FrontendController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */

    protected $request;

    protected $helpers = []; //jika ingin mengload helper silahkan dimasukan kedalam sini

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        //silahkan load semua model dibawah ini kalau bisa semua kompak disini semua

    }
}
