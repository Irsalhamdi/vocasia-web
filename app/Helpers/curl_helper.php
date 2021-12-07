<?php

use Config\Services;

function curlRequest($data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.midtrans.com/v2/charge',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
    "payment_type": ' . $data . ',
    "transaction_details": {
        "gross_amount": 44000,
        "order_id": "order-101c-1638888706"
    },
    "customer_details": {
        "email": "noreply@example.com",
        "first_name": "budi",
        "last_name": "utomo",
        "phone": "+6281 1234 1234"
    },
    "item_details": [
    {
       "id": "item01",
       "price": 21000,
       "quantity": 1,
       "name": "Ayam Zozozo"
    },
    {
       "id": "item02",
       "price": 23000,
       "quantity": 1,
       "name": "Ayam Xoxoxo"
    }
   ],
   "bank_transfer":{
     "bank": "bca",
     "free_text": {
          "inquiry": [
                {
                    "id": "Your Custom Text in ID language",
                    "en": "Your Custom Text in EN language"
                }
          ],
          "payment": [
                {
                    "id": "Your Custom Text in ID language",
                    "en": "Your Custom Text in EN language"
                }
          ]
    }
  }
}',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic U0ItTWlkLXNlcnZlci02SzE4S3E0Y1FLMlhOTk5lazZmSWwyUjk='
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    die;
}
