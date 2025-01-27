<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Model_asset');
		$this->load->library('form_validation');
		if (!$this->session->userdata('nama_pengguna')) {
			$this->session->set_flashdata(
				'info',
				'<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Maaf,</strong> Anda harus login untuk akses halaman ini...
                      </div>'
			);
			redirect('auth');
		}

		// $level_pengguna = $this->session->userdata('level');
		// if ($level_pengguna != 'Admin') {
		// 	$this->session->set_flashdata(
		// 		'info',
		// 		'<div class="alert alert-danger alert-dismissible fade show" role="alert">
		//             <strong>Maaf,</strong> Anda tidak memiliki hak akses untuk halaman ini...
		//           </div>'
		// 	);
		// 	redirect('auth');
		// }

		$bagian = $this->session->userdata('bagian');
		if ($bagian != 'Keuangan' && $bagian != 'Administrator' && $bagian != 'Auditor') {
			$this->session->set_flashdata(
				'info',
				'<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Maaf,</strong> Anda tidak memiliki hak akses untuk halaman ini...
                  </div>'
			);
			redirect('auth');
		}
	}
	public function index()
	{
		$tanggal = $this->input->get('tanggal');

		$bulan = substr($tanggal, 5, 2);
		$tahun = substr($tanggal, 0, 4);

		if (empty($tanggal)) {
			$tanggal = date('Y-m-d');
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$this->session->set_userdata('tanggal', $tanggal);
		}
		$data['bulan_lap'] = $bulan;
		$data['tahun_lap'] = $tahun;

		$data['title'] = 'Penambahan Asset';
		$data['asset'] = $this->Model_asset->get_all($bulan, $tahun);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset/view_asset', $data);
		$this->load->view('templates/footer');
	}

	public function upload()
	{
		$tanggal = $this->session->userdata('tanggal');
		$this->form_validation->set_rules('nama_asset', 'Nama Asset', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
		$this->form_validation->set_rules('id_no_per', 'No Perkiraan', 'required|trim');
		$this->form_validation->set_rules('id_bagian', 'Bagian/UPK', 'required|trim');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|numeric');
		$this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim|numeric');
		$this->form_validation->set_rules('status', 'Status Asset', 'required|trim');
		$this->form_validation->set_rules('umur', 'Umur Asset', 'required|trim|numeric');
		$this->form_validation->set_rules('persen_susut', 'Persen Penyusutan', 'required|trim|numeric');
		$this->form_validation->set_message('required', '%s masih kosong');
		$this->form_validation->set_message('numeric', '%s harus berupa angka');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Upload Asset Baru';
			$data['bagian'] = $this->Model_asset->get_bagian();
			$data['perkiraan'] = $this->Model_asset->get_perkiraan();

			$this->load->view('templates/header', $data);
			$this->load->view('templates/navbar');
			$this->load->view('templates/sidebar');
			$this->load->view('asset/view_upload_asset', $data);
			$this->load->view('templates/footer');
		} else {
			$data['asset'] = $this->Model_asset->tambah_asset();
			$this->session->set_flashdata(
				'info',
				'<div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Sukses,</strong> Data Asset baru berhasil di tambah
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                      </div>'
			);
			$alamat = 'asset?tanggal=' . $tanggal;
			redirect($alamat);
			// redirect('asset');
		}
	}

	public function asset_kurang()
	{
		$tanggal = $this->input->get('tanggal');

		$bulan = substr($tanggal, 5, 2);
		$tahun = substr($tanggal, 0, 4);

		if (empty($tanggal)) {
			$tanggal = date('Y-m-d');
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$this->session->set_userdata('tanggal', $tanggal);
		}
		$data['bulan_lap'] = $bulan;
		$data['tahun_lap'] = $tahun;

		$data['title'] = 'Pengurangan Asset';
		$data['asset'] = $this->Model_asset->get_kurang($bulan, $tahun);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset_kurang/view_asset_kurang', $data);
		$this->load->view('templates/footer');
	}

	public function asset_semua()
	{

		$data['title'] = 'Daftar Semua Asset';
		$data['asset'] = $this->Model_asset->get_semua_asset();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset/view_asset_semua', $data);
		$this->load->view('templates/footer');
	}

	public function cetak_asset_semua()
	{

		$data['title'] = 'Daftar Semua Asset';
		$data['asset'] = $this->Model_asset->get_semua_asset();

		// Set paper size and orientation
		$this->pdf->setPaper('folio', 'portrait');

		$this->pdf->filename = "semua_asset.pdf";
		$this->pdf->generate('cetakan_asset/semua_asset_pdf', $data);
	}

	// public function edit_asset_semua($id_asset)
	// {
	// 	// Validasi level pengguna
	// 	if ($this->session->userdata('level') != 'Admin') {
	// 		$this->session->set_flashdata('error', 'Anda tidak punya akses untuk update data.');
	// 		redirect('asset/asset_semua');
	// 	}

	// 	// Ambil data asset berdasarkan ID
	// 	$asset = $this->Model_asset->get_asset_by_id($id_asset);

	// 	// Validasi status_update
	// 	if ($asset->status_update == 1) {
	// 		$this->session->set_flashdata('error', 'Asset sudah tidak bisa diupdate lagi.');
	// 		redirect('asset/asset_semua');
	// 	}

	// 	$this->Model_asset->update_umur_dan_persen_susut($id_asset);
	// 	$this->session->set_flashdata(
	// 		'info',
	// 		'<div class="alert alert-primary alert-dismissible fade show" role="alert">
	// 				<strong>Sukses,</strong> Nilai Asset berhasil di kembalikan ke awal 
	// 				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
	// 				</button>
	// 			  </div>'
	// 	);
	// 	redirect('asset/asset_semua');
	// }

	public function asset_semua_kurang()
	{

		$data['title'] = 'Daftar Semua Pengurangan Asset';
		$data['asset'] = $this->Model_asset->get_semua_asset_kurang();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset_kurang/view_asset_semua_kurang', $data);
		$this->load->view('templates/footer');
	}

	public function asset_tahun()
	{
		$get_tahun = $this->input->get('tahun');
		$no_per = $this->input->get('no_per');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$tahun = date('Y');
		} else {
			$this->session->set_userdata('tambah_tahun_session', $get_tahun);
		}
		$data['tahun_lap'] = $tahun;


		if (empty($get_tahun) || empty($no_per)) {
			$data['asset'] = $this->Model_asset->get_all_tahun($tahun);
		} else {
			$data['asset'] = $this->Model_asset->get_all_tahun_perkiraan($tahun, $no_per);
		}

		$data['no_per_descriptions'] = [
			218 => 'TANAH & PENYEMPURNAAN TANAH',
			220 => 'INSTALASI SUMBER',
			222 => 'INSTALASI POMPA',
			224 => 'INSTALASI PENGOLAHAN',
			226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
			228 => 'BANGUNAN & GEDUNG',
			244 => 'PERALATAN & PERLENGKAPAN',
			246 => 'KENDARAAN',
			248 => 'INVENTARIS & PERABOTAN KANTOR'
		];

		$data['no_perkiraan'] = $no_per;
		$data['title'] = 'Penambahan Asset';
		$data['no_per'] = $this->Model_asset->get_no_per();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset/view_asset_tahun', $data);
		$this->load->view('templates/footer');
	}

	public function asset_tahun_cetak()
	{
		$get_tahun = $this->session->userdata('tambah_tahun_session');
		$no_per = $this->input->get('no_per');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$this->session->unset_userdata('tambah_tahun_session');
			$tahun = date('Y');
		}

		$data['tahun_lap'] = $tahun;

		$data['asset'] = $this->Model_asset->get_all_tahun_cetak($tahun);
		$first_group = reset($data['asset']);
		$data['total_rupiah'] = $first_group['items'][0]->total_rupiah ?? 0;

		$data['no_per_descriptions'] = [
			218 => 'TANAH & PENYEMPURNAAN TANAH',
			220 => 'INSTALASI SUMBER',
			222 => 'INSTALASI POMPA',
			224 => 'INSTALASI PENGOLAHAN',
			226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
			228 => 'BANGUNAN & GEDUNG',
			244 => 'PERALATAN & PERLENGKAPAN',
			246 => 'KENDARAAN',
			248 => 'INVENTARIS & PERABOTAN KANTOR'
		];

		$data['no_perkiraan'] = $no_per;
		$data['title'] = 'Penambahan Asset';
		$data['no_per'] = $this->Model_asset->get_no_per();

		$this->pdf->setPaper('folio', 'portrait');
		$this->pdf->filename = "tambah-asset_{$tahun}.pdf";
		$this->pdf->generate('cetakan_asset/tambah_asset_pdf', $data);
	}

	public function asset_kurang_tahun()
	{
		$get_tahun = $this->input->get('tahun');
		$no_per = $this->input->get('no_per');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$tahun = date('Y');
		} else {
			$this->session->set_userdata('kurang_tahun_session', $get_tahun);
		}
		$data['tahun_lap'] = $tahun;


		if (empty($get_tahun) || empty($no_per)) {
			$data['asset'] = $this->Model_asset->get_all_kurang_tahun($tahun);
		} else {
			$data['asset'] = $this->Model_asset->get_all_kurang_tahun_perkiraan($tahun, $no_per);
		}

		$data['no_per_descriptions'] = [
			218 => 'TANAH & PENYEMPURNAAN TANAH',
			220 => 'INSTALASI SUMBER',
			222 => 'INSTALASI POMPA',
			224 => 'INSTALASI PENGOLAHAN',
			226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
			228 => 'BANGUNAN & GEDUNG',
			244 => 'PERALATAN & PERLENGKAPAN',
			246 => 'KENDARAAN',
			248 => 'INVENTARIS & PERABOTAN KANTOR'
		];

		$data['no_perkiraan'] = $no_per;
		$data['title'] = 'Pengurangan Asset';
		$data['no_per'] = $this->Model_asset->get_no_per();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset_kurang/view_asset_kurang_tahun', $data);
		$this->load->view('templates/footer');
	}

	public function asset_kurang_tahun_cetak()
	{
		$get_tahun = $this->session->userdata('kurang_tahun_session');
		$no_per = $this->input->get('no_per');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$this->session->unset_userdata('kurang_tahun_session');
			$tahun = date('Y');
		}

		$data['tahun_lap'] = $tahun;

		$data['asset'] = $this->Model_asset->get_all_tahun_kurang_cetak($tahun);
		$first_group = reset($data['asset']);
		$data['total_rupiah'] = $first_group['items'][0]->total_rupiah ?? 0;

		$data['no_per_descriptions'] = [
			218 => 'TANAH & PENYEMPURNAAN TANAH',
			220 => 'INSTALASI SUMBER',
			222 => 'INSTALASI POMPA',
			224 => 'INSTALASI PENGOLAHAN',
			226 => 'INSTALASI TRANSMISI & DISTRIBUSI',
			228 => 'BANGUNAN & GEDUNG',
			244 => 'PERALATAN & PERLENGKAPAN',
			246 => 'KENDARAAN',
			248 => 'INVENTARIS & PERABOTAN KANTOR'
		];

		$data['no_perkiraan'] = $no_per;
		$data['title'] = 'Pengurangan Asset';
		$data['no_per'] = $this->Model_asset->get_no_per();

		$this->pdf->setPaper('folio', 'portrait');
		$this->pdf->filename = "kurang-asset_{$tahun}.pdf";
		$this->pdf->generate('cetakan_asset/kurang_asset_pdf', $data);
	}

	public function asset_kurang_akm()
	{
		$get_tahun = $this->input->get('tahun');
		$no_per = $this->input->get('no_per');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$tahun = date('Y');
		} else {
			$this->session->set_userdata('tahun_session_kurang_akm', $get_tahun);
		}
		$data['tahun_lap'] = $tahun;

		$penyusutan_data = $this->Model_asset->get_all_kurang_akm($tahun);

		$data['susut'] = $penyusutan_data['results'];
		$data['totals'] = $penyusutan_data['totals'];



		$data['no_perkiraan'] = $no_per;
		$data['title'] = 'Daftar Pengurangan Asset Tetap';
		$data['no_per'] = $this->Model_asset->get_no_per();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('asset_kurang/view_asset_kurang_akm', $data);
		$this->load->view('templates/footer');
	}

	public function asset_kurang_akm_cetak()
	{
		$get_tahun = $this->session->userdata('tahun_session_kurang_akm');
		$no_per = $this->input->get('no_per');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$this->session->unset_userdata('tahun_session_kurang_akm');
			$tahun = date('Y');
		}

		$data['tahun_lap'] = $tahun;

		$penyusutan_data = $this->Model_asset->get_all_kurang_akm($tahun);

		$data['susut'] = $penyusutan_data['results'];
		$data['totals'] = $penyusutan_data['totals'];

		$data['no_perkiraan'] = $no_per;
		$data['title'] = 'Daftar Pengurangan Penyusutan Asset';
		$data['no_per'] = $this->Model_asset->get_no_per();

		$this->pdf->setPaper('folio', 'portrait');
		$this->pdf->filename = "pengurangan_penyusutan-{$tahun}.pdf";
		$this->pdf->generate('cetakan_asset/pengurangan_penyusutan_pdf', $data);
	}
}
