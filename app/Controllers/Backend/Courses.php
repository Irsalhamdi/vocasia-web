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

        $data_courses_active = $this->model_course->get_count_course_active();
        $data_courses_pending = $this->model_course->get_count_course_pending();
        $data_courses_free = $this->model_course->get_count_course_free();
        $data_courses_paid = $this->model_course->get_count_course_paid();
        $data_courses_list = $this->model_course->get_course_list();
        $data_courses_list = $this->model_course->get_course_list();
        
        return $this->respond([
            'status' => 201,
            'error' => false,
            'data' => [
                'courses-active' => $data_courses_active,
                'courses-pending' => $data_courses_pending,
                'courses-free' => $data_courses_free,
                'courses-paid' => $data_courses_paid,
                'courses-list' => $data_courses_list
            ]
        ]);
    }

    public function show_detail($id_course)
    {
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
