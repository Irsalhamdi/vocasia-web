<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'carts';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id', 'id_user', 'cart_item', 'cart_price'];

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

    public function cart_item_list($id_user)
    {
        return $this->db->table('carts a')->select("b.title,b.discount_flag,b.discount_price,b.price,d.first_name,d.last_name")->join('courses b', 'b.id = a.cart_item')->join('users c', 'c.id = a.id_user')->join('users d', 'd.id = b.user_id')->where('c.id', $id_user)->get()->getResultObject();
    }

    public function get_total_payment_cart($id_user)
    {
        return $this->db->table('carts')->selectSum('cart_price')->where('id_user', $id_user)->groupBy('id_user')->get()->getResult();
    }
}
