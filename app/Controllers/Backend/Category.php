<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Category extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $category_data = $this->model_category->findAll();
        if ($category_data) {
            return $this->respond(get_response($category_data));
        } else {
            return $this->respond(response_failed());
        }
    }

    public function show_detail($params = null)
    {
        $category_data = $this->model_category->find($params);
        if ($category_data) {
            return $this->respond(get_response($category_data));
        } else {
            return $this->respond(response_failed());
        }
    }

    public function create()
    {
        $data = $this->request->getPost();
        if ($this->model_category->protect(false)->insert($data)) {
            return $this->respondCreated(response_create());
        } else {
            return $this->respond(response_failed());
        }
    }

    public function update($params = null)
    {
        $category_data = $this->model_category->find($params);

        if ($category_data) {

            $updated_data = $this->request->getPost();
            $this->model_category->protect(false)->update($params, $category_data);
            return $this->respondUpdated(response_update());
        } else {
            return $this->respond(response_failed());
        }
    }

    public function delete($params = null)
    {
        $category_data = $this->model_category->find($params);
        if (!empty($category_data)) {
            $this->model_category->delete($params);
            return $this->respondDeleted(response_delete());
        } else {
            return $this->respond(response_failed());
        }
    }
}
