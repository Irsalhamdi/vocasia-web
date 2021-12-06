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
        foreach ($users_mitra_list as $mitra) {
            $data[] = [
            "id" => $mitra->id,
            "id_user" => $mitra->id_user,
            "number_code" => $mitra->number_code,
            "collage" => $mitra->collage,
            "major" => $mitra->major,
            "mitra_name" => $mitra->mitra_name,
            "create_at" => $mitra->create_at,
            "update_at" => $mitra->update_at,
            "name" => $mitra->first_name.' '.$mitra->last_name,
            ];
        }
        return $this->respond(get_response($data));
    }

    public function show_detail($id = null)
    {
        $users_mitra_exist = $this->model_users_mitra->get_list_users_mitra($id);
        if ($users_mitra_exist) {
            // users mitra exists
            $data = [
            "id" => $users_mitra_exist->id,
            "id_user" => $users_mitra_exist->id_user,
            "number_code" => $users_mitra_exist->number_code,
            "collage" => $users_mitra_exist->collage,
            "major" => $users_mitra_exist->major,
            "mitra_name" => $users_mitra_exist->mitra_name,
            "create_at" => $users_mitra_exist->create_at,
            "update_at" => $users_mitra_exist->update_at,
            "name" => $users_mitra_exist->first_name.' '.$users_mitra_exist->last_name,
            ];
            return $this->respond(get_response($data));
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
