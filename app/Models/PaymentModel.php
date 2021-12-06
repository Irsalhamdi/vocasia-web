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
    protected $dateFormat           = 'int';
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
    public function get_detail_payment($id){
        return $this->db->table('payment')->select('payment.*')
                        ->where('id_user', $id)
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

    public function get_leads($code_reff = null, $filter = null)
    {
    if ($filter == 'daily') {
      $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');
      $this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
      $this->db->table('payment')->where('DAY(FROM_UNIXTIME(create_at)) = DAY(CURRENT_DATE())');
    } 
    else if ($filter == 'monthly') {
      $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');
      $this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
    }
    return $this->db->table('payment')->where('status_payment >', 0)->like('id_payment', $code_reff)->countAllResults();
    }
    
    public function get_sales($code_reff = null, $filter = null)
    {
    if ($filter == 'daily') {
      $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');
      $this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
      $this->db->table('payment')->where('DAY(FROM_UNIXTIME(create_at)) = DAY(CURRENT_DATE())');
    } 
    else if ($filter == 'monthly') {
      $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');
      $this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
    }
    return $this->db->table('payment')->where('status_payment', 0)->like('id_payment', $code_reff)->countAllResults();
    }
    
    public function get_omset($code_reff = null, $filter = null)
    {
    $this->db->table('payment')->selectSum('amount')
    ->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())')
    ->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())')
    ->where('DAY(FROM_UNIXTIME(create_at)) = DAY(CURRENT_DATE())')
    ->where('status_payment', 0)->like('id_payment', $code_reff)->get()->getResultArray();
    }

    public function get_payment($code_reff = null, $filter = null)
    {
    if ($filter == 'daily') {
      $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');
      $this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
      $this->db->table('payment')->where('DAY(FROM_UNIXTIME(create_at)) = DAY(CURRENT_DATE())');
    } 
    else if ($filter == 'monthly') {
      $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');
      $this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
    }
    return $this->db->table('payment')->like('id_payment', $code_reff)->get()->getResult();
    }

    public function get_top_courses($filter = "")
    {
      if ($filter == 'monthly') {
        $this->db->table('payment')->where('YEAR(FROM_UNIXTIME(create_at)) = YEAR(CURRENT_DATE())');$this->db->table('payment')->where('MONTH(FROM_UNIXTIME(create_at)) = MONTH(CURRENT_DATE())');
      }
      return $this->db->table('payment a')->select('*, COUNT(*) as jumlah')
      ->join('courses b', 'a.course_id = b.id')
      ->where('b.status_course', 'active')
      ->where('(b.is_free_course = 0 OR b.is_free_course is null)')
      ->groupBy('a.course_id')
      ->orderBy('jumlah', 'desc')
      ->limit('10')
      ->get()->getResultArray();
    }

    public function get_detail_commitions($reff_code = ""){
      return $this->db->table('payment a')->select('c.title as title, b.first_name as first_name, b.last_name as last_name, a.status_payment as status, a.amount as amount, b.email as email')
      ->join('users b', 'a.id_user = b.id')
      ->join('courses c', 'a.course_id = c.id')
      ->like('a.id_payment', $reff_code)->get()->getResultArray();
    }

    public function get_workshop_payment_status($id_payment){

      $check = $this->db->table('payment')->like('id_payment', $id_payment)->get()->getResult();
      
      if($check){

        if ($check == -5) {
          return 'Checkout dibatalkan';
          } elseif ($check == 2) {
            return 'Belum membayar';
          } elseif ($check == 1) {
            return 'Sudah membayar';
          } 
        else {
          return 'Expired';
        }

      }else{
        return 'ID Payment Invalid';
      }
    }
}
