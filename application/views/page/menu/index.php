<main role="main" class="container">
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('partition/menu') ?>
        </div>
        
        <div class="col-md-9">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Daftar Menu</h3>
                    <a href="<?= site_url("menu/create/") ?>" class="btn btn-success"><i class="fa-solid fa-plus"></i> Menu</a>
                </div>
            </div>

            <div class="row">
                <?php foreach ($content as $row) : ?>
                    <div class="col-md-4">
                        <div class="card mb-2">
                            <img src="<?= $row->image ? base_url("images/menu/$row->image") : base_url("images/menu/default.png") ?>" alt="" class="card-img-top fixed-size-img">
                            <div class="card-body">
                                <h5 class="card-title"> <?= $row->nama_menu ?> </h5>
                                <p class="card-text"><strong>Rp.<?= number_format($row->harga, 0, ',', '.') ?>,-</strong></p>
                                <p class="card-text"><?= $row->detail_menu ?></p>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="id_menu" value="<?= $row->id_menu ?>">
                                <input type="hidden" name="id_toko" value="<?= $row->id_toko?>">
                                <div class="input-group-append">
                                    <a href="<?= site_url("menu/detail/$row->id_menu") ?>" class="btn btn-info mr-2">Detail</a>
                                    <a href="<?= site_url("menu/edit/$row->id_menu") ?>" class="btn btn-warning mr-2">Edit</a>
                                    <a href="<?= site_url("menu/delete/$row->id_menu") ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    
</main>
