<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;
use Exception;

class DashboardInstructor extends FrontendController
{
    protected $format = 'json';

    public function index()
    {
        $course_list = $this->model_instructor->get_course_list();
        foreach ($course_list as $courses) {
            $data[] = [
                "id" => $courses->id,
                'title' => $courses->title,
                'short_description' => $courses->short_description,
                'description' => $courses->description,
                'bio_instructor' => $courses->bio_instructor,
                'bio_status' => $courses->bio_status,
                'outcomes' => $courses->outcomes,
                'language' => $courses->language,
                'category_id' => $courses->category_id,
                'sub_category_id' => $courses->sub_category_id,
                'section' => $courses->section,
                'requirement' => $courses->requirement,
                'price' => $courses->price,
                'discount_flag' => $courses->discount_flag,
                'discount_price' => $courses->discount_price,
                'level_course' => $courses->level_course,
                'user_id' => $courses->user_id,
                'thumbnail' => $this->model_course->get_thumbnail($courses->id),
                'video_url' => $courses->video_url,
                'visibility' => $courses->visibility,
                'is_top_course' => $courses->is_top_course,
                'is_admin' => $courses->is_admin,
                'status_course' => $courses->status_course,
                'course_overview_provider' => $courses->course_overview_provider,
                'meta_keyword' => $courses->meta_keyword,
                'meta_description' => $courses->meta_description,
                'is_free_course' => $courses->is_free_course,
                'is_prakerja' => $courses->is_prakerja,
                'instructor_revenue' => $courses->instructor_revenue,
                'create_at' => $courses->create_at,
                'update_at' => $courses->update_at,
                'delete_at' => $courses->delete_at,
                "instructor_name" => $courses->first_name .' '. $courses->last_name,
                "name_category" => $courses->name_category,
                "parent_category" => $courses->parent_category,
            ];
        }
        return $this->respond(get_response($data));
    }

    public function show_detail($id_course)
    {
        $course_list_by_id = $this->model_instructor->get_course_list($id_course);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        $data[] = [
                "id" => $course_list_by_id->id,
                'title' => $course_list_by_id->title,
                'short_description' => $course_list_by_id->short_description,
                'description' => $course_list_by_id->description,
                'bio_instructor' => $course_list_by_id->bio_instructor,
                'bio_status' => $course_list_by_id->bio_status,
                'outcomes' => $course_list_by_id->outcomes,
                'language' => $course_list_by_id->language,
                'category_id' => $course_list_by_id->category_id,
                'sub_category_id' => $course_list_by_id->sub_category_id,
                'section' => $course_list_by_id->section,
                'requirement' => $course_list_by_id->requirement,
                'price' => $course_list_by_id->price,
                'discount_flag' => $course_list_by_id->discount_flag,
                'discount_price' => $course_list_by_id->discount_price,
                'level_course' => $course_list_by_id->level_course,
                'user_id' => $course_list_by_id->user_id,
                'thumbnail' => $this->model_course->get_thumbnail($course_list_by_id->id),
                'video_url' => $course_list_by_id->video_url,
                'visibility' => $course_list_by_id->visibility,
                'is_top_course' => $course_list_by_id->is_top_course,
                'is_admin' => $course_list_by_id->is_admin,
                'status_course' => $course_list_by_id->status_course,
                'course_overview_provider' => $course_list_by_id->course_overview_provider,
                'meta_keyword' => $course_list_by_id->meta_keyword,
                'meta_description' => $course_list_by_id->meta_description,
                'is_free_course' => $course_list_by_id->is_free_course,
                'is_prakerja' => $course_list_by_id->is_prakerja,
                'instructor_revenue' => $course_list_by_id->instructor_revenue,
                'create_at' => $course_list_by_id->create_at,
                'update_at' => $course_list_by_id->update_at,
                'delete_at' => $course_list_by_id->delete_at,
                "instructor_name" => $course_list_by_id->first_name .' '. $course_list_by_id->last_name,
                "name_category" => $course_list_by_id->name_category,
                "parent_category" => $course_list_by_id->parent_category,
            ];
        return $this->respond(get_response($data));
    }

    public function create()
    {
        $data_course = $this->request->getJSON();
        if (is_null($data_course)) {
            throw new Exception('Data Request Not Found! Failed To Create Please Try Again');
        }
        try {
            $this->model_instructor->protect(false)->insert($data_course);
            return $this->respondCreated(response_create());
        } catch (Exception $e) {
            return $this->respondNoContent($e->getMessage());
        }
    }

    public function update($id_course = null)
    {
        $data_course = $this->request->getJSON();
        $course_list_by_id = $this->model_instructor->get_course_list($id_course);
        if (is_null($data_course) || is_null($course_list_by_id)) {
            throw new Exception('Data Request Not Found Or Id Not Found!! Failed To Update Please Try Again');
        }
        try {
            $this->model_instructor->protect(false)->update($id_course, $data_course);
            return $this->respondUpdated(response_update());
        } catch (Exception $e) {
            return $this->respondNoContent($e->getMessage());
        }
    }

    public function delete($id_course = null)
    {
        $course_list_by_id = $this->model_instructor->find($id_course);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        $this->model_instructor->delete($id_course);
        return $this->respondDeleted(response_delete());
    }
}