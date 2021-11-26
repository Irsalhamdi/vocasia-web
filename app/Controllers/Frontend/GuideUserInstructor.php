<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class GuideUserInstructor extends FrontendController
{
    protected $format = 'json';
    public function index()
    {
        $guide_user = $this->model_guide_user->get_guide_user();
        return $this->respond(get_response($guide_user));
    }

    public function show($id = null)
    {
        $guide_user = $this->model_guide_user->get_guide_user($id);
        if ($guide_user) {
            return $this->respond(get_response($guide_user));
        }else {
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $rules = $this->model_guide_user->validationRules;
        $id_user = $this->request->getVar('user_id');
        $data_users = $this->model_users->find($id_user);
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Create Please Try Again");
        } else {
            if ($data_users) {
                $data_guide_user = $this->request->getJSON();
                $this->model_guide_user->protect(false)->insert($data_guide_user);

                $data = ['is_instructor' => '1'];
                $user_detail = $this->model_users_detail->find($id_user);
                $this->model_users_detail->protect(false)->update($user_detail['id'], $data);
                return $this->respondCreated(response_create());
            }else {
                return $this->failNotFound();
            }
        }
    }

    public function update($id = null)
    {
        $data_guide_user = $this->model_guide_user->find($id);
        $user = $this->model_users->find($this->request->getVar('user_id'));
        $rules = $this->model_guide_user->validationRules;
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Update Please Try Again");
        } elseif($user){
            $data_guide_user = $this->request->getJSON();
    
            $this->model_guide_user->protect(false)->update($id, $data_guide_user);
            return $this->respondUpdated(response_update());
        }else {
            return $this->failNotFound();
        }
    }

    public function  delete($id = null)
    {
        $data_guide_user = $this->model_guide_user->find($id);

        if ($data_guide_user) {
            $this->model_guide_user->delete($id);
            return $this->respondDeleted(response_delete());
        }else {
            return $this->failNotFound();
        }
    }
}