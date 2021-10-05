<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Lesson extends FrontendController
{
    protected $format = 'json';

    public function index()
    {
        dd('Coba Routes');
    }

}