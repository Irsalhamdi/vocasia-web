<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class CoursesModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'courses';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['thumbnail'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'int';
    protected $createdField         = 'create_at';
    protected $updatedField         = 'update_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];


    public function get_course_list($id = null)
    {
        if (is_null($id)) {

            return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->get()->getResult();
        }

        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->where('a.id', $id)->get()->getRow();
    }

    public function get_count_course()
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->countAllResults();
    }

    public function get_count_course_active()
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->where('a.status_course', 'active')->countAllResults();
    }

    public function get_count_course_pending()
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->where('a.status_course', 'pending')->countAllResults();
    }
    public function get_count_course_paid()
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->where('a.is_free_course', '0')->countAllResults();
    }
    public function get_count_course_free()
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->where('a.is_free_course', '1')->countAllResults();
    }
    public function get_pagging_data($limit, $offset)
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->limit($limit, $offset)->get()->getResult();
    }
    public function home_page_course()
    {
        return $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,c.id as instructor_id")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->groupBy('b.course_id')->get()->getResultArray();
    }

    public function get_course_by_category($id_category)
    {
        return $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,c.id as instructor_id")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->where('a.category_id', $id_category)->groupBy('b.course_id')->get()->getResultArray();
    }



    public function get_prices_for_cart($id_course)
    {
        return $this->db->table('courses')->select('*')->where('id', $id_course)->get()->getRowObject();
    }

    public function get_lesson_duration($data)
    {
        if (!empty($data['course_id'])) {
            return $this->db->table('lesson')->select('*')->where('course_id', $data)->get();
        } else if (!empty($data['section'])) {
            return $this->db->table('lesson')->select('*')->where('section_id', $data)->get();
        }
    }

    public function get_rating_courses($id_course)
    {
        return $this->db->table('rating')->selectCount('user_id', 'total_review')->selectAvg('rating', 'avg_rating')->where('ratable_id', $id_course)->get()->getResult();
    }

    public function advanced_filter($data)
    {

        $data_filters =  $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,c.id as instructor_id,a.language")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->where($data)->groupBy('b.course_id')->get()->getResultArray();

        if (empty($data_filters)) {
            return null;
        } else {
            return $data_filters;
        }
    }

    public function get_rating_from_filter($data)
    {
        if ($data['a.category_id']) {
            $data_filters_rating = $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,a.language,c.id as instructor_id")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->join('rating d', 'd.ratable_id = a.id')->groupBy('a.id')->having('AVG(rating)', $data['d.rating'])->where($data)->get()->getResultArray();
        } else {

            $data_filters_rating = $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,a.language,c.id as instructor_id")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->join('rating d', 'd.ratable_id = a.id')->groupBy('a.id')->having('AVG(rating)', $data)->get()->getResultArray();
        }

        if (empty($data_filters_rating)) {
            return null;
        } else {
            return $data_filters_rating;
        }
    }

    public function get_thumbnail($id_course)
    {
        $folder = "uploads/courses_thumbnail/course_thumbnail_default_$id_course.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }
    }

    public function detail_course_for_homepage($id_course)
    {
        return $this->db->table('courses a')->select("a.id,CONCAT(b.first_name,' ',b.last_name) as instructor_name,a.level_course,COUNT(d.course_id) as total_lesson,a.description,a.outcomes,a.requirement,a.bio_status,a.price,a.discount_price,a.video_url,a.bio_instructor,b.id as uid,a.update_at,a.title")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->join('lesson d', 'd.course_id = a.id')->where('a.id', $id_course)->get()->getResult();
    }
    public function get_bio_instructor($data)
    {
        if ($data['bio_status'] == 1) {
            return $data['bio_instructor'];
        } else {
            $bio = $this->db->table('user_detail')->select('biography')->where('id_user', $data['id_user'])->get()->getRowObject();
            return $bio->biography;
        }
    }

    public function detail_intructor_by_courses($id_user)
    {
        $total_review =  $this->db->table('courses a')->select("CONCAT(b.first_name,' ',b.last_name) as instructor_name,COUNT(c.ratable_id) as total_review")->join('users b', 'b.id = a.user_id')->join('rating c', 'c.ratable_id = a.id')->where('b.id', $id_user)->get()->getRowObject();
        $total_course = $this->db->table('courses')->selectCount('user_id', 'total_course')->where('user_id', $id_user)->get()->getRowObject();
        $total_students = $this->db->table('enrol a')->selectCount('a.user_id', 'total_student')->join('courses b', 'b.id = a.course_id')->join('users c', 'c.id = b.user_id')->where('c.id', $id_user)->get()->getRowObject();
        return [
            'instructor_name' => $total_review->instructor_name,
            'total_review' => $total_review->total_review,
            'total_course' => $total_course->total_course,
            'total_students' => $total_students->total_student
        ];
    }
    public function get_section_duration($id_course)
    {
        $section = $this->db->table('courses')->select('section')->where('id', $id_course)->get()->getRowArray();
        return $section;
    }
    public function get_section_title($id_course)
    {
        return $this->db->table('section')->select('title,id')->where('course_id', $id_course)->get()->getResultObject();
    }
    public function lesson_title_from_section($id_section)
    {
        return $this->db->table('lesson')->select('title as title_lesson,duration')->where('section_id', $id_section)->get()->getResultObject();
    }

    public function get_rating_course($id_course)
    {
        $data_rating = $this->db->table('rating a')->select("CONCAT(b.first_name,' ',b.last_name) as user,a.review,a.rating")->join('users b', 'b.id = a.user_id')->where('ratable_id', $id_course)->get()->getResultObject();
        $rating = $this->db->table('rating')->selectAvg('rating', 'total_rating')->where('ratable_id', $id_course)->having('AVG(rating) = 5')->get()->getRowObject();
        $total_review = $this->db->table('rating')->selectCount('user_id', 'total_review')->where('ratable_id', $id_course)->get()->getRow();

        $rating_1 = $rating->total_rating == 1 ? ($rating->total_rating / $total_review->total_review) * 100 : '0';
        $rating_2 = $rating->total_rating == 2 ? ($rating->total_rating / $total_review->total_review) * 100 : '0';
        $rating_3 = $rating->total_rating == 3 ? ($rating->total_rating / $total_review->total_review) * 100 : '0';
        $rating_4 = $rating->total_rating == 4 ? ($rating->total_rating / $total_review->total_review) * 100 : '0';
        $rating_5 = $rating->total_rating == 5 ? ($rating->total_rating / $total_review->total_review) * 100 : '0';
        return [
            'status' => 200,
            'error' => false,
            'data' => [
                'avg_rating' => $rating->total_rating,
                'precentage_rating' => [
                    'rating_1' => $rating_1 . '%',
                    'rating_2' => $rating_2 . '%',
                    'rating_3' => $rating_3 . '%',
                    'rating_4' => $rating_4 . '%',
                    'rating_5' => $rating_5 . '%'
                ],
            ],
            'data_review' => [
                $data_rating
            ]
        ];
    }
    public function get_rating_by_star($id_course, $star)
    {
        return $this->db->table('rating a')->select("CONCAT(b.first_name,' ',b.last_name) as user,a.review,a.rating")->join('users b', 'b.id = a.user_id')->where(['ratable_id' => $id_course, 'rating' => $star])->get()->getResultObject();
    }

    public function verify_redeem_voucher($code_voucher, $course_id)
    {
        $voucher = $this->db->table('coupon')->where(['code_coupon' => $code_voucher, 'is_active' => 1])->get()->getRowObject();
        if (is_null($voucher)) {
            return null;
        }
        $courses = $this->get_course_list($course_id);
        $course_id = preg_split('/[[""\]]/', $voucher->course_id);
        for ($i = 0; $i < count($course_id); $i++) {
            if ($course_id[$i] == null || $course_id[$i] == ",") {
                continue;
            }
            $price_course = $courses->discount_price != 0 ? $courses->discount_price : $courses->price;
            if (!is_null($voucher) && !is_null($courses)) {
                if ($course_id[$i] == $courses->id) {
                    $cutting_price = $price_course - $voucher->value;
                    return $cutting_price;
                } else {
                    return null;
                }
            }
        }
    }

    public function my_course($user_id)
    {
        return $this->db->table('enrol a')->select("CONCAT(d.first_name,' ',d.last_name) as instructor_name,b.title,b.thumbnail,b.id as cid")->join('courses b', 'b.id = a.course_id')->join('users c', 'c.id = a.user_id')->join('users d', 'd.id = b.user_id')->where('a.user_id', $user_id)->get()->getResult();
    }
    public function rating_from_user($user_id, $course_id)
    {
        return $this->db->table('rating')->select('ratable_id,rating,review')->where(['user_id' => $user_id, 'ratable_id' => $course_id])->get()->getResult();
    }

    public function get_my_lesson($course_id)
    {
        return $this->db->table('lesson a')->select('a.title as lesson_title,a.*,b.title as section_title')->join('section b', 'a.section_id = b.id')->where('a.course_id', $course_id)->get()->getResult();
    }

    public function get_courses()
    {
        return $this->db->table('courses')->select('id,title')
            ->where('status_course', 'active')
            ->where('(is_free_course is null OR is_free_course = 0)')->get()->getResultArray();
    }

    public function search_course($keyword)
    {
        $course_data = $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,c.id as instructor_id,a.language")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->like('a.title', $keyword)->groupBy('b.course_id')->get()->getResultArray();

        if (!empty($course_data)) {
            return $course_data;
        } else {
            return null;
        }
    }
}
