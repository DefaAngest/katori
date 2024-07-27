<main role="main" class="container">

    <div class="row">
        <div class="col-md-8">
            <?php $this->load->view('partition/alert') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">  
            <div class="card">
                <div class="card-header">
                    Formulir Alamat pengiriman
                </div>
                <div class="card-body">
                    <form id="checkoutForm" action="<?= site_url('checkout/create') ?>" method="POST">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" class="form-control" name="nama" placeholder="Masukan nama penerima" value="<?= $input->nama ?>">
                            <?= form_error('nama') ?>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="alamat" cols="30" rows="5" class="form-control"><?= $input->alamat ?></textarea>
                            <?= form_error('alamat') ?>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Pengiriman</label>
                            <input type="date" class="form-control" name="tanggal_pengiriman" placeholder="Masukan nama penerima" value="<?= $input->tanggal_pengiriman ?>">
                            <?= form_error('tanggal_pengiriman') ?>
                        </div>
                        <div class="form-group">
                            <label for="">Telepon</label>
                            <input type="number" class="form-control" name="no_tlp" placeholder="Masukan nomor telepon penerima" value="<?= $input->no_tlp ?>">
                            <?= form_error('no_tlp') ?>
                        </div>
                        
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            preview pembelian
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>menu</th>
                                        <th>jumlah</th>
                                        <th>Harga</th>
                                    </tr>
                                    <tbody>
                                        <?php foreach ($cart as $row) : ?>
                                            <tr>
                                                <td><?= $row->nama_menu ?></td>
                                                <td><?= $row->jumlah ?></td>
                                                <td>Rp.<?= number_format($row->harga, 0, ',', '.') ?>,-</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Subtotal</td>
                                                <td>Rp.<?= number_format($row->subtotal, 0, ',', '.') ?>,-</td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th>Rp.<?= number_format(array_sum(array_column($cart, 'subtotal')), 0, ',', '.') ?>,-</th>
                                        </tr>
                                    </tfoot>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('checkoutForm').addEventListener('submit', function(event) {
            var tanggalPengiriman = document.querySelector('[name="tanggal_pengiriman"]').value;
            var today = new Date();
            var eightDaysFromNow = new Date();
            eightDaysFromNow.setDate(today.getDate() + 8);

            var formattedToday = today.toISOString().split('T')[0];
            var formattedEightDaysFromNow = eightDaysFromNow.toISOString().split('T')[0];

            if (tanggalPengiriman <= formattedToday || tanggalPengiriman < formattedEightDaysFromNow) {
                alert('Tanggal pengiriman harus diisi dengan tanggal yang akan datang dan minimal 8 hari dari hari ini.');
                event.preventDefault();
                }
        });
     });
</script>
</main>