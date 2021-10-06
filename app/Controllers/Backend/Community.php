<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Community extends BackendController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

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
        return $this->respond(get_response($community_data));
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $community_data = $this->model_community->get_list_community($id);
        return $this->respond(get_response($community_data));
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
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
                'background' => $community->background,
                'category_id' => $community->category_id,
                'create_at' => strtotime(date('D, d-M-Y'))
            ]);

            return $this->respond(response_create());
        } else {
            return $this->failNotFound();
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
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
                'background' => $community->background,
                'category_id' => $community->category_id,
                'create_at' => strtotime(date('D, d-M-Y'))
            ];
            $this->model_community->update($id_community, $data);

            return $this->respond(response_update());
        } else {
            return $this->failNotFound();
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
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
}
