<?php

namespace App\Models;

use CodeIgniter\Model;

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
    protected $allowedFields        = [];

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

    public function get_pagging_data($limit, $offset)
    {
        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->limit($limit, $offset)->get()->getResult();
    }
    public function home_page_course()
    {
        return $this->db->table('courses a')->select("a.title,a.price,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->get()->getResult();
    }

    public function get_course_by_category($id_category)
    {
        $course_by_category =  $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,b.duration")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->where('a.category_id', $id_category)->groupBy('b.course_id,b.duration')->get()->getResult();
        $total_course_find_by_category = $this->db->table('courses')->where('category_id', $id_category)->countAllResults();
        return [
            'data_course' => $course_by_category,
            'total' => $total_course_find_by_category,
        ];
    }

    public function get_duration($id_course)
    {
        $total_duration = 0;
        $get_duration = $this->db->table('lesson')->select('*')->where('course_id', $id_course)->get()->getResult();
        if ($get_duration['lesson_type'] != 'other') {
            $duration = explode(':', $get_duration['duration']);
            $hours_to_second = $duration[0] * 60 * 60;
            $minute_to_second = $duration[1] * 60;
            $second = $duration[2];
            $total_duration = $hours_to_second + $minute_to_second + $second;
        }

        $hours = floor($total_duration / 3600);
        $minute = floor(($total_duration % 3600) / 60);
        $second = $total_duration % 60;

        $h = $hours > 0 ? $hours . ($hours == 1 ? "j" : "j") : "";
        $m = $minute > 0 ? $minute . ($minute == 1 ? "m" : "m") : "";
        $s = $second > 0 ? $second . ($second == 1 ? "d" : "d") : "";
        return $h . $m . $s;
    }
}
