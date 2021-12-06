<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Affiliate extends BackendController
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
        $affiliate_list = $this->model_affiliate->get_list_affiliate();
        foreach ($affiliate_list as $a) {
            $data[] = [
                "name" => $a->first_name .' '. $a->last_name,
                "code_reff" => $a->code_reff,
                "leader" => $a->leader,
                "update_at" => $a->update_at,
                "id" => $a->id_affiliate
            ];
        }
        return $this->respond(get_response($data));
    }

    public function show_detail($id = null)
    {
        $affiliate_exist = $this->model_affiliate->get_list_affiliate($id);
        if ($affiliate_exist) {
            // affiliate exists
            $data = [
                "name" => $affiliate_exist->first_name .' '. $affiliate_exist->last_name,
                "code_reff" => $affiliate_exist->code_reff,
                "leader" => $affiliate_exist->leader,
                "update_at" => $affiliate_exist->update_at,
                "id" => $affiliate_exist->id_affiliate
            ];
        
            return $this->respond(get_response($data));
        } else {
            //affiliate not exists
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $user_exists = $this->model_users->find($this->request->getVar("user_id"));
        if (!empty($user_exists)) {
            // user exists
            $affiliate_data = $this->request->getJSON();
            if ($affiliate_data) {
                // success to create
                $this->model_affiliate->protect(false)->insert($affiliate_data);
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
        $affiliate_exist = $this->model_affiliate->get_list_affiliate($id);
        if ($affiliate_exist) {
            $user_exists = $this->model_users->find($this->request->getVar("user_id"));
            if (!empty($user_exists)) {
                // user exists
                $affiliate_data = $this->request->getJSON();
                if ($affiliate_data) {
                    // success to create
                    $this->model_affiliate->protect(false)->update($id, $affiliate_data);
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
            //affiliate not exists
            return $this->failNotFound();
        }
    }

    public function delete($id = null)
    {
        $affiliate_exist = $this->model_affiliate->get_list_affiliate($id);
        if ($affiliate_exist) {
            // affiliate exists
            $this->model_affiliate->delete($id);
            return $this->respond(response_delete());
        } else {
            // affiliate not exists
            return $this->failNotFound();
        }
    }

    public function pagging($page, $offset)
    {
        $start_index = ($page > 1) ? ($page * $offset) - $offset : 0; // hitung page saat ini
        $count_data = $this->model_affiliate->get_count_affiliate(); // hitung total data ini akan mengembalikan angka
        $total_pages = ceil($count_data / $offset); //perhitungan dari jumlah data yg dihitung dibagi dengan batas data yg ditentukan
        $get_pagging_data = $this->model_affiliate->get_pagging_data($offset, $start_index); //query berdasarkan data per halaman
        $return_data = [
            'total_page' => $total_pages,
            'data' => $get_pagging_data
        ];
        return $return_data;
    }
}
