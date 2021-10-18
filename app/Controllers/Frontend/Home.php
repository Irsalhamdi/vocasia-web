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
            foreach ($course_by_category as $cbc) {

                $lesson = $this->model_course->get_lesson_duration($cbc['id']);
                $duration = $this->get_duration($lesson);
                $data = [
                    "title" =>  $cbc['title'],
                    "short_description" => $cbc['short_description'],
                    "price" => $cbc['price'],
                    "instructor_name" => $cbc['instructor_name'],
                    "discount_flag" => $cbc['discount_flag'],
                    "discount_price" => $cbc['discount_price'],
                    "thumbnail" => $cbc['thumbnail'],
                    "level_course" => $cbc['level_course'],
                    "total_lesson" => $cbc['total_lesson'],
                    "duration" => $duration

                ];
            }
        } else {
            $data = $this->model_course->home_page_course();
        }
        return $this->respond(get_response($data));
    }

    public function get_all_category()
    {
        $list_category = $this->model_category->list_category_home();
        return $this->respond(get_response($list_category));
    }

    public function wishlist()
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
    public function add_to_wishlist()
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

    public function delete_wishlist($id_chart)
    {
        try {
            $this->model_wishlist->delete($id_chart);
            return $this->respondDeleted([
                'status' => 200,
                'error' => false,
                'data' => [
                    'messages' => 'wishlist deleted !'
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cart_list($id_user)
    {
        $cart_items = $this->model_cart->cart_item_list($id_user);
        $total_payment = $this->model_cart->get_total_payment_cart($id_user);
        $response_data = [
            'cart-items' => $cart_items,
            'total_payment' => $total_payment
        ];
        return $this->respond(get_response($response_data));
    }

    public function add_to_cart()
    {
        $data_cart = $this->request->getJSON();
        $find_price_course = $this->model_course->get_prices_for_cart($data_cart->cart_item);
        $price = $find_price_course->discount_flag != 0 ? $find_price_course->discount_price : $find_price_course->price;
        if (!is_null($find_price_course)) {
            $this->model_cart->insert([
                'id_user' => $data_cart->id_user,
                'cart_item' => $data_cart->cart_item,
                'cart_price' => $price
            ]);
            return $this->respondCreated([
                'status' => 201,
                'error' => false,
                'data' => [
                    'messages' => 'cart added !'
                ]
            ]);
        } else {
            return $this->fail('data cannot to insert !');
        }
    }

    public function users_detail($id_user)
    {
        $user_detail = $this->model_users->get_detail_users($id_user);
        return $this->respond(get_response($user_detail));
    }

    public function get_duration($lesson_duration)
    {
        $total_duration = 0;
        foreach ($lesson_duration->getResult('array') as $ld) {
            if ($ld['lesson_type'] != 'other') {
                $time = explode(':', $ld['duration']);
                $hour_to_seconds = $time[0] * 60 * 60;
                $minute_to_seconds = $time[1] * 60;
                $seconds = $time[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }


        $hours = floor($total_duration / 3600);
        $minutes = floor(($total_duration % 3600) / 60);
        $seconds = $total_duration % 60;

        $h = $hours > 0 ? $hours . ($hours == 1 ? "j" : "j") : "";
        $m = $minutes > 0 ? $minutes . ($minutes == 1 ? "m" : "m") : "";
        $s = $seconds > 0 ? $seconds . ($seconds == 1 ? "d" : "d") : "";

        return $h . $m . $s;
    }
}
