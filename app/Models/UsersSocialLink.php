<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersSocialLink extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'social_link_users';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_user', 'facebook', 'instagram', 'twitter'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'int';
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

    public function get_social_link($user_id)
    {
        return $this->db->table($this->table)->select('facebook,twitter,instagram')->where('id_user', $user_id)->get()->getRow();
    }
}
