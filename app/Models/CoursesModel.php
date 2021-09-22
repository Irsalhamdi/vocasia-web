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
    protected $protectFields        = false;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = true;
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


    public function get_course_list($id = null)
    {
        if (is_null($id)) {

            return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->get()->getResult();
        }

        return $this->db->table('courses a')->select("a.*,concat(b.first_name,' ',b.last_name) as instructor_name,c.name_category,c.parent_category")->join('users b', 'b.id = a.user_id')->join('category c', 'c.id = a.category_id')->where('a.id', $id)->get()->getRow();
    }
}
