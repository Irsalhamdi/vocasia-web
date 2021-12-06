<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Category extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $data_catgeory = $this->model_category->get_category();
        foreach ($data_catgeory as $category) {
            $data[] = [
            "id" => $category['id'],
            "code_category" => $category['code_category'],
            "name_category" => $category['name_category'],
            "parent_category" => $category['parent_category'],
            "slug_category" => $category['slug_category'],
            "font_awesome_class" => $category['font_awesome_class'],
            "thumbnail" => $this->model_category->get_thumbnail($category['id']),
            "create_at" => $category['create_at'],
            "update_at" => $category['update_at']
            ];
        }
        return $this->respond(get_response($data));
    }

    public function show($params = null)
    {
        $data_category = $this->model_category->get_category($params);
        $data = [
            "id" => $data_category['id'],
            "code_category" => $data_category['code_category'],
            "name_category" => $data_category['name_category'],
            "parent_category" => $data_category['parent_category'],
            "slug_category" => $data_category['slug_category'],
            "font_awesome_class" => $data_category['font_awesome_class'],
            "thumbnail" => $this->model_category->get_thumbnail($data_category['id']),
            "create_at" => $data_category['create_at'],
            "update_at" => $data_category['update_at']
        ];
        if ($data_category) {
            return $this->respond(get_response($data));
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

    public function thumbnail($params = null)
    {
        $data_category = $this->model_category->find($params);
        $rules = [
            'thumbnail' => 'max_size[thumbnail,2048]|is_image[thumbnail]'
        ];
        if (!$this->validate($rules)) {
            return $this->fail('Failed To Upload Image Please Try Again');
        } else {
            if ($data_category) {
                $path = "uploads/category_thumbnail";
                if (!file_exists($path)) {
                    mkdir($path);
                }
                $thumbnail = $this->request->getFile('thumbnail');
                $name = "category_thumbnail_default_$params.jpg";
                $path_photo = $path . '/' . $name;
                if (file_exists($path_photo)) {
                    unlink('uploads/category_thumbnail/' . $name);
                }
                $thumbnail->move('uploads/category_thumbnail/', $name);
                // $this->model_category->update($params, $data);

                return $this->respondCreated(response_create());
            } else {
                return $this->failNotFound();
            }
        }
    }
}
