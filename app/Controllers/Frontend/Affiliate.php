<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Affiliate extends FrontendController
{
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
}
