<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Aplikasi Kinerja & Asset PDAM Bondowoso" />
	<meta name="author" content="DIE Art'S Production" />
	<title>Kinerja | <?= $title ?></title>

	<link href="<?= base_url() ?>assets/img/pdam_biru.png" rel="icon">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css?v=3.2.0">
	<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<link href="<?= base_url() ?>assets/css/style_dashboard.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/select2/bootstrap.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/select2/select2.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/select2/select2-bootstrap-5-theme.min.css" />

	<style>
		#btn-up {
			position: fixed;
			width: 40px;
			height: 40px;
			border-radius: 50%;
			bottom: 20px;
			right: 20px;
			cursor: pointer;
			font-size: 15px;
			background: rgba(192, 192, 192, 0.5);
			color: #000;
			border: none;
			outline: none;
			padding: 5px 10px;
		}

		#btn-up:hover {
			opacity: 0.7;
		}

		#btn-up:active {
			opacity: 0.9;
		}
	</style>

</head>

<body class="hold-transition sidebar-mini">

	<div class="wrapper">