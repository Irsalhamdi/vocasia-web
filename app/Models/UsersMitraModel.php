<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersMitraModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'users_mitra';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_user','number_code','collage','major','mitra_name'];

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

    public function get_list_users_mitra($id = null)
    {
        if (is_null($id)) {
            return $this->db->table('users_mitra')->select("
                users_mitra.*,concat(users.first_name,' ',users.last_name) 
                as name")
                ->join('users', 'users.id = users_mitra.id_user')
                ->get()
                ->getResult();
        }
        return $this->db->table('users_mitra')->select("
            users_mitra.*,concat(users.first_name,' ',users.last_name) 
            as name")
            ->join('users', 'users.id = users_mitra.id_user')
            ->where('users_mitra.id', $id)
            ->get()
            ->getRow();
    }
}