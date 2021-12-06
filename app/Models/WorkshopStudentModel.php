<?php

namespace App\Models;

use CodeIgniter\Model;
use PDO;

class WorkshopStudentModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'workshop_student';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['ws_name', 'seri', 'gender', 'fname', 'lname', 'email', 'no_hp', 'instansi', 'rf_code', 'id_payment'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'date_added';
    protected $updatedField         = 'last_modified';
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

    public function get_workshop_student(){
        return $this->db->table('workshop_student')->orderBy('date_added', 'desc')
                                                    ->get()
                                                    ->getResult();
                                                   
    }

}
