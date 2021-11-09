<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;
use Exception;

class Courses extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        if (!is_null($this->request->getVar('page')) && !is_null($this->request->getVar('limit'))) {
            $page = $this->request->getVar('page');
            $limit = $this->request->getVar('limit');
            $pagging = $this->pagging($page, $limit);
            return $this->respond(response_pagging($pagging['total_page'], $pagging['data']));
        }
        $course_list = $this->model_course->get_course_list();
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
                'status_course' => 'pending',
                'course_overview_provider' => $courses->course_overview_provider,
                'meta_keyword' => $courses->meta_keyword,
                'meta_description' => $courses->meta_description,
                'is_free_course' => $courses->is_free_course,
                'is_prakerja' => $courses->is_prakerja,
                'instructor_revenue' => $courses->instructor_revenue,
                'create_at' => $courses->create_at,
                'update_at' => $courses->update_at,
                'delete_at' => $courses->delete_at,
                'instructor_name' => $courses->instructor_name,
                'name_category' => $courses->name_category,
                'parent_category' => $courses->parent_category,
            ];
        }
        return $this->respond(get_response($data));
    }

    public function show_detail($id_course)
    {
        $course_list_by_id = $this->model_course->get_course_list($id_course);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
            $data = [
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
                'status_course' => 'pending',
                'course_overview_provider' => $course_list_by_id->course_overview_provider,
                'meta_keyword' => $course_list_by_id->meta_keyword,
                'meta_description' => $course_list_by_id->meta_description,
                'is_free_course' => $course_list_by_id->is_free_course,
                'is_prakerja' => $course_list_by_id->is_prakerja,
                'instructor_revenue' => $course_list_by_id->instructor_revenue,
                'create_at' => $course_list_by_id->create_at,
                'update_at' => $course_list_by_id->update_at,
                'delete_at' => $course_list_by_id->delete_at,
                'instructor_name' => $course_list_by_id->instructor_name,
                'name_category' => $course_list_by_id->name_category,
                'parent_category' => $course_list_by_id->parent_category,
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
            $this->model_course->protect(false)->insert($data_course);
            return $this->respondCreated(response_create());
        } catch (Exception $e) {
            return $this->respondNoContent($e->getMessage());
        }
    }

    public function update($id_course = null)
    {
        $data_course = $this->request->getJSON();
        $course_list_by_id = $this->model_course->find($id_course);
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

    public function delete($id_course = null)
    {
        $course_list_by_id = $this->model_course->find($id_course);
        if (is_null($course_list_by_id)) {
            return $this->failNotFound();
        }
        $this->model_course->delete($id_course);
        return $this->respondDeleted(response_delete());
    }

    public function thumbnail($id_course = null)
    {
        $data_course = $this->model_course->find($id_course);
        $rules = [
            'thumbnail' => 'max_size[thumbnail,2048]|is_image[thumbnail]'
        ];
        if (!$this->validate($rules)) {
            return $this->fail('Failed To Upload Image Please Try Again');
        }else {
            if ($data_course) {

            $thumbnail = $this->request->getFile('thumbnail');
            $name = "course_thumbnail_default_$id_course.jpg";
    
            $data = [
                'id'=> $id_course,
                'thumbnail'  => $name
            ];
            if ($data_course['thumbnail']) {
                unlink('uploads/courses_thumbnail/' . $data_course['thumbnail']);    
            }
            $thumbnail->move('uploads/courses_thumbnail/', $name);
            $this->model_course->update($id_course, $data);
            
            return $this->respondCreated(response_create());
        }else {
            return $this->failNotFound();
        }
        }
    }

    public function pagging($page, $offset)
    {
        $start_index = ($page > 1) ? ($page * $offset) - $offset : 0; // hitung page saat ini
        $count_data = $this->model_course->get_count_course(); // hitung total data ini akan mengembalikan angka
        $total_pages = ceil($count_data / $offset); //perhitungan dari jumlah data yg dihitung dibagi dengan batas data yg ditentukan
        $get_pagging_data = $this->model_course->get_pagging_data($offset, $start_index); //query berdasarkan data per halaman
        $return_data = [
            'total_page' => $total_pages,
            'data' => $get_pagging_data
        ];
        return $return_data;
    }
}
