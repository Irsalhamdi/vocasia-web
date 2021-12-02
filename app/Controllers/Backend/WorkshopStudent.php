<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class WorkshopStudent extends BackendController
{
    public function index()
    {
        $workshops = $this->model_workshop_student->get_workshop_student();

        foreach($workshops as $workshop){
            $data[] = [
                'id' => $workshop->id,  
                'title' => base64_decode($workshop->ws_name), 
                'gender' => ($workshop->gender == '1' ? 'Bapak' : 'Ibu'), 
                'name' => $workshop->fname . '' . $workshop->lname, 
                'email' => $workshop->email, 
                'phone' => $workshop->no_hp, 
                'email' => $workshop->email, 
                'referral_code' => $workshop->rf_code, 
                'date' => $workshop->date_added, 
                'id_payment' => $workshop->id_payment,
                'status' => ($workshop->id_payment) ? $this->model_payment->get_workshop_payment_status($workshop->id_payment) : 'Hanya Daftar'
            ];
        }
        return $this->respond(get_response($data));
    }
}
