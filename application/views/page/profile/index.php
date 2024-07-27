<main role="main" class="container">

    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('partition/alert') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('partition/menu') ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <?php foreach ($content as $row) : ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= $row->image ? base_url("images/user/$row->image") : base_url('images/user/avatar.png') ?>" alt="" height="200">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <p>Nama: <?= $row->nama ?></p>
                            <p>Email: <?= $row->email ?></p>
                            <p>telepon: <?= $row->tlp ?></p>
                            <p>alamat: <?= $row->alamat ?></p>
                            <p>jenis kelamin: <?= $row->jkl ?></p>
                            <a href="<?= site_url("profile/update/$row->id_user") ?>" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</main>