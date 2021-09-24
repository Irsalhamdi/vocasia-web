<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Category extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $data = $this->category->get_category();
        return $this->respond([
            'status' => 200,
            'messages' => 'success',
            'data' => $data,
        ]);
    }

    public function show($id = null)
    {
        $data = $this->category->get_category($id);

        if ($data) {
            return $this->respond([
                'status' => 200,
                'messages' => 'success',
                'data' => $data,
            ]);
        }else{
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $rules = $this->category->validationRules;
        if (!$this->validate($rules)) {
            return $this->fail("Record fail to insert");
        }else {
            $slug = url_title($this->request->getvar('name_category'),'_',true);
        $data = [
            'code_category' => $this->request->getVar('code_category'),
            'name_category' => $this->request->getVar('name_category'),
            'parent_category' => $this->request->getVar('parent_category'),
            'slug_category' => $slug,
            'font_awesome_class' => $this->request->getVar('font_awesome_class'),
            'thumbnail' => base64_encode($this->request->getVar('thumbnail')),
        ];

        $this->category->save($data);

        return $this->respondCreated([
            'status' => 200,
            'messages' => 'Created success',
            'data' => $data,
        ]);
        }

    }

    public function update($id = null)
    {
        $data_by_id = $this->category->find($id);
        $name_category = $this->request->getVar('name_category');
        $rules = $this->category->validationRules;
        
        if (!$this->validate($rules)) {
            return $this->fail("Record fail to update");
        }elseif ($data_by_id) {         
            $slug = url_title($this->request->getVar('name_category'),'_',true);
            $data = [
                'code_category' => $this->request->getVar('code_category'),
                'name_category' => $name_category,
                'parent_category' => $this->request->getVar('parent_category'),
                'slug_category' => $slug,
                'font_awesome_class' => $this->request->getVar('font_awesome_class'),
                'thumbnail' => base64_encode($this->request->getVar('thumbnail')),
            ];
    
            $this->category->update($id, $data);
            return $this->respondCreated([
                'status' => 200,
                'messages' => 'Updated success',
                'data' => $data,
            ]);
            
        }else {
            return $this->failNotFound();
        }
        
    }

    public function delete($id = null)
    {
        $data = $this->category->find($id);
		if ($data) {
			$this->category->delete($id);

			return $this->respondDeleted([
                'status' => 200,
                'message' => 'Deleted success',
                'data' => $data,
            ]);
		} else {
			return $this->failNotFound();
		}
    }
}