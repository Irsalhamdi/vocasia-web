<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;

class Instructor extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $data_instructor = $this->model_users->get_instructor_list();
        foreach ($data_instructor as $instructor) {
            $data[] = [
                "id" => $instructor->id,
                "name_instructor" => $instructor->first_name.' '.$instructor->last_name,
            ];
        }
        return $this->respond(get_response($data));
    }
}
