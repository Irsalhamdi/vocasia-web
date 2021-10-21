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
        return $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->where('a.category_id', $id_category)->groupBy('b.course_id')->get()->getResultArray();
    }



    public function get_prices_for_cart($id_course)
    {
        return $this->db->table('courses')->select('*')->where('id', $id_course)->get()->getRowObject();
    }

    public function get_lesson_duration($id_course)
    {
        return $this->db->table('lesson')->select('*')->where('course_id', $id_course)->get();
    }

    public function get_rating_courses($id_course)
    {
        return $this->db->table('rating')->selectCount('user_id', 'total_review')->selectAvg('rating', 'avg_rating')->where('ratable_id', $id_course)->get()->getResult();
    }

    public function advanced_filter($data)
    {
        return $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,a.language")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->where($data)->groupBy('b.course_id')->get()->getResultArray();
    }

    public function get_rating_from_filter($data)
    {
        return $this->db->table('courses a')->select("a.title,a.short_description,a.price,concat(c.first_name,' ',c.last_name) as instructor_name,a.discount_flag,a.discount_price,a.thumbnail,a.level_course,COUNT(b.course_id) as total_lesson,a.id,a.language")->join('lesson b', 'b.course_id = a.id')->join('users c', 'c.id = a.user_id')->join('rating d', 'd.ratable_id = a.id')->groupBy('a.id')->having('AVG(rating)', $data)->get()->getResultArray();
    }
}
