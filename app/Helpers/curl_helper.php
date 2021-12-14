<?php

use Config\Services;

function curlRequest($data)
{
    $client = \Config\Services::curlrequest();
    $url_request = $client->setBody(json_encode($data))->request('post', 'https://api.sandbox.midtrans.com/v2/charge', array(
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Basic U0ItTWlkLXNlcnZlci02SzE4S3E0Y1FLMlhOTk5lazZmSWwyUjk=',
            'Content-Type' => 'application/json'
        ]
    ));
    $response = json_decode($url_request->getBody());
    return $response;
}
