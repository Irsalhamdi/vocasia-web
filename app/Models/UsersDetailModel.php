<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersDetailModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'user_detail';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_user', 'foto_profile', 'biography', 'datebirth', 'phone', 'bank_account_id', 'is_instructor', 'jenis_kel'];

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

    public function get_profile_users($id_user)
    {
        $folder = "uploads/foto_profile/foto_profile_default_$id_user.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }
    }
}
