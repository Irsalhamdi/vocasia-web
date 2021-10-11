<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class UsersMitra extends BackendController
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
        $users_mitra_list = $this->model_users_mitra->get_list_users_mitra();

        return $this->respond(get_response($users_mitra_list));
    }

    public function show_detail($id = null)
    {
        $users_mitra_exist = $this->model_users_mitra->get_list_users_mitra($id);
        if ($users_mitra_exist) {
            // users mitra exists
            return $this->respond(get_response($users_mitra_exist));
        } else {
            //users mitra not exists
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $user_exists = $this->model_users->find($this->request->getVar("id_user"));
        if (!empty($user_exists)) {
            // user exists
            $users_mitra_data = $this->request->getJSON();
            if ($users_mitra_data) {
                // success to create
                $this->model_users_mitra->protect(false)->insert($users_mitra_data);
                return $this->respondCreated(response_create());
            } else {
                // failed to create
                return $this->respond(response_failed());
            }
        } else {
            // user no exist
            return $this->failNotFound();
        }
    }

    public function update($id = null)
    {
        $users_mitra_exist = $this->model_users_mitra->get_list_users_mitra($id);
        if ($users_mitra_exist) {
            $user_exists = $this->model_users->find($this->request->getVar("id_user"));
            if (!empty($user_exists)) {
                // user exists
                $users_mitra_data = $this->request->getJSON();
                if ($users_mitra_data) {
                    // success to create
                    $this->model_users_mitra->protect(false)->update($id, $users_mitra_data);
                    return $this->respondCreated(response_update());
                } else {
                    // failed to create
                    return $this->respond(response_failed());
                }
            } else {
                // user no exist
                return $this->failNotFound();
            }
        } else {
            //users_mitra not exists
            return $this->failNotFound();
        }
    }

    public function delete($id = null)
    {
        $users_mitra_exist = $this->model_users_mitra->get_list_users_mitra($id);
        if ($users_mitra_exist) {
            // users mitra exists
            $this->model_users_mitra->delete($id);
            return $this->respond(response_delete());
        } else {
            // users mitra not exists
            return $this->failNotFound();
        }
    }

    public function pagging($page, $offset)
    {
        $start_index = ($page > 1) ? ($page * $offset) - $offset : 0; // hitung page saat ini
        $count_data = $this->model_users_mitra->get_count_users_mitra(); // hitung total data ini akan mengembalikan angka
        $total_pages = ceil($count_data / $offset); //perhitungan dari jumlah data yg dihitung dibagi dengan batas data yg ditentukan
        $get_pagging_data = $this->model_users_mitra->get_pagging_data($offset, $start_index); //query berdasarkan data per halaman
        $return_data = [
            'total_page' => $total_pages,
            'data' => $get_pagging_data
        ];
        return $return_data;
    }
}
