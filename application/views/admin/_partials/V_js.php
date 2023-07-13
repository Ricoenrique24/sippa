<div class="modal fade" id="ModalKeluar">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Konfirmasi</h4>
			</div>
			<div class="modal-body">
				<p>Anda yakin Keluar dari Aplikasi?&hellip;</p>
			</div>
			<form class="form-horizontal" action="<?php echo base_url() . 'Login/logout' ?>" method="post">
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tidak</button>
					<button type="submit" class="btn btn-primary">Ya</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- jQuery 3 -->
<script src="<?php echo base_url() ?>assets/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url() ?>assets/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url() ?>assets/admin/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url() ?>assets/admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url() ?>assets/admin/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/toast/jquery.toast.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url() ?>assets/admin/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url() ?>assets/admin/dist/js/demo.js"></script>

<script>
	window.onload = function() {
		document.getElementById("pass_baru").onchange = validatePassword;
		document.getElementById("pass_baru2").onchange = validatePassword;
	}

	function validatePassword() {
		var pass2 = document.getElementById("pass_baru2").value;
		var pass1 = document.getElementById("pass_baru").value;
		if (pass1 != pass2) {
			document.getElementById("pass_baru2").setCustomValidity("Passwords Tidak Sama, Coba Lagi");
		} else {
			document.getElementById("pass_baru2").setCustomValidity('');
		}
	}
	$(function() {

		$("#dataKriteria").DataTable({
			"responsive": true,
			"autoWidth": false,
		});
		$("#dataKriteria2").DataTable({
			"responsive": true,
			"autoWidth": false,
		});
		$("#dataKriteria3").DataTable({
			"responsive": true,
			"autoWidth": false,
		});
		$("#dataKriteria4").DataTable({
			"responsive": true,
			"autoWidth": false,
		});
		$("#dataHitung").DataTable({
			"responsive": true,
			"autoWidth": false,
			"bSort": true,
			"lengthChange": false,
			"paging": true,
		});
		$("#dataHasil").DataTable({
			"responsive": true,
			"autoWidth": false,
			"bSort": true,
			"lengthChange": false,
			"paging": true,
		});
		$("#dataProdi").DataTable({
			"responsive": true,
			"autoWidth": false,
			"bSort": true,
			"lengthChange": false,
			"paging": true,
		});

	});
</script>

<?php if ($this->session->flashdata('msg') == 'success-simpan') : ?>
	<script type="text/javascript">
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
	</script>
<?php elseif ($this->session->flashdata('msg') == 'success-info') : ?>
	<script type="text/javascript">
		$.toast({
			heading: 'Sukses',
			text: "Data berhasil diupdate",
			showHideTransition: 'slide',
			icon: 'info',
			hideAfter: 3000,
			position: 'bottom-right',
			bgColor: '#00c0ef',
			loaderBg: '#0596B9'
		});
	</script>
<?php elseif ($this->session->flashdata('msg') == 'success-hapus') : ?>
	<script type="text/javascript">
		$.toast({
			heading: 'Sukses',
			text: "Data berhasil dihapus",
			showHideTransition: 'slide',
			icon: 'success',
			hideAfter: 3000,
			position: 'bottom-right',
			bgColor: '#dd4b39',
			loaderBg: '#B32B1B'
		});
	</script>
<?php elseif ($this->session->flashdata('msg') == 'success-passwd') : ?>
	<script type="text/javascript">
		$.toast({
			heading: 'Sukses',
			text: "Password berhasil diubah",
			showHideTransition: 'slide',
			icon: 'success',
			hideAfter: 3000,
			position: 'bottom-right',
			bgColor: '#00a65a',
			loaderBg: '#048A4D'
		});
	</script>
<?php else : ?>
<?php endif; ?>