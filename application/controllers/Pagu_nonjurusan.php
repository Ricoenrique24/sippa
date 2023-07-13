<?php
defined('BASEPATH') or exit('No direct script access allowed');

require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pagu_nonjurusan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(['M_data', 'M_unit']);
        if ($this->session->userdata('status') != TRUE) {
            redirect(base_url("login"));
        }
    }

    public function index()
    {
        if (isset($_SESSION['id_simulasi_non'])) {
            unset($_SESSION['id_simulasi_non']);
            unset($_SESSION['tahun_simulasi_non']);
            unset($_SESSION['nominal_anggaran_non']);
        }

        $data['simulasi_jurusan'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE jenis_simulasi = 2")->result();
        $data['no'] = 0;
        $this->load->view("admin/V_panonJurusan", $data);
    }

    public function validasiData($id)
    {
        $fields = array(
            'isApproval' => 2
        );
        $where = array(
            'id_simulasi' => $id
        );

        $this->M_data->updateData('tb_simulasi', $fields, $where);

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil mengubah data.";

        redirect('pagu-anggaran-nonjurusan');
    }

    public function UnvalidasiData($id)
    {
        $fields = array(
            'isApproval' => 0
        );
        $where = array(
            'id_simulasi' => $id
        );

        $this->M_data->updateData('tb_simulasi', $fields, $where);

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil mengubah data.";

        redirect('pagu-anggaran-nonjurusan');
    }

	public function UnvalidasiDataKet()
    {
		$id = $this->input->post("idsimulasi");
		$ket = $this->input->post("keterangan");
        $fields = array(
            'isApproval' => 1,
			'keterangan' => $ket
        );
        $where = array(
            'id_simulasi' => $id
        );

        $this->M_data->updateData('tb_simulasi', $fields, $where);

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil mengubah data.";

        redirect('pagu-anggaran-nonjurusan');
    }

    public function perbaruiData($id)
    {
        $data_edit = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE id_simulasi = '$id'")->result();

        foreach ($data_edit as $key => $vall) {
            $id_simulasi = $id;
            $subkriteria = $vall->id_subkriteria;
            // proses update nilai rata-rata dan bobot di subkriteria yang dipilih
            $total_subkriteria = $this->M_data->getQuery("SELECT SUM(nilai_input) AS jml_inputan FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_subkriteria = '$subkriteria'")->row_array();
            $total_subkriteria = $total_subkriteria['jml_inputan'];

            //bobot subkriteria
            $bobot_subkriteria = $this->M_data->getQuery("SELECT bobot_subkriteria FROM tb_subkriteria WHERE id_subkriteria = '$subkriteria'")->row_array();

            $rata_rata = $vall->nilai_input / $total_subkriteria;
            $bobot_inputan = ($rata_rata * $bobot_subkriteria['bobot_subkriteria']) / 100;

            $fields_upd = array(
                'nilai_rata_rata' => $rata_rata,
                'nilai_bobot' => $bobot_inputan
            );
            $where_upd = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $vall->id_unit,
                'id_subkriteria' => $subkriteria
            );
            $this->M_data->updateData('tb_detail_simulasi', $fields_upd, $where_upd);
        }


        // Hitung Anggaran per Jurusan
        $unit_jurusan = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 2")->result();
        $kriteria = $this->M_data->getQuery("SELECT tb_subkriteria.id_kriteria, bobot_kriteria FROM `tb_subkriteria` JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE jenis_subkriteria = 'nonjurusan' GROUP BY id_kriteria")->result();
        foreach ($unit_jurusan as $key => $value) {
            $id_unit = $value->id_unit;
            $temp = 0;
            foreach ($kriteria as $key => $value2) {
                $id_kriteria = $value2->id_kriteria;

                $jml_nilai_akhir = $this->M_data->getQuery("SELECT SUM(nilai_bobot) AS bobot FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_unit = '$id_unit' AND id_kriteria = '$id_kriteria'")->row_array();

                $temp = $temp + $jml_nilai_akhir['bobot'];
            }

            $angg_global = $this->M_data->getQuery("SELECT nominal_anggaran FROM tb_simulasi WHERE id_simulasi = '$id_simulasi'")->row_array();
            $nilai_persentase = $temp;
            $angg_jurusan = round($nilai_persentase, 2) * $angg_global['nominal_anggaran'];

            $cek_data = $this->M_data->getQuery("SELECT * FROM tb_hasil WHERE id_simulasi = '$id_simulasi' AND id_unit = '$id_unit'")->num_rows();
            if ($cek_data == 0) { // jika tidak ada data maka simpan
                // simpan tb_hasil
                $fields = array(
                    'id_simulasi' => $id_simulasi,
                    'id_unit' => $id_unit,
                    'nilai_persentase' => $nilai_persentase,
                    'nilai_anggaran' => $angg_jurusan
                );
                $this->M_data->simpanData('tb_hasil', $fields);
            } else {
                // update tb_hasil
                $fields = array(
                    'nilai_persentase' => $nilai_persentase,
                    'nilai_anggaran' => $angg_jurusan
                );
                $where = array(
                    'id_simulasi' => $id_simulasi,
                    'id_unit' => $id_unit
                );
                $this->M_data->updateData('tb_hasil', $fields, $where);
            }
        } // end loop jurusan

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil memperbarui data.";

        redirect('pagu-anggaran-nonjurusan');
    }

    public function add_simulasi_jurusan()
    {
        $data['jurusan'] = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 2")->result();
        $data['subkriteria'] = $this->M_data->getQuery("SELECT * FROM tb_subkriteria WHERE jenis_subkriteria = 'nonjurusan'")->result();

        if (isset($_SESSION['id_simulasi_non'])) {
            $data['tabel_hasil'] = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$_SESSION[id_simulasi_non]'")->result();
            $data['id_simulasi'] = $_SESSION['id_simulasi_non'];
        } else {
            $data['tabel_hasil'] = NULL;
            $data['id_simulasi'] = 0;
        }
        $this->load->view("admin/V_inputpanonJurusan", $data);
    }

    public function edit_simulasi_jurusan($id)
    {
        $data['jurusan'] = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 2")->result();
        $data['subkriteria'] = $this->M_data->getQuery("SELECT * FROM tb_subkriteria WHERE jenis_subkriteria = 'nonjurusan'")->result();

        $data['tabel_parent'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE id_simulasi = '$id'")->row_array();
        $data['tabel_hasil'] = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$id'")->result();

        $this->load->view("admin/V_editpanonJurusan", $data);
    }

    public function prosesEditParent()
    {
        $id_simulasi = $this->input->post("id_simulasi");
        $tahun_hitungan = $this->input->post("tahun_hitungan");
        $jenis_simulasi = $this->input->post("jenis_simulasi");
        $nominal_anggaran = $this->input->post("nominal_anggaran");

        $fields = array(
            'tahun_simulasi' => $tahun_hitungan,
            'nominal_anggaran' => $nominal_anggaran,
            'jenis_simulasi' => $jenis_simulasi
        );
        $where = array(
            'id_simulasi' => $id_simulasi
        );
        $this->M_data->updateData('tb_simulasi', $fields, $where);

        redirect('edit-simulasi-nonjurusan/' . $id_simulasi);
    }

    public function add_inputan()
    {
        $tahun_hitungan = $this->input->post("tahun_hitungan");
        $jenis_simulasi = $this->input->post("jenis_simulasi");
        $nominal_anggaran = $this->input->post("nominal_anggaran");
        $jurusan = $this->input->post("jurusan");
        $subkriteria = $this->input->post("subkriteria");
        $inputan = $this->input->post("inputan");

        if (strpos($inputan, ';') !== false) {
            $jum_prodi = $this->M_data->getQuery("SELECT nilai_input FROM tb_detail_simulasi WHERE id_simulasi = '$_SESSION[id_simulasi]' AND id_unit = '$jurusan' AND id_subkriteria = 3")->row_array();
            $str = explode(";", $inputan);
            $temp = 0;
            for ($i = 0; $i < count($str); $i++) {
                if ($str[$i] == 'A') {
                    $angka = 0.5;
                } else if ($str[$i] == 'B') {
                    $angka = 0.4;
                } else {
                    $angka = 0.3;
                }
                $temp = $temp + $angka;
            }
            $inputan = $temp / $jum_prodi['nilai_input'];
            echo $jum_prodi['nilai_input'];
        }

        $cek = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = '$tahun_hitungan' AND jenis_simulasi = 2")->num_rows();
        if ($cek == 0) { // tambah data
            $fields_parent = array(
                'tahun_simulasi' => $tahun_hitungan,
                'jenis_simulasi' => $jenis_simulasi,
                'nominal_anggaran' => $nominal_anggaran
            );
            $id_simulasi = $this->M_data->simpanData('tb_simulasi', $fields_parent);
        } else {
            $id_simulasi = $_SESSION['id_simulasi_non'];
        }

        // Proses input nilai Inputan
        $cek_detail = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_unit = '$jurusan' AND id_subkriteria = '$subkriteria'")->num_rows();
        $getKriteria = $this->M_data->getQuery("SELECT id_kriteria FROM tb_subkriteria WHERE id_subkriteria = '$subkriteria'")->row_array();
        if ($cek_detail == 0) { // jika tidak ada data maka simpan
            $fields_detail = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $jurusan,
                'id_kriteria' => $getKriteria['id_kriteria'],
                'id_subkriteria' => $subkriteria,
                'nilai_input' => $inputan
            );
            $this->M_data->simpanData('tb_detail_simulasi', $fields_detail);
        } else { // jika ada, maka update
            $fields_detail = array(
                'nilai_input' => $inputan
            );
            $where_detail = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $jurusan,
                'id_kriteria' => $getKriteria['id_kriteria'],
                'id_subkriteria' => $subkriteria
            );
            $this->M_data->updateData('tb_detail_simulasi', $fields_detail, $where_detail);
        }

        // proses update nilai rata-rata dan bobot di subkriteria yang dipilih
        $total_subkriteria = $this->M_data->getQuery("SELECT SUM(nilai_input) AS jml_inputan FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_subkriteria = '$subkriteria'")->row_array();
        if ($total_subkriteria['jml_inputan'] == 0) {
            $total_subkriteria = $inputan;
        } else {
            $total_subkriteria = $total_subkriteria['jml_inputan'];
        }

        //bobot subkriteria
        $bobot_subkriteria = $this->M_data->getQuery("SELECT bobot_subkriteria FROM tb_subkriteria WHERE id_subkriteria = '$subkriteria'")->row_array();

        $data_subkriteria_diupdate = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_subkriteria = '$subkriteria'")->result();

        foreach ($data_subkriteria_diupdate as $key => $value) {
            $rata_rata = $value->nilai_input / $total_subkriteria;
            $bobot_inputan = ($rata_rata * $bobot_subkriteria['bobot_subkriteria']) / 100;

            $fields_upd = array(
                'nilai_rata_rata' => $rata_rata,
                'nilai_bobot' => $bobot_inputan
            );
            $where_upd = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $value->id_unit,
                'id_subkriteria' => $subkriteria
            );
            $this->M_data->updateData('tb_detail_simulasi', $fields_upd, $where_upd);
        }

        if (!isset($_SESSION['tahun_simulasi_non'])) {
            $_SESSION['id_simulasi_non'] = $id_simulasi;
            $_SESSION['tahun_simulasi_non'] = $tahun_hitungan;
            $_SESSION['nominal_anggaran_non'] = $nominal_anggaran;
        }

        redirect('tambah-simulasi-nonjurusan');
    }

    public function edit_inputan()
    {
        $id_simulasi = $this->input->post("id_simulasi");
        $tahun_hitungan = $this->input->post("tahun_hitungan");
        $jenis_simulasi = $this->input->post("jenis_simulasi");
        $nominal_anggaran = $this->input->post("nominal_anggaran");
        $jurusan = $this->input->post("jurusan");
        $subkriteria = $this->input->post("subkriteria");
        $inputan = $this->input->post("inputan");

        if (strpos($inputan, ';') !== false) {
            $jum_prodi = $this->M_data->getQuery("SELECT nilai_input FROM tb_detail_simulasi WHERE id_simulasi = '$_SESSION[id_simulasi]' AND id_unit = '$jurusan' AND id_subkriteria = 3")->row_array();
            $str = explode(";", $inputan);
            $temp = 0;
            for ($i = 0; $i < count($str); $i++) {
                if ($str[$i] == 'A') {
                    $angka = 0.5;
                } else if ($str[$i] == 'B') {
                    $angka = 0.4;
                } else {
                    $angka = 0.3;
                }
                $temp = $temp + $angka;
            }
            $inputan = $temp / $jum_prodi['nilai_input'];
        }

        // Proses input nilai Inputan
        $cek_detail = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_unit = '$jurusan' AND id_subkriteria = '$subkriteria'")->num_rows();
        $getKriteria = $this->M_data->getQuery("SELECT id_kriteria FROM tb_subkriteria WHERE id_subkriteria = '$subkriteria'")->row_array();
        if ($cek_detail == 0) { // jika tidak ada data maka simpan
            $fields_detail = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $jurusan,
                'id_kriteria' => $getKriteria['id_kriteria'],
                'id_subkriteria' => $subkriteria,
                'nilai_input' => $inputan
            );
            $this->M_data->simpanData('tb_detail_simulasi', $fields_detail);
        } else { // jika ada, maka update
            $fields_detail = array(
                'nilai_input' => $inputan
            );
            $where_detail = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $jurusan,
                'id_kriteria' => $getKriteria['id_kriteria'],
                'id_subkriteria' => $subkriteria
            );
            $this->M_data->updateData('tb_detail_simulasi', $fields_detail, $where_detail);
        }

        // proses update nilai rata-rata dan bobot di subkriteria yang dipilih
        $total_subkriteria = $this->M_data->getQuery("SELECT SUM(nilai_input) AS jml_inputan FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_subkriteria = '$subkriteria'")->row_array();
        if ($total_subkriteria['jml_inputan'] == 0) {
            $total_subkriteria = $inputan;
        } else {
            $total_subkriteria = $total_subkriteria['jml_inputan'];
        }

        //bobot subkriteria
        $bobot_subkriteria = $this->M_data->getQuery("SELECT bobot_subkriteria FROM tb_subkriteria WHERE id_subkriteria = '$subkriteria'")->row_array();

        $data_subkriteria_diupdate = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_subkriteria = '$subkriteria'")->result();

        foreach ($data_subkriteria_diupdate as $key => $value) {
            $rata_rata = $value->nilai_input / $total_subkriteria;
            $bobot_inputan = ($rata_rata * $bobot_subkriteria['bobot_subkriteria']) / 100;

            $fields_upd = array(
                'nilai_rata_rata' => $rata_rata,
                'nilai_bobot' => $bobot_inputan
            );
            $where_upd = array(
                'id_simulasi' => $id_simulasi,
                'id_unit' => $value->id_unit,
                'id_subkriteria' => $subkriteria
            );
            $this->M_data->updateData('tb_detail_simulasi', $fields_upd, $where_upd);
        }

        redirect('edit-simulasi-nonjurusan/' . $id_simulasi);
    }

    public function hitungSimulasi()
    {
        $id_simulasi = $_SESSION['id_simulasi_non'];

        $unit_jurusan = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 2")->result();
        $kriteria = $this->M_data->getQuery("SELECT tb_subkriteria.id_kriteria, bobot_kriteria FROM `tb_subkriteria` JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE jenis_subkriteria = 'nonjurusan' GROUP BY id_kriteria")->result();
        foreach ($unit_jurusan as $key => $value) {
            $id_unit = $value->id_unit;
            $temp = 0;
            foreach ($kriteria as $key => $value2) {
                $id_kriteria = $value2->id_kriteria;

                $jml_nilai_akhir = $this->M_data->getQuery("SELECT SUM(nilai_bobot) AS bobot FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_unit = '$id_unit' AND id_kriteria = '$id_kriteria'")->row_array();

                //                $kali_bobot = $jml_nilai_akhir['bobot'];

                $temp = $temp + $jml_nilai_akhir['bobot'];
            }

            $angg_global = $this->M_data->getQuery("SELECT nominal_anggaran FROM tb_simulasi WHERE id_simulasi = '$id_simulasi'")->row_array();
            $nilai_persentase = $temp;
            $angg_jurusan = round($nilai_persentase, 2) * $angg_global['nominal_anggaran'];

            $cek_data = $this->M_data->getQuery("SELECT * FROM tb_hasil WHERE id_simulasi = '$id_simulasi' AND id_unit = '$id_unit'")->num_rows();
            if ($cek_data == 0) { // jika tidak ada data maka simpan
                // simpan tb_hasil
                $fields = array(
                    'id_simulasi' => $id_simulasi,
                    'id_unit' => $id_unit,
                    'nilai_persentase' => $nilai_persentase,
                    'nilai_anggaran' => $angg_jurusan
                );
                $this->M_data->simpanData('tb_hasil', $fields);
            } else {
                // update tb_hasil
                $fields = array(
                    'nilai_persentase' => $nilai_persentase,
                    'nilai_anggaran' => $angg_jurusan
                );
                $where = array(
                    'id_simulasi' => $id_simulasi,
                    'id_unit' => $id_unit
                );
                $this->M_data->updateData('tb_hasil', $fields, $where);
            }
        } // end loop jurusan

        if (isset($_SESSION['id_simulasi_non'])) {
            unset($_SESSION['id_simulasi_non']);
            unset($_SESSION['tahun_simulasi_non']);
            unset($_SESSION['nominal_anggaran_non']);
        }

        redirect('hasil-simulasi-nonjurusan/' . $id_simulasi);
    }

    public function hitungSimulasiEdit($id)
    {
        $id_simulasi = $id;
        // Merubah Approval / Validasi ke 0 dan mengosongkan keterangan
        $this->M_data->getQuery("UPDATE `tb_simulasi` SET `isApproval` = '0', `keterangan` = '' WHERE `tb_simulasi`.`id_simulasi` = $id;");

        $unit_jurusan = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 2")->result();
        $kriteria = $this->M_data->getQuery("SELECT tb_subkriteria.id_kriteria, bobot_kriteria FROM `tb_subkriteria` JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE jenis_subkriteria = 'nonjurusan' GROUP BY id_kriteria")->result();
        foreach ($unit_jurusan as $key => $value) {
            $id_unit = $value->id_unit;
            $temp = 0;
            foreach ($kriteria as $key => $value2) {
                $id_kriteria = $value2->id_kriteria;
                $bobot_kriteria = $value2->bobot_kriteria;

                $jml_nilai_akhir = $this->M_data->getQuery("SELECT SUM(nilai_bobot) AS bobot FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_unit = '$id_unit' AND id_kriteria = '$id_kriteria'")->row_array();

                $kali_bobot = ($jml_nilai_akhir['bobot'] * $bobot_kriteria) / 100;

                $temp = $temp + $kali_bobot;
            }

            $angg_global = $this->M_data->getQuery("SELECT nominal_anggaran FROM tb_simulasi WHERE id_simulasi = '$id_simulasi'")->row_array();
            $nilai_persentase = $temp * 100;
            $angg_jurusan = (round($nilai_persentase, 2) * $angg_global['nominal_anggaran']) / 100;

            $cek_data = $this->M_data->getQuery("SELECT * FROM tb_hasil WHERE id_simulasi = '$id_simulasi' AND id_unit = '$id_unit'")->num_rows();
            if ($cek_data == 0) { // jika tidak ada data maka simpan
                // simpan tb_hasil
                $fields = array(
                    'id_simulasi' => $id_simulasi,
                    'id_unit' => $id_unit,
                    'nilai_persentase' => $nilai_persentase,
                    'nilai_anggaran' => $angg_jurusan
                );
                $this->M_data->simpanData('tb_hasil', $fields);
            } else {
                // update tb_hasil
                $fields = array(
                    'nilai_persentase' => $nilai_persentase,
                    'nilai_anggaran' => $angg_jurusan
                );
                $where = array(
                    'id_simulasi' => $id_simulasi,
                    'id_unit' => $id_unit
                );
                $this->M_data->updateData('tb_hasil', $fields, $where);
            }
        } // end loop jurusan

        redirect('hasil-simulasi-nonjurusan/' . $id_simulasi);
    }

    public function hasil_simulasi_jurusan($id = null)
    {
        if ($id != null) {
            $data['tabel_hasil']            = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$id'")->result();
            $data['tabel_hasil_simulasi']   = $this->M_data->getQuery("SELECT * FROM tb_hasil JOIN tb_unit ON tb_hasil.id_unit = tb_unit.id_unit WHERE tb_hasil.id_simulasi = '$id'")->result();

            $data['anggaran']               = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tb_simulasi.id_simulasi = '$id'")->row_array();
            $data['simulasi_jurusan']       = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE jenis_simulasi = 2 ORDER BY `tb_simulasi`.`tahun_simulasi` ASC")->result();
            $data['total']                  = $this->M_data->getQuery("SELECT SUM(nilai_anggaran) as na FROM tb_hasil JOIN tb_unit ON tb_hasil.id_unit = tb_unit.id_unit WHERE tb_hasil.id_simulasi = $id")->row_array();
        } else {
            $data['tabel_hasil'] = NULL;
            $data['tabel_hasil_simulasi'] = NULL;
        }
        $this->load->view("admin/V_hasilpanonJurusan", $data);
    }

    public function export_simulasi_jurusan($id)
    {

        $tabel_hasil = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$id'")->result();

        $tabel_hasil_simulasi = $this->M_data->getQuery("SELECT * FROM tb_hasil JOIN tb_unit ON tb_hasil.id_unit = tb_unit.id_unit WHERE tb_hasil.id_simulasi = '$id'")->result();

        $spreadsheet = new Spreadsheet;

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Jurusan')
            ->setCellValue('B1', 'Sub Kriteria')
            ->setCellValue('C1', 'Inputan')
            ->setCellValue('D1', 'Rata-rata')
            ->setCellValue('E1', 'Kali Bobot');

        $kolom = 2;
        foreach ($tabel_hasil as $value) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $value->nama_unit)
                ->setCellValue('B' . $kolom, $value->nama_subkriteria)
                ->setCellValue('C' . $kolom, round($value->nilai_input, 2))
                ->setCellValue('D' . $kolom, round($value->nilai_rata_rata, 2))
                ->setCellValue('E' . $kolom, round($value->nilai_bobot, 2));

            $kolom++;
        }

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('G1', 'Jurusan')
            ->setCellValue('H1', 'Nilai Persentase')
            ->setCellValue('I1', 'Nilai Rupiah');

        $kolom = 2;
        foreach ($tabel_hasil_simulasi as $value) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('G' . $kolom, $value->nama_unit)
                ->setCellValue('H' . $kolom, round($value->nilai_persentase, 2))
                ->setCellValue('I' . $kolom, $value->nilai_anggaran);

            $kolom++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data-Stok-Produk.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    function hapusData($id)
    {
        $where = array('id_simulasi' => $id);

        // hapus hasil 
        $this->M_data->hapus_data($where, 'tb_hasil');
        //hapus detail simulasi
        $this->M_data->hapus_data($where, 'tb_detail_simulasi');
        //hapus simulasi
        $this->M_data->hapus_data($where, 'tb_simulasi');

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil menghapus data.";

        redirect('pagu-anggaran-nonjurusan');
    }
}
