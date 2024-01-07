<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    protected $admin;
    protected $validation;

    public function __construct()
    {
        $this->admin = new AdminModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Halaman Login'
        ];
        return view('auth/v_login', $data);
    }

    public function login()
    {
        $valid = $this->validate([
            'en_email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'valid_email' => '{field} tidak valid'
                ]
            ],

            'en_pass' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]

        ]);

        if (!$valid) {
            $msg = [
                'error' => [
                    'en_email' => $this->validation->getError('en_email'),
                    'en_pass' => $this->validation->getError('en_pass'),
                ]
            ];

            return $this->response->setJSON($msg);
        } else {
            $email = $this->request->getPost('en_email');
            $pass = $this->request->getPost('en_pass');

            // var_dump($email);
            // die;

            $cek_user = $this->admin->where(['email' => $email])->first();

            if ($cek_user) {
                if ($pass == $cek_user['pass']) {
                    $set_session = [
                        'id_admin' => $cek_user['id_admin'],
                        'nama' => $cek_user['nama'],
                        'email' => $cek_user['email'],
                        'pwsd' => $cek_user['pass'],
                        'periode' => $cek_user['periode'],
                        'login' => true,
                    ];

                    session()->set($set_session);

                    $respon = [
                        'status' => true,
                        'msg' => 'Anda berhasil login',
                        'link' => base_url('dashboard'),
                    ];
                } else {
                    $respon = [
                        'status' => false,
                        'msg' => 'Password anda salah!'
                    ];
                }
            } else {
                $respon = [
                    'status' => false,
                    'msg' => 'Email anda tidak terdaftar silahkan registrasi!'
                ];
            }

            return $this->response->setJSON($respon);
        }
    }

    public function logout()
    {
        session()->destroy();

        $respon = [
            'status' => true,
            'msg' => 'Anda berhasil logout',
            'link' => base_url('')
        ];

        return $this->response->setJSON($respon);
    }

    public function viewProfil()
    {
        return view('admin/v_profil');
    }

    public function getProfil()
    {
        $profil = '<form id="e_profil" method="post" action="Javascript:Profil();">
                    ' . csrf_field() . '
                    <input type="hidden" name="en_id" value="' . session()->get('id_admin') . '">
                    <div class="form-group row">
                    <label for="en_nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text"value="' . session()->get('nama') . '" id="en_nama" name="en_nama">
                    </div>
                    </div>
                    <div class="form-group row">
                    <label for="en_email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" value="' . session()->get('email') . '" id="en_email" name="en_email" readonly>
                        <input class="form-control" type="hidden" value="' . session()->get('email') . '" id="en_email" name="en_email">
                    </div>
                    </div>
                    <div class="form-group row">
                    <label for="en_alamat" class="col-sm-2 col-form-label">Periode</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" value="' . session()->get('periode') . '" id="en_periode" name="en_periode">
                    </div>
                    </div>
                    <div class="form-group row">
                    <button type="submit" class="btn btn-primary mx-3" id="btn_update">Update Profile</button>
                    </div>
                </form>';

        return $this->response->setJSON(['status' => $profil]);
    }

    public function updateProfil()
    {
        $id = $this->request->getPost('en_id');

        $update = [
            'nama' => $this->request->getPost("en_nama"),
            'email' => $this->request->getPost("en_email"),
            'periode' => $this->request->getPost("en_periode"),
        ];

        $result = $this->admin->updateUser($id, $update);

        session()->set($update);

        if ($result) {
            $respon = [
                'status' => true,
                'msg' => 'Profil berhasil diubah!!'
            ];
        }

        return $this->response->setJSON($respon);
    }

    public function viewPassword()
    {
        return view('admin/v_password');
    }

    public function getPassword()
    {
        // var_dump(session()->get('pwsd'));
        // die;
        $pass = '<form id="e_pass" method="post" action="Javascript:Password();">
                    ' . csrf_field() . '
                    <input type="hidden" name="en_id" value="' . session()->get('id_admin') . '">
                    <div class="form-group row">
                        <label for="en_nama" class="col-sm-2 col-form-label">Password Lama</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" value="' . session()->get('pwsd') . '" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="en_email" class="col-sm-2 col-form-label">Password Baru</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="new_pass" name="new_pass">
                            <small class="text-danger err-new"></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="en_alamat" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="cnew_pass" name="cnew_pass">
                            <small class="text-danger err-cnew"></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <button type="submit" class="btn btn-primary mx-3" id="btn_update">Update Password</button>
                    </div>
                </form>';

        return $this->response->setJSON(['status' => $pass]);
    }

    public function updatePassword()
    {
        $id = $this->request->getPost('en_id');

        $valid = $this->validate([
            'new_pass' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'cnew_pass' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'matches[new_pass]',
                'errors' => [
                    'matches' => '{field} tidak sama'
                ]
            ]

        ]);

        if (!$valid) {
            $msg = [
                'error' => [
                    'new_pass' => $this->validation->getError('new_pass'),
                    'cnew_pass' => $this->validation->getError('cnew_pass'),
                ]
            ];

            return $this->response->setJSON($msg);
        } else {

            $update = [
                'pass' => $this->request->getPost("new_pass"),
            ];

            $result = $this->admin->updatePassword($id, $update);

            session()->set($update);

            if ($result) {
                $respon = [
                    'status' => true,
                    'msg' => 'Password berhasil diubah!!'
                ];
            }
        }

        return $this->response->setJSON($respon);
    }
}
