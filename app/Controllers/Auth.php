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
        $generate_token = $this->_generate_token($credentials, $type = "web");
        if (!is_null($generate_token)) {
            $cookie = [
                'name'   => 'REFRESHTOKEN',
                'value'  => $generate_token['refresh_token'],
                'expire' => 2678400, // masa berlaku 30 hari
                'path'   => '/',
                'prefix' => '',
                'secure' => true,
                'httponly' => true,
            ];
            $access_token = [
                'name'   => 'ACCESSTOKEN',
                'value'  => $generate_token['access_token'],
                'expire' => 172800, // masa berlaku 2 hari //172800
                'path'   => '/',
                'prefix' => '',
                'secure' => true,
                'httponly' => false,
            ];
            $this->response->setCookie($cookie);
            $this->response->setCookie($access_token);
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
        $generate_token_for_mobile = $this->_generate_token($credentials_login_mobile, $type = "mobile");
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
                'role' => $user_data["role_id"],
                'id' => base64_encode($user_data["id"])
            ];
            return $data;
        }
        return false;
    }


    /* Generate JWT Token*/

    private function _generate_token($credentials_login, $type)
    {
        $time = new Time();
        $valid_credentials = $this->_check_login($credentials_login);
        if ($valid_credentials == true) {
            $key = Services::getSecretKey();
            $iat = strtotime($time->now('Asia/Jakarta', 'en_US')); //masa berlaku dalam timestamp
            $nbf = $iat + 10;
            $exp = $iat + 43200; //30 hari masa aktif refresh_token
            $exp_access_token = $iat + 2880; // 2 hari masa aktif access_token 

            $payload_access_token = [
                'name' => $valid_credentials["fullname"],
                'email' => $valid_credentials["email"],
                'role' => $valid_credentials["role"],
                'expire_at' => $exp_access_token
            ];
            $payload_refresh_token = [
                'id' => $valid_credentials["id"]
            ];

            $jwt_refresh_token = JWT::encode($payload_refresh_token, $key);
            $jwt_access_token = JWT::encode($payload_access_token, $key);
            if ($type == 'web') {
                $tokens = [
                    'access_token' => $jwt_access_token,
                    'refresh_token' => $jwt_refresh_token,
                    'expired_at' => $exp_access_token
                ];
                return $tokens;
            } else if ($type == 'mobile') {
                $tokens = [
                    'token' => $jwt_access_token,
                    'expired_at' => $exp
                ];

                return $tokens;
            }
        } else {
            return null;
        }
    }
}
