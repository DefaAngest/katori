<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends MY_Model
{
    // protected $perPage = 5; -> Tidak perlu, sudah didefinisikan di kelas parent
    public $table = 'menu';

    public function getDefaultValues()
    {
        
        $nama_toko = $this->db->select('nama_toko')
                              ->from('toko')
                              ->where('id_user', $this->session->userdata('id_user'))
                              ->get()
                              ->row('nama_toko');
        return [
            'nama_menu'         => '',
            'toko'         => $nama_toko,
            'detail_menu'   => '',
            'harga'         => '',
            'image'         => ''
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'nama_menu',
                'label' => 'Nama Menu',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'detail_menu',
                'label' => 'detail menu',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'harga',
                'label' => 'Harga',
                'rules' => 'trim|required|numeric'
            ],
        ];

        return $validationRules;
    }

    public function uploadImage($fieldName, $fileName)
    {
        $config = [
            'upload_path'       => './images/menu',
            'file_name'         => $fileName,
            'allowed_types'     => 'jpg|gif|png|jpeg|JPG|PNG',
            'max_size'          => 1024,
            'max_width'         => 0,       // Tidak ada batas
            'max_height'        => 0,
            'overwrite'         => true,    // Jika nama sudah dipakai, overwrite saja
            'file_ext_tolower'  => true,    // Nama ekstensi diubah jadi lowercase
        ];

        $this->load->library('upload', $config);
        
        if ($this->upload->do_upload($fieldName)) {
            // Jika upload berhasil, ambil nama data yang diupload untuk kemudian disimpan di db
            return $this->upload->data();
        } else {
            $this->session->set_flashdata('image_error', $this->upload->display_errors('', ''));
            return false;
        }
    }

    public function deleteImage($fileName)
    {
        if (file_exists("./images/menu/$fileName")) {
            unlink("./images/menu/$fileName");
        }
    }
}

/* End of file menu_model.php */
