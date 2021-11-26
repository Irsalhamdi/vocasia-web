<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Comment extends FrontendController
{
    protected $format = 'json';
    public function index()
    {
        $comment = $this->model_comment->get_comment();
        return $this->respond(get_response($comment));
    }

    public function show($id = null)
    {
        $comment = $this->model_comment->get_comment($id);
        if ($comment) {
            return $this->respond(get_response($comment));
        }else {
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $rules = $this->model_comment->validationRules;
        $user = $this->model_users->find($this->request->getVar('user_id'));
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Create Please Try Again");
        } else {
            if ($user) {
                $data_comment = $this->request->getJSON();
    
                $this->model_comment->protect(false)->insert($data_comment);
                return $this->respondCreated(response_create());
            }else {
                return $this->failNotFound();
            }
        }
    }

    public function update($id = null)
    {
        $data_comment = $this->model_comment->find($id);
        $user = $this->model_users->find($this->request->getVar('user_id'));
        $rules = $this->model_comment->validationRules;
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Update Please Try Again");
        } elseif($user){
            $data_comment = $this->request->getJSON();
    
            $this->model_comment->protect(false)->update($id, $data_comment);
            return $this->respondUpdated(response_update());
        }else {
            return $this->failNotFound();
        }
    }

    public function  delete($id = null)
    {
        $data_comment = $this->model_comment->find($id);

        if ($data_comment) {
            $this->model_comment->delete($id);
            return $this->respondDeleted(response_delete());
        }else {
            return $this->failNotFound();
        }
    }
}