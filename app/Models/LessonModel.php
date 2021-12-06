<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'lesson';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['title','duration','course_id','section_id','video_type','video_url','lesson_type','attachment','attachment_type','summary','is_skip','order','video_type_for_mobile','video_url_for_mobile','duration_for_mobile'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
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

    public function get_list_lesson($id = null)
    {
        if (is_null($id)) {
            return $this->db->table('lesson')->select("
                lesson.*")
                ->join('courses', 'courses.id = lesson.course_id')
                ->join('section', 'section.id = lesson.section_id')
                ->get()
                ->getResult();            
            }
            return $this->db->table('lesson')->select("
                lesson.*")
                ->join('courses', 'courses.id = lesson.course_id')
                ->join('section', 'section.id = lesson.section_id')
                ->where('lesson.id', $id)
                ->get()
                ->getRow();            
    }

    public function get_lesson($id_course = null)
    {
        return $this->db->table('lesson')->select("
                lesson.title as title_lesson")
                ->join('courses', 'courses.id = lesson.course_id')
                ->where('lesson.course_id', $id_course)
                ->get()
                ->getResultArray();
    }

    public function get_count_lesson()
    {
        return $this->db->table('lesson')->select("
                lesson.*")
                ->join('courses', 'courses.id = lesson.course_id')
                ->join('section', 'section.id = lesson.section_id')
                ->countAllResults();
    }
}
