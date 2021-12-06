<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;
use CodeIgniter\I18n\Time;

class Home extends FrontendController
{
    protected $format = 'json';

    public function detail_users_login()
    {
        $emai = $this->request->getVar('email');
        $data_user = $this->model_users->where('email', $emai)->first();
        $data_response = [
            'id_user' => $data_user['id'],
            'fullname' => $data_user['first_name'] . ' ' . $data_user['last_name'],
            'email' => $data_user['email'],
            'foto_profile' => $this->model_users->get_foto_profile($data_user['id']),
            'is_instructor' => $this->model_users_detail->is_instructor_user($data_user['id']),
        ];
        return $this->respond(get_response($data_response));
    }

    public function get_all_courses()
    {
        if ($this->request->getVar('category')) {
            $slug_category = $this->request->getVar('category');
            $course_by_category = $this->model_course->get_course_by_category($slug_category);
            $data = array();
            foreach ($course_by_category as $key => $cbc) {

                $lesson = $this->model_course->get_lesson_duration(['course_id' => $cbc['id']]);
                $total_students = $this->model_enrol->get_count_enrols_courses($cbc['id']);
                $rating_review = $this->model_course->get_rating_courses($cbc['id']);
                if ($cbc['discount_price'] != 0) {
                    $get_discount_percent = ($cbc['discount_price'] / $cbc['price']) * 100;
                } elseif ($cbc['discount_price'] == 0) {
                    $get_discount_percent = 0;
                }
                $duration = $this->get_duration($lesson);
                $data[$key] = [
                    "id_course" => $cbc['id'],
                    "instructor_id" => $cbc["instructor_id"],
                    "title" =>  $cbc['title'],
                    "short_description" => strip_tags($cbc['short_description']),
                    "price" => $cbc['price'],
                    "instructor_name" => $cbc['first_name'] . ' ' . $cbc['last_name'],
                    "discount_flag" => $cbc['discount_flag'],
                    "discount_price" => $cbc['discount_price'],
                    "thumbnail" => $this->model_course->get_thumbnail($cbc['id']),
                    "level_course" => $cbc['level_course'],
                    "total_lesson" => $cbc['total_lesson'],
                    "duration" => $duration,
                    "students" => $total_students,
                    "rating" => $rating_review,
                    "total_discount" => intval($get_discount_percent)

                ];
            }
        } else {
            $course = $this->model_course->home_page_course();
            foreach ($course as $key => $all_course) {
                $total_students = $this->model_enrol->get_count_enrols_courses($all_course['id']);
                $rating_review = $this->model_course->get_rating_courses($all_course['id']);
                if ($all_course['discount_price'] != 0) {
                    $get_discount_percent = ($all_course['discount_price'] / $all_course['price']) * 100;
                } elseif ($all_course['discount_price'] == 0) {
                    $get_discount_percent = 0;
                }
                $discount = intval($get_discount_percent);
                $data[$key] = [
                    "id_course" => $all_course['id'],
                    "instructor_id" => $all_course["instructor_id"],
                    "title" =>  $all_course['title'],
                    "price" => $all_course['price'],
                    "instructor_name" => $all_course['first_name'] . ' ' . $all_course['last_name'],
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

    public function search_keyword_course()
    {
        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $course = $this->model_course->search_course($keyword);
            if (is_null($course)) {
                return $this->failNotFound();
            } else {
                $course_search_data = $this->course_data($course);
                foreach ($course_search_data as $course) {
                    $data[] = [
                        "title" => $course->title,
                        "short_description" => $course->short_description,
                        "price" => $course->price,
                        "instructor_name" => $course->first_name . ' ' . $course->last_name,
                        "discount_flag" => $course->discount_flag,
                        "discount_price" => $course->discount_price,
                        "thumbnail" => $course->thumbnail,
                        "level_course" => $course->level_course,
                        "total_lesson" => $course->total_lesson,
                        "id" => $course->id,
                        "instructor_id" => $course->instructor_id,
                        "language" => $course->language
                    ];
                }
                return $this->respond(get_response($data));
            }
        } else {
            return $this->failNotFound();
        }
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
            foreach ($item_wishlist as $wishlist) {
                if ($wishlist->discount_price != 0) {
                    $get_discount_percent = ($wishlist->discount_price / $wishlist->price) * 100;
                } elseif ($wishlist->discount_price == 0) {
                    $get_discount_percent = 0;
                }
                $data[] = [
                    "wishlist_id" => $wishlist->wishlist_id,
                    "title" => $wishlist->title,
                    "price" => $wishlist->price,
                    "instructor" => $wishlist->first_name . ' ' . $wishlist->last_name,
                    "thumbnail" => $this->model_course->get_thumbnail($wishlist->course_id),
                    "discount_price" => $wishlist->discount_price,
                    "discount_flag" => $wishlist->discount_flag,
                    "total_discount" => intval($get_discount_percent)
                ];
            }
            return $this->respond(get_response($data));
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

    public function delete_cart($id_cart)
    {
        $cart_data = $this->model_cart->find($id_cart);
        if (!is_null($cart_data)) {
            $this->model_cart->delete($id_cart);
            return $this->respondDeleted([

                'status' => 200,
                'error' => false,
                'data' => [
                    'messages' => 'cart deleted !'
                ]
            ]);
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
            $check_cart = $this->model_cart->where(['id_user' => $data_cart->id_user, 'cart_item' => $data_cart->cart_item])->first();
            if ($check_cart) {
                $this->model_cart->delete($check_cart['id']);
                return $this->respondDeleted([
                    'status' => 200,
                    'error' => false,
                    'data' => [
                        'messages' => 'cart delete !'
                    ]
                ]);
            }
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
        $social_user = $this->model_users_social_link->get_social_link($id_user);
        $data = [
            'id_user' => $user_detail->id,
            'fullname' => $user_detail->first_name . ' ' . $user_detail->last_name,
            'biography' => $user_detail->biography,
            'datebirth' => $user_detail->datebirth,
            'phone' => $user_detail->phone,
            'social_link' => !empty($social_user) ? $social_user : null,

        ];
        return $this->respond(get_response($data));
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

        if ($this->request->getVar()) {
            if ($this->request->getVar('category')) {
                $category = $this->request->getVar('category');
                $filter[0]["a.category_id"] = $category;
            }
            if ($this->request->getVar('price')) {
                $price = $this->request->getVar('price');
                $filter[0]["a.is_free_course"] = $price;
            }
            if ($this->request->getVar('level')) {
                $level = $this->request->getVar('level');
                $filter[0]["a.level_course"] = $level;
            }
            if ($this->request->getVar('language')) {
                $language = $this->request->getVar('language');
                $filter[0]["a.language"] = $language;
            }
            if ($this->request->getVar('rating')) {
                $rating = $this->request->getVar('rating');
                $filter[0]["d.rating"] = $rating;
            }
            if (empty($this->request->getVar('rating'))) {
                $data_filter = $this->model_course->advanced_filter($filter[0]);
                if (is_null($data_filter)) {
                    return $this->failNotFound('not found!');
                }
                foreach ($data_filter as $course) {
                    $data[] = [
                        "title" => $course->title,
                        "short_description" => $course->short_description,
                        "price" => $course->price,
                        "instructor_name" => $course->first_name . ' ' . $course->last_name,
                        "discount_flag" => $course->discount_flag,
                        "discount_price" => $course->discount_price,
                        "thumbnail" => $course->thumbnail,
                        "level_course" => $course->level_course,
                        "total_lesson" => $course->total_lesson,
                        "id" => $course->id,
                        "instructor_id" => $course->instructor_id,
                        "language" => $course->language
                    ];
                }
                $data_response = $this->course_data($data);
                return $this->respond(get_response($data_response));
            } else {
                $data_filter_rating = $this->model_course->get_rating_from_filter($filter[0]);
                if (is_null($data_filter_rating)) {
                    return $this->failNotFound('not found!');
                }
                foreach ($data_filter_rating as $course) {
                    $data[] = [
                        "title" => $course->title,
                        "short_description" => $course->short_description,
                        "price" => $course->price,
                        "instructor_name" => $course->first_name . ' ' . $course->last_name,
                        "discount_flag" => $course->discount_flag,
                        "discount_price" => $course->discount_price,
                        "thumbnail" => $course->thumbnail,
                        "level_course" => $course->level_course,
                        "total_lesson" => $course->total_lesson,
                        "id" => $course->id,
                        "instructor_id" => $course->instructor_id,
                        "language" => $course->language
                    ];
                }
                $data_response = $this->course_data($data);
                return $this->respond(get_response($data_response));
            }
        } else {
            return $this->failNotFound('not found !');
        }
    }

    public function course_data($course_data)
    {
        $data = array();
        foreach ($course_data as $key => $cd) {
            $lesson = $this->model_course->get_lesson_duration(['course_id' => $cd['id']]);
            $total_students = $this->model_enrol->get_count_enrols_courses($cd['id']);
            $rating_review = $this->model_course->get_rating_courses($cd['id']);
            $duration = $this->get_duration($lesson);
            if ($cd['discount_price'] != 0) {
                $get_discount_percent = ($cd['discount_price'] / $cd['price']) * 100;
            } elseif ($cd['discount_price'] == 0) {
                $get_discount_percent = 0;
            }
            $discount = intval($get_discount_percent);
            $data[$key] = [
                "id_course" => $cd['id'],
                "instructor_id" => $cd["instructor_id"],
                "title" =>  $cd['title'],
                "short_description" => strip_tags($cd['short_description']),
                "price" => $cd['price'],
                "instructor_name" => $cd['first_name'] . ' ' . $cd['last_name'],
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
                'title' => $courses->title,
                'instructor_id' => $courses->uid,
                'instructor' => $courses->first_name . ' ' . $courses->last_name,
                'level_course' => $courses->level_course,
                'total_lesson' => $courses->total_lesson,
                'total_students' => $total_students,
                'description' => strip_tags($courses->description),
                'outcome' => $courses->outcomes,
                'requirement' => $courses->requirement,
                'price' => $courses->price,
                'discount_price' => $courses->discount_price,
                'video_url' => $courses->video_url,
                'total_duration' => $total_duration,
                'bio' => strip_tags($this->model_course->get_bio_instructor(['id_user' => $courses->uid, 'bio_status' => $courses->bio_status, 'bio_instructor' => $courses->bio_instructor])),
                'rating' => $this->model_course->get_rating_courses($courses->id),
                'total_discount' => $discount,
                'last_modified' => !is_null($courses->update_at) ? $this->_generate_humanize_timestamps($courses->update_at) : $this->_generate_humanize_timestamps($courses->create_at)
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
            foreach ($data_rating as $r) {
                $data[] = [
                    'user' => $r->first_name . ' ' . $r->last_name,
                    'review' => $r->review,
                    'rating' => $r->rating,
                ];
            }
            return $this->respond(get_response($data_rating));
        }
        $data_rating = $this->model_course->get_rating_course($course_id);
        return $this->respond($data_rating);
    }
    public function user_profile($id = null)
    {
        $user_id = $this->model_users->find($id);

        $rules = [
            'first_name' => [
                'rules' => 'required'
            ],
            'biography' => [
                'rules' => 'required'
            ],
            'phone' => [
                'rules' => 'required'
            ],
            'phone' => [
                'rules' => 'required'
            ],
            'datebirth' => [
                'rules' => 'required'
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 403,
                'error' => false,
                'data' => [
                    'message' => $this->validator->getErrors()
                ]
            ], 403);
        } else {
            if (!empty($user_id)) {

                $update = $this->request->getJSON();
                // var_dump($update);
                // die;
                $this->model_users->update($id, $update);

                $user_detail = $this->model_users_detail->where('id_user', $id)->first();

                if ($user_detail) {
                    $user['id_user'] = $id;
                    $user['biography'] = $update->biography;
                    $user['phone'] = $update->phone;
                    $user['datebirth'] = $update->datebirth;
                    $this->model_users_detail->where('id_user', $id)->set($user)->update();

                    if (!empty($update->social_link)) {
                        $user_social_link = $this->model_users_social_link->where('id_user', $id)->first();

                        if ($user_social_link) {
                            $user['id_user'] = $id;
                            $user['facebook'] = !empty($update->social_link->facebook) ? $update->social_link->facebook : $user_social_link['facebook'];
                            $user['instagram'] = !empty($update->social_link->instagram) ? $update->social_link->instagram : $user_social_link['instagram'];
                            $user['twitter'] = !empty($update->social_link->twitter) ? $update->social_link->twitter : $user_social_link['twitter'];
                            $this->model_users_social_link->where('id_user', $id)->set($user)->update();
                        } else {
                            $user['id_user'] = $id;
                            $user['facebook'] = !empty($update->social_link->facebook) ? $update->social_link->facebook : null;
                            $user['instagram'] = !empty($update->social_link->instagram) ? $update->social_link->instagram : null;
                            $user['twitter'] = !empty($update->social_link->twitter) ? $update->social_link->twitter : null;
                            $this->model_users_social_link->save($user);
                        }
                    }
                } else {
                    $user['id_user'] = $id;
                    $user['biography'] = $update->biography;
                    $user['phone'] = $update->phone;
                    $this->model_users_detail->save($user);
                }

                return $this->respondUpdated(response_update());
            } else {
                return $this->failNotFound();
            }
        }
    }
    public function user_credentials($id = null)
    {
        $user = $this->model_users->find($id);

        $rules = [
            'email' => [
                'rules' => 'required|valid_email'
            ],
            'old_password' => [
                'rules' => 'required|min_length[6]'
            ],
            'password' => [
                'rules' => 'required|min_length[6]'
            ],
            'new_password_confirm' => [
                'rules' => 'required|matches[password]'
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 403,
                'error' => false,
                'data' => [
                    'message' => $this->validator->getErrors()
                ]
            ], 403);
        } else {
            $update = $this->request->getJSON();
            $data['email'] = $update->email;
            $data['old_password'] = sha1($update->old_password);
            $data['password'] = sha1($update->password);
            $data['new_password_confirm'] = sha1($update->new_password_confirm);

            if ($data['old_password'] !== $user['password']) {
                return $this->respond([
                    'status' => 403,
                    'error' => false,
                    'data' => [
                        'message' => 'Wrong current password'
                    ]
                ], 403);
            } else {
                if ($data['new_password_confirm'] === $user['password']) {
                    return $this->respond([
                        'status' => 403,
                        'error' => false,
                        'data' => [
                            'message' => 'New Password cannot be the same as current password'
                        ]
                    ], 403);
                } else {
                    $this->model_users->update($id, $data);
                    return $this->respondUpdated(response_update());
                }
            }
        }
    }
    public function user_photo($id = null)
    {
        $rules = [
            'foto_profile' => [
                'rules' => 'uploaded[foto_profile]|is_image[foto_profile]',
                'errors' => [
                    'uploaded' => 'foto_profile must be uploaded',
                    'is_image' => 'what you choose is not a picture',
                    'mime_in' => 'what you choose is not a picture'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 403,
                'error' => false,
                'data' => [
                    'message' => $this->validator->getErrors()
                ]
            ], 403);
        } else {
            $user = $this->model_users->find($id);
            $id_user = $user['id'];
            if ($user) {
                $foto_profile = $this->request->getFile('foto_profile');
                $name = "foto_profile_default_$id_user.jpg";
                $folder_path = 'uploads/foto_profile/' . $name;

                $data = [
                    'id' => $id,
                    'foto_profile'  => $name
                ];

                if (file_exists('uploads/foto_profile')) {
                    if (file_exists($folder_path)) {
                        unlink('uploads/foto_profile/' . $name);
                    }
                    $foto_profile->move('uploads/foto_profile/', $name);
                    // $this->model_users_detail->update($id, $data);
                    return $this->respondCreated(response_create());
                } else {
                    mkdir('uploads/foto_profile');
                    if (file_exists($name)) {
                        unlink('uploads/foto_profile/' . $name);
                    }
                    $foto_profile->move('uploads/foto_profile/', $name);
                    // $this->model_users_detail->update($id, $data);

                    return $this->respondCreated(response_create());
                }
            } else {
                return $this->failNotFound();
            }
        }
    }

    public function redeem_voucher()
    {
        $voucher = $this->request->getVar('voucher');
        $course_id = $this->request->getVar('course_id');
        $id_user = $this->request->getVar('user_id');
        $verify_voucher = $this->model_course->verify_redeem_voucher($voucher, $course_id);
        if (!is_null($verify_voucher)) {
            $this->model_cart->insert([
                'id_user' => $id_user,
                'cart_item' => $course_id,
                'cart_price' => $verify_voucher
            ]);
            return $this->respondCreated([
                'status' => 201,
                'error' => false,
                'data' => [
                    'messages' => 'voucher redeem !'
                ]
            ]);
        } else {
            return $this->failNotFound('Voucher invalid !');
        }
    }

    public function my_course($user_id)
    {
        $data = array();
        $my_course = $this->model_course->my_course($user_id);
        if (!empty($my_course)) {
            foreach ($my_course as $key => $values) {
                $data[$key] = [
                    'course_id' => $values->cid,
                    'instructor' => $values->first_name . ' ' . $values->last_name,
                    'title' => $values->title,
                    'thumbnail' => $this->model_course->get_thumbnail($values->cid),
                    'rating' => $this->model_course->rating_from_user($user_id, $values->cid)
                ];
            }
            return $this->respond(get_response($data));
        } else {
            return $this->failNotFound();
        }
    }

    public function course_payment()
    {
        $payment = $this->request->getJSON();
        $payment_insert = $this->model_payment->insert([
            'payment_id' => rand(0, 9999999999),
        ]);
        return $this->respondCreated(response_create());
    }

    public function my_lesson()
    {
        $course_id = $this->request->getVar('course');
        $user_id = $this->request->getVar('user');
        $where = [
            'course_id' => $course_id,
            'user_id' => $user_id
        ];
        $check_user = $this->model_enrol->where($where)->first();
        if (!is_null($check_user)) {
            $data_lesson = $this->model_course->get_my_lesson($course_id);
            return $this->respond(get_response($data_lesson));
        } else {
            return $this->failForbidden('Cannot Access This Course');
        }
    }

    public function watch_history()
    {
        $watch_history = $this->request->getJSON();
        $update_progress = $this->model_watch->insert($watch_history);
        return $this->respondCreated(response_create());
    }

    public function get_watch_history($user_id)
    {
        $watch_history = $this->model_watch->where('id_user', $user_id)->get()->getResult();
        return $this->respond(get_response($watch_history));
    }

    public function payment()
    {
        $data_payment = $this->request->getJSON();
        $find_course_instructor = $this->model_course->where('id', $data_payment->course_id)->first();
        $instructor_revenue = $find_course_instructor['instructor_revenue'] == 0 ? 0 : $data_payment->amount * $find_course_instructor['instructor_revenue'] / 100;
        $admin_revenue = $data_payment->amount - $instructor_revenue;
        $this->model_payment->insert([
            'id_payment' => rand(0, 99999),
            'id_user' => $data_payment->user_id,
            'payment_type' => $data_payment->payment_type,
            'payment_bank' => $data_payment->payment_bank,
            'payment_va' => $data_payment->payment_va,
            'coupon' => $data_payment->coupon,
            'course_id' => $data_payment->course_id,
            'amount' => $data_payment->amount,
            'admin_revenue' => $admin_revenue,
            'instructor_revenue' => $instructor_revenue,
            'instructor_payment_status' => 0,
            'status_payment' => 2,
            'status' => 0

        ]);
        return $this->respondCreated(response_create());
    }

    private function _generate_humanize_timestamps($time)
    {
        $date_stamps = Time::createFromTimestamp($time, 'Asia/Jakarta', 'en_US');
        $get_date =  $date_stamps->toDateTimeString();
        $exp = explode('-', $get_date);
        $date = substr($exp[2], 0, 2);
        $month_in_bahasa = [
            "01" => 'Januari',
            "02" => 'Februari',
            "03" => 'Maret',
            "04" => 'April',
            "05" => 'Mei',
            "06" => 'Juni',
            "07" => 'Juli',
            "08" => 'Agustus',
            "09" => 'September',
            "10" => 'Oktober',
            "11" => 'November',
            "12" => 'Desember'

        ];
        $month = $exp[1];
        $formated_humanize = $date . ' ' . $month_in_bahasa[$exp[1]] . ' ' . $exp[0];
        return $formated_humanize;
    }

    public function review($id)
    {

        $rules = $this->model_review->validationRules;
        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => [
                    'message' => $this->validator->getErrors()
                ]
            ], 403);
        } else {

            $value =  $this->request->getJSON();

            $data = [
                'review' => $value->review,
                'ratable_id' => $value->course_id,
                'ratable_type' => 'course',
                'rating' => $value->rating,
                'date_added' => strtotime(date('D, d-M-Y')),
                'user_id' => $id,
            ];

            $this->model_review->review($data);
            return $this->respondCreated(response_create());
        }
    }
}
