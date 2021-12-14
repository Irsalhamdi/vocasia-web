<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrolModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'enrol';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['user_id', 'course_id', 'payment_id', 'create_at', 'update_at','finish_date'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
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

    public function get_list_enrol($id = null)
    {
        if (!empty($id)) {
            return $this->db->table('enrol')->select(
                "enrol.*,users.first_name,users.last_name,
                courses.title as title,
                courses.description as course_description,
                courses.short_description as short_description,
                courses.bio_instructor as instructor_biography,
                courses.outcomes as outcomes,
                courses.section as section,
                courses.requirement as requirement,
                courses.level_course as course_level, 
                courses.price as price,
                courses.discount_price as discount,
                courses.language as languange,
                courses.status_course as course_status"
            )
                ->join('users', 'users.id = enrol.user_id')
                ->join('courses', 'courses.id = enrol.course_id')
                ->where('enrol.id', $id)
                ->get()
                ->getRow();
        }
        return $this->db->table('enrol')->select("enrol.*,users.first_name,users.last_name,courses.title as title,user_detail.phone")->join('users', 'users.id = enrol.user_id')->join('user_detail', 'user_detail.id_user = enrol.user_id')->join('courses', 'courses.id = enrol.course_id')->get()->getResult();
    }

    public function get_count_enrol()
    {
        return $this->db->table('enrol')->select("enrol.*,users.first_name,users.last_name,courses.title as Title,")->join('users', 'users.id = enrol.user_id')->join('courses', 'courses.id = enrol.course_id')->countAllResults();
    }
    public function get_pagging_data($limit, $offset)
    {
        return $this->db->table('enrol')->select("enrol.*,users.first_name,users.last_name,courses.title as title,")->join('users', 'users.id = enrol.user_id')->join('courses', 'courses.id = enrol.course_id')->limit($limit, $offset)->get()->getResult();
    }

    public function get_count_enrols_courses($id_course)
    {
        $data = $this->db->table('enrol')->select('COUNT(user_id) as total_students')->where('course_id', $id_course)->groupBy('course_id')->get()->getRowObject();
        $data_count = !empty($data->total_students) ? $data->total_students : null;
        return $data_count;
    }

    public function get_sertifikat($user_id = null , $course_id = null)
    {
        return $this->db->table('enrol')->select(
                "enrol.*,users.first_name,users.last_name,
                courses.title as title,courses.id as course_id")
                ->join('users', 'users.id = enrol.user_id')
                ->join('courses', 'courses.id = enrol.course_id')
                ->where('enrol.user_id', $user_id)
                ->where('enrol.course_id', $course_id)
                ->get()
                ->getRow();
    }
    
}
