<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends MY_Controller
{
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

    public function index($input = null)
    {
        // Mengambil list cart yang akan dicheckout
        $this->checkout->table  = 'cart';
        $data['cart'] = $this->checkout->select([
                'cart.id_cart', 'cart.jumlah', 'cart.subtotal',
                'menu.nama_menu', 'menu.image', 'menu.harga'
            ])
            ->join('menu', 'cart.id_menu = menu.id_menu')
            ->where('cart.id_user', $this->id_user)
            ->get();

        if (!$data['cart']) {
            $this->session->set_flashdata('warning', 'Tidak ada produk di dalam keranjang');
            redirect(base_url('home'));
        }

        // Jika input kosong (user belum input), maka isi form dari awal (form kosong)
        $data['input']  = $input ? $input : (object) $this->checkout->getDefaultValues();
        $data['title']  = 'Checkout';
        $data['page']   = 'page/checkout/index';

        $this->view($data);
    }

    /**
     * Fungsi ini memasukan suatu pesanan ke tabel 'orders' 
     * dan memindahkan list cart user ke 'order_detail'
     */
    public function create()
    {
        if (!$_POST) {
            redirect(site_url('checkout'));
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if (!$this->checkout->validate()) { // Jika validasi gagal, kembalikan ke index dengan kirim last input
            return $this->index($input);
        }
        
        
        //format tgl pengiriman
        $tanggalPengiriman = DateTime::createFromFormat('Y-m-d', $input->tanggal_pengiriman);
        if (!$tanggalPengiriman || $tanggalPengiriman->format('Y-m-d') !== $input->tanggal_pengiriman) {
            $this->session->set_flashdata('error', 'Tanggal pengiriman harus diisi dengan format yang benar (YYYY-MM-DD).');
            return $this->index($input);
        }

        // Menghitung total dari subtotal order suatu user
        $total = $this->db->select_sum('subtotal')
            ->where('id_user', $this->id_user)
            ->get('cart')
            ->row()         // Select first row
            ->subtotal;     // Select column subtotal

        // Menyiapkan insert table orders
        $data = [
            'id_user'   => $this->id_user,
            'tanggal'      => date('Y-m-d'),
            'tanggal_pengiriman'      => $input->tanggal_pengiriman,
            'invoice'   => $this->id_user . date('YmdHis'),
            'total_bayar'     => $total,
            'nama'      => $input->nama,
            'alamat'   => $input->alamat,
            'no_tlp'    => $input->no_tlp,
            'status_pembayaran'    => 'unpaid',
            'status_pesanan'    => 'waiting'
        ];
//        
        error_log(print_r($data, true));
//        var_dump($data); die;
        // Jika insert berhasil, siapkan insert lagi ke dalam order_detail
        if ($id_order = $this->checkout->create($data)) { 
            // Ambil list cart yang telah dipesan user
            $cart = $this->db->where('id_user', $this->id_user) 
                ->get('cart')
                ->result_array();

            // Modifikasi tiap cart
            foreach ($cart as $row) {
                $row['id_order'] = $id_order;             // Tambah kolom id_order
                unset($row['id_cart']);         // Hapus kolom tidak penting
                $this->db->insert('detail_order', $row);    // Insert ke tabel order_detail
            }

            $this->db->delete('cart', ['id_user' => $this->id_user]);    // Hapus cart user sekarang

            $this->session->set_flashdata('success', 'Data berhasil disimpan');

            $data['title']      = 'Checkout Success';
            $data['content']    = (object) $data;
            
            $this->checkout->table = 'detail_order';
            $data['detail_order'] = $this->checkout->select([
                'detail_order.id_detail', 'detail_order.id_order', 'detail_order.id_menu', 'detail_order.jumlah', 'detail_order.subtotal',
                'menu.nama_menu', 'menu.image', 'menu.harga', 'menu.id_menu'
            ])
            ->join('menu', 'menu.id_menu = detail_order.id_menu')
            ->where('detail_order.id_order', $id_order)
            ->get();
            
            
            $data['page']       = 'page/checkout/success';

            $this->view($data);
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
            return $this->index($input);    // Kembali ke index dengan kirim last input
        }
    }
}

/* End of file Checkout.php */
