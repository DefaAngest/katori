<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout_model extends MY_Model 
{
    public $table = 'orderan';   // Ini akan diubah2 di controller Checkout

    public function getDefaultValues()
    {
        return [
            'nama'      => $this->nama_user,
            'alamat'   => '',
            'no_tlp'     => '',
            'status_pesanan'    => '',
            'tanggal_pengiriman'    => ''
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'alamat',
                'label' => 'Alamat',
                'rules' => 'trim|required'
            ],
//            [
//                'field' => 'tanngal_pengiriman',
//                'label' => 'tanggal pengiriman',
//                'rules' => 'required'
//            ],
            [
                'field' => 'no_tlp',
                'label' => 'Telepon',
                'rules' => 'trim|required|max_length[15]'
            ]
        ];

        return $validationRules;
    }
}

/* End of file Checkout_model.php */
