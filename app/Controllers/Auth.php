<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;
use Config\Services;
use \Firebase\JWT\JWT;
use CodeIgniter\I18n\Time;

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

    /* Login untuk frontend website*/

    public function login()
    {
        $credentials = $this->request->getJSON();
        $generate_token = $this->_generate_token($credentials);
        if (!is_null($generate_token)) {
            $cookie = [
                'name'   => 'TOKEN',
                'value'  => $generate_token['token'],
                'expire' => 86400, // masa berlaku 24 jam
                'path'   => '/',
                'prefix' => '',
                'secure' => true,
                'httponly' => false,
            ];
            $this->response->setCookie($cookie);
            $response_data = [
                'is_mobile' => false,
                'exp' => $generate_token['expired_at'],
            ];
            return $this->respond(response_login($response_data));
        } else {
            return $this->failNotFound();
        }
    }

    public function login_mobile()
    {
        $credentials_login_mobile = $this->request->getJSON();
        $generate_token_for_mobile = $this->_generate_token($credentials_login_mobile);
        if (!is_null($generate_token_for_mobile)) {
            $response_data = [
                'is_mobile' => true,
                'token' => $generate_token_for_mobile['token'],
                'exp' => $generate_token_for_mobile['expired_at'],
            ];
            return $this->respond(response_login($response_data));
        }
    }

    public function logout()
    {
        delete_cookie('TOKEN');
        return $this->respond(response_logout());
    }

    /* Check kredensial login user*/

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


    /* Generate JWT Token*/

    private function _generate_token($credentials_login)
    {
        $time = new Time();
        $valid_credentials = $this->_check_login($credentials_login);
        if ($valid_credentials) {
            $key = Services::getSecretKey();
            $iat = strtotime($time->now('Asia/Jakarta', 'en_US')); //masa berlaku dalam timestamp
            $nbf = $iat + 10;
            $exp = $iat + 1440;

            $payload = [
                'name' => $valid_credentials["fullname"],
                'email' => $valid_credentials["email"],
                'role' => $valid_credentials["role"],
                'expire_at' => $exp
            ];
            $jwt = JWT::encode($payload, $key);
            $tokens = [
                'token' => $jwt,
                'expired_at' => $exp
            ];
            return $tokens;
        } else {
            return null;
        }
    }
}
