<?php

function get_response($data)
{
    $response = [
        'status' => 200,
        'error' => false,
        'data' => [
            $data
        ]
    ];
    return $response;
}

function response_create()
{
    $response = [
        'status' => 201,
        'error' => false,
        'data' => [
            'messages' => 'data successfull created'
        ]
    ];
    return $response;
}

function response_update()
{
    $response = [
        'status' => 200,
        'error' => false,
        'data' => [
            'messages' => 'data successfull updated'
        ]
    ];
    return $response;
}

function response_delete()
{
    $response = [
        'status' => 200,
        'error' => false,
        'data' => [
            'messages' => 'data successfull deleted'
        ]
    ];
    return $response;
}
function response_failed()
{
    $response = [
        'status' => 500,
        'error' => true,
        'data' => [
            'messages' => 'Invalid Data'
        ]
    ];
    return $response;
}
