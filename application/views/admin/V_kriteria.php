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
							<h3 class="box-title">Data Master Kriteria</h3>
						</div><br>
						<!-- /.box-header -->
						<div class="box-body">
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-addkriteria"><i class="fa fa-plus"></i> Tambah Data</button><br><br>
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
										<th>Nama Kriteria</th>
										<th>Persen Bobot Kriteria (%)</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($kriteria as $key => $value) {
										$no++; ?>
										<tr>
											<td><?= $no ?></td>
											<td><?= $value->nama_kriteria ?></td>
											<td><?= $value->bobot_kriteria ?></td>
											<td>
												<a class="editKriteria" data-toggle="modal" data-target="#modal-editkriteria" href="#" id_kriteria="<?= $value->id_kriteria ?>" nama_kriteria="<?= $value->nama_kriteria ?>" bobot_kriteria="<?= $value->bobot_kriteria ?>" jenis_kriteria="<?= $value->jenis_kriteria ?>">
													<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></button>
												</a>
												<button type="button" class="btn btn-danger btn-sm delKriteria" id="delKriteria" data-id="<?= $value->id_kriteria ?>" data-nama="<?= $value->nama_kriteria ?>" name="delKriteria" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<br><br>
							<table id="dataKriteria2" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th style="width:20px">No</th>
										<th>Nama Kriteria</th>
										<th>Persen Bobot Kriteria (%)</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($kriteria2 as $key2 => $value2) {
										$no2++; ?>
										<tr>
											<td><?= $no2 ?></td>
											<td><?= $value2->nama_kriteria ?></td>
											<td><?= $value2->bobot_kriteria ?></td>
											<td>
												<a class="editKriteria" data-toggle="modal" data-target="#modal-editkriteria" href="#" id_kriteria="<?= $value2->id_kriteria ?>" nama_kriteria="<?= $value2->nama_kriteria ?>" bobot_kriteria="<?= $value2->bobot_kriteria ?>" jenis_kriteria="<?= $value2->jenis_kriteria ?>">
													<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></button>
												</a>
												<button type="button" class="btn btn-danger btn-sm delKriteria" id="delKriteria" data-id="<?= $value2->id_kriteria ?>" data-nama="<?= $value2->nama_kriteria ?>" name="delKriteria" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
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

		$(document).ready(function() {
			$(".editKriteria").click(function(event) {
				$("#eid_kriteria").val($(this).attr("id_kriteria"));
				$("#nama_kriteria").val($(this).attr("nama_kriteria"));
				$("#bobot_kriteria").val($(this).attr("bobot_kriteria"));
				$("#bobot_kriteria_old").val($(this).attr("bobot_kriteria"));
				$("#jenis_kriteria").val($(this).attr("jenis_kriteria"));
			});

			$(".delKriteria").click(function(event) {
				$("#did_kriteria").val($(this).data("id"));
				var nama = $(this).data("nama");
				$('#txt_hapus').html('');
				$('#txt_hapus').append('<p>Apakah Anda yakin akan menghapus data kriteria dengan nama <b>' + nama + '</b> ?</p>');
				$("#modal-hapus").modal();
			});
		});
	</script>

	<div class="modal fade" id="modal-addkriteria">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Form Tambah Kriteria</h4>
				</div>
				<form role="form" action="<?= site_url('Master/add_kriteria') ?>" method="post" id="quickForm" autocomplete="off">
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group">
								<label for="exampleNamaUser">Nama Kriteria</label>
								<input type="text" class="form-control" id="exampleNamaUser" name="nama_kriteria" placeholder="Buat Nama Kriteria" required>
							</div>
							<div class="form-group">
								<label for="exampleNamaUser">Bobot Kriteria</label>
								<input type="text" class="form-control" id="exampleNamaUser" name="bobot_kriteria" placeholder="Bobot dalam persen" required>
							</div>
							<div class="form-group">
								<label>Jenis Kriteria</label>
								<select class="form-control" id="exampleNamaUser" name="jenis_kriteria" required>
									<option value="" selected>Pilih Jenis</option>
									<option value="jurusan">Jurusan</option>
									<option value="nonjurusan">Non Jurusan</option>
								</select>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <i class="fa fa-ban"></i> Batal</button>
						<button type="submit" class="btn btn-primary" id="btn_addKarakter"> <i class="fa fa-save"></i> Simpan</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<div class="modal fade" id="modal-editkriteria">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" action="<?= site_url('Master/edit_kriteria') ?>" method="post" id="quickForm" autocomplete="off">
					<div class="modal-header">
						<h4 class="modal-title-primary">Form Edit Kriteria</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group">
								<label for="exampleNamaUser">Nama Kriteria</label>
								<input type="hidden" id="eid_kriteria" name="eid_kriteria">
								<input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" placeholder="Buat Nama Kriteria" required>
							</div>
							<div class="form-group">
								<label for="exampleNamaUser">Bobot Kriteria (%)</label>
								<input type="text" class="form-control" id="bobot_kriteria" name="bobot_kriteria" placeholder="Bobot dalam persen" required>
								<input type="hidden" id="bobot_kriteria_old" name="bobot_kriteria_old">
							</div>
							<div class="form-group">
								<label>Jenis Kriteria</label>
								<select class="form-control" id="jenis_kriteria" name="jenis_kriteria" required>
									<option value="" selected>Pilih Jenis</option>
									<option value="jurusan">Jurusan</option>
									<option value="nonjurusan">Non Jurusan</option>
								</select>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="btn_addKarakter">Simpan</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<div class="modal fade" id="modal-hapus">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" action="<?= site_url('Master/hapus_kriteria') ?>" method="post" autocomplete="off">
					<div class="modal-header">
						<h4 class="modal-title">Konfirmasi</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="did_kriteria" name="id_kriteria">
						<div id="txt_hapus"></div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="btn_delProduk">Ya</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
</body>

</html>