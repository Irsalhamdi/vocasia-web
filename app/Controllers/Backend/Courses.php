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
                'title' => $courses->title,
                'create_at' => $courses->create_at,
                'update_at' => $courses->update_at,
                'delete_at' => $courses->delete_at,
                'instructor_name' => $courses->first_name . ' ' . $courses->last_name,
                'name_category' => $courses->name_category,
                'lesson' => $this->model_lesson->get_lesson($courses->id),
                'section' => $this->model_section->get_section($courses->id),
                'count_enrol' => $this->model_enrol->get_count_enrols_courses($courses->id),
                'status_course' => $courses->status_course,
                'price' => $courses->price,
                "id" => $courses->id
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
            'title' => $course_list_by_id->title,
            'create_at' => $course_list_by_id->create_at,
            'name_category' => $course_list_by_id->name_category,
            'lesson' => $this->model_lesson->get_lesson($course_list_by_id->id),
            'section' => $this->model_section->get_section($course_list_by_id->id),
            'count_enrol' => $this->model_enrol->get_count_enrols_courses($course_list_by_id->id),
            'status_course' => $course_list_by_id->status_course,
            'price' => $course_list_by_id->price,
            "id" => $course_list_by_id->id
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
        } else {
            if ($data_course) {
                $path = 'uploads/courses_thumbnail';
                if (!file_exists($path)) {
                    mkdir($path);
                }
                $id_course_thumbnail = $data_course['id'];
                $path_folder = `uploads/courses_thumbnail/course_thumbnail_default_$id_course_thumbnail.jpg`;
                $thumbnail = $this->request->getFile('thumbnail');
                $name = "course_thumbnail_default_$id_course_thumbnail.jpg";
                if (file_exists($path_folder)) {
                    unlink('uploads/courses_thumbnail/' . $name);
                }
                $thumbnail->move('uploads/courses_thumbnail/', $name);
                // $this->model_course->update($id_course, $data);
                return $this->respondCreated(response_create());
            } else {
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
        
        foreach ($get_pagging_data as $courses) {
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

        $return_data = [
            'total_page' => $total_pages,
            'data' => $data
        ];
        return $return_data;
    }

    public function info_courses()
    {
        $data_courses_active = $this->model_course->get_count_course_active();
        $data_courses_pending = $this->model_course->get_count_course_pending();
        $data_courses_free = $this->model_course->get_count_course_free();
        $data_courses_paid = $this->model_course->get_count_course_paid();

        return $this->respond([
            'status' => 201,
            'error' => false,
            'data' => [
                'courses-active' => $data_courses_active,
                'courses-pending' => $data_courses_pending,
                'courses-free' => $data_courses_free,
                'courses-paid' => $data_courses_paid,
            ]
        ]);
    }
}
