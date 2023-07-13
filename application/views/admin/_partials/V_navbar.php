<nav class="navbar navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<?php if ($this->session->userdata('akses') == 1 || $this->session->userdata('akses') == 2) : ?>
				<a href="<?php echo site_url('beranda') ?>" class="navbar-brand"><b>SI</b>PPA</a>
			<?php else : ?>
				<a href="#" class="navbar-brand"><b>SI</b>PPA</a>
			<?php endif; ?>


			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
				<i class="fa fa-bars"></i>
			</button>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
			<?php if ($this->session->userdata('akses') == 1) : ?>
				<ul class="nav navbar-nav">
					<li class="<?php echo $this->uri->segment(1) == 'beranda' ? 'active' : '' ?>"><a href="<?php echo site_url('beranda') ?>">Beranda <span class="sr-only">(current)</span></a></li>
					<li class="<?php echo $this->uri->segment(1) == 'pagu-anggaran-jurusan' ? 'active' : '' ?>"><a href="<?php echo site_url('pagu-anggaran-jurusan') ?>">Pagu Anggaran (Jurusan)</a></li>
					<li class="<?php echo $this->uri->segment(1) == 'pagu-anggaran-nonjurusan' ? 'active' : '' ?>"><a href="<?php echo site_url('pagu-anggaran-nonjurusan') ?>">Pagu Anggaran (Non Jurusan)</a></li>
				</ul>
			<?php elseif ($this->session->userdata('akses') == 2) : ?>
				<ul class="nav navbar-nav">
					<li class="<?php echo $this->uri->segment(1) == 'beranda' ? 'active' : '' ?>"><a href="<?php echo site_url('beranda') ?>">Beranda <span class="sr-only">(current)</span></a></li>
					<li class="dropdown <?php echo $this->uri->segment(1) == 'unit' || $this->uri->segment(1) == 'user' || $this->uri->segment(1) == 'data-master-kriteria' || $this->uri->segment(1) == 'data-master-subkriteria' ? 'active' : '' ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Data Master <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?php echo site_url('unit') ?>">Unit</a></li>
							<li><a href="<?php echo site_url('user') ?>">User</a></li>
							<li><a href="<?php echo site_url('data-master-kriteria') ?>">Kriteria</a></li>
							<li><a href="<?php echo site_url('data-master-subkriteria') ?>">Subkriteria</a></li>
						</ul>
					</li>
					<li class="<?php echo $this->uri->segment(1) == 'referensi' ? 'active' : '' ?>"><a href="<?php echo site_url('referensi') ?>">Referensi</a></li>
					<li class="<?php echo $this->uri->segment(1) == 'pagu-anggaran-jurusan' ? 'active' : '' ?>"><a href="<?php echo site_url('pagu-anggaran-jurusan') ?>">Pagu Anggaran (Jurusan)</a></li>
					<li class="<?php echo $this->uri->segment(1) == 'pagu-anggaran-nonjurusan' ? 'active' : '' ?>"><a href="<?php echo site_url('pagu-anggaran-nonjurusan') ?>">Pagu Anggaran (Non Jurusan)</a></li>
				</ul>
			<?php elseif ($this->session->userdata('akses') == 3) : ?>
				<ul class="nav navbar-nav">
					<li class="<?php echo $this->uri->segment(1) == 'pagu-anggaran-jurusan' ? 'active' : '' ?>"><a href="<?php echo site_url('pagu-anggaran-jurusan') ?>">Pagu Anggaran (Jurusan)</a></li>
				</ul>
			<?php else : ?>
				<ul class="nav navbar-nav">
					<li class="<?php echo $this->uri->segment(1) == 'pagu-anggaran-nonjurusan' ? 'active' : '' ?>"><a href="<?php echo site_url('pagu-anggaran-nonjurusan') ?>">Pagu Anggaran (Non Jurusan)</a></li>
				</ul>
			<?php endif; ?>

		</div>
		<!-- /.navbar-collapse -->
		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- Messages: style can be found in dropdown.less-->

				<!-- /.messages-menu -->

				<!-- Notifications Menu -->

				<!-- Tasks Menu -->

				<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<img src="<?php echo base_url() ?>assets/admin/dist/img/profil.png" class="user-image" alt="User Image">
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs"><?php echo $this->session->userdata("nama"); ?></span>
					</a>
					<ul class="dropdown-menu">
						<!-- The user image in the menu -->
						<li class="user-header">
							<img src="<?php echo base_url() ?>assets/admin/dist/img/profil.png" class="img-circle" alt="User Image">

							<p>
								<?php echo $this->session->userdata("nama"); ?>
								<?php if ($this->session->userdata("akses") == 1) { ?>
									<small>PIMPINAN</small>
								<?php } elseif ($this->session->userdata("akses") == 2) { ?>
									<small>SUPERADMIN</small>
								<?php } elseif ($this->session->userdata("akses") == 3) { ?>
									<small>ADMIN JURUSAN</small>
								<?php } else { ?>
									<small>ADMIN NON JURUSAN</small>
								<?php } ?>
							</p>
						</li>
						<!-- Menu Body -->

						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="<?php echo site_url('user/ubah_passwd') ?>" class="btn btn-default btn-flat">Ubah Password</a>
							</div>
							<div class="pull-right">
								<a class="btn btn-default btn-flat" data-toggle="modal" data-target="#ModalKeluar">Keluar</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- /.navbar-custom-menu -->
	</div>
	<!-- /.container-fluid -->
</nav>
