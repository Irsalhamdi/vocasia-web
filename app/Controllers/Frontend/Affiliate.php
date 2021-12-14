<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Affiliate extends FrontendController
{
    
    protected $format = 'json';

    public function pull($id){

        $rules = [
            'bank_id' => 'required|integer',
            'no_rekening' => 'required|integer',
            'on_behalf_of' => 'required',
            'nominal' => 'required|integer',
            'pb_token' => 'required'
        ];

        if(!$this->validate($rules)){
            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);
        }else{

            $affiliator = $this->model_affiliate->get_affiliate($id);
            if ($affiliator) {

                $data = $this->request->getJSON();

                    $saldo = $this->model_affiliate->get_saldo($id);

                    if ($data->nominal > $saldo->pb_saldo) {
                        return $this->respond([
                            'status' => 403,
                            'error' => true,
                            'data' => [
                                'message' => 'Nominal yang anda masukkan salah'
                            ]
                        ], 403);
                    } else {

                        $payment = $this->model_payment->get_detail_payment($id);

                        $data = [
                            'id_users'          => $id,
                            'id_payment'        => $payment->id_payment,
                            'pb_payment'        => 'Penarikan Saldo Komisi',
                            'pb_nominal'        => $data->nominal,
                            'pb_type'           => '2',
                            'pb_affiliate'      => 1,
                            'pb_bank'           => $data->bank_id,
                            'pb_norek'          => $data->no_rekening,
                            'pb_on_behalf_of'   => $data->on_behalf_of,
                            'pb_status'         => 0,
                            'pb_saldo'          => ($saldo->pb_saldo - $data->nominal),
                            'pb_token'          => $data->pb_token
                        ];
                        $this->model_affiliate->tarik_saldo($data);
                        return $this->respond([
                            'status' => 201,
                            'error' => false,
                            'data' => [
                                'message' => 'Permintaan penarikan saldo berhasil, menunggu proses..'
                            ]
                        ], 201);
                    }
            } else {
                return $this->failNotFound();
            }
        }
    }
    public function access($id){
        $user = $this->model_affiliate->get_affiliate($id);
        
        if($user){
            $affiliate = $this->model_affiliate->get_affiliate_access();

            return $this->respond([
                'status' => 201,
                'error' => false,
                'data' => [
                    'affilate' => $affiliate,
                ]
            ]);
        }else{
            return $this->failNotFound();
        }
    }
    public function saldo($id){
        $user = $this->model_affiliate->get_affiliate($id);

        if($user){

            $saldo = $this->model_affiliate->get_saldo($id);
            $saldo_history = $this->model_affiliate->get_history_saldo($id);

            return $this->respond([
                'status' => 201,
                'error' => false,
                'data' => [
                    'saldo' => $saldo,
                    'saldo_history' => $saldo_history
                ]
            ]);
        }else{
             return $this->failNotFound();
        }
    }
    public function subscription($id){
        $user = $this->model_affiliate->get_affiliate($id);
        if($user){
            $reff_code = $user->code_reff;
        
            $subscription = $this->model_affiliate->get_user_subscription($reff_code);
            return $this->respond(get_response($subscription));
        }else{
            return $this->failNotFound();
        }
    }

    public function dashboard_affiliate($user = null)
    {
        $data_affiliate = $this->model_affiliate->get_code_reff($user);
        if ($data_affiliate) {
            $code_reff = $data_affiliate->code_reff;
            $user_id = $data_affiliate->user_id;
            
            $leads = $this->model_payment->get_leads($code_reff);
            $leads_daily = $this->model_payment->get_leads($code_reff, 'daily');
            $leads_monthly = $this->model_payment->get_leads($code_reff, 'monthly');
            
            $sales = $this->model_payment->get_sales($code_reff);
            $sales_daily = $this->model_payment->get_sales($code_reff, 'daily');
            $sales_monthly = $this->model_payment->get_sales($code_reff, 'monthly');
            
            $omset = $this->model_payment->get_omset($code_reff);
            $omset_daily = $this->model_payment->get_omset($code_reff, 'daily');
            $omset_monthly = $this->model_payment->get_omset($code_reff, 'monthly');
            
            $commition = $this->model_payment_balance->get_commition($user_id);
            $commition_daily = $this->model_payment_balance->get_commition($user_id, 'daily');
            $commition_monthly = $this->model_payment_balance->get_commition($user_id, 'monthly');
            
            $payment = $this->model_payment->get_payment($code_reff);
            $payment_daily = $this->model_payment->get_payment($code_reff, 'daily');
            $payment_monthly = $this->model_payment->get_payment($code_reff, 'monthly');

            $top_courses = $this->model_payment->get_top_courses();
            $top_courses_monthly = $this->model_payment->get_top_courses('monthly');

            $top_sales = $this->model_payment_balance->get_top_affiliates();
            
            return $this->respond([
                'status' => 201,
                'error' => false,
                'data' => [
                    'code_reff' => $code_reff,
                    'user_id' => $user_id,
                    'leads' => $leads,
                    'leads_daily' => $leads_daily,
                    'leads_monthly' => $leads_monthly,
                    'sales' => $sales,
                    'sales_daily' => $sales_daily,
                    'sales_monthly' => $sales_monthly,
                    'omset' => $omset,
                    'omset_daily' => $omset_daily,
                    'omset_monthly' => $omset_monthly,
                    'commition' => $commition,
                    'commition_daily' => $commition_daily,
                    'commition_monthly' => $commition_monthly,
                    'payment' => $payment,
                    'payment_daily' => $payment_daily,
                    'payment_monthly' => $payment_monthly,
                    'top_courses' => $top_courses,
                    'top_courses_monthly' => $top_courses_monthly,
                    'top_sales' => $top_sales,
                ]
            ]);  
        }else {
            return $this->failNotFound();
        }
    }

    public function add_affiliator()
    {
        $user_exists = $this->model_users->find($this->request->getVar("user_id"));
        if (!empty($user_exists)) {
            // user exists
            $code = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789');
            shuffle($code);
            $code_rand = '';
            foreach (array_rand($code, 5) as $k) $code_rand .= $code[$k];

            $affiliate_data = $this->request->getJSON();
            $affiliate_data->code_reff = $code_rand;

            if ($affiliate_data) {
                // success to create
                $this->model_affiliate->protect(false)->insert($affiliate_data);
                return $this->respondCreated(response_create());
            } else {
                // failed to create
                return $this->respond(response_failed());
            }
        } else {
            // user no exist
            return $this->failNotFound();
        }
    }

    public function courses()
    {
        $data_courses = $this->model_course->get_courses();
        return $this->respond(get_response($data_courses));
    }

    public function share_link($id_course)
    {
        $course_details = $this->model_course->get_course_list($id_course);
        $shareable_link = site_url('home/course/' . url_title(strtolower($course_details->title)) . '/' . $course_details->id);

        return $this->respond(get_response($shareable_link));
    }

    public function commitions($user = null)
    {
        $data_affiliate = $this->model_affiliate->get_code_reff($user);

        if ($data_affiliate) {
            $code_reff = $data_affiliate->code_reff;
            $user_id = $data_affiliate->user_id;
            $detail_commitions = $this->model_payment->get_detail_commitions($code_reff);

            return $this->respond([
                'status' => 201,
                'error' => false,
                'data' => [
                    'code_reff' => $code_reff,
                    'user_id' => $user_id,
                    'detail' => $detail_commitions,
                ]
            ]);  

        }else {
            return $this->failNotFound();
        }
    }


}
