<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentBalanceModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'payment_balance';
    protected $primaryKey           = 'id_pb';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_users', 'id_payment', 'pb_payment', 'pb_nominal', 'pb_type', 'pb_affiliate', 'pb_bank', 'pb_norek', 'pb_on_behalf_of', 'pb_status', 'pb_saldo', 'pb_date', 'pb_date', 'pb_date', 'pb_date_done', 'pb_token'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_users' => 'required',
        'id_payment' => 'required',
        'pb_nominal' => 'required',
        'pb_on_behalf_of' => 'required',
        'pb_bank' => 'required',
        'pb_norek' => 'required'
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

    public function get_list_pb_by_user($user_id)
    {
        return $this->db->table('payment_balance')
            ->select("payment_balance.pb_nominal")
            ->where('payment_balance.id_users', $user_id)
            ->get()
            ->getRow();
    }
}
