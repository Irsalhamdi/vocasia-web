<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Category extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $data_catgeory = $this->model_category->get_category();
        return $this->respond(get_response($data_catgeory));
    }

    public function show($params = null)
    {
        $data_category = $this->model_category->get_category($params);

        if ($data_category) {
            return $this->respond(get_response($data_category));
        } else {
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $rules = $this->model_category->validationRules;
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Create Please Try Again");
        } else {
            $data_category = $this->request->getJSON();
            $slug_category = url_title($data_category->name_category);

            $data_category->slug_category = $slug_category;

            $this->model_category->protect(false)->insert($data_category);

            return $this->respondCreated(response_create());
        }
    }

    public function update($params = null)
    {
        $data_by_id = $this->model_category->find($params);
        $rules = $this->model_category->validationRules;

        if (!$this->validate($rules)) {
            return $this->fail("Failed To Update Please Try Again");
        } elseif ($data_by_id) {
            $data_category = $this->request->getJSON();
            $slug_category = url_title($data_category->name_category);
            
            $data_category->slug_category = $slug_category;

            $this->model_category->protect(false)->update($params, $data_category);
            return $this->respondCreated(response_update());
        } else {
            return $this->failNotFound();
        }
    }

    public function delete($params = null)
    {
        $data_category = $this->model_category->find($params);
        if ($data_category) {
            $this->model_category->delete($params);

            return $this->respondDeleted(response_delete());
        } else {
            return $this->failNotFound();
        }
    }
}
