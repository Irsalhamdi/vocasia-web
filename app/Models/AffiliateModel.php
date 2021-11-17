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
    protected $allowedFields        = ['user_id', 'leader', 'co_leader', 'code_reff', 'is_active'];

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

    public function get_affiliate($id){

        if(!empty($id)){
            return $this->db->table('affiliate')->select('affiliate.*')
                            ->where('affiliate.user_id', $id)
                            ->where('is_active', 1)
                            ->get()
                            ->getRow();
        }
        return $this->db->table('affiliate')->select('affiliate.*')
                        ->where('is_active', 1)
                        ->orderBy('create_at', 'DESC')
                        ->get()
                        ->getResult();
    }

    public function get_list_affiliate($id = null)
    {
        if (is_null($id)) {
            return $this->db->table('affiliate')->select("affiliate.*,concat(users.first_name,' ',users.last_name) as name")->join('users', 'users.id = affiliate.user_id')->get()->getResult();
        }
        return $this->db->table('affiliate')->select("affiliate.*,concat(users.first_name,' ',users.last_name) as name")->join('users', 'users.id = affiliate.user_id')->where('affiliate.id_affiliate', $id)->get()->getRow();
    }

    public function get_count_affiliate()
    {
        return $this->db->table('affiliate')->select("affiliate.*,concat(users.first_name,' ',users.last_name) as name")->join('users', 'users.id = affiliate.user_id')->countAllResults();
    }

    public function get_pagging_data($limit, $offset)
    {
        return $this->db->table('affiliate')->select("affiliate.*,concat(users.first_name,' ',users.last_name) as name")->join('users', 'users.id = affiliate.user_id')->limit($limit, $offset)->get()->getResult();
    }

    public function get_affiliate_access(){
        return $this->db->table('courses a')->select('a.id as course_id, a.title as title, b.first_name as first_name, b.last_name as last_name, a.short_description as short_description, a.is_free_course as is_free_course, a.discount_flag as discount_flag, a.discount_price as discounted_price, a.price as price, COUNT(*) as jumlah')
                        ->join('users b', 'a.user_id = b.id')
                        ->where('a.status_course', 'active')
                        ->where('a.is_free_course', 0)
                        ->groupBy('a.id')
                        ->get()
                        ->getResult();
    }

    public function get_saldo($id){
        return $this->db->table('payment_balance')->selectSUM('payment_balance.pb_saldo')
                        ->where('id_users', $id)
                        ->where('pb_affiliate', 1)
                        ->orderBy('id_pb', 'desc')
                        ->get()
                        ->getRow();
    }

    public function get_history_saldo($id){
        return $this->db->table('payment_balance')
                        ->where('id_users', $id)
                        ->where('pb_affiliate', 1)
                        ->get()
                        ->getResult();
    }

    public function get_user_subscription($reff_code = ''){
        return $this->db->table('payment')
                        ->select('*, COUNT(*) as jumlah')
                        ->join('users', 'payment.id_user = users.id')
                        ->like('payment.id_payment', $reff_code)
                        ->groupBy('payment.id_user')
                        ->where('payment.status_payment', 0)
                        ->orderBy('jumlah', 'desc')
                        ->get()
                        ->getResult();
    }

    public function tarik_saldo($data){
        return $this->db->table('payment_balance')->insert($data);
    }

}
