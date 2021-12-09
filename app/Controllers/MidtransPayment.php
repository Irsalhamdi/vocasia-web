<?php

namespace App\Controllers;

use App\Models\CoursesModel;
use App\Models\EnrolModel;
use CodeIgniter\RESTful\ResourceController;
use Midtrans\Config as MidtransConfig;
use Config\Services;
use Exception;
use Midtrans\CoreApi as CoreApi;
use Midtrans\Notification as Notification;

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
        $this->enrol_model = model('EnrolModel');
        helper('curl');
        MidtransConfig::$overrideNotifUrl = 'https://api.vocasia.pasia.id/midtrans/payment/notification';
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
            $url_request = curlRequest($transaction_data);
            $response = $url_request;
        } elseif ($data_invoice->payment_type == 'cstore' && $data_invoice->store == 'alfamart') {
            $transaction_data = array(
                'payment_type' => $data_invoice->payment_type,
                'transaction_details' => $data_transaction,
                'item_details'        => $data_items,
                'customer_details'    => $data_customer,
                'cstore' => array(
                    'store' => $data_invoice->store,
                    'message' => "Message",
                    "alfamart_free_text_1" => 'Pembayaran Kursus Vocasia'
                )
            );
            $response = curlRequest($transaction_data);
        } elseif ($data_invoice->payment_type == 'cstore' && $data_invoice->store == 'indomaret') {
            $transaction_data = array(
                'payment_type' => $data_invoice->payment_type,
                'transaction_details' => $data_transaction,
                'item_details'        => $data_items,
                'customer_details'    => $data_customer,
                'cstore' => array(
                    'store' => $data_invoice->store,
                    "message" => 'Pembayaran Kursus Platform Vocasia'
                )
            );
            $response = curlRequest($transaction_data);
        } elseif ($data_invoice->payment_type == 'echannel') {
            $transaction_data = array(
                'payment_type' => $data_invoice->payment_type,
                'transaction_details' => $data_transaction,
                'item_details'        => $data_items,
                'customer_details'    => $data_customer,
                'echannel' => array(
                    'bill_info1' => 'Pembayaran Kursus Platform Vocasia',
                    'bill_info2' => 'Pembayaran Online E Channel Mandiri'
                )
            );
            $response = curlRequest($transaction_data);
        }
        if ($response->transaction_status == 'pending') {
            foreach ($data_invoice->data_invoice as $key => $values) {
                $find_instructor_course = $this->course_model->select('instructor_revenue')->where('id', $values->id_kursus)->get()->getResult();
                foreach ($find_instructor_course as $key => $values2) {
                    if (!is_null($values2->instructor_revenue)) {
                        $instructor_revenue = $values2->instructor_revenue / 100 * $values->harga;
                        $admin_revenue = $values->harga - $instructor_revenue;
                    } else {
                        $instructor_revenue = 0;
                        $admin_revenue = $values->harga;
                    }
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

    public function notify_transaction()
    {
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $notification = $notif->getResponse();
        if ($notification->transaction_status == 'settlement') {
            if ($notification->fraud_status == 'accept') {
                $id_payment = $notification->order_id;
                $this->model_payment->where('id_payment', $id_payment)->set(['status_payment' => 1])->update();
                $this->_enrolled_user($id_payment);
            }
        }
    }

    private function _enrolled_user($id_payment)
    {
        try {
            $find_user_id = $this->model_payment->where('id_payment', $id_payment)->get()->getResult();
            $check_enrolled = $this->enrol_model->where('payment_id', $id_payment)->get()->getResult();
            if (!empty($find_user_id) && empty($check_enrolled)) {
                foreach ($find_user_id as $key => $value) {
                    $this->enrol_model->insert([
                        'user_id' => $value->id_user,
                        'course_id' => $value->course_id,
                        'payment_id' => $value->id_payment,
                    ]);
                }
            } else {
                return $this->fail('You Have Enrolled This Course !');
            }
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }
}
