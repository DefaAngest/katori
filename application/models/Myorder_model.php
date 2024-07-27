<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Myorder_model extends MY_Model 
{
    public $table = 'orderan';

    public function getDefaultValues()
    {
        return [
            'id_order'          => '',
            'nama_rekening'      => '',
            'nomor_rekening'    => '',
            'nominal'           => '',
            'note'              => '',
            'image_bayar'             => ''
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'nama_rekening',
                'label' => 'Nama Pemilik',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'nomor_rekening',
                'label' => 'Nomor Rekening',
                'rules' => 'trim|required|max_length[50]'
            ],
            [
                'field' => 'nominal',
                'label' => 'Nominal',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'image_bayar',
                'label' => 'Bukti Transfer',
                'rules' => 'callback_image_required'
            ]
        ];

        return $validationRules;
    }

    public function uploadImage($fieldName, $fileName)
    {
        $config = [
            'upload_path'       => './images/confirm',
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
}

/* End of file Myorder_model.php */
