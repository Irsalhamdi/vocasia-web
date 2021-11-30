<?php

namespace App\Controllers\BackEnd;

use App\Controllers\Backend\BackendController;

class Setting extends BackendController
{
    public function system_settings()
    {
        $data = $this->model_setting->findAll();
        return $this->respond(get_response($data));
    }
    public function update_system_settings()
    { 
        $rules = $this->model_setting->validationRules;

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);
        } else {

            $data = $this->request->getJSON();
            $this->model_setting->update_system_settings($data);
            return $this->respondUpdated(response_update());
        }
    }
    public function frontend_settings(){
        
        $banner_title = $this->model_frontend->banner_title();
        $banner_sub_title = $this->model_frontend->banner_sub_title();
        $about_us = $this->model_frontend->about_us();
        $terms_condition = $this->model_frontend->terms_and_condition();
        $privacy_policy = $this->model_frontend->privacy_policy();

        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'Judul Spanduk' => $banner_title->value,
                'Sub Judul Spanduk' => $banner_sub_title->value,
                'Tentang Kami' => $about_us->value,
                'Syarat dan Ketentuan' => $terms_condition->value,
                'Syarat dan Ketentuan' => $privacy_policy->value,
                'light_logo' => $this->model_setting->get_light_logo(),
                'dark_logo' => $this->model_setting->get_dark_logo(),
                'small_logo' => $this->model_setting->get_small_logo(),
                'favicon_logo' => $this->model_setting->get_favicon_logo(),
            ]
        ]);
    }
    public function update_frontend_settings(){

        $rules = $this->model_frontend->validationRules;

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);
        } else {

            $data = $this->request->getJSON();
            $this->model_frontend->update_frontend_settings($data);
            return $this->respondUpdated(response_update());
        }        
    }
    public function update_light_logo_settings()
    {
        $file = $this->request->getFile('light_logo');

		if (!empty($file)) {

            $name = "light_logo_default_.jpg";
            $folder_path = 'uploads/light_logo/' . $name;  

            if (file_exists($folder_path)) {
                    
                unlink('uploads/light_logo/' . $name);

            }

			$file->move('uploads/light_logo/', $name);
            return $this->respondUpdated(response_update());

        }else{

            return $this->failNotFound('Failed to update light logo');

        }
    }
    public function update_dark_logo_settings()
    {
        $file = $this->request->getFile('dark_logo');

		if (!empty($file)) {

            $name = "dark_logo_default_.jpg";
            $folder_path = 'uploads/dark_logo/' . $name;  

            if (file_exists($folder_path)) {
                    
                unlink('uploads/dark_logo/' . $name);

            }

			$file->move('uploads/dark_logo/', $name);
            return $this->respondUpdated(response_update());

        }else{

            return $this->failNotFound('Failed to update light logo');
            
        }              
    }
    public function update_small_logo_settings()
    {
        $file = $this->request->getFile('small_logo');

		if (!empty($file)) {

            $name = "small_logo_default_.jpg";
            $folder_path = 'uploads/small_logo/' . $name;  

            if (file_exists($folder_path)) {
                    
                unlink('uploads/small_logo/' . $name);

            }

			$file->move('uploads/small_logo/', $name);
            return $this->respondUpdated(response_update());

        }else{

            return $this->failNotFound('Failed to update light logo');
            
        }               
    }
    public function update_favicon_logo_settings()
    {
        $file = $this->request->getFile('favicon_logo');

		if (!empty($file)) {

            $name = "favicon_logo_default_.jpg";
            $folder_path = 'uploads/favicon_logo/' . $name;  

            if (file_exists($folder_path)) {
                    
                unlink('uploads/favicon_logo/' . $name);

            }

			$file->move('uploads/favicon_logo/', $name);
            return $this->respondUpdated(response_update());

        }else{

            return $this->failNotFound('Failed to update light logo');
            
        }            
    }
    public function payment_settings()
    {
        $system_currency = $this->model_setting->system_currency();
        $currency_position = $this->model_setting->currency_position();
        $paypal = $this->model_setting->paypal();
        $stripe_keys = $this->model_setting->stripe_keys();

        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'system_currency' => $system_currency->value,                
                'currency_position' => $currency_position->value,
                'paypal' => json_decode($paypal->value),
                'stripe_keys' => json_decode($stripe_keys->value)
            ]
        ]);        

    }
    public function update_payment_settings()
    {
        $rules = [
            'system_currency' => 'required',
            'currency_position' => 'required'
        ];

        if(!$this->validate($rules)){

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);
        }

        $data = $this->request->getJSON();
        $this->model_setting->update_payment_settings($data);
        return $this->respondUpdated(response_update());
    }
    public function update_paypal_settings()
    {
        $rules = [
                    "active" => "required|integer",
                    "mode" => "required",
                    "paypal_currency" => "required",
                    "sandbox_client_id" => "required",
                    "production_client_id" => "required"
                ];

        if(!$this->validate($rules)){

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);

        }else{

            $data = $this->request->getJSON();
            $this->model_setting->update_paypal_settings($data);
            return $this->respondUpdated(response_update());

        }
    }    
    public function update_stripe_settings()
    {
        $rules = [
                    "active" => "required|integer",
                    "testmode" => "required",
                    "stripe_currency" => "required",
                    "public_key" => "required",
                    "secret_key" => "required",
                    "public_live_key" => "required",
                    "secret_live_key" => "required"
                ];

        if(!$this->validate($rules)){

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);

        }else{

            $data = $this->request->getJSON();
            $this->model_setting->update_stripe_settings($data);
            return $this->respondUpdated(response_update());

        }
    }
    public function instructor_settings()
    {        
        $revenue = $this->model_setting->revenue();
        $permission = $this->model_setting->permission();

        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'allow_instructor' => $permission->value,                
                'instructor_revenue' => $revenue->value,
                'admin_revenue' => 100 - $revenue->value
            ]
        ]);
    }
    public function update_instructor_settings()
    {
        $rules = [
            'instructor_revenue' => 'required',
            'allow_instructor' => 'required'
        ];

        if(!$this->validate($rules)){

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);
        }

        $data = $this->request->getJSON();
        $this->model_setting->update_instructor_settings($data);
        return $this->respondUpdated(response_update());
    }
    public function smtp_settings()
    {

        $protocol = $this->model_setting->protocol();
        $smtp_host = $this->model_setting->smtp_host();
        $smtp_port = $this->model_setting->smtp_port();
        $smtp_user = $this->model_setting->smtp_user();
        $smtp_pass = $this->model_setting->smtp_pass();

        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'protocol' => $protocol->value,                
                'smtp_host' => $smtp_host->value,
                'smtp_port' => $smtp_port->value,
                'smtp_user' => $smtp_user->value,
                'smtp_pass' => $smtp_pass->value,
            ]
        ]);
        
    }
    public function update_smtp_settings()
    {

        $rules = [
            'protocol' => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_user' => 'required',
            'smtp_pass' => 'required',
        ];

        if(!$this->validate($rules)){

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);
        }

        $data = $this->request->getJSON();
        $this->model_setting->update_smtp_settings($data);
        return $this->respondUpdated(response_update());        

    }
    public function manage_language(){
        
        $data = $this->model_setting->get_all_languages();;

        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'list_of_language' => $data
            ]
        ], 200);
    }
    public function theme_settings(){

        $theme = $this->model_frontend->theme();
        
        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'theme' => $theme
            ]
        ], 200);
    }
    public function mobile_settings(){

        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => []
        ], 200);
    }
}
