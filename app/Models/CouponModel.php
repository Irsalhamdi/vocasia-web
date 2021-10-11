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

    public function get_list_coupon($id = null)
    {
        if (!empty($id)) {
            return $this->db->table('coupon')->select("coupon.*")
                ->join('users', 'users.id = coupon.user_id')
                ->where('coupon.id', $id)
                ->get()
                ->getRow();
        }
        return $this->db->table('coupon')->select("coupon.*")
            ->join('users', 'users.id = coupon.user_id')
            ->get()
            ->getResult();
    }

    public function get_coupons_prakerja()
    {
        // return $this->db->table('coupon a')->join();
    }

    public function get_count_coupon()
    {
        return $this->db->table('coupon')->select("coupon.*")->join('users', 'users.id = coupon.user_id')->countAllResults();
    }

    public function get_pagging_data($limit, $offset)
    {
        return $this->db->table('coupon')->select("coupon.*")->join('users', 'users.id = coupon.user_id')->limit($limit, $offset)->get()->getResult();
    }
}
