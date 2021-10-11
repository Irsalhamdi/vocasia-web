<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class PaymentBalance extends FrontendController
{
    public function index($user_id = null)
    {
        $payment_balance_data = $this->model_payment_balance->get_list_pb_by_user($user_id);
        return $this->respond(get_response($payment_balance_data));
    }
    public function create()
    {
        $rules = $this->model_payment_balance->validationRules;
        if (!$this->validate($rules)) {
            return $this->fail("Failed To Create Please Try Again");
        } else {
            $paymant_balance_data = $this->request->getJSON();
            $this->model_payment_balance->save($paymant_balance_data);
            return $this->respondCreated(response_create());
        }
    }
}
