<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\Response;
use Config\Services;
use \Firebase\JWT\JWT;

class Auth extends ResourceController
{
    public function __construct()
    {
        $this->model = new UsersModel();
        helper(['response', 'cookie']);
    }

    public function register()
    {
        $register_data = $this->request->getJSON();
        $name = explode(' ', $register_data->name);
        $this->model->insert([
            'first_name' => $name[0],
            'last_name' => $name[1],
            'email' => $register_data->email,
            'password' => sha1($register_data->password),
            'role_id' => 2,
            'is_verified' => 'deactive',
            'create_at' => strtotime(date('D,d-M-Y'))
        ]);

        return $this->respondCreated(response_register());
    }

    public function login()
    {
        $credentials = $this->request->getJSON();
        $valid_credentials = $this->_check_login($credentials);
        if ($valid_credentials) {
            $key = Services::getSecretKey();
            $iat = time(); //masa berlaku dalam timestamp
            $nbf = $iat + 10;
            $exp = $iat + 3600;

            $payload = [
                'name' => $valid_credentials["fullname"],
                'email' => $valid_credentials["email"],
                'role' => $valid_credentials["role"],
                'expire_at' => $exp
            ];
            $jwt = JWT::encode($payload, $key);
            $cookie = [
                'name'   => 'TOKEN',
                'value'  => $jwt,
                'expire' => $exp,
                'domain' => 'localhost',
                'path'   => '/',
                'prefix' => '',
                'secure' => true,
                'httponly' => true,
            ];
            $this->response->setCookie($cookie);
            return $this->respond(response_login($exp));
        } else {
            return $this->failNotFound();
        }
    }

    private function _check_login($credentials)
    {
        $user_data = $this->model->where('email', $credentials->email)->first();
        if (!is_null($user_data) && sha1($credentials->password) == $user_data["password"]) {
            $data = [
                'fullname' => $user_data["first_name"] . " " . $user_data["last_name"],
                'email' => $user_data["email"],
                'role' => $user_data["role_id"]
            ];
            return $data;
        }
        return false;
    }

    public function logout()
    {
        delete_cookie('TOKEN');
        return $this->respond(response_logout());
    }
}
