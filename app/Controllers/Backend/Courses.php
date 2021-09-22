<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Courses extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $course_list = $this->model_course->get_course_list();

        return $this->respond(get_response($course_list));
    }

    public function show($id = null)
    {
        $course_list_by_id = $this->model_course->get_course_list($id);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        return $this->respond(get_response($course_list_by_id));
    }

    public function create()
    {
        $data_course = $this->request->getJSON();
        $this->model_course->protect(false)->insert($data_course);
        return $this->respondCreated(response_create());
    }

    public function update($id = null)
    {
        $data_course = $this->request->getJSON();
        $course_list_by_id = $this->model_course->get_course_list($id);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        $this->model_course->update($id, $data_course);
        return $this->respondUpdated(response_update());
    }

    public function delete($id = null)
    {
        $course_list_by_id = $this->model_course->get_course_list($id);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        $this->model_course->delete($id);
        return $this->respondDeleted(response_delete());
    }
}
