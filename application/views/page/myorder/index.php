<main role="main" class="container">
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('partition/menu') ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    Daftar Orders
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status pembayaran</th>
                                <th>Status pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($content as $row) : ?>
                                <tr>
                                    <td>
                                        <a href="<?= site_url("myorder/detail/$row->invoice") ?>"><strong>#<?= $row->invoice ?></strong></a>
                                    </td>
                                    <td><?= str_replace('-', '/', date('d-m-Y', strtotime($row->tanggal))) ?></td>
                                    <td>Rp.<?= number_format($row->total_bayar, 0, ',', '.') ?>,-</td>
                                    <td> 
                                        <?php $this->load->view('partition/status_pembayaran', ['status_pembayaran' => $row->status_pembayaran]) ?>
                                    </td>
                                    <td>
                                        <?php $this->load->view('partition/status_pesanan', ['status_pesanan' => $row->status_pesanan]) ?>
                                    </td>
                                    <?php if ($row->status_pesanan == 'process') : ?>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="<?= site_url("myorder/done/$row->invoice") ?>"><i class="fa-solid fa-check"></i>selesai</a>
                                    </td>
                                    <?php endif;?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>