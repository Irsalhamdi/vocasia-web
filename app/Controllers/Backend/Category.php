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
        }else{
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $rules = $this->model_category->validationRules;
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Create Please Try Again");
        }else {
            $slug = url_title($this->request->getvar('name_category'),'_',true);
        $data_category = [
            'code_category' => $this->request->getVar('code_category'),
            'name_category' => $this->request->getVar('name_category'),
            'parent_category' => $this->request->getVar('parent_category'),
            'slug_category' => $slug,
            'font_awesome_class' => $this->request->getVar('font_awesome_class'),
            'thumbnail' => base64_encode($this->request->getVar('thumbnail')),
        ];

        $this->model_category->save($data_category);

        return $this->respondCreated(response_create());
        }

    }

    public function update($params = null)
    {
        $data_by_id = $this->model_category->find($params);
        $name_category = $this->request->getVar('name_category');
        $rules = $this->model_category->validationRules;
        
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Update Please Try Again");
        }elseif ($data_by_id) {         
            $slug = url_title($this->request->getVar('name_category'),'_',true);
            $data_category = [
                'code_category' => $this->request->getVar('code_category'),
                'name_category' => $name_category,
                'parent_category' => $this->request->getVar('parent_category'),
                'slug_category' => $slug,
                'font_awesome_class' => $this->request->getVar('font_awesome_class'),
                'thumbnail' => base64_encode($this->request->getVar('thumbnail')),
            ];
    
            $this->model_category->update($params, $data_category);
            return $this->respondCreated(response_update());
            
        }else {
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