<?php

if ($status_pesanan == 'waiting') {
    $badge_status   = 'badge-warning';
    $status_pesanan         = 'menunggu';
}
elseif ($status_pesanan == 'waiting_confirmation') {
    $badge_status   = 'badge-info';
    $status_pesanan         = 'menunggu konfirmasi';
}
elseif ($status_pesanan == 'process') {
    $badge_status   = 'badge-warning';
    $status_pesanan         = 'diproses';
}

elseif ($status_pesanan == 'done') {
    $badge_status   = 'badge-success';
    $status_pesanan         = 'selesai';
}

elseif ($status_pesanan == 'cancel') {
    $badge_status   = 'badge-danger';
    $status_pesanan         = 'Dibatalkan';
}
else {
    $badge_status   = 'badge-secondary';
    $status_pesanan = 'tidak diketahui';
}

if ($status_pesanan) : ?>
    <span class="badge badge-pill <?= $badge_status ?>"><?= $status_pesanan ?></span>
<?php endif ?>
