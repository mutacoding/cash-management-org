<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_kategori';
    protected $primaryKey       = 'id_kategori';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kategori', 'jenis_id'
    ];

    public function allKategori()
    {
        $getData = $this->db->table('tb_kategori');
        $getData->join('tb_jenis', 'tb_jenis.id_jenis = tb_kategori.jenis_id');
        $query = $getData->get();
        return $query->getResultArray();
    }

    public function createKategori($save)
    {
        return $this->db->table('tb_kategori')->insert($save);
    }

    public function updateKategori($id, $update)
    {
        return $this->update(['id_kategori' => $id], $update);
    }

    public function deleteKategori($id)
    {
        return $this->delete(['id_kategori' => $id]);
    }

    public function selectKategori($id)
    {
        return $this->where(['jenis_id' => $id])->findAll();
    }
}
