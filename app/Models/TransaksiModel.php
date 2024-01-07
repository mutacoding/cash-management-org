<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_transaksi';
    protected $primaryKey       = 'id_transaksi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tgl_transaksi', 'kategori', 'keterangan', 'pemasukan', 'pengeluaran'
    ];

    // Dates
    //protected $useTimestamps = true;

    public function createTransaksi($save)
    {
        return $this->db->table('tb_transaksi')->insert($save);
    }

    public function pmsHariIni()
    {
        $tanggal_hari_ini = date("Y-m-d");
        $data = "SELECT SUM(pemasukan) as pmsHariIni from tb_transaksi WHERE tgl_transaksi = '$tanggal_hari_ini' ";
        return $data = $this->db->query($data)->getRowArray();
    }

    public function pmsBulanIni()
    {
        $tanggal_awal_bulan_ini = date("Y-m-01");
        $tanggal_akhir_bulan_ini = date("Y-m-t");
        $data = "SELECT SUM(pemasukan) as pmsBulanIni from tb_transaksi WHERE tgl_transaksi BETWEEN '$tanggal_awal_bulan_ini' AND '$tanggal_akhir_bulan_ini'";
        return $data = $this->db->query($data)->getRowArray();
    }

    public function totalPemasukan()
    {
        $data = "SELECT SUM(pemasukan) as totalPemasukan from tb_transaksi";
        return $data = $this->db->query($data)->getRowArray();
    }

    public function pngHariIni()
    {
        $tanggal_hari_ini = date("Y-m-d");
        $data = "SELECT SUM(pengeluaran) as pngHariIni from tb_transaksi WHERE tgl_transaksi = '$tanggal_hari_ini' ";
        return $data = $this->db->query($data)->getRowArray();
    }

    public function pngBulanIni()
    {
        $tanggal_awal_bulan_ini = date("Y-m-01");
        $tanggal_akhir_bulan_ini = date("Y-m-t");
        $data = "SELECT SUM(pengeluaran) as pngBulanIni from tb_transaksi WHERE tgl_transaksi BETWEEN '$tanggal_awal_bulan_ini' AND '$tanggal_akhir_bulan_ini'";
        return $data = $this->db->query($data)->getRowArray();
    }

    public function totalPengeluaran()
    {
        $data = "SELECT SUM(pengeluaran) as totalPengeluaran from tb_transaksi";
        return $data = $this->db->query($data)->getRowArray();
    }

    public function filterTransaksi($tgl_mulai, $tgl_sampai, $kategori)
    {
        if ($kategori == 'Semua') {
            $data = "SELECT * FROM `tb_transaksi` WHERE tgl_transaksi BETWEEN '$tgl_mulai' AND '$tgl_sampai' ";
            return $data = $this->db->query($data)->getResultArray();
        } else {
            $data = "SELECT * FROM `tb_transaksi` WHERE kategori = '$kategori' AND tgl_transaksi BETWEEN '$tgl_mulai' AND '$tgl_sampai' ";
            return $data = $this->db->query($data)->getResultArray();
        }
    }

    public function filterTotalTransaksi($tgl_mulai, $tgl_sampai, $kategori)
    {
        if ($kategori == 'Semua') {
            $data = "SELECT SUM(pemasukan) AS totalPemasukan, SUM(pengeluaran) AS totalPengeluaran FROM tb_transaksi WHERE tgl_transaksi BETWEEN '$tgl_mulai' AND '$tgl_sampai'";
            return $data = $this->db->query($data)->getRowArray();
        } else {
            $data = "SELECT SUM(pemasukan) AS totalPemasukan, SUM(pengeluaran) AS totalPengeluaran FROM tb_transaksi WHERE kategori = '$kategori' AND tgl_transaksi BETWEEN '$tgl_mulai' AND '$tgl_sampai'";
            return $data = $this->db->query($data)->getRowArray();
        }
    }
}
