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
            <div class="card">
                <div class="card-header">
                    Detail Orders #<?= $order->invoice ?>
                    <div class="float-right">
                        <?php $this->load->view('partition/status_pembayaran', ['status_pembayaran' => $order->status_pembayaran]) ?>
                    </div>
                    <div class="float-right">
                        <?php $this->load->view('partition/status_pesanan', ['status_pesanan' => $order->status_pesanan]) ?>
                    </div>
                </div>
                <div class="card-body">
                    <p>Tanggal pemesanan: <?= str_replace('-', '/', date('d-m-Y', strtotime($order->tanggal))) ?></p>
                    <p>Tanggal pengiriman: <?= str_replace('-', '/', date('d-m-Y', strtotime($order->tanggal_pengiriman))) ?></p>
                    <p>Nama: <?= $order->nama ?></p>
                    <p>Telepon: <?= $order->no_tlp ?></p>
                    <p>Alamat: <?= $order->alamat ?></p>
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
                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td class="text-center"><strong>Rp.<?= number_format($order->total_bayar, 0, ',', '.') ?>,-</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol konfirmasi pembayaran hanya ditampilkan jika user belum membayar / waiting -->
                <?php if ($order->status_pembayaran == 'unpaid') : ?>
                    <div class="card-footer">
                        <a id="pay-button" href="#" class="btn btn-success" 
                           data-invoice="<?= $order->invoice ?>"
                           data-nama="<?= $order->nama ?>"
                           data-no_tlp="<?= $order->no_tlp ?>"
                           data-email="<?= isset($user_email->email) ? $user_email->email : '' ?>"
                           data-total-bayar="<?= $order->total_bayar ?>"
                        >Bayar Sekarang</a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <script 
        type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<SB-Mid-client-MYqRM65UVpKKuY5w>">
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#pay-button').click(function(event) {
            event.preventDefault();
            $(this).attr("disabled", "disabled");

            var invoice = $(this).data('invoice');
            var nama = $(this).data('nama');
            var noTlp = $(this).data('no_tlp');
            var email = $(this).data('email');
            var totalBayar = $(this).data('total-bayar');
            
            // Ambil data item sebagai array
            var items = [];
            $('.order-item').each(function() {
                var item = {
                    nama_menu: $(this).data('nama_menu'),
                    jumlah: $(this).data('jumlah'),
                    harga: $(this).data('harga'),
                    id_detail: $(this).data('id_detail'),
                    id_menu: $(this).data('id_menu')
                };
                items.push(item);
            });

            $.ajax({
                url: '<?=site_url()?>/Myorder/token',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    invoice: invoice,
                    nama: nama,
                    no_tlp: noTlp,
                    email: email,
                    total_bayar: totalBayar,
                    items: items // Kirim array item
                }),
                cache: false,
                dataType: 'json', // Specify the expected data type
                success: function(data) {
                    if (data.token) {
                        snap.pay(data.token, {
                            onSuccess: function(result) {
                                console.log('Payment success:', result);
                                window.location.href = '<?= site_url('myorder/payment_success') ?>/' + invoice;
                            },
                            onPending: function(result){
                                console.log('Payment pending:', result);
                                window.location.href = '<?= site_url('myorder/payment_pending') ?>/';
                            },
                            onError: function(result){
                                console.log('Payment error:', result);
                                window.location.href = '<?= site_url('myorder/payment_error') ?>/';
                            }
                        });
                    } else {
                        console.error('No token received:', data);
                        alert('Token tidak diterima');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error getting Midtrans token:', error);
                    alert('Error getting Midtrans token: ' + error);
                }
            });
        });
    });
    </script>
</main>
