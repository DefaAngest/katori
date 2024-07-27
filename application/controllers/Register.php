<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $is_login = $this->session->userdata('is_login');
        
        if ($is_login) {
            redirect(base_url());   // Jika sudah login, redirect ke home
            return;
        }
    }

    public function index()
    {
        // Apakah ada post ke controller ini
        if (!$_POST) {
            $input = (object) $this->register->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if (!$this->register->validate()) {
            // Jika validasi gagal maka arahkan ke form register lagi
            $data['title'] = 'Register';
//            $data['input'] = $input;
            $data['page'] = 'page/register/regist_select';

            $this->view($data);

            return;
        }

        // Input data
        if ($this->register->run($input)) {
            $this->session->set_flashdata('success', 'Berhasil melakukan registrasi');
            redirect(base_url());
        } else {
            $this->session->set_flashdata('error', 'Oops terjadi suatu kesalahan');
            redirect(base_url('register'));
        }
    }
    
    public function regis_user()
    {
        // Apakah ada post ke controller ini
        if (!$_POST) {
            $input = (object) $this->register->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if (!$_POST) {
            // Jika validasi gagal maka arahkan ke form register lagi
            $data['title'] = 'Register-user';
            $data['input'] = $input;
            $data['page'] = 'page/register/regist_user';

            $this->view($data);

            return;
        }
        
        if ($id_user = $this->register->create($data)) { 
            // Ambil list cart yang telah dipesan user
            $pengguna = $this->db->where('id_user', $this->id_user) 
                ->get('user')
                ->result_array();

            // Modifikasi tiap cart
            foreach ($pengguna as $row) {
                $row['id_user'] = $id_user;             // Tambah kolom id_order
                unset($row['id']);
                $this->db->insert('data_pengguna', $row);    // Insert ke tabel data_user
            }


            $this->session->set_flashdata('success', 'Data berhasil disimpan');

            ;
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
            return $this->index($input);    // Kembali ke index dengan kirim last input
        }
        
        
        // Input data
        if ($this->register->run($input)) {
            $this->session->set_flashdata('success', 'Berhasil melakukan registrasi');
            
            
            
            redirect(base_url());
        } else {
            $this->session->set_flashdata('error', 'Oops terjadi suatu kesalahan');
            redirect(site_url('register/regis_user'));
        }
    }
    public function regis_katering()
    {
        // Apakah ada post ke controller ini
        if (!$_POST) {
            $input = (object) $this->register->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if (!$_POST) {
            // Jika validasi gagal maka arahkan ke form register lagi
            $data['title'] = 'Register-katering';
            $data['input'] = $input;
            $data['page'] = 'page/register/regist_Katering';

            $this->view($data);

            return;
        }
        
        // Input data
        if ($this->register->run_shop($input)) {
            var_dump($this->session->userdata)(); die;
            $this->session->set_flashdata('success', 'Berhasil melakukan registrasi');
            redirect(base_url());
        } else {
            $this->session->set_flashdata('error', 'Oops terjadi suatu kesalahan');
            redirect(site_url('register/regis_katering'));
        }
    }
}

/* End of file Register.php */
