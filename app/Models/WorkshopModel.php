<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class WorkshopModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'workshop';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['title', 'description', 'price', 'is_active'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'date_added';
    protected $updatedField         = 'last_modified';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title' => 'required',
        'description' => 'required',
        'price' => 'required'
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

    public function get_workshop()
    {
        return $this->db->table('workshop')->where('is_active', 1)
                                           ->get()
                                           ->getResult();
    }

    public function check_duplication_workshop($data)
    {
        $duplicate_workshop_check = $this->db->table('workshop')
                                         ->where('title', $data->title)
                                         ->get()
                                         ->getRow();

        if ($duplicate_workshop_check) {
            return false;
        } else {
            return true;
        }
    }

}
