<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'coupon';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'user_id', 'type_coupon', 'code_coupon', 'value', 'course_id', 'quantity', 'is_active'
    ];

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

    public function get_list_coupon()
    {
        return $this->db->table('coupon')->select("
            coupon.*,concat(users.first_name,' ',users.last_name) 
            as name,courses.title,courses.short_description")
            ->join('users', 'users.id = coupon.user_id')
            ->join('courses', 'courses.id = coupon.course_id')
            ->get()
            ->getResult();
    }

    public function get_coupons_prakerja()
    {
        // return $this->db->table('coupon a')->join();
    }
}
