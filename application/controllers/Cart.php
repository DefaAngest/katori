<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Cart
 *
 * @author user
 */
class Cart extends MY_Controller{
    //put your code here
    private $id;
    public function __construct()
    {
        parent::__construct();
        
        $is_login = $this->session->userdata('is_login');
        $this->id_user = $this->session->userdata('id_user');

        if (!$is_login) {   // Jika ternyata belum ada session
            redirect(base_url());
            return;
        }
    }
    
    public function index()
    {
        $data['title']      = 'Keranjang Belanja';
        $data['content']    = $this->cart->select([
                'cart.id_cart', 'cart.jumlah', 'cart.subtotal',
                'menu.nama_menu', 'menu.image', 'menu.harga'
            ])
            ->join('menu', 'cart.id_menu = menu.id_menu')
            ->where('cart.id_user', $this->id_user)
            ->get();
        $data['page']       = 'page/cart';
//        var_dump($data); die;
        return $this->view($data);
    }
    
    
    public function add()
    {
        if (!$_POST || $this->input->post('jumlah') < 50) {
            $this->session->set_flashdata('error', 'jumlah tidak boleh kosong / kurang dari 50');
            redirect(base_url());
        }elseif (!$_POST || $this->input->post('jumlah') > 10000) {
            $this->session->set_flashdata('error', 'jumlah melebihi batas pemesanan (10000)');
            redirect(base_url());
        } else {
           $input              = (object) $this->input->post(null, true);

            // Mengambil data produk yang dipilih, untuk mendapatkan price
            $this->cart->table  = 'menu';
            $menu           = $this->cart->where('id_menu', $input->id_menu)->first();
            
            // Mengambil data toko yang dipilih, untuk mendapatkan price
            $this->cart->table  = 'menu';
            $toko           = $this->cart->where('id_menu', $input->id_menu)->first();

            // Ambil cart untuk dicek apakah user sudah pesan
            $this->cart->table  = 'cart';
            $cart               = $this->cart->where('id_user', $this->id)->where('id_menu', $input->id_menu)->first();

            $subtotal           = $menu->harga * $input->jumlah;

            if ($cart) {    // Jika ternyata user sudah pesan, maka update cart
                $data = [
                    'jumlah'       => $cart->jumlah + $input->jumlah,
                    'subtotal'  => $cart->subtotal + $subtotal
                ];

                if ($this->cart->where('id', $cart->id)->update($data)) {   // Jika update berhasil
                    $this->session->set_flashdata('success', 'Produk berhasil ditambahkan');
                } else {
                    $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
                }

                redirect(base_url());
            }

            // --- Insert cart baru ---
            $data = [
                'id_menu'    => $input->id_menu,
                'id_user'       => $this->id_user,
                'id_toko'       => $input->id_toko,
                'jumlah'           => $input->jumlah,
                'subtotal'      => $subtotal
            ];
//            var_dump($data); die;
            if ($this->cart->create($data)) {   // Jika insert berhasil
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
            }

            redirect(base_url()); 
        }
    }
    
    public function delete($id_cart)
    {
        if (!$_POST) {
            // Jika diakses tidak dengan menggunakan method post, kembalikan ke home (forbidden)
            redirect(site_url('cart/index'));
        }

        if (!$this->cart->where('id_cart', $id_cart)->first()) {  // Jika data tidak ditemukan
            $this->session->set_flashdata('warning', 'Maaf data tidak ditemukan');
            redirect(site_url('cart/index'));
        }

        if ($this->cart->where('id_cart', $id_cart)->delete()) {  // // Lakukan delete & Jika delete berhasil
            $this->session->set_flashdata('success', 'Cart berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Oops, terjadi suatu kesalahan');
        }

        redirect(site_url('cart/index'));
    }
    
    public function update($id_cart)
    {
        if (!$_POST || $this->input->post('jumlah') < 50) {
            $this->session->set_flashdata('error', 'jumlah tidak boleh kosong / kurang dari 50');
            redirect(site_url('cart/index'));
        }
        if (!$_POST || $this->input->post('jumlah') > 10000) {
            $this->session->set_flashdata('error', 'jumlah melebihi batas pemesanan (10000)');
            redirect(site_url('cart/index'));
        }

        $data['content']    = $this->cart->where('id_cart', $id_cart)->first();   // Mengambil data dari cart

        if (!$data['content']) {
            $this->session->set_flashdata('warning', 'Data tidak ditemukan');
            redirect(site_url('cart/index'));
        }

        // Mengambil data produk yang dipilih, untuk mendapatkan price
        $this->cart->table  = 'menu';
        $menu            = $this->cart->where('id_menu', $data['content']->id_menu)->first();

        // Menghitung subtotal baru
        $data['input']      = (object) $this->input->post(null, true);
        $subtotal           = $data['input']->jumlah * $menu->harga;

        // Update data
        $cart = [
            'jumlah'       => $data['input']->jumlah,
            'subtotal'  => $subtotal
        ];

        $this->cart->table  = 'cart';
        if ($this->cart->where('id_cart', $id_cart)->update($cart)) {   // Jika update berhasil
            $this->session->set_flashdata('success', 'Kuantitas berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
        }

        redirect(site_url('cart/index'));
    }
    
}
