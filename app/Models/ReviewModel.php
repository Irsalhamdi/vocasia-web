<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'rating';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['rating', 'user_id', 'ratable_id', 'ratable_type', 'review'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'date_added';
    protected $updatedField         = 'last_modified';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'rating' => 'required|integer',
        'course_id' => 'required|integer',
        'review' => 'required'
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

    public function review($data){

        $check = $this->db->table('rating')->where('rating.user_id', $data['user_id'])
                                           ->Where('rating.ratable_id', $data['ratable_id'])
                                           ->Where('rating.ratable_type', $data['ratable_type'])           
                                           ->get()
                                           ->getRow();

        if($check){
            return $this->db->table('rating')->where('rating.user_id', $check->user_id)
                                             ->Where('rating.ratable_id', $check->ratable_id)
                                             ->Where('rating.ratable_type', $check->ratable_type)
                                             ->update($data);
        }else{
            return $this->db->table('rating')->insert($data);
        }                                
    }

}
