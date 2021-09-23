<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use Exception;

class Courses extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $course_list = $this->model_course->get_course_list();

        return $this->respond(get_response($course_list));
    }

    public function show_detail()
    {
        $id_course = $this->request->getVar('id');
        $course_list_by_id = $this->model_course->get_course_list($id_course);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        return $this->respond(get_response($course_list_by_id));
    }

    public function create()
    {
        $data_course = $this->request->getJSON();
        if (is_null($data_course)) {
            throw new Exception('Data Request Not Found! Failed To Create Please Try Again');
        }
        try {
            $this->model_course->protect(false)->insert($data_course);
            return $this->respondCreated(response_create());
        } catch (Exception $e) {
            return $this->respondNoContent($e->getMessage());
        }
    }

    public function update($params = null)
    {
        $id_course = $this->request->getVar('id');
        $data_course = $this->request->getJSON();
        $course_list_by_id = $this->model_course->get_course_list($id_course);
        if (is_null($data_course) || is_null($course_list_by_id)) {
            throw new Exception('Data Request Not Found Or Id Not Found!! Failed To Update Please Try Again');
        }
        try {
            $this->model_course->protect(false)->update($id_course, $data_course);
            return $this->respondUpdated(response_update());
        } catch (Exception $e) {
            return $this->respondNoContent($e->getMessage());
        }
    }

    public function delete($params = null)
    {
        $id_course = $this->request->getVar('id');
        $course_list_by_id = $this->model_course->get_course_list($id_course);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        $this->model_course->delete($id_course);
        return $this->respondDeleted(response_delete());
    }
}
