<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Dashboard extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $data_courses = $this->model_course->get_count_course();
        $data_courses_active = $this->model_course->get_count_course_active();
        $data_lessons = $this->model_lesson->get_count_lesson();
        $data_enrols = $this->model_enrol->get_count_enrol();
        $data_users = $this->model_users->get_count_user();
        $data_instructor = $this->model_users->get_data_instructor();
        foreach ($data_instructor as $instructor) {
            $data[] = [
            "id" => $instructor->id,
            "fullname" => $instructor->first_name.' '.$instructor->last_name,
            "email" => $instructor->email,
            "is_instructor" => $instructor->is_instructor,
            "title" => $instructor->title,
            "instructor_revenue" => $instructor->instructor_revenue,
            
            ];
        }
        $data_new_users = $this->model_users->get_count_new_user();

        return $this->respond([
            'status' => 201,
            'error' => false,
            'data' => [
                'courses' => $data_courses,
                'courses-active' => $data_courses_active,
                'lessons' => $data_lessons,
                'enrols' => $data_enrols,
                'users' => $data_users,
                'new-users' => $data_new_users,
                'data-instructor' => $data
            ]
        ]);
    }
}
