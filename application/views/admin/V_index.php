<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view("admin/_partials/V_header.php") ?>
</head>

<body class="hold-transition skin-blue layout-top-nav">
	<div class="wrapper">

		<header class="main-header">
			<?php $this->load->view("admin/_partials/V_navbar.php") ?>
		</header>
		<div class="content-wrapper">
			<div class="container">
				<?php $this->load->view("admin/_partials/V_breadcrumb.php") ?>
				<!-- Main content -->
				<?php
				//membuat format rupiah dengan PHP
				//tutorial www.malasngoding.com

				function rupiah($angka)
				{

					$hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
					return $hasil_rupiah;
				}

				?>
				<section class="content">

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Filter Pagu Anggaran Berdasarkan Tahun</h3>
						</div>
						<!-- /.box-header -->
						<form action="<?php echo base_url('beranda/filter_data') ?>" method="post" enctype="multipart/form-data">
							<div class="box-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Tahun Anggaran</label>
											<select class="form-control select" style="width: 100%;" id="tahun" name="tahun">
												<option value="">--- Pilih ---</option>

												<?php foreach ($tahunAnggaran as $key => $ta) : ?>
													<option value="<?= $ta->tahun_simulasi ?>" <?php echo $this->session->userdata("setTahun") == $ta->tahun_simulasi ? 'selected' : '' ?>><?= $ta->tahun_simulasi ?></option>
												<?php endforeach; ?>


											</select>
										</div>
										<!-- /.form-group -->
									</div>
									<!-- /.col -->
								</div>
								<!-- /.row -->
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"> <i class="fa fa-filter"></i> Tampilkan</button>
							</div>
						</form>
					</div>
					<!-- /.box -->
					<div class="row">
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box bg-aqua">
								<span class="info-box-icon" style="height: 99px !important;"><i class="fa fa-user"></i></span>

								<div class="info-box-content" style="height: 99px !important;">
									<span class="info-box-text" style="font-size: 12px !important">User</span>
									<span class="info-box-number"><?php echo $pengguna ?></span>

									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box bg-red">
								<span class="info-box-icon" style="height: 99px !important;"><i class="fa fa-file-pdf-o"></i></span>

								<div class="info-box-content" style="height: 99px !important;">
									<span class="info-box-text" style="font-size: 12px !important">Referensi</span>
									<span class="info-box-number"><?php echo $referensi ?></span>
									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>

								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->

						<!-- fix for small devices only -->
						<div class="clearfix visible-sm-block"></div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box bg-green">
								<span class="info-box-icon" style="height: 99px !important;"><i class="fa fa-shopping-cart"></i></span>

								<div class="info-box-content">
									<span class="info-box-text" style="font-size: 12px !important">Total Pagu Anggaran <br> (Jurusan)</span>
									<?php if (empty($paguJur->nominal_anggaran)) {
										$nilaiJur = 0;
									} else {
										$nilaiJur = $paguJur->nominal_anggaran;
									} ?>
									<span class="info-box-number" style="font-size: 16px !important"><?php echo rupiah($nilaiJur) ?></span>

									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
									<span class="progress-description">
										<?php if (!empty($paguJur->tahun_simulasi)) : ?>
											T.A - <?php echo $paguJur->tahun_simulasi ?>
										<?php else : ?>
											T.A - <?php echo "Not Found" ?>
										<?php endif; ?>
									</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->

						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="info-box bg-yellow">
								<span class="info-box-icon" style="height: 99px !important;"><i class="fa fa-shopping-cart"></i></span>

								<div class="info-box-content">
									<span class="info-box-text" style="font-size: 12px !important">Total Pagu Anggaran <br> (Non Jurusan)</span>
									<?php if (empty($paguNon->nominal_anggaran)) {
										$nilai = 0;
									} else {
										$nilai = $paguNon->nominal_anggaran;
									} ?>

									<span class="info-box-number" style="font-size: 16px !important"><?php echo rupiah($nilai) ?></span>

									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
									<span class="progress-description">
										<?php if (!empty($paguNon->tahun_simulasi)) : ?>
											T.A - <?php echo $paguNon->tahun_simulasi ?>
										<?php else : ?>
											T.A - <?php echo "Not Found" ?>
										<?php endif; ?>
									</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</section>
				<!-- /.content -->
			</div>
			<!-- /.container -->
		</div>
		<!-- /.content-wrapper -->
		<?php $this->load->view("admin/_partials/V_footer.php") ?>
	</div>
	<!-- ./wrapper -->
	<?php $this->load->view("admin/_partials/V_js.php") ?>
</body>

</html>