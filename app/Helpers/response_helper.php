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
        'status' => 400,
        'error' => true,
        'data' => [
            'messages' => 'Invalid Data'
        ]
    ];
    return $response;
}

function response_pagging($total_page, $data)
{
    $response = [
        'status' => 200,
        'error' => false,
        'totalPages' => $total_page,
        'data' => [
            $data
        ]
    ];
    return $response;
}

function response_register()
{
    $response = [
        'status' => 200,
        'error' => false,
        'data' => [
            'messages' => 'User Registered!'
        ],
    ];
    return $response;
}

function response_login($data)
{
    $response = [
        'status' => 200,
        'error' => false,
        'data' => [
            'messages' => 'Login Success!',
            'expire_at' => $data
        ],
    ];
    return $response;
}

function response_logout()
{
    $response = [
        'status' => 200,
        'error' => false,
        'data' => [
            'messages' => 'User Logged Out!'
        ],
    ];
    return $response;
}
