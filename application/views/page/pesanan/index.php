<main role="main" class="container">
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('partition/menu') ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    Daftar pesanan
                </div>
                <div class="card-body">
                    <table class="table" id="table_pesanan">
                        <thead>
                            <tr>
                                <th>invoice</th>
                                <th>Tanggal pemesanan</th>
                                <th>Tanggal pengiriman</th>
                                <th>Total</th>
                                <th>status</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($content as $row) : ?>
                                <tr>
                                    <td value="<?= $row->invoice ?>"><strong>#<?= $row->invoice ?></strong></td>
                                    <td value="<?= $row->tanggal_order ?>"><?= str_replace('-', '/', date('d-m-Y', strtotime($row->tanggal_order))) ?></td>
                                    <td value="<?= $row->tanggal_kirim ?>"><?= str_replace('-', '/', date('d-m-Y', strtotime($row->tanggal_kirim))) ?></td>
                                    <td>Rp.<?= number_format($row->total, 0, ',', '.') ?>,-</td>
                                    <td>
                                        <?php $this->load->view('partition/status_pesanan', ['status_pesanan' => $row->status]) ?>
                                    </td>
                                    <td>
                                       <a href="<?= site_url("pesanan/confirm/$row->invoice") ?>" class="btn btn-sm btn-warning">process</a>
                                       <!--<a href="" class="badge badge-pill badge-danger">refund</a>-->
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

</main>
<script>
$(document).ready( function () {
    $('#table_pesanan').DataTable();
} );
</script>