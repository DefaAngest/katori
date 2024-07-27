<main role="main" class="container">
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('partition/menu') ?>
        </div>
        <?php foreach ($content as $row) : ?>
        <div class="col-md-9">
            <div class="w3-row w3-padding-64" id="about">
    <div class="w3-col m6 w3-padding-large w3-hide-small">
     <img src="<?=  base_url("images/menu/default.png") ?>" class="w3-round w3-image w3-opacity-min" alt="Table Setting" width="300" height="300">
    </div>

    <div class="w3-col m6 w3-padding-large">
      <h1 class="w3-center"><?= $row->nama_toko ?></h1><br>
      <p class="w3-large"><?= $row->desc ?></p>
      <p class="w3-large w3-text-grey w3-hide-medium">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod temporincididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
                <hr>
    <h5><i class="fa-solid fa-user"></i> <?= $row->pemilik ?></h5>
    <h5 class="w3-center"><i class="fa-solid fa-location-dot"></i> <?= $row->alamat ?></h5>
    <h5 class="w3-center"><i class="fa-solid fa-phone"></i> <?= $row->tlp ?></h5>
  </div>
            
        </div>
        <?php endforeach  ?>
    </div>
</main>2