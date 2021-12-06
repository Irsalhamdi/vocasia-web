<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'comment';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['body','user_id','commentable_id','commentable_type','date_added','last_modified'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'int';
    protected $createdField         = 'date_added';
    protected $updatedField         = 'last_modified';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required'
    ];
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

    public function get_comment_by_course($id_course = null)
    {
        return $this->db->table('comment a')->select("a.*,b.first_name, b.last_name, c.title")->join('users b', 'b.id = a.user_id')->join('courses c', 'c.id = a.commentable_id')->where('a.commentable_id', $id_course)->get()->getResult();
    }
}
