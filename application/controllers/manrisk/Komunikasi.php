<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Komunikasi & Konsultasi (SNI ISO 31000:2018 Klausa 6.2).
 *
 * Mencatat sesi komunikasi dengan pihak relevan: tiga langkah (dengarkan,
 * selaraskan, uji & konfirmasi), empat pertanyaan kunci, lima hasil yang
 * diharapkan, dan tindak lanjutnya. Berbeda dari kolom konsultasi pada
 * dokumen Lingkup yang hanya merupakan ringkasan satu dokumen.
 */
class Komunikasi extends MY_Controller
{
    private $tujuan = 'manrisk/komunikasi';

    /** Folder fisik foto pendukung, relatif terhadap FCPATH. */
    private $fotoPath = 'assets/uploads/manrisk/komunikasi/';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_mri_komunikasi');
        $this->load->model('Model_mri_register');
        $this->load->model('Model_mri_lingkup');
        $this->load->model('Model_mri_pengaturan');
        $this->load->library('form_validation');
    }

    /**
     * Pengguna non-pusat hanya boleh mengubah catatan unitnya sendiri,
     * termasuk menolak catatan tingkat organisasi (id_upk NULL).
     */
    private function tolakBilaBukanMilik($k)
    {
        if ($this->aksesSemuaUnit()) {
            return true;
        }
        $paks = $this->unitPaksa();
        if ($paks === null || (int) $k->id_upk !== (int) $paks) {
            $this->pesan('<strong>Maaf,</strong> Anda hanya dapat mengelola data unit sendiri.', 'danger');
            redirect($this->tujuan);
            return false;
        }
        return true;
    }

    /** Lokasi fisik berkas foto. */
    private function pathFoto($nama_file)
    {
        return rtrim(FCPATH, '/\\') . '/' . $this->fotoPath . basename((string) $nama_file);
    }

    /**
     * Unggah semua berkas pada input foto[].
     * @return array{0:int,1:string[]} [jumlah berhasil, daftar galat]
     */
    private function unggahFotoBanyak($id_komunikasi)
    {
        if (empty($_FILES['foto']['name'])) {
            return [0, []];
        }

        $berkas = $_FILES['foto'];
        if (!is_array($berkas['name'])) {
            $berkas = [
                'name'     => [$berkas['name']],
                'type'     => [$berkas['type']],
                'tmp_name' => [$berkas['tmp_name']],
                'error'    => [$berkas['error']],
                'size'     => [$berkas['size']],
            ];
        }

        $folder = rtrim(FCPATH, '/\\') . '/' . $this->fotoPath;
        if (!is_dir($folder)) {
            @mkdir($folder, 0777, true);
        }
        if (!is_dir($folder)) {
            return [0, ['Folder upload foto tidak dapat dibuat.']];
        }

        $this->load->library('upload');
        $konfig = [
            'upload_path'   => $folder,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048, // KB  // 2 MB
            'encrypt_name'  => true,
        ];

        $jumlah = 0;
        $galat  = [];
        foreach ($berkas['name'] as $i => $nama) {
            if ($nama === '' || (int) $berkas['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $_FILES['foto_single'] = [
                'name'     => $nama,
                'type'     => $berkas['type'][$i],
                'tmp_name' => $berkas['tmp_name'][$i],
                'error'    => $berkas['error'][$i],
                'size'     => $berkas['size'][$i],
            ];

            $this->upload->initialize($konfig);
            if (!$this->upload->do_upload('foto_single')) {
                $pesanGalat = trim(strip_tags($this->upload->display_errors('', '')));
                $galat[] = $pesanGalat !== '' ? $pesanGalat : ('Foto ' . $nama . ' gagal diunggah.');
                continue;
            }

            $info = $this->upload->data();
            if ($this->Model_mri_komunikasi->tambahFoto($id_komunikasi, $info['file_name'])) {
                $jumlah++;
            } else {
                @unlink($info['full_path']);
                $galat[] = 'Foto ' . $nama . ' gagal disimpan ke database.';
            }
        }
        unset($_FILES['foto_single']);

        return [$jumlah, array_values(array_unique(array_filter($galat)))];
    }

    /** Hapus foto yang dicentang pada form edit. */
    private function hapusFotoTerpilih($id_komunikasi, $daftar)
    {
        if (!is_array($daftar)) {
            $daftar = $daftar ? [$daftar] : [];
        }
        foreach ($daftar as $id_foto) {
            $f = $this->Model_mri_komunikasi->getFotoById((int) $id_foto, (int) $id_komunikasi);
            if (!$f) {
                continue;
            }
            if ($this->Model_mri_komunikasi->hapusFoto((int) $id_foto, (int) $id_komunikasi)) {
                @unlink($this->pathFoto($f->nama_file));
            }
        }
    }

    // ------------------------------------------------------------------
    // DAFTAR SESI KOMUNIKASI
    // ------------------------------------------------------------------
    public function index($id = null)
    {
        if ($id !== null) {
            $this->form($id);
            return;
        }

        $tahun = $this->tahunAktif(2027);
        $f = ['tahun' => $tahun];

        if (!$this->aksesSemuaUnit()) {
            $f['id_upk'] = $this->unitPaksa();
        } elseif ($this->angka('id_upk')) {
            $f['id_upk'] = $this->angka('id_upk');
        }

        $status = $this->teks('status');
        if (in_array($status, Model_mri_komunikasi::STATUS, true)) {
            $f['status'] = $status;
        }
        $lanjut = $this->teks('status_tindak_lanjut');
        if (in_array($lanjut, Model_mri_komunikasi::STATUS_TINDAK_LANJUT, true)) {
            $f['status_tindak_lanjut'] = $lanjut;
        }
        $q = $this->teks('q');
        if ($q !== '') {
            $f['q'] = $q;
        }

        $daftarTahun = $this->Model_mri_komunikasi->daftarTahun();
        foreach ([2027, $tahun] as $t) {
            if (!in_array((int) $t, $daftarTahun, true)) {
                $daftarTahun[] = (int) $t;
            }
        }
        rsort($daftarTahun);

        $this->render('manrisk/view_komunikasi', [
            'title'            => 'KOMUNIKASI & KONSULTASI',
            'tahun'            => $tahun,
            'filter'           => $f,
            'daftar'           => $this->Model_mri_komunikasi->getList($f),
            'rekap'            => $this->Model_mri_komunikasi->rekap($tahun, isset($f['id_upk']) ? $f['id_upk'] : null),
            'daftar_tahun'     => $daftarTahun,
            'unit_list'        => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'status_list'      => Model_mri_komunikasi::STATUS,
            'lanjut_list'      => Model_mri_komunikasi::STATUS_TINDAK_LANJUT,
            'akses_semua'      => $this->aksesSemuaUnit(),
            'boleh_tulis'      => $this->Model_mri_pengaturan->bolehUbah('komunikasi_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // DETAIL SATU SESI (termasuk foto pendukung)
    // ------------------------------------------------------------------
    public function detail($id = null)
    {
        $k = $this->Model_mri_komunikasi->getById($id);
        if (!$k) {
            $this->pesan('Catatan komunikasi tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->tolakBilaBukanMilik($k)) {
            return;
        }

        $this->render('manrisk/view_komunikasi_detail', [
            'title'       => 'DETAIL KOMUNIKASI',
            'catatan'     => $k,
            'foto'        => $this->Model_mri_komunikasi->getFoto($k->id_komunikasi),
            'boleh_tulis' => $this->Model_mri_pengaturan->bolehUbah('komunikasi_risiko'),
        ]);
    }

    // ------------------------------------------------------------------
    // FORM TAMBAH / UBAH
    // ------------------------------------------------------------------
    public function form($id = null)
    {
        $k = $id ? $this->Model_mri_komunikasi->getById($id) : null;
        if ($id && !$k) {
            $this->pesan('Catatan komunikasi tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if ($k && !$this->tolakBilaBukanMilik($k)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('komunikasi_risiko')) {
            $this->pesan('Komunikasi & Konsultasi sedang dikunci. Buka kunci pada Pengaturan terlebih dahulu.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $tahunForm = $k ? (int) $k->tahun : $this->tahunAktif(2027);

        $this->form_validation->set_rules('tahun', 'Tahun', 'required|integer');
        $this->form_validation->set_rules('triwulan', 'Triwulan', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[4]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('topik', 'Topik / Agenda', 'required|trim|max_length[200]');
        $this->form_validation->set_rules(
            'metode',
            'Metode',
            'required|in_list[' . implode(',', Model_mri_komunikasi::METODE) . ']'
        );
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[DRAFT,SELESAI]');
        $this->form_validation->set_rules(
            'status_tindak_lanjut',
            'Status Tindak Lanjut',
            'required|in_list[BELUM,PROSES,SELESAI]'
        );
        $this->form_validation->set_message('required', '%s masih kosong');
        $this->form_validation->set_message('greater_than_equal_to', '%s minimal %s');
        $this->form_validation->set_message('less_than_equal_to', '%s maksimal %s');
        $this->form_validation->set_message('in_list', '%s tidak valid');

        if ($this->diposting() && $this->form_validation->run() === true) {
            $tahun = (int) $this->input->post('tahun', true) ?: $tahunForm;
            $id_upk = $this->aksesSemuaUnit() ? $this->angka('id_upk') : $this->unitPaksa();

            // Tautan opsional: abaikan bila risiko/lingkup bukan milik tahun
            // dan unit yang sama, agar relasi tidak menyesatkan.
            $id_register = $this->angka('id_register');
            if ($id_register) {
                $r = $this->Model_mri_register->getById($id_register);
                if (
                    !$r
                    || (int) $r->tahun !== $tahun
                    || ($id_upk && $r->id_upk && (int) $r->id_upk !== (int) $id_upk)
                ) {
                    $id_register = null;
                }
            }

            $id_lingkup = $this->angka('id_lingkup');
            if ($id_lingkup) {
                $l = $this->Model_mri_lingkup->getLingkupById($id_lingkup);
                if (
                    !$l
                    || (int) $l->tahun !== $tahun
                    || ($id_upk && $l->id_upk && (int) $l->id_upk !== (int) $id_upk)
                ) {
                    $id_lingkup = null;
                }
            }

            $d = [
                'tahun'               => $tahun,
                'triwulan'            => (int) $this->input->post('triwulan', true),
                'id_upk'              => $id_upk,
                'id_lingkup'          => $id_lingkup,
                'id_register'         => $id_register,
                'tanggal'             => $this->teks('tanggal'),
                'metode'              => $this->teks('metode'),
                'topik'               => $this->teks('topik'),
                'pihak_terlibat'      => $this->teks('pihak_terlibat'),
                'pic_komunikasi'      => $this->teks('pic_komunikasi'),
                'catatan_dengar'      => $this->teks('catatan_dengar'),
                'catatan_selaras'     => $this->teks('catatan_selaras'),
                'catatan_konfirmasi'  => $this->teks('catatan_konfirmasi'),
                'q_sasaran'           => $this->teks('q_sasaran'),
                'q_risiko'            => $this->teks('q_risiko'),
                'q_kontrol'           => $this->teks('q_kontrol'),
                'q_tindakan'          => $this->teks('q_tindakan'),
                'hasil_pemahaman'     => $this->teks('hasil_pemahaman') === '1',
                'hasil_prioritas'     => $this->teks('hasil_prioritas') === '1',
                'hasil_validasi'      => $this->teks('hasil_validasi') === '1',
                'hasil_kesepakatan'   => $this->teks('hasil_kesepakatan') === '1',
                'hasil_tindak_lanjut' => $this->teks('hasil_tindak_lanjut') === '1',
                'ringkasan_hasil'     => $this->teks('ringkasan_hasil'),
                'tindak_lanjut'       => $this->teks('tindak_lanjut'),
                'pic_tindak_lanjut'   => $this->teks('pic_tindak_lanjut'),
                'target_selesai'      => $this->teks('target_selesai') ?: null,
                'status_tindak_lanjut' => $this->teks('status_tindak_lanjut'),
                'status'              => $this->teks('status'),
            ];

            $simpan = $this->Model_mri_komunikasi->simpan($d, $id ? (int) $id : null);
            if ($simpan) {
                $idSimpan = $id ? (int) $id : (int) $this->db->insert_id();

                // Foto lama yang dicentang dihapus lebih dulu, lalu unggahan baru.
                if ($k) {
                    $this->hapusFotoTerpilih($idSimpan, $this->input->post('hapus_foto'));
                }
                list($jumlahFoto, $galatFoto) = $idSimpan
                    ? $this->unggahFotoBanyak($idSimpan)
                    : [0, ['Sesi tidak memiliki ID, foto tidak dapat disimpan.']];

                $pesan = 'Catatan komunikasi <strong>' . html_escape($d['topik']) . '</strong> disimpan.';
                if ($jumlahFoto) {
                    $pesan .= ' <strong>' . (int) $jumlahFoto . '</strong> foto pendukung ditambahkan.';
                }
                if ($galatFoto) {
                    $this->pesan(
                        $pesan . ' Sebagian foto gagal diunggah: ' . html_escape(implode(' ', $galatFoto)),
                        'warning'
                    );
                } else {
                    $this->pesan($pesan);
                }
            } else {
                $this->pesan('Catatan komunikasi gagal disimpan.', 'danger');
            }
            redirect($this->tujuan . '?tahun=' . $tahun);
            return;
        }

        if ($this->diposting()) {
            $tahunForm = (int) $this->input->post('tahun', true) ?: $tahunForm;
            $id_upk = $this->aksesSemuaUnit() ? $this->angka('id_upk') : $this->unitPaksa();
        } else {
            $id_upk = $k ? $k->id_upk : ($this->aksesSemuaUnit() ? null : $this->unitPaksa());
        }
        $risiko = $this->Model_mri_register->getListDenganSkor([
            'tahun'  => $tahunForm,
            'id_upk' => $id_upk,
        ]);
        $lingkup = $this->Model_mri_lingkup->getLingkup($tahunForm, $id_upk);

        $this->render('manrisk/view_komunikasi_form', [
            'title'       => $id ? 'UBAH KOMUNIKASI' : 'TAMBAH KOMUNIKASI',
            'catatan'     => $k,
            'tahun'       => $tahunForm,
            'risiko'      => $risiko,
            'lingkup'     => $lingkup,
            'unit_list'   => $this->db->from('bagian_upk')->order_by('id_bagian', 'ASC')->get()->result(),
            'metode_list' => Model_mri_komunikasi::METODE,
            'status_list' => Model_mri_komunikasi::STATUS,
            'lanjut_list' => Model_mri_komunikasi::STATUS_TINDAK_LANJUT,
            'akses_semua' => $this->aksesSemuaUnit(),
            'foto'        => $k ? $this->Model_mri_komunikasi->getFoto($k->id_komunikasi) : [],
        ]);
    }

    // ------------------------------------------------------------------
    // HAPUS
    // ------------------------------------------------------------------
    public function hapus($id = null)
    {
        $k = $this->Model_mri_komunikasi->getById($id);
        if (!$k) {
            $this->pesan('Catatan komunikasi tidak ditemukan.', 'warning');
            redirect($this->tujuan);
            return;
        }
        if (!$this->tolakBilaBukanMilik($k)) {
            return;
        }
        if (!$this->Model_mri_pengaturan->bolehUbah('komunikasi_risiko')) {
            $this->pesan('Komunikasi & Konsultasi sedang dikunci.', 'warning');
            redirect($this->tujuan);
            return;
        }

        $foto = $this->Model_mri_komunikasi->getFoto($id);
        if ($this->Model_mri_komunikasi->hapus($id)) {
            foreach ($foto as $f) {
                @unlink($this->pathFoto($f->nama_file));
            }
        }
        $this->pesan('Catatan komunikasi <strong>' . html_escape($k->topik) . '</strong> dihapus.');
        redirect($this->tujuan . '?tahun=' . (int) $k->tahun);
    }
}
