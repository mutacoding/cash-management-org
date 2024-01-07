<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_admin';
    protected $primaryKey       = 'id_admin';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama', 'email', 'pass', 'periode'
    ];

    public function updateUser($id, $update)
    {
        return $this->update(['id_admin' => $id], $update);
    }

    public function updatePassword($id, $update)
    {
        return $this->update(['id_admin' => $id], $update);
    }
}
