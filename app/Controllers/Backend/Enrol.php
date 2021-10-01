<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Enrol extends BackendController
{
    protected $format = 'json';
    public function index()
    {
        $enrol_list = $this->model_enrol->get_list_enrol();
        return $this->respond(get_response($enrol_list));
    }
    public function create()
    {
        $user_exists = $this->model_users->find($this->request->getVar('user_id'));
        if ($user_exists) {
            // users exist
            $course_exists = $this->model_course->find($this->request->getVar('course_id'));
            if ($course_exists) {
                // course exist
                $payment_exists = $this->model_payment->find($this->request->getVar('payment_id'));
                if ($payment_exists) {
                    // payment exist
                    $enrol_data = $this->request->getJSON();
                    if ($enrol_data) {
                        // success to enrol
                        $this->model_enrol->protect(false)->save($enrol_data);
                        return $this->respondCreated(response_create());
                    } else {
                        // failed to enrol
                        return $this->respond(response_failed());
                    }
                } else {
                    // payment not exist
                    return $this->failNotFound();
                }
            } else {
                // course not exist
                return $this->failNotFound();
            }
        } else {
            // user not exist
            return $this->failNotFound();
        }
    }
    public function show_detail($id_enrol = null)
    {
        $enrol_exists = $this->model_enrol->get_list_enrol($id_enrol);
        if ($enrol_exists) {
            // enrol exists
            return $this->respond(get_response($enrol_exists));
        } else {
            // enrol not exist
            return $this->failNotFound();
        }
    }
    public function update($enrol_id = null)
    {
        $enrol_exists = $this->model_enrol->find($enrol_id);
        if ($enrol_exists) {
            $user_exists = $this->model_users->find($this->request->getVar('user_id'));
            if ($user_exists) {
                $course_exists = $this->model_course->find($this->request->getVar('course_id'));
                if ($course_exists) {
                    $payment_exists = $this->model_payment->find($this->request->getVar('payment_id'));
                    if ($payment_exists) {
                        $enrol_update = $this->request->getJSON();
                        if ($enrol_update) {
                            $this->model_enrol->protect(false)->update($enrol_id, $enrol_update);
                            return $this->respondUpdated(response_update());
                        } else {
                            return $this->respond(response_failed());
                        }
                    } else {
                        return $this->failNotFound();
                    }
                } else {
                    return $this->failNotFound();
                }
            } else {
                return $this->failNotFound();
            }
        } else {
            return $this->failNotFound();
        }
    }
    public function delete($id_enrol = null)
    {
        $enrol_exists = $this->model_enrol->find($id_enrol);
        if ($enrol_exists) {
            // enrol exist
            $this->model_enrol->delete($id_enrol);
            return $this->respondDeleted(response_delete());
        } else {
            // enrol not exist
            return $this->failNotFound();
        }
    }
}
