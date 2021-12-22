<?php

namespace App\Models;

use CodeIgniter\Model;

class QnaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'qna_thread';
    protected $primaryKey       = 'id_qna';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['sender', 'title', 'quest', 'id_course', 'id_lesson', 'up', 'user_id_up', 'date_added', 'status'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'create_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'sender' => 'required|integer',
        'title' => 'required',
        'quest' => 'required',
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

    public function check_qna($id_qna){

        return $this->db->table('qna_thread')
                        ->select("qna_thread.id_qna")
                        ->where('id_qna', $id_qna)
                        ->get()
                        ->getRow();
    }

    public function search($id_course, $data){

        $text = $data->text;

        return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.id as ids, b.last_name')
                                             ->join('users b', 'b.id = qna_thread.sender', 'left')
                                             ->where('qna_thread.id_course', $id_course)
                                             ->like('qna_thread.title', $text, 'BOTH')
                                             ->orLike('qna_thread.quest', $text, 'BOTH')
                                             ->orderBy('qna_thread.id_qna', 'desc')
                                             ->get()
                                             ->getResultArray();
    }   

    public function all($idc, $idl, $fil1, $fil2){

        if ($fil1 == 'all' && $fil2 == 'recent') {
            return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.last_name,b.id as ids, count(c.id_qna) as jum_rep')
                            ->join('users b', 'b.id = qna_thread.sender', 'left')
                            ->join('qna_replies c', 'qna_thread.id_qna = c.id_qna', 'left')
                            ->where('qna_thread.id_course', $idc)
                            ->orderBy('qna_thread.id_qna', 'DESC')                            
                            ->groupBy('qna_thread.id_qna')
                            ->get(5, 0)
                            ->getResultArray();
        }
        if($fil1 == 'all' && $fil2 != 'recent'){
            return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.last_name,b.id as ids, count(c.id_qna) as jum_rep')
                            ->join('users b', 'b.id = qna_thread.sender', 'left')
                            ->join('qna_replies c', 'qna_thread.id_qna = c.id_qna', 'left')
                            ->where('qna_thread.id_course', $idc)
                            ->orderBy('qna_thread.up', 'DESC')    
                            ->groupBy('qna_thread.id_qna')                            
                            ->get(5, 0)
                            ->getResultArray();                                        
        }
        if($fil1 != 'all' && $fil2 == 'recent'){
            return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.last_name,b.id as ids, count(c.id_qna) as jum_rep')
                            ->join('users b', 'b.id = qna_thread.sender', 'left')
                            ->join('qna_replies c', 'qna_thread.id_qna = c.id_qna', 'left')
                            ->where('qna_thread.id_course', $idc)
                            ->where('qna_thread.id_lesson', $idl)
                            ->orderBy('qna_thread.id_qna', 'DESC')                               
                            ->groupBy('qna_thread.id_qna')                            
                            ->get(5, 0)
                            ->getResultArray();                                                   
        }        
    }

    public function more($idc, $idl, $fil1, $fil2){
        
        if ($fil1 == 'all' && $fil2 == 'recent') {
            return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.last_name,b.id as ids, count(c.id_qna) as jum_rep')
                            ->join('users b', 'b.id = qna_thread.sender', 'left')
                            ->join('qna_replies c', 'qna_thread.id_qna = c.id_qna', 'left')
                            ->where('qna_thread.id_course', $idc)
                            ->orderBy('qna_thread.id_qna', 'DESC')                            
                            ->groupBy('qna_thread.id_qna')
                            ->get()
                            ->getResultArray();
        }
        if($fil1 == 'all' && $fil2 != 'recent'){
            return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.last_name,b.id as ids, count(c.id_qna) as jum_rep')
                            ->join('users b', 'b.id = qna_thread.sender', 'left')
                            ->join('qna_replies c', 'qna_thread.id_qna = c.id_qna', 'left')
                            ->where('qna_thread.id_course', $idc)
                            ->orderBy('qna_thread.up', 'DESC')    
                            ->groupBy('qna_thread.id_qna')                            
                            ->get()
                            ->getResultArray();                                        
        }
        if($fil1 != 'all' && $fil2 == 'recent'){
            return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.last_name,b.id as ids, count(c.id_qna) as jum_rep')
                            ->join('users b', 'b.id = qna_thread.sender', 'left')
                            ->join('qna_replies c', 'qna_thread.id_qna = c.id_qna', 'left')
                            ->where('qna_thread.id_course', $idc)
                            ->where('qna_thread.id_lesson', $idl)
                            ->orderBy('qna_thread.id_qna', 'DESC')                               
                            ->groupBy('qna_thread.id_qna')                            
                            ->get()
                            ->getResultArray();                                                   
        }
    }

    public function get_qna_detail($id_qna){
    
        return $this->db->table('qna_thread')->select('qna_thread.*, b.first_name, b.id as ids, b.last_name')
                        ->join('users b', 'b.id = qna_thread.sender', 'left')
                        ->where('qna_thread.id_qna', $id_qna)
                        ->get()
                        ->getRow();
    }

    public function get_qna_count($id_course){

        return $this->db->table('qna_thread')->select("qna_thread.*")
                        ->where('qna_thread.id_course', $id_course)
                        ->countAllResults();
    }

    public function check_up($id_qna){

        return $this->db->table('qna_thread')
                        ->select('qna_thread.up')
                        ->where('id_qna', $id_qna)
                        ->get()
                        ->getRow();
    }

    public function up($id_qna){

        return $this->db->table('qna_thread')
                        ->select('qna_thread.user_id_up')
                        ->where('qna_thread.id_qna', $id_qna)
                        ->get()
                        ->getRow();
    }

    public function do_up_qna($id, $data){

        return $this->db->table('qna_thread')
                        ->where('id_qna', $id)
                        ->update($data);
    }

    public function get_up($id_qna){

        return $this->db->table('qna_thread')->select('user_id_up')
                        ->where('qna_thread.id_qna', $id_qna)
                        ->get()
                        ->getRow();
    }

}
