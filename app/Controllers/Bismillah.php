<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JenisModel;
use App\Models\KategoriModel;
use App\Models\TransaksiModel;

class Bismillah extends BaseController
{
  protected $transaksi;
  protected $jenis;
  protected $kategori;
  protected $validation;

  public function __construct()
  {
    $this->transaksi = new TransaksiModel();
    $this->jenis = new JenisModel();
    $this->kategori = new KategoriModel();
    $this->validation = \Config\Services::validation();
  }

  public function index()
  {
    $data = [
      'pmsHariIni'        => $this->transaksi->pmsHariIni(),
      'pmsBulanIni'       => $this->transaksi->pmsBulanIni(),
      'totalPemasukan'    => $this->transaksi->totalPemasukan(),
      'pngHariIni'        => $this->transaksi->pngHariIni(),
      'pngBulanIni'       => $this->transaksi->pngBulanIni(),
      'totalPengeluaran'  => $this->transaksi->totalPengeluaran(),
    ];
    return view("admin/v_index", $data);
  }

  public function viewTransaksi()
  {
    $data = [
      'title'       => 'Data Transaksi',
      'jenis'       => $this->jenis->findAll(),
      'kategori'       => $this->kategori->findAll(),
    ];
    return view("admin/v_transaksi", $data);
  }

  public function getAllTransaksi()
  {
    $data['data'] = array();
    $result = $this->transaksi->findAll();
    $no = 1;
    foreach ($result as $key => $value) {
      $data['data'][$key] = array(
        $no,
        $value['tgl_transaksi'],
        $value['kategori'],
        $value['keterangan'],
        $value['pemasukan'] == 0 ? "-" : "Rp " . number_format($value['pemasukan'], 0, ",", "."),
        $value['pengeluaran'] == 0 ? "-" : "Rp " . number_format($value['pengeluaran'], 0, ",", "."),
      );
      $no++;
    }
    return $this->response->setJSON($data);
  }

  public function createTransaksi()
  {
    $valid = $this->validate([
      'en_ket' => [
        'label' => 'Keterangan',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong!!'
        ]
      ],

      'en_jml' => [
        'label' => 'Nominal',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong!!'
        ]
      ],
    ]);

    if (!$valid) {
      $msg = [
        'error' => [
          'en_kat' => $this->validation->getError('en_kat'),
          'en_jenis' => $this->validation->getError('en_jenis'),
          'en_ket' => $this->validation->getError('en_ket'),
          'en_jml' => $this->validation->getError('en_jml'),
        ]
      ];

      return $this->response->setJSON($msg);
    } else {

      $jenis = $this->request->getPost('en_jenis');

      if ($jenis == 1) {
        $save = [
          'tgl_transaksi' => date("Y-m-d"),
          'kategori' => $this->request->getPost('en_kat'),
          'keterangan' => $this->request->getPost('en_ket'),
          'pemasukan' => $this->request->getPost('en_jml'),
          'pengeluaran' => 0,
        ];
      } else if ($jenis == 2) {
        $save = [
          'tgl_transaksi' => date("Y-m-d"),
          'kategori' => $this->request->getPost('en_kat'),
          'keterangan' => $this->request->getPost('en_ket'),
          'pemasukan' => 0,
          'pengeluaran' => $this->request->getPost('en_jml'),
        ];
      }

      $status = $this->transaksi->createTransaksi($save);

      if ($status) {
        $respon = [
          'status' => true,
          'msg' => 'Transaksi berhasil ditambah!!'
        ];
      } else {
        $respon = [
          'status' => false,
          'msg' => 'Maaf, transaksi gagal ditambah!!'
        ];
      }

      return $this->response->setJSON($respon);
    }
  }

  public function selectKategori()
  {
    $id_jenis = $this->request->getPost('id_jenis');
    $kategori = $this->kategori->selectKategori($id_jenis);
    $select = '<option>Pilih Kategori</option>';
    foreach ($kategori as $value) {
      $select .= '
        <option value="' . $value['kategori'] . '">' . $value['kategori'] . '</option>;
      ';
    }

    return $this->response->setJSON(['status' => $select]);
  }

  public function viewLaporan()
  {
    $data = [
      'title'       => 'Laporan',
      'kategori'       => $this->kategori->findAll(),
    ];
    return view("admin/v_laporan", $data);
  }

  public function filterLaporan()
  {
    $tgl_mulai = $this->request->getPost('en_mulai');
    $tgl_sampai = $this->request->getPost('en_sampai');
    $kategori = $this->request->getPost('en_kat');

    $t_filter = $this->transaksi->filterTransaksi($tgl_mulai, $tgl_sampai, $kategori);
    $t_transaksi = $this->transaksi->filterTotalTransaksi($tgl_mulai, $tgl_sampai, $kategori);

    // var_dump($t_transaksi);
    // die;

    $no = 1;
    $total = $t_transaksi['totalPemasukan'] - $t_transaksi['totalPengeluaran'];

    $table = '<div class="row mb-2">
    <div class="col-lg-6">
      <table class="table table-borderless">
        <tbody>
          <tr>
            <td>Dari Tanggal</td>
            <td>:</td>
            <td>' . $tgl_mulai . '</td>
          </tr>
          <tr>
            <td>Sampai Tanggal</td>
            <td>:</td>
            <td>' . $tgl_sampai . '</td>
          </tr>
          <tr>
            <td>Kategori</td>
            <td>:</td>
            <td>' . $kategori . '</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <table class="table table-bordered" style="width: 100%;">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Kategori</th>
        <th scope="col">Keterangan</th>
        <th scope="col">Kas Masuk</th>
        <th scope="col">Kas Keluar</th>
      </tr>
    </thead>
    <tbody>';

    foreach ($t_filter as $value) {
      $table .=
        '
          <tr>
            <td>' . $no . '</td>
            <td>' . $value['tgl_transaksi'] . '</td>
            <td>' . $value['kategori'] . '</td>
            <td>' . $value['keterangan'] . '</td>
            <td>' . "Rp " . number_format($value['pemasukan'], 0, ",", ".") . '</td>
            <td>' . "Rp " . number_format($value['pengeluaran'], 0, ",", ".")  . '</td>
          </tr>
        ';
      $no++;
    }

    $table .=
      '<tr>
          <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
          <td>' . "Rp " . number_format($t_transaksi['totalPemasukan'], 0, ",", ".") . '</td>
          <td>' . "Rp " . number_format($t_transaksi['totalPengeluaran'], 0, ",", ".") . '</td>
        </tr>
        
        <tr>
          <td colspan="4" style="text-align: right; font-weight: bold;">Saldo</td>
          <td colspan="2" style="background-color: skyblue; text-align: center; font-weight: bold;">' . "Rp " . number_format($total, 0, ",", ".") . '</td>
        </tr>
      </tbody>
      </table>
    ';

    return $this->response->setJSON(['status' => $table]);
  }

  public function viewKategori()
  {
    $data = [
      'title'       => 'Kategori',
      'jenis'       => $this->jenis->findAll(),
    ];
    return view("admin/v_kategori", $data);
  }

  public function getAllKategori()
  {
    $data['data'] = array();
    $result = $this->kategori->allKategori();
    $no = 1;
    foreach ($result as $key => $value) {
      $ops = '<tr>';
      $ops .= '<a class="btn btn-success text-white" onclick="Edit(' . $value['id_kategori'] . ')"><i class="fa fa-pen"></i></a>';
      $ops .= '<a class="btn btn-danger text-white" onclick="Delete(' . $value['id_kategori'] . ')"><i class="fa fa-trash"></i></a>';
      $ops .= '</tr>';
      $data['data'][$key] = array(
        $no,
        $value['kategori'],
        $value['jenis'],
        $ops
      );
      $no++;
    }
    return $this->response->setJSON($data);
  }

  public function createKategori()
  {
    $valid = $this->validate([
      'en_kat' => [
        'label' => 'Kategori Kas',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong!!'
        ]
      ],
    ]);

    if (!$valid) {
      $msg = [
        'error' => [
          'en_kat' => $this->validation->getError('en_kat'),
        ]
      ];

      return $this->response->setJSON($msg);
    } else {

      $save = [
        'kategori' => $this->request->getPost('en_kat'),
        'jenis_id' => $this->request->getPost('en_jenis'),
      ];

      $status = $this->kategori->createKategori($save);

      if ($status) {
        $respon = [
          'status' => true,
          'msg' => 'Kategori berhasil ditambah!!'
        ];
      } else {
        $respon = [
          'status' => false,
          'msg' => 'Maaf, kategori gagal ditambah!!'
        ];
      }

      return $this->response->setJSON($respon);
    }
  }

  public function getOneKategori()
  {
    $id = $this->request->getPost('id');

    if ($this->validation->check($id, 'required|numeric')) {

      $data = $this->kategori->where('id_kategori', $id)->first();

      return $this->response->setJSON($data);
    } else {
      throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
  }

  public function updateKategori()
  {
    $id = $this->request->getPost("en_id");

    $update = [
      'kategori' => $this->request->getPost('en_kat'),
      'jenis_id' => $this->request->getPost('en_jenis'),
    ];

    $status = $this->kategori->updateKategori($id, $update);

    if ($status) {
      $respon = [
        'status' => true,
        'msg' => 'Kategori berhasil diubah!!'
      ];
    } else {
      $respon = [
        'status' => false,
        'msg' => 'Maaf, kategori gagal diubah!!'
      ];
    }

    return $this->response->setJSON($respon);
  }

  public function deleteKategori()
  {
    $id = $this->request->getPost("id");

    $status = $this->kategori->deleteKategori($id);

    if ($status) {
      $respon = [
        'status' => true,
        'msg' => 'Kategori berhasil dihapus!!'
      ];
    } else {
      $respon = [
        'status' => false,
        'msg' => 'Maaf, kategori gagal dihapus!!'
      ];
    }

    return $this->response->setJSON($respon);
  }

  public function viewJenis()
  {
    $data = [
      'title'       => 'Jenis Arus Kas',
    ];
    return view("admin/v_jenis", $data);
  }

  public function getAllJenis()
  {
    $data['data'] = array();
    $result = $this->jenis->select()->findAll();
    $no = 1;
    foreach ($result as $key => $value) {
      $ops = '<tr>';
      $ops .= '<a class="btn btn-success text-white" onclick="Edit(' . $value['id_jenis'] . ')"><i class="fa fa-pen"></i></a>';
      $ops .= '<a class="btn btn-danger text-white" onclick="Delete(' . $value['id_jenis'] . ')"><i class="fa fa-trash"></i></a>';
      $ops .= '</tr>';
      $data['data'][$key] = array(
        $no,
        $value['jenis'],
        $ops
      );
      $no++;
    }
    return $this->response->setJSON($data);
  }

  public function createJenis()
  {
    $valid = $this->validate([
      'en_jenis' => [
        'label' => 'Jenis Arus Kas',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong!!'
        ]
      ],
    ]);

    if (!$valid) {
      $msg = [
        'error' => [
          'en_jenis' => $this->validation->getError('en_jenis'),
        ]
      ];

      return $this->response->setJSON($msg);
    } else {

      $save = [
        'jenis' => $this->request->getPost('en_jenis'),
      ];

      $status = $this->jenis->createJenis($save);

      if ($status) {
        $respon = [
          'status' => true,
          'msg' => 'Jenis arus kas berhasil ditambah!!'
        ];
      } else {
        $respon = [
          'status' => false,
          'msg' => 'Maaf, jenis arus gagal ditambah!!'
        ];
      }

      return $this->response->setJSON($respon);
    }
  }

  public function getOneJenis()
  {
    $id = $this->request->getPost('id');

    if ($this->validation->check($id, 'required|numeric')) {

      $data = $this->jenis->where('id_jenis', $id)->first();

      return $this->response->setJSON($data);
    } else {
      throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
  }

  public function updateJenis()
  {
    $id = $this->request->getPost("en_id");

    $update = [
      'jenis' => $this->request->getPost('en_jenis'),
    ];

    $status = $this->jenis->updateJenis($id, $update);

    if ($status) {
      $respon = [
        'status' => true,
        'msg' => 'Jenis arus kas berhasil diubah!!'
      ];
    } else {
      $respon = [
        'status' => false,
        'msg' => 'Maaf, jenis arus kas gagal diubah!!'
      ];
    }

    return $this->response->setJSON($respon);
  }

  public function deleteJenis()
  {
    $id = $this->request->getPost("id");

    $status = $this->jenis->deleteJenis($id);

    if ($status) {
      $respon = [
        'status' => true,
        'msg' => 'Jenis arus berhasil dihapus!!'
      ];
    } else {
      $respon = [
        'status' => false,
        'msg' => 'Maaf, jenis arus gagal dihapus!!'
      ];
    }

    return $this->response->setJSON($respon);
  }
}
