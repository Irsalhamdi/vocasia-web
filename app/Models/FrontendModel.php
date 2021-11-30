<?php

namespace App\Models;

use CodeIgniter\Model;

class FrontendModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'frontend_settings';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['key', 'value'];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'banner_title' => 'required',
        'banner_sub_title' => 'required',
        'about_us' => 'required',
        'terms_and_condition' => 'required',
        'privacy_policy' => 'required',
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

    public function banner_title(){ 
        
        return $this->db->table('frontend_settings')->select('frontend_settings.value')
                                           ->where('key', 'banner_title')
                                           ->get()
                                           ->getRow();
    }

    public function banner_sub_title(){
        
        return $this->db->table('frontend_settings')->select('frontend_settings.value')
                                           ->where('key', 'banner_sub_title')
                                           ->get()
                                           ->getRow();
    }

    public function about_us(){
        
        return $this->db->table('frontend_settings')->select('frontend_settings.value')
                                           ->where('key', 'about_us')
                                           ->get()
                                           ->getRow();
    }

    public function terms_and_condition(){
        
        return $this->db->table('frontend_settings')->select('frontend_settings.value')
                                           ->where('key', 'terms_and_condition')
                                           ->get()
                                           ->getRow();
    }

    public function privacy_policy(){
        
        return $this->db->table('frontend_settings')->select('frontend_settings.value')
                                           ->where('key', 'privacy_policy')
                                           ->get()
                                           ->getRow();
    }

    public function update_frontend_settings($data){

        $banner_title = ['value' => $data->banner_title];
        $banner_sub_title = ['value' => $data->banner_sub_title];
        $about_us = ['value' => $data->about_us];
        $terms_and_condition = ['value' => $data->terms_and_condition];
        $privacy_policy = ['value' => $data->privacy_policy];

        $return = [

            $value_banner_title =  $this->db->table('frontend_settings')->where('key', 'banner_title')->update($banner_title), 
            $value_banner_sub_title = $this->db->table('frontend_settings')->where('key', 'banner_sub_title')->update($banner_sub_title),
            $value_about_us = $this->db->table('frontend_settings')->where('key', 'about_us')->update($about_us),
            $value_terms_and_condition = $this->db->table('frontend_settings')->where('key', 'terms_and_condition')->update($terms_and_condition),
            $value_privacy_policy = $this->db->table('frontend_settings')->where('key', 'privacy_policy')->update($privacy_policy),
        ];           
        
        return $return;        
    }

    public function theme(){
        
        return $this->db->table('frontend_settings')->select('frontend_settings.value')
                                                    ->where('key', 'theme')
                                                    ->get()
                                                    ->getRow();
    }
}
