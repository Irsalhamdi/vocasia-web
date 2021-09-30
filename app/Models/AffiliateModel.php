<?php

namespace App\Models;

use CodeIgniter\Model;

class AffiliateModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'affiliate';
    protected $primaryKey           = 'id_affiliate';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['user_id','leader','co_leader','code_reff','is_active'];

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

    public function get_list_affiliate($id = null)
    {
        if (is_null($id)) {
            return $this->db->table('affiliate')->select("
                affiliate.*,concat(users.first_name,' ',users.last_name) 
                as name")
                ->join('users', 'users.id = affiliate.user_id')
                ->get()
                ->getResult();            
            }
            return $this->db->table('affiliate')->select("
                affiliate.*,concat(users.first_name,' ',users.last_name) 
                as name")
                ->join('users', 'users.id = affiliate.user_id')
                ->where('affiliate.id_affiliate', $id)
                ->get()
                ->getRow();            
    }
}
