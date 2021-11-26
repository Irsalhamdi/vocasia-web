<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;

class Instructor extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $data_instructor = $this->model_users->get_instructor_list();
        return $this->respond(get_response($data_instructor));
    }
}
