<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class MidtransPayment extends BaseController
{
    public function index()
    {
        \Midtrans\Config::$serverKey = 'SB-Mid-server-6K18Kq4cQK2XNNNek6fIl2R9';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;

        $client_key = 'SB-Mid-client-JKeQLtkIddmCivY5';

        $transaction_details = array(
            'order_id'    => time(),
            'gross_amount'  => 200000
        );

        $items = array(
            array(
                'id'       => 'item1',
                'price'    => 100000,
                'quantity' => 1,
                'name'     => 'Adidas f50'
            ),
            array(
                'id'       => 'item2',
                'price'    => 50000,
                'quantity' => 2,
                'name'     => 'Nike N90'
            )
        );

        // Populate customer's billing address
        $billing_address = array(
            'first_name'   => "Andri",
            'last_name'    => "Setiawan",
            'address'      => "Karet Belakang 15A, Setiabudi.",
            'city'         => "Jakarta",
            'postal_code'  => "51161",
            'phone'        => "081322311801",
            'country_code' => 'IDN'
        );

        // Populate customer's shipping address
        $shipping_address = array(
            'first_name'   => "John",
            'last_name'    => "Watson",
            'address'      => "Bakerstreet 221B.",
            'city'         => "Jakarta",
            'postal_code'  => "51162",
            'phone'        => "081322311801",
            'country_code' => 'IDN'
        );

        // Populate customer's info
        $customer_details = array(
            'first_name'       => "Andri",
            'last_name'        => "Setiawan",
            'email'            => "test@test.com",
            'phone'            => "081322311801",
            'billing_address'  => $billing_address,
            'shipping_address' => $shipping_address
        );

        $transaction_data = array(
            'payment_type' => 'gopay',
            'credit_card'  => array(
                'token_id'      => $client_key,
                'authentication' => true,
                //        'bank'          => 'bni', // optional to set acquiring bank
                //        'save_token_id' => true   // optional for one/two clicks feature
            ),
            'transaction_details' => $transaction_details,
            'item_details'        => $items,
            'customer_details'    => $customer_details
        );

        $response = \Midtrans\CoreApi::charge($transaction_data);

        var_dump($response);
    }
}
