<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of HomeController
 *
 * @author user
 */
class Toko extends MY_Controller{
    
    private $id_user;

    public function __construct()
    {
        parent::__construct();
        
        $is_login = $this->session->userdata('is_login');
        $this->id_user = $this->session->userdata('id_user');
        $this->nama_user = $this->session->userdata('name');

        if (!$is_login) {   // Jika ternyata belum ada session
            redirect(base_url());
            return;
        }
    }
    
    //put your code here
    public function index()
    {
       $data['title'] = 'katering';
       $data['content'] = $this->toko->select(
               [
                   'toko.id_toko', 'toko.nama_toko', 'toko.alamat', 
                   'toko.kota', 'toko.no_tlp_toko as tlp', 'toko.deskripsi_toko as desc', 'toko.image', 'data_pengguna.nama as pemilik'
               ]
       )
       ->join('data_pengguna', 'toko.user_id = data_pengguna.user_id')
       ->where('toko.id_user', $this->id_user)
       ->get();
       $data['page'] = 'page/toko/index';
//       var_dump($data); die;
       $this->view($data);
       
    }
    
}
