<main role="main" class="container">

    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('partition/alert') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Checkout berhasil
                </div>
                <div class="card-body">
                <h5>Nomor order: <?= $content->invoice ?></h5>
                <p>Terima kasih sudah melakukan pemesanan.</p>
                
                <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail_order as $row) : ?>
                                <tr class="order-item" 
                                    data-nama_menu="<?= $row->nama_menu ?>"
                                    data-jumlah="<?= $row->jumlah ?>"
                                    data-harga="<?= $row->harga ?>"
                                    data-id_detail="<?= $row->id_detail ?>"
                                    data-id_menu="<?= $row->id_menu ?>">
                                    <td>
                                        <p><img src="<?= $row->image ? base_url("images/menu/$row->image") : base_url('images/menu/default.png') ?>" alt="" height="50"> <strong><?= $row->nama_menu ?></strong></p>
                                    </td>
                                    <td class="text-center">Rp.<?= number_format($row->harga, 0, ',', '.') ?>,-</td>
                                    <td class="text-center"><?= $row->jumlah ?></td>
                                    <td class="text-center">Rp.<?= number_format($row->subtotal, 0, ',', '.') ?>,-</td>
                                </tr>
                            <?php endforeach ?>
                            
                        </tbody>
                    </table>
                
                
                <p>Total pembayaran: <strong>Rp.<?= number_format($content->total_bayar, 0, ',', '.') ?>,-</strong></p>
                <p>Silahkan melakukan pembayaran melalui link berikut:</p>
                <a href="<?= site_url("myorder/detail/$content->invoice") ?>" class="btn btn-success">Lanjutkan Pembayaran</a>
                <a href="<?= base_url() ?>" class="btn btn-primary"><i class="fas fa-angle-left"></i> Kembali</a>
                </div>

            </div>
        </div>
    </div>
</main>