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
class Home extends MY_Controller{
    
    //put your code here
    public function index()
    {
       $data['title'] = 'Homepage';
       $data['content'] = $this->home->select(
               [
                   'menu.id_menu', 'menu.nama_menu', 'menu.toko', 'menu.detail_menu', 
                   'menu.harga', 'menu.image', 'toko.id_toko', 'toko.nama_toko', 'toko.kota AS kota'
               ]
       )
       ->join('toko', 'toko.nama_toko = menu.toko')
       ->get();
       $data['page'] = 'page/index';
       
       $this->view($data);
       
    }
    
}
