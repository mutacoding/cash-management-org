<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_jenis';
    protected $primaryKey       = 'id_jenis';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'jenis'
    ];

    public function createJenis($save)
    {
        return $this->db->table('tb_jenis')->insert($save);
    }

    public function updateJenis($id, $update)
    {
        return $this->update(['id_jenis' => $id], $update);
    }

    public function deleteJenis($id)
    {
        return $this->delete(['id_jenis' => $id]);
    }
}
