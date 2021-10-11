<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Lesson extends FrontendController
{
    protected $format = 'json';

    public function index()
    {
        $lesson_list = $this->model_lesson->get_list_lesson();
        return $this->respond(get_response($lesson_list));
    }

    public function show_detail($id = null)
    {
        $lesson_exist = $this->model_lesson->get_list_lesson($id);
        if ($lesson_exist) {
            // lesson exists
            return $this->respond(get_response($lesson_exist));
        } else {
            //lesson not exists
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $course_exists = $this->model_course->find($this->request->getVar("course_id"));
        $section_exists = $this->model_section->find($this->request->getVar("section_id"));
        if (!empty($course_exists && $section_exists)) {
            // course exists and section exists
            $lesson_data = $this->request->getJSON();
            if ($lesson_data) {
                // success to create
                $this->model_lesson->protect(false)->insert($lesson_data);
                return $this->respondCreated(response_create());
            } else {
                // failed to create
                return $this->respond(response_failed());
            }      
        } else {
            // course no exist and section no exists
            return $this->failNotFound();
        }
    }

    public function update($id = null)
    {
        $lesson_exist = $this->model_lesson->get_list_lesson($id);
        $course_exists = $this->model_course->find($this->request->getVar("course_id"));
        $section_exists = $this->model_section->find($this->request->getVar("section_id"));

        if (!empty($course_exists && $section_exists && $lesson_exist)) {
            // course exists and section exists
            $lesson_data = $this->request->getJSON();
            if ($lesson_data) {
                // success to create
                $this->model_lesson->protect(false)->update($id, $lesson_data);
                return $this->respondUpdated(response_update());
            } else {
                // failed to create
                return $this->respond(response_failed());
            }      
        } else {
            // course no exist and section no exists
            return $this->failNotFound();
        }
    }

    public function delete($id = null)
    {
        $lesson_exist = $this->model_lesson->get_list_lesson($id);
        if ($lesson_exist) {
            // lesson exists
            $this->model_lesson->delete($id);
            return $this->respond(response_delete());
        } else {
            //lesson not exists
            return $this->failNotFound();
        }
    }

}