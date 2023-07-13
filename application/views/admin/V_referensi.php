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
									<button type="button" class="btn btn-success" onclick="add_ref()"> <i class="fa fa-plus"></i> Tambah Data</button>
									<button type="button" class="btn btn-default" onclick="reload_table()"> <i class="fa fa-refresh"></i> Reload Data</button>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table id="dataRef" class="table table-bordered table-striped nowrap" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>No</th>
													<th>Keterangan</th>
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

	<?php $this->load->view("admin/_partials/V_js.php") ?>
	<script type="text/javascript">
		var save_method; //for save method string
		var table;

		$(document).ready(function() {
			//datatables
			table = $('#dataRef').DataTable({

				"processing": true, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables' server-side processing mode.
				"order": [], //Initial no order.

				// Load data for the table's content from an Ajax source
				"ajax": {
					"url": "<?php echo site_url('Referensi/ajax_list') ?>",
					"type": "POST"
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


		});

		function reload_table() {
			table.ajax.reload(null, false); //reload datatable ajax 
		}

		function add_ref() {
			save_method = 'add';
			$('#form')[0].reset();
			$('.form-group').removeClass('has-error');
			$('.help-block').empty();
			$('#modal_form').modal('show');
			$('#modalJudulRef').text('Tambah Referensi');
		}

		function save() {
			$('#btnSimpan').attr('disabled', true);
			var url;

			if (save_method == 'add') {
				url = "<?php echo site_url('Referensi/ajax_add') ?>";
			} else {
				url = "<?php echo site_url('Referensi/ajax_update') ?>";

			}
			var formData = new FormData($('#form')[0]);
			$.ajax({
				url: url,
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
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

		function edit_ref(id) {
			save_method = 'update';
			$('#form')[0].reset(); // reset form on modals
			$('.form-group').removeClass('has-error'); // clear error class
			$('.help-block').empty(); // clear error string
			//Ajax Load data from ajax
			$.ajax({
				url: "<?php echo site_url('Referensi/ajax_edit/') ?>" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data) {
					$('[name="id_ref"]').val(data.id_ref);
					$('[name="keterangan"]').val(data.keterangan);
					$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
					$('#modalJudulRef').text('Edit Referensi'); // Set title to Bootstrap modal title
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('Gagal mengambil data');
				}
			});
		}

		function hapus_ref(id) {
			if (confirm('Yakin menghapus data ini?')) {
				// ajax delete data to database
				$.ajax({
					url: "<?php echo site_url('Referensi/ajax_delete/') ?>" + id,
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

		function lihat_ref(data) {
			// window.location = "uploads/" + data, '_blank';
			window.open(
				'uploads/' + data,
				'_blank' // <- This is what makes it open in a new window.
			);
		}
	</script>

	<div class="modal fade" id="modal_form">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalJudulRef">Judul Modal</h4>
				</div>

				<div class="modal-body">
					<form action="#" id="form">
						<input type="hidden" value="" id="id_ref" name="id_ref" readonly />
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Keterangan</label>
									<input class="form-control" type="text" id="keterangan" name="keterangan" />
									<small class="help-block"></small>
								</div>
								<!-- /.form-group -->
								<div class="form-group">
									<label>File</label>
									<input class="form-control" type="file" id="nama_file" name="nama_file" />
									<small class="help-block"></small>
								</div>
								<!-- /.form-group -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <i class="fa fa-ban"></i> Batal</button>
					<button type="button" id="btnSimpan" onclick="save()" class="btn btn-primary"> <i class="fa fa-save"></i> Simpan</button>
				</div>

			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
</body>

</html>
