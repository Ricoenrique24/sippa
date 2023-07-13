<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['M_data', 'M_unit']);
        if ($this->session->userdata('status') != TRUE) {
            redirect(base_url("login"));
        }
    }

    public function kriteria()
    {
        $data['kriteria'] = $this->M_data->getQuery("SELECT * FROM tb_kriteria WHERE jenis_kriteria = 'jurusan'")->result();
        $data['kriteria2'] = $this->M_data->getQuery("SELECT * FROM tb_kriteria WHERE jenis_kriteria = 'nonjurusan'")->result();
        $data['no'] = 0;
        $data['no2'] = 0;


        $this->load->view("admin/V_kriteria", $data);
    }

    public function add_kriteria()
    {
        $nama_kriteria = $this->input->post('nama_kriteria');
        $bobot_kriteria = $this->input->post('bobot_kriteria');
        $jenis_kriteria = $this->input->post('jenis_kriteria');

        $fields = array(
            'nama_kriteria' => $nama_kriteria,
            'bobot_kriteria' => $bobot_kriteria,
            'jenis_kriteria' => $jenis_kriteria
        );
        $this->M_data->simpanData('tb_kriteria', $fields);

        redirect('data-master-kriteria');
    }

    public function edit_kriteria()
    {
        $id_kriteria = $this->input->post('eid_kriteria');
        $nama_kriteria = $this->input->post('nama_kriteria');
        $bobot_kriteria = $this->input->post('bobot_kriteria');
        $bobot_kriteria_old = $this->input->post('bobot_kriteria_old');
        $jenis_kriteria = $this->input->post('jenis_kriteria');

        $cek_bobot = $this->M_data->getQuery("SELECT SUM(bobot_kriteria) AS bk FROM tb_kriteria WHERE jenis_kriteria = '$jenis_kriteria'")->row_array();
        $new_all_bobot = ($cek_bobot['bk'] - $bobot_kriteria_old) + $bobot_kriteria;

        if ($new_all_bobot <= 100) {
            $fields = array(
                'nama_kriteria' => $nama_kriteria,
                'bobot_kriteria' => $bobot_kriteria,
                'jenis_kriteria' => $jenis_kriteria
            );
            $where = array(
                'id_kriteria' => $id_kriteria
            );
            $this->M_data->updateData('tb_kriteria', $fields, $where);

            $_SESSION['type'] = "success";
            $_SESSION['judul'] = "Sukses";
            $_SESSION['isi'] = "Berhasil mengubah data.";
        } else {
            $_SESSION['type'] = "danger";
            $_SESSION['judul'] = "GAGAL";
            $_SESSION['isi'] = "Gagal menyimpan, total bobot melebihi 100%.";
        }

        redirect('data-master-kriteria');
    }

    public function subkriteria()
    {
        $data['subkriteria'] = $this->M_data->getQuery("SELECT * FROM tb_subkriteria JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE tb_kriteria.jenis_kriteria='jurusan' ORDER BY `tb_kriteria`.`id_kriteria` ASC")->result();
        $data['subkriteria2'] = $this->M_data->getQuery("SELECT * FROM tb_subkriteria JOIN tb_kriteria ON tb_subkriteria.id_kriteria = tb_kriteria.id_kriteria WHERE tb_kriteria.jenis_kriteria='nonjurusan'")->result();
        $data['no'] = 0;
        $data['no2'] = 0;
        $data['kriteria'] = $this->M_data->getQuery("SELECT * FROM tb_kriteria")->result();

        $this->load->view("admin/V_subkriteria", $data);
    }

    public function add_subkriteria()
    {
        $kriteria = $this->input->post('kriteria');
        $nama_subkriteria = $this->input->post('nama_subkriteria');
        $bobot_subkriteria = $this->input->post('bobot_subkriteria');
        $jenis_subkriteria = $this->input->post('jenis_subkriteria');

        $cek_bobot = $this->M_data->getQuery("SELECT SUM(bobot_subkriteria) AS bs FROM tb_subkriteria WHERE id_kriteria = '$kriteria'")->row_array();
        $new_all_bobot = $cek_bobot['bs'] + $bobot_subkriteria;

        if ($new_all_bobot <= 100) {
            $fields = array(
                'id_kriteria' => $kriteria,
                'nama_subkriteria' => $nama_subkriteria,
                'bobot_subkriteria' => $bobot_subkriteria,
                'jenis_subkriteria' => $jenis_subkriteria
            );
            $this->M_data->simpanData('tb_subkriteria', $fields);

            $_SESSION['type'] = "success";
            $_SESSION['judul'] = "Sukses";
            $_SESSION['isi'] = "Berhasil menyimpan data.";
        } else {
            $_SESSION['type'] = "danger";
            $_SESSION['judul'] = "GAGAL";
            $_SESSION['isi'] = "Gagal menyimpan, total bobot melebihi 100%.";
        }

        redirect('data-master-subkriteria');
    }

    public function edit_subkriteria()
    {
        $id_subkriteria = $this->input->post('eid_subkriteria');
        $kriteria = $this->input->post('kriteria');
        $nama_subkriteria = $this->input->post('nama_subkriteria');
        $bobot_subkriteria = $this->input->post('bobot_subkriteria');
        $bobot_subkriteria_old = $this->input->post('bobot_subkriteria_old');
        $jenis_subkriteria = $this->input->post('jenis_subkriteria');

        $cek_bobot = $this->M_data->getQuery("SELECT SUM(bobot_subkriteria) AS bs FROM tb_subkriteria WHERE id_kriteria = '$kriteria'")->row_array();
        $new_all_bobot = ($cek_bobot['bs'] - $bobot_subkriteria_old) + $bobot_subkriteria;

        if ($new_all_bobot <= 100) {
            $fields = array(
                'id_kriteria' => $kriteria,
                'nama_subkriteria' => $nama_subkriteria,
                'bobot_subkriteria' => $bobot_subkriteria,
                'jenis_subkriteria' => $jenis_subkriteria
            );
            $where = array(
                'id_subkriteria' => $id_subkriteria
            );

            $this->M_data->updateData('tb_subkriteria', $fields, $where);

            $_SESSION['type'] = "success";
            $_SESSION['judul'] = "Sukses";
            $_SESSION['isi'] = "Berhasil mengubah data.";
        } else {
            $_SESSION['type'] = "danger";
            $_SESSION['judul'] = "GAGAL";
            $_SESSION['isi'] = "Gagal menyimpan, total bobot melebihi 100%.";
        }

        redirect('data-master-subkriteria');
    }

    public function hapus_kriteria()
    {
        $id = $this->input->post("id_kriteria");

        $where = array('id_kriteria' => $id);
        $this->M_data->hapus_data($where, 'tb_kriteria');

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil menghapus data.";

        redirect('data-master-kriteria');
    }

    public function hapus_subkriteria()
    {
        $id = $this->input->post("id_subkriteria");

        $where = array('id_subkriteria' => $id);
        $this->M_data->hapus_data($where, 'tb_subkriteria');

        $_SESSION['type'] = "success";
        $_SESSION['judul'] = "Sukses";
        $_SESSION['isi'] = "Berhasil menghapus data.";

        redirect('data-master-subkriteria');
    }
}
