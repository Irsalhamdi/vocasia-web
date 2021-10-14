<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Home extends FrontendController
{
    protected $format = 'json';

    public function get_all_courses()
    {
        if ($this->request->getVar('category')) {
            $slug_category = $this->request->getVar('category');
            $course_by_category = $this->model_course->get_course_by_category($slug_category);
            // for ($i = 0; $i <= $course_by_category['total']; $i++) {
            //     $get_duration = $this->model_course->get_duration($course_by_category['data_course']['id']);
            //     var_dump($get_duration);
            //     die;
            // }
            return $this->respond(get_response($course_by_category['data_course']));
        }
        $list_course = $this->model_course->home_page_course();
        return $this->respond(get_response($list_course));
    }

    public function get_all_category()
    {
        $list_category = $this->model_category->list_category_home();
        return $this->respond(get_response($list_category));
    }

    public function chart_list()
    {
        $id_user = $this->request->getVar('users');
        if (!is_null($id_user)) {
            $item_wishlist = $this->model_wishlist->get_user_wishlist($id_user);
            return $this->respond(get_response($item_wishlist));
        } else {
            return $this->respond([
                'status' => 200,
                'error' => false,
                'data' => [
                    'messages' => 'Wishlist Empty !'
                ]
            ]);
        }
    }
    public function add_to_chart()
    {
        try {
            $wishlist_item = $this->request->getJSON();
            $this->model_wishlist->insert($wishlist_item);
            return $this->respondCreated([
                'status' => 201,
                'error' => false,
                'data' => [
                    'messages' => 'Wishlist Success Added !'
                ]
            ]);
        } catch (\Throwable $th) {
            return $this->failNotFound();
        }
    }

    public function delete_chart($id_chart)
    {
        try {
            $this->model_wishlist->delete($id_chart);
            return $this->respondDeleted([
                'status' => 200,
                'error' => false,
                'data' => [
                    'messages' => 'chart deleted !'
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function users_detail($id_user)
    {
        $user_detail = $this->model_users->get_detail_users($id_user);
        return $this->respond(get_response($user_detail));
    }
}
