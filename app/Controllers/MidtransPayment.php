<?php

namespace App\Controllers;

use App\Models\CoursesModel;
use CodeIgniter\RESTful\ResourceController;
use Midtrans\Config as MidtransConfig;
use Config\Services;
use Midtrans\CoreApi as CoreApi;

class MidtransPayment extends ResourceController
{
    protected $format = 'json';
    public function __construct()
    {

        $this->server_key = Services::getMidtransServerKey();
        $client_key = Services::getMidtransClientKey();
        MidtransConfig::$isProduction = false;
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$serverKey = Services::getMidtransServerKey();;
        $this->model_payment = model('PaymentModel');
        $this->course_model = model('CoursesModel');
    }


    function charge()
    {
        $data_invoice = $this->request->getJSON();
        $data_transaction = array();
        $id_user = $data_invoice->customer_detail->id_user;
        $data_customer = array(
            'first_name' => $data_invoice->customer_detail->nama,
            'email' => $data_invoice->customer_detail->email,
        );
        $data_items = array();


        foreach ($data_invoice->data_invoice as $key => $values) {
            $find_instructor_course = $this->course_model->select('instructor_revenue')->where('id', $values->id_kursus)->get()->getResult();
            $data_items[$key] = [
                'id'   => $values->id_kursus,
                'name' => $values->title_kursus,
                'quantity'  => $values->qty,
                'price' => $values->harga

            ];
        }
        $data_transaction = [
            'order_id' => rand() + $id_user,
            'gross_amount' => $data_invoice->total_payment
        ];
        if ($data_invoice->payment_type == 'gopay') {
            $transaction_data = array(
                'payment_type' => $data_invoice->payment_type,
                'transaction_details' => $data_transaction,
                'item_details'        => $data_items,
                'customer_details'    => $data_customer
            );
            $response = CoreApi::charge($transaction_data);
        } elseif ($data_invoice->payment_type == 'bank_transfer') {
            $transaction_data = array(
                'payment_type' => $data_invoice->payment_type,
                'transaction_details' => $data_transaction,
                'item_details'        => $data_items,
                'customer_details'    => $data_customer,
                'bank_transfer' => array(
                    'bank' => $data_invoice->bank
                )
            );
            $client = \Config\Services::curlrequest();
            $url_request = $client->setBody(json_encode($transaction_data))->request('post', 'https://api.sandbox.midtrans.com/v2/charge', array(
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic U0ItTWlkLXNlcnZlci02SzE4S3E0Y1FLMlhOTk5lazZmSWwyUjk=',
                    'Content-Type' => 'application/json'
                ]
            ));
            $response = json_decode($url_request->getBody());
        }
        if ($response->transaction_status == 'pending') {
            foreach ($data_invoice->data_invoice as $key => $values) {
                $find_instructor_course = $this->course_model->select('instructor_revenue')->where('id', $values->id_kursus)->get()->getResult();
                foreach ($find_instructor_course as $key => $values2) {
                    $instructor_revenue = $values2->instructor_revenue / 100 * $values->harga;
                    $admin_revenue = $values->harga - $instructor_revenue;
                }
                if ($response->payment_type != 'bank_transfer') {
                    $this->model_payment->insert([
                        'id_payment' => $response->order_id,
                        'id_user' => $id_user,
                        'payment_type' => $response->payment_type,
                        'course_id' => $values->id_kursus,
                        'amount' => $values->harga,
                        'admin_revenue' => $admin_revenue,
                        'instructor_revenue' => $instructor_revenue,
                        'status_payment' => 2,
                        'status' => 0

                    ]);
                } else {
                    $bank_name = $response->va_numbers[0];
                    $this->model_payment->insert([
                        'id_payment' => $response->order_id,
                        'id_user' => $id_user,
                        'payment_type' => $response->payment_type,
                        'payment_bank' => $bank_name->bank,
                        'payment_va'   => $bank_name->va_number,
                        'course_id' => $values->id_kursus,
                        'amount' => $values->harga,
                        'admin_revenue' => $admin_revenue,
                        'instructor_revenue' => $instructor_revenue,
                        'status_payment' => 2,
                        'status' => 0

                    ]);
                }
            }
        }

        return $this->respondCreated($response);
    }
}
