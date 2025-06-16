<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login', [
            'pesan' => session()->getFlashdata('pesan'),
            'validation' => session()->getFlashdata('validation')
        ]);
    }

    public function login_action()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            // kasih validation ke flashdata supaya bisa diambil di view
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $session = session();
        $loginModel = new LoginModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $loginModel->where('username', $username)->first();

        if (!$user) {
            $session->setFlashdata('pesan', 'Username tidak ditemukan!');
            return redirect()->back()->withInput();
        }

        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('pesan', 'Password salah! Silakan coba lagi.');
            return redirect()->back()->withInput();
        }

        $status = strtolower(trim($user['status'] ?? ''));
        $allowed_status = ['aktif', 'aktiv'];
        if (!in_array($status, $allowed_status)) {
            $session->setFlashdata('pesan', 'Akun belum aktif atau status tidak valid!');
            return redirect()->back()->withInput();
        }

        $role = strtolower(trim($user['role'] ?? ''));

        if (empty($role)) {
            log_message('error', 'Role user kosong untuk username: ' . $username);
            $session->setFlashdata('pesan', 'Role tidak ditemukan dalam database.');
            return redirect()->back()->withInput();
        }

        $session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $role,
            'is_logged_in' => true,
            'id_pegawai' => $user['id_pegawai'] ?? $user['id'], // fallback ke id kalau id_pegawai kosong
        ]);

        $routes = [
            'admin' => '/admin/home',
            'pegawai' => '/pegawai/home',
            'kepsek'  => '/kepsek/home',
        ];

        if (isset($routes[$role])) {
            return redirect()->to($routes[$role]);
        }

        $allowedRoles = ['admin', 'pegawai', 'kepsek'];
        if (!in_array($role, $allowedRoles)) {
            $session->setFlashdata('pesan', 'Role tidak dikenali. Silakan hubungi administrator.');
            return redirect()->back()->withInput();
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
