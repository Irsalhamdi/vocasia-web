<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\JWT;
use App\Models\UsersModel;
use CodeIgniter\I18n\Time;
use Exception;

use function PHPUnit\Framework\throwException;

class AuthFilter implements FilterInterface
{

    public function __construct()
    {
        $this->user_model = new UsersModel();
        $this->secret_key = Services::getSecretKey();
    }
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        $user_model = $this->user_model;
        $auth_header = $request->getServer('HTTP_AUTHORIZATION');
        if (!is_null($auth_header)) {
            $secret_key = $this->secret_key;
            $explode_white_space = explode(' ', $auth_header);
            $token = $explode_white_space[1];
            if ($this->CheckExpiredToken($token) === true) {
                try {
                    $decode_jwt = JWT::decode($token, $secret_key, array('HS256'));
                    $validate_user = $user_model->validate_user($decode_jwt->email);
                    if ($validate_user->role_id == $decode_jwt->role) {
                        if ($arguments[0] == 'admin') {
                            if ($decode_jwt->role_name == 'admin') {
                                return $request;
                            } else {
                                $response_invalid = [
                                    'status' => 401,
                                    'messages' => 'Cannot Acces This Resources! Invalid Users !'
                                ];
                                return Services::response()->setStatusCode('401')->setJSON($response_invalid);
                            }
                        } else if ($arguments[0] == 'user') {
                            if ($decode_jwt->role_name == 'user') {
                                return $request;
                            } else {
                                $response_invalid = [
                                    'status' => 401,
                                    'messages' => 'Cannot Acces This Resources! Invalid Users !'
                                ];
                                return Services::response()->setStatusCode('401')->setJSON($response_invalid);
                            }
                        }
                    }
                    $response_invalid = [
                        'status' => 401,
                        'messages' => 'Cannot Acces This Resources! Invalid Users !'
                    ];
                    return Services::response()->setStatusCode('401')->setJSON($response_invalid);
                } catch (\Throwable $th) {
                    return Services::response()->setStatusCode(404)->setJSON([
                        'status' => 404,
                        'message' => 'token invalid !'
                    ]);
                    die;
                }
            } else {
                return Services::response()->setStatusCode(404)->setJSON([
                    'status' => 404,
                    'message' => 'token invalid !'
                ]);
                die;
            }
        } else {
            $response_unauthorize = [
                'status' => 403,
                'messages' => 'Unauthorize Access! Token Not Found !'
            ];
            return Services::response()->setStatusCode(403)->setJSON($response_unauthorize);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }

    public function CheckExpiredToken($token)
    {
        $time = new Time();
        $decode_jwt = JWT::decode($token, $this->secret_key, array('HS256'));
        $time_now = strtotime($time->now('Asia/Jakarta', 'en_US'));
        if ($decode_jwt->expire_at > $time_now) {
            return true;
        } else {
            return false;
        }
    }
}
