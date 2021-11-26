<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'banner';
    protected $primaryKey           = 'id_b';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['img','img_web_mobile_version','url','status','date'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'date';
    protected $updatedField         = '';
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

    public function get_banner($id = null)
    {
        if ($id == null) {
            return $this->findAll();
        } else {
            return $this->where('id_b', $id)->first();
        }
    }

    public function get_img($id)
    {
        $folder = "uploads/banner_img/banner_default_$id.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }
    }
}
