<main role="main" class="container">

    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('partition/alert') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    Keranjang Belanja
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($content as $row) : ?>
                                <tr>
                                    <td>
                                        <p><img src="<?= $row->image ? base_url("images/menu/$row->image") : base_url('images/menu/default.png') ?>" alt="" height="50"> <strong><?= $row->nama_menu ?></strong></p>
                                    </td>
                                    <td class="text-center">Rp.<?= number_format($row->harga, 0, ',', '.') ?>,-</td>
                                    <td>
                                        <form action="<?= site_url("cart/update/$row->id_cart") ?>" method="POST">
                                            <input type="hidden" name="id_cart" value="<?= $row->id_cart ?>">
                                            <div class="input-group">
                                                <input type="number" name="jumlah" class="form-control text-center" value="<?= $row->jumlah ?>">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-info"><i class="fas fa-check"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center">Rp.<?= number_format($row->subtotal, 0, ',', '.') ?>,-</td>
                                    <td>
                                        <form action="<?= site_url("cart/delete/$row->id_cart") ?>" method="POST">
                                            <input type="hidden" name="id" value="<?= $row->id_cart ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td class="text-center"><strong>Rp.<?= number_format(array_sum(array_column($content, 'subtotal')), 0, ',', '.') ?>,-</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="<?= site_url('checkout') ?>" class="btn btn-success float-right">Checkout <i class="fas fa-angle-right"></i></a>
                    <a href="<?= base_url('home') ?>" class="btn btn-warning text-white"><i class="fas fa-angle-left"></i> Kembali belanja</a>
                </div>
            </div>
        </div>
    </div>
</main>