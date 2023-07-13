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
				<section class="content">

					<!-- SELECT2 EXAMPLE -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Data Pagu Global (Jurusan)</h3>
						</div><br>
						<!-- /.box-header -->
						<div class="box-body">
							<?php if ($this->session->userdata('akses') == 2) { ?>
								<a href="<?= site_url('tambah-simulasi-jurusan'); ?>"> <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Simulasi</button><br><br></a>
							<?php } ?>
							<?php if (isset($_SESSION['type'])) { ?>
								<div class="alert alert-<?php echo $_SESSION['type']; ?> alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $_SESSION['isi']; ?>
								</div>
								<?php
								unset($_SESSION['type']);
								unset($_SESSION['isi']);
								unset($_SESSION['judul']);
								?>
							<?php } ?>
							<table id="dataKriteria" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th style="width:20px">No</th>
										<th>Tahun</th>
										<th>Anggaran</th>
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($simulasi_jurusan as $key => $value) {
										$no++; ?>
										<?php if ($this->session->userdata('akses') == 2) { ?>
											<?php if ($value->isApproval == 0 && $value->keterangan == NULL) {
												echo "<tr>";
											} else if ($value->isApproval == 1 && $value->keterangan != NULL) {
												echo "<tr style='background-color:#f56954 !important; color:white !important'>";
											} else if ($value->isApproval == 2) {
												echo "<tr style='background-color:#1F6E8C !important; color:white !important'>";
											} ?>

										<?php } ?>
										<td><?= $no ?></td>
										<td><?= $value->tahun_simulasi ?></td>
										<td><?= number_format($value->nominal_anggaran, 0, ",", ".") ?></td>
										<td><?php if ($value->isApproval == 0 && $value->keterangan == NULL) {
												echo "Belum validasi";
											} else if ($value->isApproval == 1 && $value->keterangan != NULL) {
												echo "<span>Ada revisi</span>";
											} else {
												echo "Sudah validasi";
											} ?></td>
										<td>
											<!-- Jikalau akun superadmin maka tambahkan tombol edit, hapus dan lihat -->
											<?php if ($this->session->userdata('akses') == 2) { ?>
												<?php if ($value->isApproval == 0) { ?>
													<a class="btn btn-success btn-sm" href="<?= base_url('edit-simulasi-jurusan/' . $value->id_simulasi) ?>"><i class="fa fa-edit"></i> Edit</a>
													<a class="btn btn-info btn-sm" href="<?= base_url('hasil-simulasi-jurusan/' . $value->id_simulasi) ?>"><i class="fa fa-eye"></i> Cek Data</a>
													<a class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin akan menghapus data simulasi ini.?')" href="<?= base_url('Pagu_jurusan/hapusData/' . $value->id_simulasi) ?>"><i class="fa fa-trash"></i> Hapus</a>
												<?php }else if ($value->isApproval == 1){ ?>
													<a class="btn btn-info btn-sm" href="<?= base_url('hasil-simulasi-jurusan/' . $value->id_simulasi) ?>"><i class="fa fa-eye"></i> Cek Data</a>
													<a class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin akan menghapus data simulasi ini.?')" href="<?= base_url('Pagu_jurusan/hapusData/' . $value->id_simulasi) ?>"><i class="fa fa-trash"></i> Hapus</a>
												<?php }else { ?>
													<a class="btn btn-info btn-sm" href="<?= base_url('hasil-simulasi-jurusan/' . $value->id_simulasi) ?>"><i class="fa fa-eye"></i> Cek Data</a>
												<?php }?>

											<?php } else if ($this->session->userdata('akses') == 1) { ?>
												<a class="btn btn-info btn-sm" href="<?= base_url('hasil-simulasi-jurusan/' . $value->id_simulasi) ?>"><i class="fa fa-eye"></i> Cek Data</a>
											<?php } else { ?>
												oopps Error...
											<?php } ?>
										</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.box -->

				</section>
				<!-- /.content -->
			</div>
			<!-- /.container -->
		</div>
		<!-- /.content-wrapper -->
		<?php foreach ($simulasi_jurusan as $key => $value) { ?>
			<div class="modal fade" id="modal-default<?= $value->id_simulasi ?>">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Revisi Pagu Anggaran Jurusan</h4>
						</div>
						<form action="<?= base_url('Pagu_jurusan/UnvalidasiDataKet') ?>" method="post">
							<div class="modal-body">


								<div class="form-group">
									<label>Keterangan</label>
									<textarea class="form-control" name="keterangan" rows="8" required><?= $value->keterangan ?></textarea>
									<input type="hidden" class="form-control" value="<?= $value->id_simulasi ?>" name="idsimulasi" readonly>
								</div>


							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-primary">Simpan</button>
							</div>
						</form>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
		<?php } ?>

		<?php $this->load->view("admin/_partials/V_footer.php") ?>
	</div>
	<!-- ./wrapper -->
	<?php $this->load->view("admin/_partials/V_js.php") ?>
	<script>
		function hanyaAngka(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			return true;
		}
	</script>
</body>

</html>