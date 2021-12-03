<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Community extends BackendController
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

        $community_data = $this->model_community->get_list_community();
        foreach ($community_data as $c) {
            $data[] = [
                "name" => $c->name,
                "name_category" => $c->name_category,
                "background" => $this->model_community->get_background($c->id),
                "id" => $c->id
            ];
        }
        return $this->respond(get_response($data));
    }

    public function show($id = null)
    {
        $community_data = $this->model_community->get_list_community($id);
        $data[] = [
                "name" => $community_data->name,
                "name_category" => $community_data->name_category,
                "background" => $community_data->background,
                "id" => $community_data->id
            ];
        return $this->respond(get_response($data));
    }

    public function create()
    {
        $community = $this->request->getJSON();;
        $rules = $this->model_community->validationRules;

        if (!$this->validate($rules)) {
            return $this->respond(response_failed());
        }

        if (!is_null($this->model_community->checking_valid_data($community->category_id))) {
            $this->model_community->insert([
                'name' => $community->name,
                'category_id' => $community->category_id,
                'create_at' => strtotime(date('D, d-M-Y'))
            ]);

            return $this->respond(response_create());
        } else {
            return $this->failNotFound();
        }
    }

    public function update($id_community = null)
    {
        $community = $this->request->getJSON();
        $rules = $this->model_community->validationRules;

        if (!$this->validate($rules)) {
            return $this->respond(response_failed());
        }

        if (!is_null($this->model_community->checking_valid_data($community->category_id))) {
            $data = [
                'name' => $community->name,
                'category_id' => $community->category_id,
                'create_at' => strtotime(date('D, d-M-Y'))
            ];
            $this->model_community->update($id_community, $data);

            return $this->respond(response_update());
        } else {
            return $this->failNotFound();
        }
    }

    public function delete($id = null)
    {
        $find_community = $this->model_community->find($id);
        if (!is_null($find_community)) {
            $this->model_community->delete($id);
            return $this->respondDeleted(response_delete());
        }
        return $this->failNotFound();
    }

    public function pagging($page, $offset)
    {
        $start_index = ($page > 1) ? ($page * $offset) - $offset : 0; // hitung page saat ini
        $count_data = $this->model_community->get_count_community(); // hitung total data ini akan mengembalikan angka
        $total_pages = ceil($count_data / $offset); //perhitungan dari jumlah data yg dihitung dibagi dengan batas data yg ditentukan
        $get_pagging_data = $this->model_community->get_pagging_data($offset, $start_index); //query berdasarkan data per halaman
        $return_data = [
            'total_page' => $total_pages,
            'data' => $get_pagging_data
        ];
        return $return_data;
    }

    public function background($id = null)
    {
        $data_community = $this->model_community->find($id);
        $rules = [
            'background' => 'max_size[background,2048]|is_image[background]'
        ];
        if (!$this->validate($rules)) {
            return $this->fail('Failed To Upload Image Please Try Again');
        } else {
            if ($data_community) {
                $path = "uploads/community_background";
                if (!file_exists($path)) {
                    mkdir($path);
                }
                $background = $this->request->getFile('background');
                $name = "community_background_default_$id.jpg";
                $path_photo = $path . '/' . $name;
                if (file_exists($path_photo)) {
                    unlink('uploads/community_background/' . $name);
                }
                $background->move('uploads/community_background/', $name);
                // $this->model_community->update($id, $data);

                return $this->respondCreated(response_create());
            } else {
                return $this->failNotFound();
            }
        }
    }
}
