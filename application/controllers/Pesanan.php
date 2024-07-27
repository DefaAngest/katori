<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan extends MY_Controller 
{
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
        
        $params = array('server_key' => 'SB-Mid-server-d0Tc_GXS2purszfYuDzAPXFb', 'production' => false);
        $this->load->library('midtrans');
        $this->midtrans->config($params);
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title']      = 'Daftar Pesanan';
        $data['content'] = $this->pesanan->select([
                'orderan.id_order', 'orderan.id_user', 'orderan.invoice', 'orderan.tanggal_pengiriman as tanggal_kirim', 'orderan.tanggal as tanggal_order', 
                'orderan.total_bayar as total', 'orderan.status_pesanan as status',
            ])
            ->join('detail_order', 'orderan.id_order = detail_order.id_order')
            ->join('toko', 'toko.id_toko = detail_order.id_toko')
            ->where('toko.id_user', $this->id_user)
            ->where('orderan.status_pembayaran', 'lunas')
            ->groupBy('orderan.id_order')
            ->orderBy('tanggal', 'DESC')
            ->get();

        $data['page']       = 'page/pesanan/index';
//        var_dump($data); die;
        $this->view($data);
    }

    /**
     * Untuk melihat detail dari suatu order
     */
    public function detail($invoice)
    {
        // Ambil data order berdasarkan invoice
        $data['order'] = $this->myorder->where('invoice', $invoice)->first();
        
        if (!$data['order']) {
            $this->session->set_flashdata('warning', 'Data tidak ditemukan');
            redirect(site_url('myorder'));
        }

        // Ambil detail order dari tabel detail_order dan item dari tabel menu
        $this->myorder->table = 'detail_order';
        $data['detail_order'] = $this->myorder->select([
                'detail_order.id_detail', 'detail_order.id_order', 'detail_order.id_menu', 'detail_order.jumlah', 'detail_order.subtotal',
                'menu.nama_menu', 'menu.image', 'menu.harga', 'menu.id_menu'
            ])
            ->join('menu', 'menu.id_menu = detail_order.id_menu')
            ->where('detail_order.id_order', $data['order']->id_order)
            ->get();

        // Ambil data order yang sudah dikonfirmasi jika statusnya sudah bukan 'waiting'
//        if ($data['order']->status_pesanan !== 'waiting') {
//            $this->myorder->table = 'pembayaran';
//            $data['order_confirm'] = $this->myorder->where('id_order', $data['order']->id_order)->first();
//        }

        // Ambil email pengguna dari tabel user berdasarkan id_user dari tabel orderan
        $this->myorder->table = 'user';
        $data['user_email'] = $this->myorder->where('id', $data['order']->id_user)->select('email')->first();
        
        $data['page'] = 'page/myorder/detail';

        $this->view($data);
    }

    /**
     * Untuk melakukan konfirmasi pembayaran
     */

public function token() {
    // Ambil data POST dalam format JSON
    $postData = json_decode(file_get_contents('php://input'), true);

    // Validasi apakah $postData bukan null
    if ($postData !== null) {
        $invoice = $postData['invoice'];
        $nama = $postData['nama'];
        $no_tlp = $postData['no_tlp'];
        $email = $postData['email'];
        $total_bayar = $postData['total_bayar'];
        
        // Ambil data item dari POST yang dikirim sebagai array
        $items = $postData['items'];

        // Siapkan array untuk item details
        $item_details = array();

        // Pastikan $items adalah array atau object
        if (is_array($items) || is_object($items)) {
            foreach ($items as $item) {
                $item_details[] = array(
                    'id' => $item['id_detail'],
                    'price' => $item['harga'],
                    'quantity' => $item['jumlah'],
                    'name' => $item['nama_menu']
                );
            }
        } else {
            // Log error jika $items bukan array atau object
            error_log('Items is not an array or object: ' . print_r($items, true));
        }

        $transaction_data = array(
            'order_id' => $invoice,
            'gross_amount' => $total_bayar,
        );

        $customer_details = array(
            'first_name' => $nama,
            'last_name' => "",
            'email' => $email,
            'phone' => $no_tlp,
        );

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O", $time),
            'unit' => 'day',
            'duration' => 1
        );

        $transaction_data = array(
            'transaction_details' => $transaction_data,
            'item_details'        => $item_details,
            'customer_details'    => $customer_details,
            'expiry'              => $custom_expiry
        );

        // Log transaction data for debugging
        error_log(json_encode($transaction_data));

        // Dapatkan Snap Token dari Midtrans
        $snapToken = $this->midtrans->getSnapToken($transaction_data);

        // Log Snap Token for debugging
        error_log($snapToken);

        // Kirim token kembali sebagai response
        echo json_encode(array('token' => $snapToken));
    } else {
        // Log error jika $postData null
        error_log('No post data received');
        echo json_encode(array('error' => 'No data received'));
    }
}


    
    public function confirm($invoice) {
        $status = [
            'status_pesanan'       => 'process'
        ];

        $this->pesanan->table  = 'orderan';
        if ($this->pesanan->where('invoice', $invoice)->update($status)) {   // Jika update berhasil
            $this->session->set_flashdata('success', 'pesanan telah di process');
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
        }

        redirect(site_url('pesanan/index'));
    }
 
    

    
    public function payment_success($invoice = null)
    {
        if ($invoice) {
            // Ambil id_trans berdasarkan id_invoice dari tabel transaksi
            $this->db->select('id_order');
            $this->db->where('invoice', $invoice);
            $query = $this->db->get('orderan');
            $result = $query->row();
    
            if ($result) {
                $id_order = $result->id_order;
                $status_pembayaran = "lunas"; 
                $status_pesanan = "waiting_confirmation"; // Status pembayaran berhasil

    
                // Update status di tabel transaksi
                $this->myorder->where('id_order', $id_order)->update(['status_pembayaran' => $status_pembayaran, 'status_pesanan' => $status_pesanan]);
    
                // Set pesan berhasil di flash data
                $this->session->set_flashdata('message', '<div class="alert alert-success alert">Pembayaran Berhasil!</div>');
            } else {
                // Jika invoice tidak ditemukan
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert">Invoice tidak ditemukan!</div>');
            }
        } else {
            // Jika id_invoice tidak ada
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert">ID Invoice tidak ditemukan!</div>');
        }
    
        // Redirect ke halaman history
        redirect(site_url('myorder'), 'refresh');
    
        // Muat data dan tampilkan halaman (tidak akan pernah dieksekusi karena redirect sebelumnya)
        $this->data['title'] = 'Daftar Transaksi';
        $this->data['cek_cart_history'] = $this->Cart_model->cart_history()->row();
        $this->data['cart_history'] = $this->Cart_model->cart_history()->result();
    
        $this->load->view('front/cart/history', $this->data);
    } 
    
//    public function image_required()
//    {
//        // Jika file upload kosong, 
//        // atau file upload pada field image namanya itu kosong
//        if (empty($_FILES) || $_FILES['image']['name'] === '') {
//            $this->session->set_flashdata('image_error', 'Bukti transfer tidak boleh kosong');
//            return false;   // Return false agar tidak melanjutkan proses
//        }
//        
//        return true;
//    }
}

/* End of file Myorder.php */
