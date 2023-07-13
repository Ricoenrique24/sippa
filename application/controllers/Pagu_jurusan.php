<?php
defined('BASEPATH') or exit('No direct script access allowed');

require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pagu_jurusan extends CI_Controller
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
        if (isset($_SESSION['id_simulasi'])) {
            unset($_SESSION['id_simulasi']);
            unset($_SESSION['tahun_simulasi']);
            unset($_SESSION['nominal_anggaran']);
        }

        $data['simulasi_jurusan'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE jenis_simulasi = 1 ORDER BY `tb_simulasi`.`tahun_simulasi` ASC")->result();
        $data['no'] = 0;
        $this->load->view("admin/V_paJurusan", $data);
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

        redirect('pagu-anggaran-jurusan');
    }

    public function UnvalidasiData($id)
    {
        $fields = array(
            'isApproval' => 3
        );
        $where = array(
            'id_simulasi' => $id
        );

        $this->M_data->updateData('tb_simulasi', $fields, $where);

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil mengubah data.";

        redirect('pagu-anggaran-jurusan');
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

        redirect('pagu-anggaran-jurusan');
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
            echo $bobot_inputan . "|";

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
        $unit_jurusan = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 1")->result();
        $kriteria = $this->M_data->getQuery("SELECT tb_subkriteria.id_kriteria, bobot_kriteria FROM `tb_subkriteria` JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE jenis_subkriteria = 'jurusan' GROUP BY id_kriteria")->result();
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

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil memperbarui data.";

        redirect('pagu-anggaran-jurusan');
    }

    public function add_simulasi_jurusan()
    {
        $data['jurusan']        = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 1")->result();
        $data['subkriteria']    = $this->M_data->getQuery("SELECT * FROM tb_subkriteria WHERE jenis_subkriteria = 'jurusan' ORDER BY `tb_subkriteria`.`id_kriteria` ASC")->result();

        if (isset($_SESSION['id_simulasi'])) {
            $data['tabel_hasil'] = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$_SESSION[id_simulasi]'")->result();
            $data['id_simulasi'] = $_SESSION['id_simulasi'];
        } else {
            $data['tabel_hasil'] = NULL;
            $data['id_simulasi'] = 0;
        }
        $this->load->view("admin/V_inputpaJurusan", $data);
    }

    public function cari_prodi($id)
    {
        $data = $this->M_data->getQuery("SELECT `nama_prodi` FROM `tb_prodi` WHERE `id_unit` = " . $id . ";")->result();
        echo json_encode($data);
    }

    public function hitung_prodi($id)
    {
        $data = $this->M_data->getQuery("SELECT COUNT(`id_prodi`) AS jumlah_prodi FROM `tb_prodi` WHERE `id_unit` = " . $id . ";")->result();
        echo json_encode($data);
    }

    public function edit_simulasi_jurusan($id)
    {
        $data['jurusan']        = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 1")->result();
        $data['data_prodi']     = $this->M_data->getQuery("SELECT * FROM `tb_history_prodi` WHERE `id_simulasi` = '$id';")->result();
        $data['subkriteria']    = $this->M_data->getQuery("SELECT * FROM tb_subkriteria WHERE jenis_subkriteria = 'jurusan'")->result();

        $data['tabel_parent'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE id_simulasi = '$id'")->row_array();
        $data['tabel_hasil'] = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$id'")->result();

        $this->load->view("admin/V_editpaJurusan", $data);
    }

    public function cek_data($id_simulasi, $id_unit, $id_subkriteria)
    {
        // var_dump($id_simulasi, $id_unit, $id_subkriteria);
        $data   = $this->M_data->getQuery("SELECT COUNT(`id_detail_simulasi`) AS 'jumlah' FROM `tb_detail_simulasi`  
                            WHERE `id_simulasi` = '$id_simulasi' AND `id_unit` = $id_unit AND `id_subkriteria` = $id_subkriteria;")->result();
        echo json_encode($data);
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

        redirect('edit-simulasi-jurusan/' . $id_simulasi);
    }

    public function add_inputan()
    {
        $tahun_hitungan     = $this->input->post("tahun_hitungan");
        $jenis_simulasi     = $this->input->post("jenis_simulasi");
        $nominal_anggaran   = $this->input->post("nominal_anggaran");
        $jurusan            = $this->input->post("jurusan");
        $subkriteria        = $this->input->post("subkriteria");
        $inputan            = $this->input->post("inputan");

        if (strpos($inputan, ';') !== false) {
            $jum_prodi = $this->M_data->getQuery("SELECT nilai_input FROM tb_detail_simulasi WHERE id_simulasi = '$_SESSION[id_simulasi]' AND id_unit = '$jurusan' AND id_subkriteria = 3")->row_array();

            // Kebutuhan untuk memasukkan data pada capaian akreditasi
            $prodi     = $this->M_data->getQuery("SELECT `nama_prodi` FROM `tb_prodi` WHERE `id_unit` = '$jurusan';")->result();
            $nam_jur   = $this->M_data->getQuery("SELECT `nama_unit` FROM `tb_unit` WHERE `id_unit` = '$jurusan';")->result()[0]->nama_unit;

            // Menghilangkan dan Merekonstruksi nilai array yang hilang
            $str       = explode(";", $inputan);
            $str       = array_filter($str);
            $str       = array_values($str);
            $temp      = 0;

            for ($i = 0; $i < count($str); $i++) {
                // Mengambil Nama Prodi
                $nama_prodi      = $prodi[$i]->nama_prodi;

                // Memasukkan pada database
                $this->M_data->getQuery("INSERT INTO `tb_history_prodi` (`id_history`, `id_simulasi`, `jurusan`, `prodi`, `akreditasi`) VALUES (NULL, '$_SESSION[id_simulasi]', '$nam_jur', '$nama_prodi', '$str[$i]');");
                // echo '<pre>';
                // var_dump("INSERT INTO `tb_history_prodi` (`id_history`, `id_simulasi`, `jurusan`, `prodi`, `akreditasi`) VALUES (NULL, '$_SESSION[id_simulasi]', '$nam_jur', '$nama_prodi', '$str[$i]");
                // echo '</pre>';

                // Menghitung bobot nilai Capaian Akreditasi Prodi
                if ($str[$i] == 'A' || $str[$i] == 'UNGGUL') {
                    $angka = 0.5;
                } else if ($str[$i] == 'B' || $str[$i] == 'SANGAT BAIK') {
                    $angka = 0.4;
                } else {
                    $angka = 0.3;
                }
                $temp = $temp + $angka;
            }

            // Mengubah Nilai prodi menjadi angka nilai
            $inputan = $temp / $jum_prodi['nilai_input'];
            // var_dump($temp);
            // var_dump("\n". $jum_prodi['nilai_input']."");
        } else {
            echo "error";
        }

        $cek = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = '$tahun_hitungan' AND jenis_simulasi = 1")->num_rows();
        if ($cek == 0) { // tambah data
            $fields_parent = array(
                'tahun_simulasi' => $tahun_hitungan,
                'jenis_simulasi' => $jenis_simulasi,
                'nominal_anggaran' => $nominal_anggaran
            );
            $id_simulasi = $this->M_data->simpanData('tb_simulasi', $fields_parent);
        } else {
            $id_simulasi = $_SESSION['id_simulasi'];
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

            if ($total_subkriteria == 0) {
                $total_subkriteria = 1;
            }

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

        if (!isset($_SESSION['tahun_simulasi'])) {
            $_SESSION['id_simulasi'] = $id_simulasi;
            $_SESSION['tahun_simulasi'] = $tahun_hitungan;
            $_SESSION['nominal_anggaran'] = $nominal_anggaran;
        }

        redirect('tambah-simulasi-jurusan');
    }

    public function edit_inputan()
    {
        $id_simulasi        = $this->input->post("id_simulasi");
        $tahun_hitungan     = $this->input->post("tahun_hitungan");
        $jenis_simulasi     = $this->input->post("jenis_simulasi");
        $nominal_anggaran   = $this->input->post("nominal_anggaran");
        $jurusan            = $this->input->post("jurusan");
        $subkriteria        = $this->input->post("subkriteria");
        $inputan            = $this->input->post("inputan");

        if (strpos($inputan, ';') !== false) {
            $jum_prodi = $this->M_data->getQuery("SELECT nilai_input FROM tb_detail_simulasi WHERE id_simulasi = '$id_simulasi' AND id_unit = '$jurusan' AND id_subkriteria = 3")->row_array();

            // Kebutuhan untuk memasukkan data pada capaian akreditasi
            $prodi     = $this->M_data->getQuery("SELECT `nama_prodi` FROM `tb_prodi` WHERE `id_unit` = '$jurusan';")->result();
            $nam_jur   = $this->M_data->getQuery("SELECT `nama_unit` FROM `tb_unit` WHERE `id_unit` = '$jurusan';")->result()[0]->nama_unit;
            $id_his    = $this->M_data->getQuery("SELECT `id_history` FROM `tb_history_prodi` WHERE `id_simulasi` = $id_simulasi AND `jurusan` = '$nam_jur';")->result();

            // Menghilangkan dan Merekonstruksi nilai array yang hilang
            $str       = explode(";", $inputan);
            $str       = array_filter($str);
            $str       = array_values($str);
            $temp      = 0;

            // Debugging Tools 
            // echo "Jumlah inputan : ". count($str)."\n";
            // echo "Jumlah data pada tabel history : " . count($id_his) . "\n";
            // echo "Jumlah data pada tabel prodi : " . count($prodi) . "\n";

            for ($i = 0; $i < count($str); $i++) {
                // Mengambil Nama Prodi
                $nama_prodi      = $prodi[$i]->nama_prodi;

                // Memasukkan pada database [menggunakan parameter $str[$i] untuk menghitung jumlah prodi]
                if (count($str) > count($id_his) || count($str) <= count($prodi)) {
                    // jika data yang diupdate mengalami perubahan pada penambahan prodi, Maka hapus terlebih dahulu.Lalu tambahkan dengan data yang baru:
                    if ($i == 0) {
                        // Hapus data hanya sekali
                        $where = array(
                            'id_simulasi'   => $id_simulasi,
                            'jurusan'       => $nam_jur
                        );

                        // hapus data 
                        $this->M_data->hapus_data($where, 'tb_history_prodi');

                        // Cek data berhasil dihapus
                        // var_dump("delete", $i);
                    }
                    // Cek data berhasil ditambahkan
                    // var_dump("insert", $i);
                   
                    //Tambahkan data baru
                    $this->M_data->getQuery("INSERT INTO `tb_history_prodi` (`id_history`, `id_simulasi`, `jurusan`, `prodi`, `akreditasi`) VALUES (NULL, '$id_simulasi', '$nam_jur', '$nama_prodi', '$str[$i]');");
                } else if(count($str) <= count($id_his)) {
                    // jika data yang diupdate tidak mengalami perubahan pada penambahan prodi, Maka update data: 

                    // Cek data berhasil diupdate
                    var_dump("update", $i);
                    $history         = $id_his[$i]->id_history;
                    $this->M_data->getQuery("UPDATE `tb_history_prodi` SET `jurusan` = '$nam_jur', `prodi` = '$nama_prodi', `akreditasi` = '$str[$i]' WHERE `tb_history_prodi`.`id_history` = $history;");

                    
                }
                
                // Cek Apakah data bisa terquery dengan baik
                // echo '<pre>';
                // var_dump("UPDATE `tb_history_prodi` SET `jurusan` = '$nam_jur', `prodi` = '$nama_prodi', `akreditasi` = '$str[$i]' WHERE `tb_history_prodi`.`id_history` = $history;");
                // echo '</pre>';

                // Menghitung Bobot nilai capaian akreditasi
                if ($str[$i] == 'A' || $str[$i] == 'UNGGUL') {
                    $angka = 0.5;
                } else if ($str[$i] == 'B' || $str[$i] == 'SANGAT BAIK') {
                    $angka = 0.4;
                } else {
                    $angka = 0.3;
                }
                $temp = $temp + $angka;
            }
            $inputan = $temp / $jum_prodi['nilai_input'];
            var_dump($temp, $inputan);
            var_dump("\n" . $jum_prodi['nilai_input'] . "");
        } else {
            echo "error";
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
            if ($total_subkriteria == 0) {
                $total_subkriteria = 1;
            }

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

        redirect('edit-simulasi-jurusan/' . $id_simulasi);
    }

    public function hitungSimulasi()
    {
        $id_simulasi = $_SESSION['id_simulasi'];

        $unit_jurusan = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 1")->result();
        $kriteria = $this->M_data->getQuery("SELECT tb_subkriteria.id_kriteria, bobot_kriteria FROM `tb_subkriteria` JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE jenis_subkriteria = 'jurusan' GROUP BY id_kriteria")->result();
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

        if (isset($_SESSION['id_simulasi'])) {
            unset($_SESSION['id_simulasi']);
            unset($_SESSION['tahun_simulasi']);
            unset($_SESSION['nominal_anggaran']);
        }

        redirect('hasil-simulasi-jurusan/' . $id_simulasi);
    }

    public function hitungSimulasiEdit($id)
    {
        $id_simulasi = $id;

        // Merubah Approval / Validasi ke 0 dan mengosongkan keterangan
        $this->M_data->getQuery("UPDATE `tb_simulasi` SET `isApproval` = '0', `keterangan` = '' WHERE `tb_simulasi`.`id_simulasi` = $id;");

        $unit_jurusan = $this->M_data->getQuery("SELECT * FROM tb_unit WHERE jenis_unit = 1")->result();
        $kriteria = $this->M_data->getQuery("SELECT tb_subkriteria.id_kriteria, bobot_kriteria FROM `tb_subkriteria` JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE jenis_subkriteria = 'jurusan' GROUP BY id_kriteria")->result();
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

        redirect('hasil-simulasi-jurusan/' . $id_simulasi);
    }

    public function hasil_simulasi_jurusan($id = null)
    {
        if ($id != null) {
            $data['tabel_hasil']            = $this->M_data->getQuery("SELECT * FROM tb_detail_simulasi JOIN tb_unit ON tb_detail_simulasi.id_unit = tb_unit.id_unit JOIN tb_subkriteria ON tb_detail_simulasi.id_subkriteria = tb_subkriteria.id_subkriteria WHERE tb_detail_simulasi.id_simulasi = '$id'")->result();
            $data['tabel_hasil_simulasi']   = $this->M_data->getQuery("SELECT * FROM tb_hasil JOIN tb_unit ON tb_hasil.id_unit = tb_unit.id_unit WHERE tb_hasil.id_simulasi = '$id'")->result();
            $data['data_prodi']             = $this->M_data->getQuery("SELECT * FROM `tb_history_prodi` WHERE `id_simulasi` = '$id';")->result();

            $data['total']                  = $this->M_data->getQuery("SELECT SUM(nilai_anggaran) as na FROM tb_hasil JOIN tb_unit ON tb_hasil.id_unit = tb_unit.id_unit WHERE tb_hasil.id_simulasi = $id")->row_array();
            $data['simulasi_jurusan']       = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE jenis_simulasi = 1 ORDER BY `tb_simulasi`.`tahun_simulasi` ASC")->result();
            $data['anggaran']               = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tb_simulasi.id_simulasi = '$id'")->row_array();
        } else {
            $data['tabel_hasil']            = NULL;
            $data['tabel_hasil_simulasi']   = NULL;
            $data['data_prodi']             = NULL;
        }
        $this->load->view("admin/V_hasilpaJurusan", $data);
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
        //hapus history prodi
        $this->M_data->hapus_data($where, 'tb_history_prodi');
        //hapus simulasi
        $this->M_data->hapus_data($where, 'tb_simulasi');

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil menghapus data.";

        redirect('pagu-anggaran-jurusan');
    }
}
