<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Amortisasi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Model_amortisasi');
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
		$get_tahun = $this->input->get('tahun');
		$tahun = substr($get_tahun, 0, 4);

		if (empty($get_tahun)) {
			$tahun = date('Y');
			// Hapus session jika tidak ada tahun yang dipilih
			$this->session->unset_userdata('tahun_session_amortisasi');
		} else {
			$this->session->set_userdata('tahun_session_amortisasi', $get_tahun);
		}
		$data['tahun_lap'] = $tahun;
		$data['title'] = 'Daftar Amortisasi / Surat Berharga';
		$penyusutan_data = $this->Model_amortisasi->get_amortisasi($tahun);
		$data['susut'] = $penyusutan_data['results'];
		$data['total_amortisasi'] = $penyusutan_data['total_amortisasi'];

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar');
		$this->load->view('templates/sidebar');
		$this->load->view('amortisasi/view_amortisasi', $data);
		$this->load->view('templates/footer');
	}

	public function cetak_amortisasi()
	{
		$tahun = $this->session->userdata('tahun_session_amortisasi');
		if (empty($tahun)) {
			$this->session->unset_userdata('tahun_session_amortisasi');
			$tahun = date('Y');
		}
		$data['tahun_lap'] = $tahun;
		$data['title'] = 'Daftar Amortisasi / Surat Berharga';
		$penyusutan_data = $this->Model_amortisasi->get_amortisasi($tahun);
		$data['susut'] = $penyusutan_data['results'];
		$data['total_amortisasi'] = $penyusutan_data['total_amortisasi'];

		$this->pdf->setPaper('folio', 'landscape');
		$this->pdf->filename = "daftar_amortisasi-{$tahun}.pdf";
		$this->pdf->generate('cetakan/amortisasi_pdf', $data);
	}

	public function upload()
	{
		$tanggal = $this->session->userdata('tanggal');
		$this->form_validation->set_rules('nama_amortisasi', 'Nama Amortisasi', 'required|trim');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
		$this->form_validation->set_rules('id_no_per', 'No Perkiraan', 'required|trim');
		$this->form_validation->set_rules('rupiah', 'Rupiah', 'required|trim|numeric');
		$this->form_validation->set_rules('status', 'Status Asset', 'required|trim');
		$this->form_validation->set_message('required', '%s masih kosong');
		$this->form_validation->set_message('numeric', '%s harus berupa angka');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Upload Amortisasi Baru';
			$data['perkiraan'] = $this->Model_amortisasi->get_perkiraan();

			$this->load->view('templates/header', $data);
			$this->load->view('templates/navbar');
			$this->load->view('templates/sidebar');
			$this->load->view('amortisasi/view_upload_amortisasi', $data);
			$this->load->view('templates/footer');
		} else {
			$data['asset'] = $this->Model_amortisasi->tambah_asset();
			$this->session->set_flashdata(
				'info',
				'<div class="alert alert-primary alert-dismissible fade show" role="alert">
	                    <strong>Sukses,</strong> Data Amortisasi baru berhasil di tambah
	                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
	                    </button>
	                  </div>'
			);
			$alamat = 'amortisasi?tanggal=' . $tanggal;
			redirect($alamat);
			// redirect('asset');
		}
	}
}
