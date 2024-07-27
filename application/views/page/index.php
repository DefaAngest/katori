<?php
function truncate($text, $chars = 20) {
    if (strlen($text) <= $chars) {
        return $text;
    }
    $shortText = substr($text, 0, $chars) . '...';
    return "<span class='short-text'>$shortText</span><span class='full-text d-none'>$text</span><a href='#' class='read-more'> Baca Selengkapnya</a>";
}
?>

<main role="main" class="container">
    <?php $this->load->view('partition/alert') ?>

    <div class="row">
        <div class="row">
            <?php foreach ($content as $row) : ?>
                <div class="col-md-4">
                    <div class="card mb-3 fixed-size-card-home">
                        <img src="<?= $row->image ? base_url("images/menu/$row->image") : base_url("images/menu/default.png") ?>" alt="" class="card-img-top fixed-size-img-home">
                        <div class="card-body">
                            <h5 class="card-title"> <?= $row->nama_menu ?> </h5>
                            <p class="card-text"><strong>Rp.<?= number_format($row->harga, 0, ',', '.') ?>,-</strong></p>
                            <p class="card-text"><?= $row->detail_menu ?></p>
                            <!--gunakan truncate($row->detail_menu, 20) untuk fungsi shortener-->
                        </div>
                        <div class="card-footer">
                            <form action="<?= site_url('cart/add') ?>" method="POST">
                                <input type="hidden" name="id_menu" value="<?= $row->id_menu ?>">
                                <input type="hidden" name="id_toko" value="<?= $row->id_toko ?>">
                                <div class="input-group">
                                    <input type="number" name="jumlah" value="50" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Add to cart</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed'); // Cek jika ini muncul di konsol
    document.querySelectorAll('.read-more').forEach(function (element) {
        console.log('Adding click event to:', element); // Cek jika elemen ditemukan
        element.addEventListener('click', function (event) {
            event.preventDefault();
            const shortText = this.previousElementSibling.previousElementSibling;
            const fullText = this.previousElementSibling;

            console.log('shortText:', shortText);
            console.log('fullText:', fullText);

            if (shortText && fullText) {
                shortText.classList.toggle('d-none');
                fullText.classList.toggle('d-none');
                this.textContent = this.textContent === ' Baca Selengkapnya' ? ' Tampilkan Lebih Sedikit' : ' Baca Selengkapnya';
            } else {
                console.error('Elemen shortText atau fullText tidak ditemukan.');
            }
        });
    });
});

</script>