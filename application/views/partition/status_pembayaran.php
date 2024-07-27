<?php

if ($status_pembayaran == 'lunas') {
    $badge_status   = 'badge-success';
    $status_pembayaran = 'Lunas';
}

elseif ($status_pembayaran == 'unpaid') {
    $badge_status   = 'badge-warning';
    $status_pembayaran = 'menunggu pembayaran';
}

elseif ($status_pembayaran == 'cancel') {
    $badge_status   = 'badge-danger';
    $status_pembayaran = 'Dibatalkan';
}
else {
    $badge_status   = 'badge-secondary';
    $status_pembayaran = 'tidak diketahui';
}

if ($status_pembayaran) : ?>
    <span class="badge badge-pill <?= $badge_status ?>"><?= $status_pembayaran ?></span>
<?php endif ?>
