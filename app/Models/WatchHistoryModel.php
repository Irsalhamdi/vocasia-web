<?php

namespace App\Models;

use CodeIgniter\Model;

class WatchHistoryModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'watch_history';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_user', 'course_id', 'lesson_id', 'progress'];

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

    public function get_user($user_id)
    {
        return $this->db->table('watch_history a')->select('course_id,progress')
        ->where('a.id_user',$user_id)->get()->getResult();
    }

    public function count_progress($course_id, $user_id)
    {
        return $this->db->table('watch_history a')->select('course_id,progress')
        ->where('a.course_id',$course_id)->where('a.id_user',$user_id)->countAllResults();
    }


}
