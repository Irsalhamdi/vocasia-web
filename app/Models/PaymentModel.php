<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'payment';
    protected $primaryKey           = 'id_payment';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_user', 'payment_type', 'payment_bank', 'payment_va', 'coupon', 'course_id', 'amount', 'admin_revenue', 'instructor_revenue', 'instructor_payment_status', 'code_trans', 'status_payment', 'status'];

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

    protected $db;
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function get_admin_revenue()
    {
        return $this->db->table('payment')
            ->selectSUM("payment.admin_revenue")
            ->get()
            ->getResult();
    }
    public function get_instructor_revenue($id = null)
    {
        return $this->db->table('payment')
            ->selectSUM("payment.instructor_revenue")
            ->where('payment.id_user', $id)
            ->get()
            ->getRow();
    }
    // public function update_admin_revenue()
    // {
    //     $builder = $this->db->table('payment');
    //     $builder->set('payment.admin_revenue', '0');
    //     $builder->insert();

    //     return $builder;
    // }
}
