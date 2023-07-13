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
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Ubah Password</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<form action="<?php echo base_url('User/update_passwd') ?>" id="form" method="post">
							<div class="box-body">
								<div class="row">
									<input type="hidden" value="<?php echo $this->session->userdata("ses_id"); ?>" id="id_pengguna" name="id_pengguna" readonly />
									<div class="col-md-6">
										<div class="form-group">
											<label>Password Baru</label>
											<input name="pass_baru" id="pass_baru" class="form-control" type="password" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Ulangi Password Baru</label>
											<input name="pass_baru2" id="pass_baru2" class="form-control" type="password" required>
											<div id="notif_passwd"></div>
										</div>
									</div>
								</div>
								<!-- /.row -->
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Simpan</button>
							</div>
						</form>
					</div>
					<!-- /.box -->
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
</body>

</html>
