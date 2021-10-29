<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class UsersModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'users';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['first_name', 'email', 'password', 'username', 'role_id', 'is_verified', 'create_at', 'update_at'];

    // Dates
    protected $useTimestamps        = false;
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

    public function validate_user($email)
    {

        $get_email = $this->db->table('users a')->select("a.email,concat(a.first_name,' ',a.last_name) as fullname,a.id as uid,a.role_id,b.role_name")->join('role_user b', 'a.role_id=b.id')->where('a.email', $email)->get()->getRow();
        if ($get_email) {
            return $get_email;
        } else {
            return null;
        }
    }

    public function get_detail_users($id_user)
    {
        return $this->db->table('users a')->select("concat(a.first_name,' ',a.last_name) as fullname,b.biography,b.datebrith,b.phone")->join('user_detail b', 'b.id_user = a.id')->where('a.id', $id_user)->get()->getRow();
    }

    public function get_count_user()
    {
        return $this->db->table('users a')->select("a.email,concat(a.first_name,' ',a.last_name) as fullname,a.id as uid,a.role_id")->countAllResults();
    }

    public function get_data_instructor()
    {
        return $this->db->table('users a')->select("a.id,concat(a.first_name,' ',a.last_name) as fullname,a.email,b.is_instructor,c.title,payment.instructor_revenue")->join('user_detail b', 'b.id_user = a.id')->join('courses c', 'c.user_id = a.id')->join('payment', 'payment.id_user = a.id')->get()->getResult();
    }

    public function get_count_new_user()
    {   
        return $this->db->table('users')->select("FROM_UNIXTIME(users.create_at) as time,users.id", false)->groupBy('month(time)')->countAllResults();
    }

}
