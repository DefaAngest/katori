<main role="main" class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span>Formulir menu</span>
                </div>
                <div class="card-body">
                    <?= form_open_multipart($form_action, ['method' => 'POST']) ?>
                        <?= isset($input->id_menu) ? form_hidden('id', $input->id_menu) : '' ?>
                        <?= isset($input->toko) ? form_hidden('toko', $input->toko ) : '' ?>
                        <div class="form-group">
                            <label for="">Menu</label>
                            <?= form_input('nama_menu', $input->nama_menu, ['class' => 'form-control', 'id' => 'nama_menu', 'required' => true, 'autofocus' => true]) ?>
                            <?= form_error('nama_menu') ?>
                        </div>
                   
                        <div class="form-group">
                            <label for="">Deskripsi</label>
                            <?= form_textarea(['name' => 'detail_menu', 'value' => $input->detail_menu, 'row' => 4, 'class' => 'form-control']) ?>
                            <?= form_error('detail_menu') ?>
                        </div>
                        <div class="form-group">
                            <label for="">Harga</label>
                            <?= form_input(['type' => 'number', 'name' => 'harga', 'value' => $input->harga, 'class' => 'form-control', 'required' => true]) ?>
                            <?= form_error('harga') ?>

                        <div class="form-group">
                            <label for="">Gambar</label>
                            <br>
                            <?= form_upload('image') ?>
                            <?php if ($this->session->flashdata('image_error')) :  ?>
                                <small class="form-text text-danger"><?= $this->session->flashdata('image_error') ?></small>
                            <?php endif ?>
                            <?php if ($input->image) : ?>
                                <img src="<?= base_url("images/menu/$input->image") ?>" alt="" height="150">
                            <?php endif ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</main>