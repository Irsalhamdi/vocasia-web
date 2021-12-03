<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Enrol extends BackendController
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
        $enrol_list = $this->model_enrol->get_list_enrol();
        foreach ($enrol_list as $enrol) {
            $data[] = [
            "id" => $enrol->id,
            "user_id" => $enrol->user_id,
            "course_id" => $enrol->course_id,
            "payment_id" => $enrol->payment_id,
            "create_at" => $enrol->create_at,
            "name" => $enrol->first_name.' '.$enrol->last_name,
            "title" => $enrol->title,
            "phone" => $enrol->phone,
            "foto_profile" => $this->model_users->get_foto_profile($enrol->user_id)
            ];
        }
        return $this->respond(get_response($data));
    }
    public function create()
    {
        $user_exists = $this->model_users->find($this->request->getVar('user_id'));
        if ($user_exists) {
            // users exist
            $course_exists = $this->model_course->find($this->request->getVar('course_id'));
            if ($course_exists) {
                // course exist
                $enrol_data = $this->request->getJSON();
                if ($enrol_data) {
                    // success to enrol
                    $payment_id = rand(00000000, 99999999);
                    $data = [
                        'user_id' => $enrol_data->user_id,
                        'course_id' => $enrol_data->course_id,
                        'payment_id' => $payment_id,
                        'create_at' => strtotime(date('D,d-M-Y'))
                    ];
                    $this->model_enrol->save($data);
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
    }
    public function show_detail($id_enrol = null)
    {
        $enrol_exists = $this->model_enrol->get_list_enrol($id_enrol);
        if ($enrol_exists) {
            // enrol exists
            $data = [
            "id" => $enrol_exists->id,
            "user_id" => $enrol_exists->user_id,
            "course_id" => $enrol_exists->course_id,
            "payment_id" => $enrol_exists->payment_id,
            "create_at" => $enrol_exists->create_at,
            "update_at" => $enrol_exists->update_at,
            "finish_date" => $enrol_exists->finish_date,
            "name" => $enrol_exists->first_name.' '.$enrol_exists->last_name,
            "title" => $enrol_exists->title,
            "course_description" => $enrol_exists->course_description,
            "short_description" => $enrol_exists->short_description,
            "instructor_biography" => $enrol_exists->instructor_biography,
            "outcomes" => $enrol_exists->outcomes,
            "section" => $enrol_exists->section,
            "requirement" => $enrol_exists->requirement,
            "course_level" => $enrol_exists->course_level,
            "price" => $enrol_exists->price,
            "discount" => $enrol_exists->discount,
            "languange" => $enrol_exists->languange,
            "course_status" => $enrol_exists->course_status
            ];
            return $this->respond(get_response($data));
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
                    $enrol_update = $this->request->getJSON();
                    $payment_id = rand(00000000, 99999999);
                    $data = [
                        'user_id' => $enrol_update->user_id,
                        'course_id' => $enrol_update->course_id,
                        'payment_id' => $payment_id,
                        'update_at' => strtotime(date('D,d-M-Y'))
                    ];
                    if ($enrol_update) {
                        $this->model_enrol->update($enrol_id, $data);
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

    public function pagging($page, $offset)
    {
        $start_index = ($page > 1) ? ($page * $offset) - $offset : 0; // hitung page saat ini
        $count_data = $this->model_enrol->get_count_enrol(); // hitung total data ini akan mengembalikan angka
        $total_pages = ceil($count_data / $offset); //perhitungan dari jumlah data yg dihitung dibagi dengan batas data yg ditentukan
        $get_pagging_data = $this->model_enrol->get_pagging_data($offset, $start_index); //query berdasarkan data per halaman
        foreach ($get_pagging_data as $enrol) {
            $data[] = [
            "id" => $enrol->id,
            "user_id" => $enrol->user_id,
            "course_id" => $enrol->course_id,
            "payment_id" => $enrol->payment_id,
            "create_at" => $enrol->create_at,
            "update_at" => $enrol->update_at,
            "finish_date" => $enrol->finish_date,
            "name" => $enrol->first_name.' '.$enrol->last_name,
            "title" => $enrol->title
            ];
        }
        $return_data = [
            'total_page' => $total_pages,
            'data' => $enrol
        ];
        return $return_data;
    }
}
