<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'settings';
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
            'system_name' => 'required',
            'system_title' => 'required',
            'website_keywords' => 'required',
            'website_description' => 'required',
            'author' => 'required',
            'slogan' => 'required',
            'system_email' => 'required',
            'address' => 'required',
            'phone' => 'required|integer|max_length[14]',
            'youtube_api_key' => 'required',
            'vimeo_api_key' => 'required',
            'purchase_code' => 'required',
            'language' => 'required',
            'student_email_verification' => 'required',
            'footer_text' => 'required',
            'footer_link' => 'required'
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

    public function revenue(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'instructor_revenue')
                                           ->get()
                                           ->getRow();
                                  
    }

    public function permission(){
        
        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'allow_instructor')
                                           ->get()
                                           ->getRow();
    }

    public function update_system_settings($data){

        $system_name = ['value' => $data->system_name];
        $system_title = ['value' => $data->system_title];
        $website_keywords = ['value' => $data->website_keywords];
        $website_description = ['value' => $data->website_description];
        $author = ['value' => $data->author];
        $slogan = ['value' => $data->slogan];
        $system_email = ['value' => $data->system_email];
        $address = ['value' => $data->address];
        $phone = ['value' => $data->phone];
        $youtube_api_key = ['value' => $data->youtube_api_key];
        $vimeo_api_key = ['value' => $data->vimeo_api_key];
        $purchase_code = ['value' => $data->purchase_code];
        $language = ['value' => $data->language];
        $student_email_verification = ['value' => $data->student_email_verification];
        $footer_text = ['value' => $data->footer_text];
        $footer_link = ['value' => $data->footer_link];

        $return = [

            $value_system_name =  $this->db->table('settings')->where('key', 'system_name')->update($system_name), 
            $value_system_title = $this->db->table('settings')->where('key', 'system_title')->update($system_title),
            $value_website_keywords =  $this->db->table('settings')->where('key', 'website_keywords')->update($website_keywords), 
            $value_website_description = $this->db->table('settings')->where('key', 'website_description')->update($website_description),
            $value_author =  $this->db->table('settings')->where('key', 'author')->update($author), 
            $value_slogan = $this->db->table('settings')->where('key', 'slogan')->update($slogan),
            $value_system_email =  $this->db->table('settings')->where('key', 'system_email')->update($system_email), 
            $value_address = $this->db->table('settings')->where('key', 'address')->update($address),
            $value_phone =  $this->db->table('settings')->where('key', 'phone')->update($phone), 
            $value_youtube_api_key = $this->db->table('settings')->where('key', 'youtube_api_key')->update($youtube_api_key),
            $value_vimeo_api_key =  $this->db->table('settings')->where('key', 'vimeo_api_key')->update($vimeo_api_key), 
            $value_purchase_code = $this->db->table('settings')->where('key', 'purchase_code')->update($purchase_code),
            $value_language =  $this->db->table('settings')->where('key', 'language')->update($language), 
            $value_student_email_verification = $this->db->table('settings')->where('key', 'student_email_verification')->update($student_email_verification),
            $value_footer_text =  $this->db->table('settings')->where('key', 'footer_text')->update($footer_text), 
            $value_footer_link = $this->db->table('settings')->where('key', 'footer_link')->update($footer_link),
        ];           
        
        return $return;

    }

    public function get_light_logo(){

        $folder = "uploads/light_logo/light_logo_default_.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }        
    }

    public function get_dark_logo(){

        $folder = "uploads/dark_logo/dark_logo_default_.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }        
    }

    public function get_small_logo(){

        $folder = "uploads/small_logo/small_logo_default_.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }        
    }

    public function get_favicon_logo(){

        $folder = "uploads/favicon_logo/favicon_logo_default_.jpg";
        if (file_exists($folder)) {
            return base_url() . '/' . $folder;
        } else {
            return null;
        }        
    }

    public function system_currency(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'system_currency')
                                           ->get()
                                           ->getRow();

    }

    public function currency_position(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'currency_position')
                                           ->get()
                                           ->getRow();

    }

    public function paypal(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'paypal')
                                           ->get()
                                           ->getRow();

    }

    public function stripe_keys(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'stripe_keys')
                                           ->get()
                                           ->getRow();

    }

    public function update_paypal_settings($data){
    
        $paypal_info = array();
        $paypal['active'] = $data->active;
        $paypal['mode'] = $data->mode;
        $paypal['sandbox_client_id'] = $data->sandbox_client_id;
        $paypal['production_client_id'] = $data->production_client_id;
        $paypal_currency = ['value' => $data->paypal_currency];

        array_push($paypal_info, $paypal);

        $data =  ['value' => json_encode($paypal_info)];

        $return = [
                    $this->db->table('settings')->where('key', 'paypal')->update($data),
                    $this->db->table('settings')->where('key', 'paypal_currency')->update($paypal_currency),
        ];

        return $return;

    }

    public function update_stripe_settings($data){
    
        $stripe_info = array();
        $stripe['active'] = $data->active;
        $stripe['testmode'] = $data->testmode;
        $stripe['public_key'] = $data->public_key;
        $stripe['secret_key'] = $data->secret_key;
        $stripe['public_live_key'] = $data->public_live_key;
        $stripe['secret_live_key'] = $data->secret_live_key;
        $stripe_currency = ['value' => $data->stripe_currency];        

        array_push($stripe_info, $stripe);

        $data =  ['value' => json_encode($stripe_info)];

        $return = [
                    $this->db->table('settings')->where('key', 'stripe_keys')->update($data),
                    $this->db->table('settings')->where('key', 'stripe_currency')->update($stripe_currency),
        ];

        return $return;

    }

    public function update_payment_settings($data){

        $system_currency = ['value' => $data->system_currency];
        $currency_position = ['value' => $data->currency_position];

        $return = [

            $value_system_currency =  $this->db->table('settings')->where('key', 'system_currency')->update($system_currency), 

            $value_currency_position = $this->db->table('settings')->where('key', 'currency_position')->update($currency_position)
        ];           
        
        return $return;        
    }

    public function update_instructor_settings($data){

        $instructor_revenue = ['value' => $data->instructor_revenue];
        $allow_instructor = ['value' => $data->allow_instructor];

        $return = [

            $revenue =  $this->db->table('settings')->where('key', 'instructor_revenue')->update($instructor_revenue), 

            $permission = $this->db->table('settings')->where('key', 'allow_instructor')->update($allow_instructor)
        ];           
        
        return $return;
    }

    public function protocol(){
        
        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'protocol')
                                           ->get()
                                           ->getRow();

    }

    public function smtp_host(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'smtp_host')
                                           ->get()
                                           ->getRow();        

    }

    public function smtp_port(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'smtp_port')
                                           ->get()
                                           ->getRow();

    }

    public function smtp_user(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'smtp_user')
                                           ->get()
                                           ->getRow();

    }

    public function smtp_pass(){

        return $this->db->table('settings')->select('settings.value')
                                           ->where('key', 'smtp_pass')
                                           ->get()
                                           ->getRow();

    }

    public function update_smtp_settings($data){

        $protocol = ['value' => $data->protocol];
        $smtp_host = ['value' => $data->smtp_host];
        $smtp_port = ['value' => $data->smtp_port];
        $smtp_user = ['value' => $data->smtp_user];
        $smtp_pass = ['value' => $data->smtp_user];

        $return = [

            $value_protocol =  $this->db->table('settings')->where('key', 'protocol')->update($protocol), 
            $value_smtp_host = $this->db->table('settings')->where('key', 'smtp_host')->update($smtp_host),
            $value_smtp_port = $this->db->table('settings')->where('key', 'smtp_port')->update($smtp_port),
            $value_smtp_user = $this->db->table('settings')->where('key', 'smtp_user')->update($smtp_user),
            $value_smtp_pass = $this->db->table('settings')->where('key', 'smtp_pass')->update($smtp_pass),
        ];           
        
        return $return;        

    }

    function get_all_languages()
    {
        $language_files = array();
        $all_files = $this->get_list_of_language_files();

        foreach ($all_files as $file) {
            $info = pathinfo($file);
                if (isset($info['extension']) && strtolower($info['extension']) == 'json') {
                    $file_name = explode('.json', $info['basename']);
                    array_push($language_files, $file_name[0]);
                }
        }
        return $language_files;
    }

    function get_list_of_language_files($dir = APPPATH . '/language', &$results = array())
    {
        $files = scandir($dir);
            foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                if (!is_dir($path)) {
                    $results[] = $path;
                } else if ($value != "." && $value != "..") {
                    $this->get_list_of_directories_and_files($path, $results);
                    $results[] = $path;
                }
            }
        return $results;
    }    

    function get_list_of_directories_and_files($dir = APPPATH, &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                if (!is_dir($path)) {
                    $results[] = $path;
                } else if ($value != "." && $value != "..") {
                    $this->get_list_of_directories_and_files($path, $results);
                    $results[] = $path;
                }
        }
        return $results;
    }
    
}
