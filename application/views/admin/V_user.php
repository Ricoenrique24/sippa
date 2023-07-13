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
					<!-- START CUSTOM TABS -->
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<div class="box-header">
									<button type="button" class="btn btn-success" onclick="add_user()"> <i class="fa fa-plus"></i> Tambah Data</button>
									<button type="button" class="btn btn-default" onclick="reload_table()"> <i class="fa fa-refresh"></i> Reload Data</button>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table id="mytable" class="table table-bordered table-striped nowrap" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>No</th>
													<th>Username</th>
													<th>Nama Lengkap</th>
													<th>Role</th>
													<th>Unit</th>
													<th>Aksi</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<!-- END CUSTOM TABS -->
					<!-- START PROGRESS BARS -->
					<!-- END PROGRESS BARS -->

					<!-- START ACCORDION & CAROUSEL-->
					<!-- END ACCORDION & CAROUSEL-->

					<!-- START TYPOGRAPHY -->
					<!-- END TYPOGRAPHY -->

				</section>
				<!-- /.content -->
			</div>
			<!-- /.container -->
		</div>
		<!-- /.content-wrapper -->
		<?php $this->load->view("admin/_partials/V_footer.php") ?>
	</div>
	<!-- ./wrapper -->

	<div class="modal fade" id="modal_form">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalJudulUser">Judul Modal</h4>
				</div>
				<form action="#" id="form" role="form">
					<div class="modal-body">
						<input type="hidden" value="" id="id_pengguna" name="id_pengguna" readonly />
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Username</label>
									<input class="form-control" type="text" id="username" name="username" />
									<small class="help-block"></small>
								</div>
								<!-- /.form-group -->
								<div class="form-group">
									<label>Role</label>
									<select class="form-control select" style="width: 100%;" id="level" name="level">
										<option disabled selected>--- Pilih ---</option>
										<option value="1">Pimpinan</option>
										<option value="2">Superadmin</option>
										<option value="3">Admin Jurusan</option>
										<option value="4">Admin Non Jurusan</option>
									</select>
									<small class="help-block"></small>
								</div>
								<!-- /.form-group -->
							</div>
							<!-- /.col -->
							<div class="col-md-6">
								<div class="form-group">
									<label>Nama Lengkap</label>
									<input class="form-control" type="text" id="nama_lengkap" name="nama_lengkap" />
									<small class="help-block"></small>
								</div>
								<!-- /.form-group -->
								<div class="form-group">
									<label>Unit</label>
									<select class="form-control select" style="width: 100%;" id="id_unit" name="id_unit">
										<option disabled selected>--- Pilih ---</option>
										<?php foreach ($unit as $row) : ?>
											<option value="<?php echo $row->id_unit; ?>"><?php echo $row->nama_unit; ?></option>
										<?php endforeach; ?>
									</select>
									<small class="help-block"></small>
								</div>
								<!-- /.form-group -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <i class="fa fa-ban"></i> Batal</button>
						<button type="button" id="btnSimpan" onclick="save()" class="btn btn-primary"> <i class="fa fa-save"></i> Simpan</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<?php $this->load->view("admin/_partials/V_js.php") ?>
	<script type="text/javascript">
		var save_method; //for save method string
		var table;

		$(document).ready(function() {
			//datatables
			table = $('#mytable').DataTable({

				"processing": true, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables' server-side processing mode.
				"order": [], //Initial no order.

				// Load data for the table's content from an Ajax source
				"ajax": {
					"url": "<?php echo site_url('User/ajax_list') ?>",
					"type": "POST",
				},

				"language": {
					"infoFiltered": ""
				},

				//Set column definition initialisation properties.
				"columnDefs": [{
					"targets": [-1], //first column
					"orderable": false, //set not orderable
				}, ],

			});

			//set input/textarea/select event when change value, remove class error and remove text help block 
			$("input").change(function() {
				$(this).parent().parent().removeClass('has-error');
				$(this).next().empty();
			});

			$("select").change(function() {
				$(this).parent().parent().removeClass('has-error');
				$(this).next().empty();
			});

		});

		function reload_table() {
			table.ajax.reload(null, false); //reload datatable ajax 
		}

		function add_user() {
			save_method = 'add';
			$('#form')[0].reset();
			$('.form-group').removeClass('has-error');
			$('.help-block').empty();
			$('#modal_form').modal('show');
			$('#modalJudulUser').text('Tambah User');
		}

		function save() {
			$('#btnSimpan').attr('disabled', true);
			var url;
			if (save_method == 'add') {
				url = "<?php echo site_url('User/ajax_add') ?>";
			} else {
				url = "<?php echo site_url('User/ajax_update') ?>";

			}

			$.ajax({
				url: url,
				type: "POST",
				data: $('#form').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status == 1) {
						$('#modal_form').modal('hide');
						reload_table();

						$.toast({
							heading: 'Sukses',
							text: "Data berhasil disimpan",
							showHideTransition: 'slide',
							icon: 'success',
							hideAfter: 3000,
							position: 'bottom-right',
							bgColor: '#00a65a',
							loaderBg: '#048A4D'
						});
					} else if (data.status == 2) {
						$.toast({
							heading: 'Gagal Menyimpan Data',
							text: "Username sudah dipakai",
							showHideTransition: 'slide',
							icon: 'success',
							hideAfter: 3000,
							position: 'bottom-right',
							bgColor: '#dd4b39',
							loaderBg: '#B32B1B'
						});
					} else {
						for (var i = 0; i < data.inputerror.length; i++) {
							$('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
							$('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
						}
					}
					$('#btnSimpan').attr('disabled', false);

				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('Gagal menambah / mengupdate data');
					$('#btnSimpan').attr('disabled', false);
				}
			});
		}

		function edit_user(id) {
			save_method = 'update';
			$('#form')[0].reset(); // reset form on modals
			$('.form-group').removeClass('has-error'); // clear error class
			$('.help-block').empty(); // clear error string
			//Ajax Load data from ajax
			$.ajax({
				url: "<?php echo site_url('User/ajax_edit/') ?>" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data) {
					$('[name="id_pengguna"]').val(data.id_pengguna);
					$('[name="username"]').val(data.username);
					$('[name="nama_lengkap"]').val(data.nama_lengkap);
					$('[name="level"]').val(data.level);
					$('[name="id_unit"]').val(data.id_unit);
					$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
					$('#modalJudulUser').text('Edit User'); // Set title to Bootstrap modal title
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('Gagal mengambil data');
				}
			});
		}

		function hapus_user(id) {
			if (confirm('Yakin menghapus data ini?')) {
				// ajax delete data to database
				$.ajax({
					url: "<?php echo site_url('User/ajax_delete/') ?>" + id,
					type: "POST",
					dataType: "JSON",
					success: function(data) {
						reload_table();
						$.toast({
							heading: 'Sukses',
							text: "Data berhasil dihapus",
							showHideTransition: 'slide',
							icon: 'success',
							hideAfter: 3000,
							position: 'bottom-right',
							bgColor: '#00a65a',
							loaderBg: '#048A4D'
						});
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Gagal menghapus data');
					}
				});
			}
		}

		function reset_user(id) {
			if (confirm('Yakin mereset password data ini?')) {
				// ajax delete data to database
				$.ajax({
					url: "<?php echo site_url('User/reset_password/') ?>" + id,
					type: "POST",
					dataType: "JSON",
					success: function(data) {
						reload_table();
						$.toast({
							heading: 'Sukses',
							text: "Password berhasil direset",
							showHideTransition: 'slide',
							icon: 'success',
							hideAfter: 3000,
							position: 'bottom-right',
							bgColor: '#00a65a',
							loaderBg: '#048A4D'
						});
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Gagal mengupdate data');
					}
				});
			}
		}
	</script>
</body>

</html>
