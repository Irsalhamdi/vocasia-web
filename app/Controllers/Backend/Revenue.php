<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Revenue extends BackendController
{
    protected $format = 'json';
    public function admin_revenue()
    {
        $admin_revenue = $this->model_payment->get_admin_revenue();
        return $this->respond(get_response($admin_revenue));
    }
    public function instructor_revenue($id_course = null)
    {
        $instructor_revenue = $this->model_payment->get_instructor_revenue($id_course);
        return $this->respond(get_response($instructor_revenue));
    }
    // public function update_admin_revenue()
    // {
    //     $admin_revenue = $this->model_payment->update_admin_revenue();
    //     return $this->respondUpdated($admin_revenue);
    // }
}
