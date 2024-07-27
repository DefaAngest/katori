<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register_model extends MY_Model
{
    protected $table = 'user';
//    protected $table2 = 'data_pengguna';// Tabel ditentukan manual karena nama class model bukan nama tabel

    /**
     * Untuk mendapatkan default values saat form register diload
     */
    public function getDefaultValues()
    {
        return [
            'nama'      => '',
            'email'     => '',
            'password'  => '',
            'level'      => '',
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'nama',  // Sesuai kolom pada tabel
                'label' => 'Nama',  // Nama yang mewakili field tersebut
                'rules' => 'trim|required'
            ],
            [
                'field' => 'email',
                'label' => 'E-Mail',
                'rules' => 'trim|required|valid_email|is_unique[user.email]', // is_unique: harus unik pada kolom email
                'errors' => [
                    'is_unique' => 'This %s already exists.'
                ]
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[6]'
            ],
            [
                'field' => 'password_confirmation',
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[password]' // matches: harus sama sama field password di atas
            ]
        ];

        return $validationRules;
    }

    public function run($input)
    {
        $data = [
            'nama'      => $input->name,
            'email'     => strtolower($input->email),
            'password'  => $input->password,
            'level'      => '3'
        ];
//        $data2 = [
//            'nama'      => $input->nama,
//            'email'     => strtolower($input->email)
//
//        ];

        $user = $this->create($data);   // Insert database
        
        
        $sess_data = [
            'id_user'        => $user,
            'name'      => $data['nama'],
            'email'     => $data['email'],
            'level'      => $data['level'],
            'is_login'  => true
        ];

        $this->session->set_userdata($sess_data);
//        var_dump($sess_data); die;

        
        return true;
    }
    public function run_shop($input)
    {
        $data = [
            'nama'      => $input->name,
            'email'     => strtolower($input->email),
            'password'  => $input->password,
            'level'      => '2'
        ];
//        $data2 = [
//            'nama'      => $input->nama,
//            'email'     => strtolower($input->email)
//
//        ];

        $user = $this->create($data);   // Insert database
//        $this->db->insert('data_pengguna', $data2);
        
        $sess_data = [
            'id_user'        => $user,
            'name'      => $data['nama'],
            'email'     => $data['email'],
            'level'      => $data['level'],
            'is_login'  => true
        ];

        $this->session->set_userdata($sess_data);

        
        return true;
    }
}

/* End of file Register_model.php */
