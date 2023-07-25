<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($this->session->userdata("akses") == 1) {
  $level = 'PIMPINAN';
} elseif ($this->session->userdata("akses") == 2) {
  $level = 'SUPERADMIN';
} elseif ($this->session->userdata("akses") == 3) {
  $level = 'ADMIN JURUSAN';
} else {
  $level = 'ADMIN NON JURUSAN';
} ?>
<title><?php echo NAMA_WEB . " | HALAMAN " . $level; ?></title>

<!-- Icon untuk header -->
<link rel="icon" href="https://blog-edutore-partner.s3.ap-southeast-1.amazonaws.com/wp-content/uploads/2022/06/06042128/POLIJE-LOGO.png" type="image/png">

<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/bower_components/Ionicons/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/toast/jquery.toast.min.css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/dist/css/skins/_all-skins.min.css">
<script src="<?php echo base_url() ?>assets/admin/plugins/jquery-3.1.1.min.js" type="text/javascript"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">