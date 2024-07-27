
<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
	<div class="container">
		<a class="navbar-brand" href="<?= base_url() ?>">KATORI</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
			aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="<?= base_url() ?>">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item dropdown">
					<a href="#" class="nav-link dropdown-toggle" id="dropdown-1" , data-toggle="dropdown"
						aria-haspopup="true" aria-expanded="false">Manage</a>
					<div class="dropdown-menu" aria-labelledby="dropdown-1">
                                                <?php if($this->session->userdata('level')== 1):?>
						<a href="#" class="dropdown-item">Kota</a>
						<a href="#" class="dropdown-item">Toko</a>
						<a href="#" class="dropdown-item">Order</a>
                                                <a href="#" class="dropdown-item">dashboard</a>
						<a href="#" class="dropdown-item">User</a>
                                                <?php elseif($this->session->userdata('level')==2):?>
						<a href="#" class="dropdown-item">Toko</a>
						<a href="#" class="dropdown-item">menu</a>
                                                <a href="#" class="dropdown-item">pesanan</a>
                                                <?php endif;?>
					</div>
				</li>
			</ul>
			<ul class="navbar-nav">
                                <?php if($this->session->userdata('level')== 3):?>
				<li class="nav-item">
					<a href="<?= site_url('cart') ?>" class="nav-link"><i class="fas fa-shopping-cart"></i> Cart(<?= getCart() ?>)</a>
				</li>
                                <?php endif ?>
				<?php if (!$this->session->userdata('is_login')) : ?>
					<li class="nav-item">
						<a href="<?= site_url('login') ?>" class="nav-link">Login</a>
					</li>
					<li class="nav-item">
						<a href="<?= site_url('register') ?>" class="nav-link">Register</a>
					</li>
				<?php else : ?>
					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle" id="dropdown-2" , data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-user"></i> <?= $this->session->userdata("name") ?></a>
						<div class="dropdown-menu" aria-labelledby="dropdown-2">
                                                    <?php if($this->session->userdata('level')== 1 || $this->session->userdata('level')== 2):?>
                                                    <a href="<?= site_url('profile') ?>" class="dropdown-item">Profile</a>
                                                    <a href="<?= site_url('logout') ?>" class="dropdown-item">Logout</a>
                                                    <?php else : ?>
							<a href="<?= site_url('profile') ?>" class="dropdown-item">Profile</a>
							<a href="<?= site_url('myorder') ?>" class="dropdown-item">Orders</a>
							<a href="<?= site_url('logout') ?>" class="dropdown-item">Logout</a>
                                                    <?php endif;?>
						</div>
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
</nav>