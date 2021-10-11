<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Coupon extends BackendController
{
    protected $format = 'json';
    public function index()
    {
        if (!is_null($this->request->getVar('page')) && !is_null($this->request->getVar('limit'))) {
            $page = $this->request->getVar('page');
            $limit = $this->request->getVar('limit');
            $pagging = $this->pagging($page, $limit);
            return $this->respond(response_pagging($pagging['total_page'], $pagging['data']));
        }
        $coupon_list = $this->model_coupon->get_list_coupon();
        return $this->respond(get_response($coupon_list));
    }
    public function create()
    {
        $user_exists = $this->model_users->find($this->request->getVar("user_id"));
        if (!empty($user_exists)) {
            // user exists
            $coupon_data = $this->request->getJSON();
            if ($coupon_data) {
                // success to create
                $this->model_coupon->insert($coupon_data);
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
    public function show_detail($id_coupon = null)
    {
        $coupon_exist = $this->model_coupon->get_list_coupon($id_coupon);
        if ($coupon_exist) {
            // coupon exists
            return $this->respond(get_response($coupon_exist));
        } else {
            //coupon not exists
            return $this->failNotFound();
        }
    }
    public function update($coupon_id = null)
    {
        $coupon_exists = $this->model_coupon->find($coupon_id);
        if (!empty($coupon_exists)) {
            // coupon exists
            $user_exists = $this->model_users->find($this->request->getPost('user_id'));
            if ($user_exists) {
                // user exists
                $course_exists = $this->model_course->find($this->request->getPost('course_id'));
                if ($course_exists) {
                    // course exists
                    $coupon_update = $this->request->getJSON();
                    if ($coupon_update) {
                        // success to update
                        $this->model_coupon->update($coupon_id, $coupon_update);
                        return $this->respondUpdated(response_update());
                    } else {
                        // failed to update
                        return $this->respond(response_failed());
                    }
                } else {
                    // course not exists
                    return $this->failNotFound();
                }
            } else {
                // user not exists
                return $this->failNotFound();
            }
        } else {
            // coupon not exists
            return $this->failNotFound();
        }
    }
    public function delete($coupon_id = null)
    {
        $coupon_exist = $this->model_coupon->find($coupon_id);
        if ($coupon_exist) {
            // coupon exists
            $this->model_coupon->delete($coupon_id);
            return $this->respond(response_delete());
        } else {
            // coupon not exists
            return $this->failNotFound();
        }
    }

    public function prakerja_coupons()
    {
        $this->model_coupon->get_coupons_prakerja();
    }

    public function pagging($page, $offset)
    {
        $start_index = ($page > 1) ? ($page * $offset) - $offset : 0;
        $count_data = $this->model_coupon->get_count_coupon();
        $total_pages = ceil($count_data / $offset);
        $get_pagging_data = $this->model_coupon->get_pagging_data($offset, $start_index);
        $return_data = [
            'total_page' => $total_pages,
            'data' => $get_pagging_data
        ];
        return $return_data;
    }
}
