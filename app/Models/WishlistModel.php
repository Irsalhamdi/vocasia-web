<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'wishlist';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['id_user', 'wishlist_item', 'create_at', 'update_at'];

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

    public function get_user_wishlist($id_user)
    {
        return $this->db->table('wishlist a')->select("a.id as wishlist_id, b.title, b.price, d.first_name, d.last_name, b.thumbnail")->join('courses b', 'b.id = a.wishlist_item')->join('users c', 'c.id = a.id_user')->join('users d', 'd.id = b.user_id')->where('a.id_user', $id_user)->get()->getResult();
    }
}
