<?php

namespace App\Models;

use CodeIgniter\Model;

class QnaReplyModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'qna_replies';
    protected $primaryKey       = 'id_rep';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_qna', 'sender', 'text_rep', 'date_add', 'up'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'sender' => 'required|integer',
        'text_rep' => 'required'
    ];
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

    public function get_qna_detail_reply($id_qna){

        return $this->db->table('qna_replies')->select('qna_replies.*, b.first_name, b.id as ids, b.last_name')
                        ->join('users b', 'b.id = qna_replies.sender', 'left')
                        ->where('qna_replies.id_qna', $id_qna)
                        ->get()
                        ->getResultArray();
    }

}
