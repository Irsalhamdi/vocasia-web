<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\CategoryModel;

class CommunityModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'community';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['name', 'background', 'category_id', 'create_at', 'update_at'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required',
        'background' => 'required',
        'category_id' => 'required'
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

    public function get_list_community($id = null)
    {
        if (!is_null($id)) {
            return $this->db->table('community a')->select('a.*,b.name_category')->join('category b', 'b.id=a.category_id')->where('a.id', $id)->get()->getRow();
        }
        return $this->db->table('community a')->select('a.*,b.name_category')->join('category b', 'b.id=a.category_id')->get()->getResult();
    }

    public function checking_valid_data($data)
    {
        $id_category = $data;
        $category_model = new CategoryModel();
        helper('response');
        if (is_null($category_model->find($id_category))) {
            return null;
        }
        return $data;
    }

    public function get_count_community()
    {
        return $this->db->table('community a')->select('a.*,b.name_category')->join('category b', 'b.id=a.category_id')->countAllResults();
    }

    public function get_pagging_data($limit, $offset)
    {
        return $this->db->table('community a')->select('a.*,b.name_category')->join('category b', 'b.id=a.category_id')->limit($limit, $offset)->get()->getResult();
    }
}
