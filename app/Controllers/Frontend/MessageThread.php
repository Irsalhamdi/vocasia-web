<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

use function PHPUnit\Framework\isEmpty;

class MessageThread extends FrontendController
{
    protected $format = 'json';

    public function sender($user_id = null)
    {
        $users = $this->model_message_thread->get_message_sender($user_id);
       
        if ($users) {
            foreach ($users as $user) {
            $data[] = [
                'id' => $user->message_thread_id,
                'receiver' => $user->receiver_first_name. ' ' .$user->receiver_last_name,
                'sender' => $user->sender_first_name. ' ' .$user->sender_last_name,
                'message thread code' => $user->message_thread_code,
                'last_message_timestamp' => $user->last_message_timestamp
            ];
        }
        return $this->respond(get_response($data));
        }else {
            return $this->failNotFound();
        }
    }
    
    public function receiver($user_id = null)
    {
        $users = $this->model_message_thread->get_message_receiver($user_id);
        if ($users) {
            foreach ($users as $user) {
                $data[] = [
                    'id' => $user->message_thread_id,
                    'receiver' => $user->receiver_first_name. ' ' .$user->receiver_last_name,
                    'sender' => $user->sender_first_name. ' ' .$user->sender_last_name,
                    'message thread code' => $user->message_thread_code,
                    'last_message_timestamp' => $user->last_message_timestamp
                ];
            }
            return $this->respond(get_response($data));
        }else {
            return $this->failNotFound();
        }

    }

    public function create($user_id = null)
    {
        $sender = $this->model_users->find($user_id);
        $receiver = $this->model_users->find($this->request->getVar('receiver'));
        
        if ($sender && $receiver) {
            $code = str_split('abcdefghijklmnopqrstuvwxyz' . '0123456789');
            shuffle($code);
            $code_rand = '';
            foreach (array_rand($code, 15) as $k) $code_rand .= $code[$k];

            $data = $this->request->getJson();
            $data->message_thread_code = $code_rand;
            $data->sender = $user_id;
            
            $this->model_message_thread->protect(false)->insert($data);
            return $this->respondCreated(response_create());
        }else {
            return $this->failNotFound();
        }
    }
}
