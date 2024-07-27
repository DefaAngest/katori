<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Login
 *
 * @author user
 */
class Login extends MY_Controller{
    public function __construct() {
        parent::__construct();
        
        $is_login = $this->session->userdata('is_login');
        if ($is_login) {
            redirect(base_url());
            return;
        }
    }
    //put your code here
    public function index()
    {
        if (!$_POST) {
            $input = (object) $this->login->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if (!$this->login->validate()) {    // Jika validasi gagal
            $data['title'] = 'Login';
            $data['input'] = $input;
            $data['page'] = 'page/login';

            $this->view($data);

            return;
        }

        if ($this->login->run($input)) {
            $this->session->set_flashdata('success', 'Berhasil melakukan login');
            redirect(base_url());
        } else {
            $this->session->set_flashdata('error', 'E-mail/Password salah ');
            redirect(site_url('login'));
        }
    }
    
    public function logout()
    {
        $sess_data = ['id', 'name', 'email', 'level', 'is_login'];

        $this->session->unset_userdata($sess_data);
        $this->session->sess_destroy();
        redirect(base_url());
        echo "After Unset: ";
        var_dump($this->session->userdata());
    }
    
}

