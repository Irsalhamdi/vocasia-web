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
            $data = array();
            foreach ($course_by_category as $key => $cbc) {

                $lesson = $this->model_course->get_lesson_duration($cbc['id']);
                $total_students = $this->model_enrol->get_count_enrols_courses($cbc['id']);
                $rating_review = $this->model_course->get_rating_courses($cbc['id']);
                $duration = $this->get_duration($lesson);
                $data[$key] = [
                    "title" =>  $cbc['title'],
                    "short_description" => $cbc['short_description'],
                    "price" => $cbc['price'],
                    "instructor_name" => $cbc['instructor_name'],
                    "discount_flag" => $cbc['discount_flag'],
                    "discount_price" => $cbc['discount_price'],
                    "thumbnail" => $this->model_course->get_thumbnail($cbc['id']),
                    "level_course" => $cbc['level_course'],
                    "total_lesson" => $cbc['total_lesson'],
                    "duration" => $duration,
                    "students" => $total_students,
                    "rating" => $rating_review

                ];
            }
        } else {
            $course = $this->model_course->home_page_course();
            foreach ($course as $key => $all_course) {
                $total_students = $this->model_enrol->get_count_enrols_courses($all_course['id']);
                $rating_review = $this->model_course->get_rating_courses($all_course['id']);
                $get_discount_percent = ($all_course['dicount_price'] / $all_course['price']) * 100;
                $discount = intval($get_discount_percent);
                $data[$key] = [
                    "title" =>  $all_course['title'],
                    "price" => $all_course['price'],
                    "instructor_name" => $all_course['instructor_name'],
                    "discount_flag" => $all_course['discount_flag'],
                    "discount_price" => $all_course['discount_price'],
                    "thumbnail" => $this->model_course->get_thumbnail($all_course['id']),
                    "students" => $total_students,
                    "rating" => $rating_review,
                    "total_discount" => $discount

                ];
            }
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

    public function filter()
    {
        $filter = array();
        if ($this->request->getVar('category')) { //get berdasarkan category
            if ($this->request->getVar('price')) { //category -> price
                if ($this->request->getVar('level')) { // cateogry -> price ->
                    if ($this->request->getVar('language')) {
                        if ($this->request->getVar('rating')) {
                            $filter = [
                                'a.is_free_course' => $this->request->getVar('price'),
                                'a.category_id' => $this->request->getVar('category'),
                                'a.level_course' => $this->request->getVar('level'),
                                'a.language' => $this->request->getVar('language'),
                                'd.rating' => $this->request->getVar('rating')

                            ];
                            $data_filter_rating = $this->model_course->get_rating_from_filter($filter);
                            $data = $this->course_data($data_filter_rating);
                            return $this->respond(get_response($data));
                        } else {
                            $filter = [
                                'a.is_free_course' => $this->request->getVar('price'),
                                'a.category_id' => $this->request->getVar('category'),
                                'a.level_course' => $this->request->getVar('level'),
                                'a.language' => $this->request->getVar('language')

                            ];
                            var_dump($filter);
                            die;
                        }
                    } else {
                        $filter = [
                            'a.is_free_course' => $this->request->getVar('price'),
                            'a.category_id' => $this->request->getVar('category'),
                            'a.level_course' => $this->request->getVar('level')

                        ];
                    }
                } else {
                    $filter = [
                        'a.is_free_course' => $this->request->getVar('price'),
                        'a.category_id' => $this->request->getVar('category')

                    ];
                }
            } else {
                $filter = [
                    'a.category_id' => $this->request->getVar('category')
                ];
            }
        } else if ($this->request->getVar('price')) {
            $filter = [
                'a.is_free_course' => $this->request->getVar('price')
            ];
        } else if ($this->request->getVar('level')) {
            $filter = [
                'a.level_course' => $this->request->getVar('level')
            ];
        } else if ($this->request->getVar('language')) {
            $filter = [
                'a.language' => $this->request->getVar('language')
            ];
        } else if ($this->request->getVar('rating')) {
            $filter = [
                'rating' => $this->request->getVar('rating')
            ];
            $data_filter_rating = $this->model_course->get_rating_from_filter($filter);
            $data = $this->course_data($data_filter_rating);
            return $this->respond(get_response($data));
        }
        $data_filter = $this->model_course->advanced_filter($filter);
        $data = $this->course_data($data_filter);
        if (!is_null($data)) {

            return $this->respond(get_response($data));
        } else {
            return $this->failNotFound();
        }
    }

    public function course_data($course_data)
    {
        $data = NULL;
        try {
            foreach ($course_data as $key => $cd) {

                $lesson = $this->model_course->get_lesson_duration($cd['id']);
                $total_students = $this->model_enrol->get_count_enrols_courses($cd['id']);
                $rating_review = $this->model_course->get_rating_courses($cd['id']);
                $duration = $this->get_duration($lesson);
                $get_discount_percent = ($cd['dicount_price'] / $cd['price']) * 100;
                $discount = intval($get_discount_percent);
                $data[$key] = [
                    "title" =>  $cd['title'],
                    "short_description" => $cd['short_description'],
                    "price" => $cd['price'],
                    "instructor_name" => $cd['instructor_name'],
                    "discount_flag" => $cd['discount_flag'],
                    "discount_price" => $cd['discount_price'],
                    "thumbnail" => $this->model_course->get_thumbnail($cd['id']),
                    "level_course" => $cd['level_course'],
                    "total_lesson" => $cd['total_lesson'],
                    "language" => $cd['language'],
                    "duration" => $duration,
                    "students" => $total_students,
                    "rating" => $rating_review,
                    "total_discount" => $discount

                ];
            }
            return $data;
        } catch (\Throwable $th) {
            return $this->failNotFound('Data Not Found !');
        }
    }
    public function detail_courses($id_course)
    {
        $data = array();
        $data_course = $this->model_course->detail_course_for_homepage($id_course);
        foreach ($data_course as $key => $courses) {
            $total_duration_by_course_id = $this->model_course->get_lesson_duration(['course_id' => $courses->id]);
            $total_duration = $this->get_duration($total_duration_by_course_id);
            $total_students = $this->model_enrol->get_count_enrols_courses($courses->id);
            $get_discount_percent = ($courses->discount_price / $courses->price) * 100;
            $discount = intval($get_discount_percent);
            $data[$key] = [
                'id' => $courses->id,
                'instructor' => $courses->instructor_name,
                'level_course' => $courses->level_course,
                'total_lesson' => $courses->total_lesson,
                'total_students' => $total_students,
                'description' => $courses->description,
                'outcome' => $courses->outcomes,
                'requirement' => $courses->requirement,
                'price' => $courses->price,
                'discount_price' => $courses->discount_price,
                'video_url' => $courses->video_url,
                'total_duration' => $total_duration,
                'bio' => $this->model_course->get_bio_instructor(['id_user' => $courses->uid, 'bio_status' => $courses->bio_status, 'bio_instructor' => $courses->bio_instructor]),
                'rating' => $this->model_course->get_rating_courses($courses->id),
                'total_discount' => $discount
            ];
        }
        return $this->respond(get_response($data));
    }

    public function get_instructor_student($instructor_id)
    {
        $data = $this->model_course->detail_intructor_by_courses($instructor_id);

        return $this->respond(get_response($data));
    }

    public function get_sections_duration($course_id)
    {
        $arr_data = array();
        $data = $this->model_course->get_section_duration($course_id);
        foreach ($data as $key => $section) {
            $string_to_array_section = preg_split('/[[,\]]/', $section);
            for ($i = 0; $i < count($string_to_array_section); $i++) {
                if ($string_to_array_section[$i] == null) {
                    continue;
                }
                $duration_by_section = $this->model_course->get_lesson_duration(['section' => $string_to_array_section[$i]]);
                $total_duration_section = $this->get_duration($duration_by_section);
                $arr_data[] = $total_duration_section;
            }
        }
        return $arr_data;
    }

    public function get_sections($id_course)
    {
        $data = array();
        $total_duration = $this->get_sections_duration($id_course);
        $section_title = $this->model_course->get_section_title($id_course);
        foreach ($section_title as $key => $st) {
            $course_by_section  = $this->model_course->lesson_title_from_section($st->id);
            $data[$key] = [
                'title' => $st->title,
                'duration' => !empty($total_duration[$key]) ? $total_duration[$key] : null,
                'data_lesson' => [
                    $course_by_section
                ]
            ];
        }
        return $this->respond(get_response($data));
    }
    public function get_rating($course_id)
    {
        if ($this->request->getVar('star')) {
            $star = $this->request->getVar('star');
            $data_rating = $this->model_course->get_rating_by_star($course_id, $star);
            return $this->respond(get_response($data_rating));
        }
        $data_rating = $this->model_course->get_rating_course($course_id);
        return $this->respond($data_rating);
    }
}
