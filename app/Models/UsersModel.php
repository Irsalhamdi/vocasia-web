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
        $get_email = $this->db->table('users a')->select("a.email,a.first_name,a.last_name,a.id as uid,a.role_id,b.role_name")->join('role_user b', 'a.role_id=b.id')->where('a.email', $email)->get()->getRow();
        if ($get_email) {
            return $get_email;
        } else {
            return null;
        }
    }

    public function get_detail_users($id_user)
    {
<<<<<<< HEAD
        return $this->db->table('users a')->select("a.first_name,a.last_name,b.biography,b.datebrith,b.phone")->join('user_detail b', 'b.id_user = a.id')->where('a.id', $id_user)->get()->getRow();
=======
        return $this->db->table('users a')->select("a.first_name,a.last_name,b.biography,b.datebirth,b.phone,a.id")->join('user_detail b', 'b.id_user = a.id')->where('b.id_user', $id_user)->get()->getRow();
>>>>>>> 6956f93eb3a91d6a31b195313385a38704d55102
    }

    public function get_count_user()
    {
        return $this->db->table('users a')->select("a.email,a.first_name,a.last_name,a.id as uid,a.role_id")->countAllResults();
    }

    public function get_data_instructor()
    {
        return $this->db->table('users a')->select("a.id,a.first_name,a.last_name,a.email,b.is_instructor,c.title,payment.instructor_revenue")->join('user_detail b', 'b.id_user = a.id')->join('courses c', 'c.user_id = a.id')->join('payment', 'payment.id_user = a.id')->get()->getResult();
    }

    public function get_count_new_user()
    {
        return $this->db->table('users')->select("FROM_UNIXTIME(users.create_at) as time,users.id", false)->groupBy('month(time)')->countAllResults();
    }

    public function get_foto_profile($id_user)
    {
        $name = "foto_profile_default_$id_user.jpg";
        $folder_path = 'uploads/foto_profile/' . $name;
        if (file_exists($folder_path)) {
            return base_url() . '/' . $folder_path;
        } else {
            return null;
        }
    }

    public function get_instructor_list()
    {
        return $this->db->table('users a')->select("a.id,a.first_name,a.last_name")->join('user_detail b', 'b.id_user = a.id')->where('b.is_instructor', 1)->get()->getResult();
    }
}
