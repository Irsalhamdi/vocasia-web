<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'category';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['code_category', 'name_category', 'parent_category', 'slug_category', 'font_awesome_class', 'thumbnail'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'create_at';
    protected $updatedField         = 'update_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'code_category' => 'required',
        'name_category' => 'required',
        'parent_category' => 'required',
        'font_awesome_class' => 'required',
        'thumbnail' => 'required',
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

    public function get_category($params = null)
    {
        if ($params == null) {
            return $this->findAll();
        } else {
            return $this->where('id', $params)->first();
        }
    }

    public function list_category_home()
    {
        return $this->db->table($this->table)->select('id,name_category,slug_category')->get()->getResult();
    }
}
