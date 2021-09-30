<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Affiliate extends BackendController
{
    protected $format = 'json';

    public function index()
    {
        $affiliate_list = $this->model_affiliate->get_list_affiliate();

        return $this->respond(get_response($affiliate_list));
    }

    public function show_detail($id = null)
    {
        $affiliate_exist = $this->model_affiliate->get_list_affiliate($id);
        if ($affiliate_exist) {
            // affiliate exists
            return $this->respond(get_response($affiliate_exist));
        } else {
            //affiliate not exists
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $user_exists = $this->model_users->find($this->request->getVar("user_id"));
        if (!empty($user_exists)) {
            // user exists
            $affiliate_data = $this->request->getJSON();
            if ($affiliate_data) {
                // success to create
                $this->model_affiliate->protect(false)->insert($affiliate_data);
                return $this->respondCreated(response_create());
            } else {
                // failed to create
                return $this->respond(response_failed());
            }      
        } else {
            // user no exist
            return $this->failNotFound();
        }
    }
    
    public function update($id = null)
    {
        $affiliate_exist = $this->model_affiliate->get_list_affiliate($id);
        if ($affiliate_exist) {
            $user_exists = $this->model_users->find($this->request->getVar("user_id"));
            if (!empty($user_exists)) {
                // user exists
                $affiliate_data = $this->request->getJSON();
                if ($affiliate_data) {
                    // success to create
                    $this->model_affiliate->protect(false)->update($id, $affiliate_data);
                    return $this->respondCreated(response_update());
                } else {
                    // failed to create
                    return $this->respond(response_failed());
                }      
            } else {
                // user no exist
                return $this->failNotFound();
            }
        } else {
            //affiliate not exists
            return $this->failNotFound();
        }
    }

    public function delete($id = null)
    {
        $affiliate_exist = $this->model_affiliate->get_list_affiliate($id);
        if ($affiliate_exist) {
            // affiliate exists
            $this->model_affiliate->delete($id);
            return $this->respond(response_delete());
        } else {
            // affiliate not exists
            return $this->failNotFound();
        }
    }
}