<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Dashboard extends BackendController
{
    public function index()
    {
        return $this->respond(response_create());
    }
    
    public function coba()
    {
        return $this->respond("ini halaman coba");
    }
}
