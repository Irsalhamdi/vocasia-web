<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Comment extends FrontendController
{
    protected $format = 'json';

    public function show($id_course = null)
    {
        $comment = $this->model_comment->get_comment_by_course($id_course);
        if ($comment) {
            foreach ($comment as $c) {
            $data[] = [
            "commentable_id" => $c->commentable_id,
            "title" => $c->title,
            "commentable_type" => $c->commentable_type,
            "body" => $c->body,
            "user_id" => $c->user_id,
            "user" => $c->user,
            "foto_profil" => $this->model_users_detail->get_profile_users($c->user_id),
            "id_comment" => $c->id,
            "date_added" => $c->date_added,
            "last_modified" => $c->last_modified,
            ];
        }
            return $this->respond(get_response($data));
        }else {
            return $this->failNotFound();
        }
    }

    public function create_by_course($id_course = null)
    {
        $rules = $this->model_comment->validationRules;
        $user = $this->model_users->find($this->request->getVar('user_id'));
        $courses = $this->model_course->find($id_course);

        if (!$this->validate($rules)) {
            return $this->fail("Failed To Create Please Try Again");
        } else {
            if ($user && $courses) {
                $data_comment = $this->request->getJSON();
                $data_comment->commentable_id = $id_course;
    
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

        if($id){
            $data = $this->request->getJSON();
            $data->user_id = $data_comment['user_id'];
            $data->commentable_id = $data_comment['commentable_id'];
    
            $this->model_comment->protect(false)->update($id, $data);
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