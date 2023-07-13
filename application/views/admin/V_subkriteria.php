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
							<h3 class="box-title">Data Master Sub Kriteria</h3>
						</div><br>
						<!-- /.box-header -->
						<div class="box-body">
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-addsubkriteria"><i class="fa fa-plus"></i> Tambah Data</button><br><br>
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
							<table id="dataKriteria3" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th style="width:20px">No</th>
										<th>Nama Sub Kriteria</th>
										<th>Nama Kriteria</th>
										<th>Jenis Sub Kriteria</th>
										<th>Persen Bobot Sub Kriteria (%)</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($subkriteria as $key => $value) {
										$no++; ?>
										<tr>
											<td><?= $no ?></td>
											<td><?= $value->nama_subkriteria ?></td>
											<td><?= $value->nama_kriteria ?></td>
											<td><?= $value->jenis_subkriteria ?></td>
											<td><?= $value->bobot_subkriteria ?></td>
											<td>
												<a class="editSubkriteria" data-toggle="modal" data-target="#modal-editsubkriteria" href="#" id_subkriteria="<?= $value->id_subkriteria ?>" id_kriteria="<?= $value->id_kriteria ?>" nama_subkriteria="<?= $value->nama_subkriteria ?>" jenis_subkriteria="<?= $value->jenis_subkriteria ?>" bobot_subkriteria="<?= $value->bobot_subkriteria ?>">
													<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></button>
												</a>
												<button type="button" class="btn btn-danger btn-sm delSubKriteria" id="delSubKriteria" data-id="<?= $value->id_subkriteria ?>" data-nama="<?= $value->nama_subkriteria ?>" name="delSubKriteria" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<br><br>
							<table id="dataKriteria4" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th style="width:20px">No</th>
										<th>Nama Sub Kriteria</th>
										<th>Nama Kriteria</th>
										<th>Jenis Sub Kriteria</th>
										<th>Persen Bobot Sub Kriteria (%)</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($subkriteria2 as $key2 => $value2) {
										$no2++; ?>
										<tr>
											<td><?= $no2 ?></td>
											<td><?= $value2->nama_subkriteria ?></td>
											<td><?= $value2->nama_kriteria ?></td>
											<td><?= $value2->jenis_subkriteria ?></td>
											<td><?= $value2->bobot_subkriteria ?></td>
											<td>
												<a class="editSubkriteria" data-toggle="modal" data-target="#modal-editsubkriteria" href="#" id_subkriteria="<?= $value2->id_subkriteria ?>" id_kriteria="<?= $value2->id_kriteria ?>" nama_subkriteria="<?= $value2->nama_subkriteria ?>" jenis_subkriteria="<?= $value2->jenis_subkriteria ?>" bobot_subkriteria="<?= $value2->bobot_subkriteria ?>">
													<button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></button>
												</a>
												<button type="button" class="btn btn-danger btn-sm delSubKriteria" id="delSubKriteria" data-id="<?= $value2->id_subkriteria ?>" data-nama="<?= $value2->nama_subkriteria ?>" name="delSubKriteria" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
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

	<script>
		function hanyaAngka(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))

				return false;
			return true;
		}

		$(document).ready(function() {
			$(".editSubkriteria").click(function(event) {
				$("#eid_subkriteria").val($(this).attr("id_subkriteria"));
				$("#kriteria").val($(this).attr("id_kriteria"));
				$("#nama_subkriteria").val($(this).attr("nama_subkriteria"));
				$("#bobot_subkriteria").val($(this).attr("bobot_subkriteria"));
				$("#bobot_subkriteria_old").val($(this).attr("bobot_subkriteria"));
				$("#jenis_subkriteria").val($(this).attr("jenis_subkriteria"));
			});

			$(".delSubKriteria").click(function(event) {
				$("#did_subkriteria").val($(this).data("id"));
				var nama = $(this).data("nama");
				$('#txt_hapus').html('');
				$('#txt_hapus').append('<p>Apakah Anda yakin akan menghapus data subkriteria dengan nama <b>' + nama + '</b> ?</p>');
				$("#modal-hapus").modal();
			});
		});
	</script>

	<div class="modal fade" id="modal-addsubkriteria">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" action="<?= site_url('Master/add_subkriteria') ?>" method="post" id="quickForm" autocomplete="off">
					<div class="modal-header">
						<h4 class="modal-title-primary">Form Tambah Sub Kriteria</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group">
								<label>Kriteria</label>
								<select class="form-control" name="kriteria" required>
									<option value="" selected>Pilih Kriteria</option>
									<?php foreach ($kriteria as $key => $val) { ?>
										<option value="<?= $val->id_kriteria ?>"><?= $val->nama_kriteria; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="exampleNamaUser">Nama Sub Kriteria</label>
								<input type="text" class="form-control" id="exampleNamaUser" name="nama_subkriteria" placeholder="Buat Nama Sub Kriteria" required>
							</div>
							<div class="form-group">
								<label for="exampleNamaUser">Bobot Sub Kriteria (%)</label>
								<input type="text" class="form-control" id="exampleNamaUser" name="bobot_subkriteria" placeholder="Bobot dalam persen" required>
							</div>
							<div class="form-group">
								<label>Jenis Sub Kriteria</label>
								<select class="form-control" name="jenis_subkriteria" required>
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

	<div class="modal fade" id="modal-editsubkriteria">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" action="<?= site_url('Master/edit_subkriteria') ?>" method="post" id="quickForm" autocomplete="off">
					<div class="modal-header">
						<h4 class="modal-title-primary">Form Edit Sub Kriteria</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group">
								<label>Kriteria</label>
								<select class="form-control" id="kriteria" name="kriteria" required>
									<option value="" selected>Pilih Kriteria</option>
									<?php foreach ($kriteria as $key => $val) { ?>
										<option value="<?= $val->id_kriteria ?>"><?= $val->nama_kriteria; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="exampleNamaUser">Nama Sub Kriteria</label>
								<input type="hidden" id="eid_subkriteria" name="eid_subkriteria">
								<input type="text" class="form-control" id="nama_subkriteria" name="nama_subkriteria" placeholder="Buat Nama Sub Kriteria" required>
							</div>
							<div class="form-group">
								<label for="exampleNamaUser">Bobot Sub Kriteria (%)</label>
								<input type="text" class="form-control" id="bobot_subkriteria" name="bobot_subkriteria" placeholder="Bobot dalam persen" required>
								<input type="hidden" id="bobot_subkriteria_old" name="bobot_subkriteria_old">
							</div>
							<div class="form-group">
								<label>Jenis Sub Kriteria</label>
								<select class="form-control" id="jenis_subkriteria" name="jenis_subkriteria" required>
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
				<form role="form" action="<?= site_url('Master/hapus_subkriteria') ?>" method="post" autocomplete="off">
					<div class="modal-header">
						<h4 class="modal-title">Konfirmasi</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="did_subkriteria" name="id_subkriteria">
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

	<?php $this->load->view("admin/_partials/V_js.php") ?>

</body>

</html>