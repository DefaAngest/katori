<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $role = $this->session->userdata('level');
        $this->id_user = $this->session->userdata('id_user');

        if ($role != '2') {
            redirect(base_url('/'));
            return;
        }
    }

    public function index()
    {
        $data['title']      = 'Menu Katering';
        $data['content']    = $this->menu->select(
                [
                    'menu.id_menu', 'menu.nama_menu', 'menu.image', 'menu.harga', 'menu.toko', 'menu.detail_menu',
                    'toko.id_user','toko.id_toko','toko.nama_toko'
                ]
            )
            ->join('toko', 'menu.toko = toko.nama_toko')    // Query untuk mencari suatu data produk beserta kategorinya
            ->where('toko.id_user', $this->id_user)
//            ->paginate($page)
            ->get();
//        $data['total_rows'] = $this->product->count();
//        $data['pagination'] = $this->product->makePagination(base_url('product'), 2, $data['total_rows']);
        $data['page']       = 'page/menu/index';

        $this->view($data);
    }

//    public function search($page = null)
//    {
//        if (isset($_POST['keyword'])) {
//            $this->session->set_userdata('keyword', $this->input->post('keyword'));
//        } else {
//            redirect(base_url('product'));
//        }
//
//        $keyword = $this->session->userdata('keyword');
//
//        $data['title']      = 'Admin: Produk';
//        $data['content']    = $this->product->select(
//                [
//                    'product.id', 'product.title AS product_title', 'product.image', 'product.price', 'product.is_available',
//                    'category.title AS category_title'
//                ]
//            )
//            ->join('category')
//            ->like('product.title', $keyword)
//            ->orLike('description', $keyword)   // Tidak hanya mencari di title melainkan di desc juga
//            ->paginate($page)
//            ->get();
//        $data['total_rows'] = $this->product->like('product.title', $keyword)->orLike('description', $keyword)->count();
//        $data['pagination'] = $this->product->makePagination(base_url('product/search'), 3, $data['total_rows']);
//        $data['page']       = 'pages/product/index';
//
//        $this->view($data);
//    }

    public function reset()
    {
        $this->session->unset_userdata('keyword');  // Clear dulu keyword dari session   
        redirect(base_url('product'));
    }

    public function create()
    {
        if (!$_POST) {
            $input = (object) $this->menu->getDefaultValues();
        } else {
            $input = (object) $this->input->post(null, true);
        }

        if (!empty($_FILES) && $_FILES['image']['name'] !== '') {   // Jika upload'an tidak kosong
            $imageName  = url_title($input->nama_menu, '-', true) . '-' . date('YmdHis');  // Membuat slug
            $upload     = $this->menu->uploadImage('image', $imageName);    // Mulai upload
            if ($upload) {
                // Jika upload berhasil, pasang nama file yang diupload ke dalam database
                $input->image   = $upload['file_name'];
            } else {
                redirect(site_url('menu/create'));
            }
        }
        
        //ambil nama toko
//        $this->menu->table = 'toko';
//        $toko = $this->menu->select('nama_toko as toko')
////                     ->from('toko')
//                     ->where('id_user', $this->id_user)
//                     ->get();
////             var_dump($toko); die;             
////        $input = $input->toko = $toko;
        
        
        if (!$this->menu->validate()) {
            $data['title']          = 'Tambah menu';
//            $data['toko']    = $this->menu->select(
//                    [
//                    'menu.id_menu', 'menu.nama_menu', 'menu.image', 'menu.harga', 'menu.toko', 'menu.detail_menu',
//                    'toko.id_user','toko.id_toko','toko.nama_toko as toko'
//                    ]
//                )
//            ->join('toko', 'menu.toko = toko.nama_toko')    // Query untuk mencari suatu data produk beserta kategorinya
//            ->where('toko.id_user', $this->id_user)
////            ->paginate($page)
//            ->get();
//            $data['input']          = $input->toko = $toko->toko;
            $data['input']          = $input;
            $data['form_action']    = site_url('menu/create');
            $data['page']           = 'page/menu/form';

            $this->view($data);
            return;
        }
//        var_dump($input); die;

        if ($this->menu->create($input)) {   // Jika insert berhasil
            $this->session->set_flashdata('success', 'Data berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
        }

        redirect(site_url('menu'));
    }

    public function edit($id)
    {
        $data['content'] = $this->product->where('id', $id)->first();

        if (!$data['content']) {
            $this->session->set_flashdata('warning', 'Maaf data tidak ditemukan');
            redirect(base_url('product'));
        }

        if (!$_POST) {
            $data['input'] = $data['content'];
        } else {
            $data['input'] = (object) $this->input->post(null, true);
        }

        if (!empty($_FILES) && $_FILES['image']['name'] !== '') {   // Jika upload'an tidak kosong
            $imageName  = url_title($data['input']->title, '-', true) . '-' . date('YmdHis');  // Membuat slug
            $upload     = $this->product->uploadImage('image', $imageName);    // Mulai upload
            if ($upload) {
                if ($data['content']->image !== '') {
                    // Jika data di database ini memiliki gambar, maka hapus dulu file gambarnya
                    $this->product->deleteImage($data['content']->image);
                }
                // Jika upload berhasil, pasang nama file yang diupload ke dalam database
                $data['input']->image   = $upload['file_name'];
            } else {
                redirect(base_url("product/edit/$id"));
            }
        }

        if (!$this->product->validate()) {
            $data['title']          = 'Ubah Produk';
            $data['form_action']    = base_url("product/edit/$id");
            $data['page']           = 'pages/product/form';

            $this->view($data);
            return;
        }

        if ($this->product->where('id', $id)->update($data['input'])) {   // Update data
            $this->session->set_flashdata('success', 'Data berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
        }

        redirect(base_url('product'));
    }

    public function delete($id)
    {
        if (!$_POST) {
            redirect(base_url('product'));
        }

        $product = $this->product->where('id', $id)->first();

        if (!$product) {
            $this->session->set_flashdata('warning', 'Maaf data tidak ditemukan');
            redirect(base_url('product'));
        }

        if ($this->product->where('id', $id)->delete()) {   // Lakukan penghapusan di db
            $this->product->deleteImage($product->image);   // Lakukan penghapusan gambar
            $this->session->set_flashdata('success', 'Data berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Oops! Terjadi kesalahan');
        }

        redirect(base_url('product'));
    }

    public function unique_slug()
    {
        $slug       = $this->input->post('slug');
        $id         = $this->input->post('id');
        $product    = $this->product->where('slug', $slug)->first(); // Akan terisi jika terdapat slug yang sama

        if ($product) {
            if ($id == $product->id) {  // Keperluan edit tidak perlu ganti slug, jadi tidak masalah
                return true;
            }

            // Jika terdapat suatu nilai pada $product, berikan pesan error pertanda slug sudah ada di db
            $this->load->library('form_validation');
            $this->form_validation->set_message('unique_slug', '%s sudah digunakan');
            return false;
        }

        return true;
    }
}

/* End of file Product.php */
