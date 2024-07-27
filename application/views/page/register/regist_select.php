<main role="main" class="container">
    
    <style>
        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            margin: 10px;
            width: 300px;
        }
    </style>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php $this->load->view('partition/alert') ?>
        </div>
    </div>

   <div class="container">
        <div class="row card-container">
            <!-- First Card for Account Type 1 -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="<?= base_url('assets/img/person2.png') ?>" alt="Account Type 1" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title" style="text-align:center">pengguna</h5>
                        <p class="card-text" style="text-align:center">Daftar sebagai pengguna.</p>
                    </div>
                    <div class="card-footer">
                        <a href="<?= site_url('register/regis_user') ?>" class="btn btn-primary btn-block">Register user</a>
                    </div>
                </div>
            </div>
            <!-- Second Card for Account Type 2 -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="<?= base_url('assets/img/food.png') ?>" alt="Account Type 2" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title" style="text-align:center" >katering</h5>
                        <p class="card-text" style="text-align:center">Daftar sebagai pemilik katering</p>
                    </div>
                    <div class="card-footer">
                        <form action="<?= site_url('register/regis_katering') ?>" method="POST">
                            <div class="input-group">
                                <button class="btn btn-primary btn-block" type="submit">Register Type 2</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>