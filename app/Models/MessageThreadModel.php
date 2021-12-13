<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageThreadModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'message_thread';
    protected $primaryKey       = 'message_thread_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['message_thread_code','sender','receiver','last_message_timestamp'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function get_message_sender($user_id)
    {
        return $this->db->table('message_thread a')
        ->select('a.*,b.first_name as sender_first_name,b.last_name as sender_last_name,c.first_name as receiver_first_name,c.last_name as receiver_last_name')
        ->join('users b','b.id = a.sender')
        ->join('users c','c.id = a.receiver')
        ->where('a.sender',$user_id)->get()->getResult();
    }
    
    public function get_message_receiver($user_id)
    {
        return $this->db->table('message_thread a')
        ->select('a.*,b.first_name as sender_first_name,b.last_name as sender_last_name,c.first_name as receiver_first_name,c.last_name as receiver_last_name')
        ->join('users b','b.id = a.sender')
        ->join('users c','c.id = a.receiver')
        ->where('a.receiver',$user_id)->get()->getResult();
    }
    
}
