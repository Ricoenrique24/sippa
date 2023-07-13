<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_data');
		if ($this->session->userdata('status') != TRUE) {
			redirect(base_url("login"));
		}
	}

	public function index()
	{
		$data['pengguna'] = $this->M_data->getQuery("SELECT * FROM tb_pengguna")->num_rows();
		$data['referensi'] = $this->M_data->getQuery("SELECT * FROM tb_referensi")->num_rows();
		$data['tahunAnggaran'] = $this->M_data->getQuery("SELECT DISTINCT(`tahun_simulasi`) FROM `tb_simulasi` ORDER BY tahun_simulasi ASC;")->result();
		$data['paguJur'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = YEAR(CURDATE()) AND jenis_simulasi=1")->row();
		$data['paguNon'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = YEAR(CURDATE()) AND jenis_simulasi=2")->row();
		$this->load->view("admin/V_index", $data);
	}

	public function filter_data()
	{
		$data['pengguna'] = $this->M_data->getQuery("SELECT * FROM tb_pengguna")->num_rows();
		$data['referensi'] = $this->M_data->getQuery("SELECT * FROM tb_referensi")->num_rows();
		$data['tahunAnggaran'] = $this->M_data->getQuery("SELECT DISTINCT(`tahun_simulasi`) FROM `tb_simulasi`;")->result();
		$tahun = $this->input->post('tahun');
		$this->session->set_userdata('setTahun', $tahun);
		if ($tahun == "") {
			$data['paguJur'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = YEAR(CURDATE()) AND jenis_simulasi=1")->row();
			$data['paguNon'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = YEAR(CURDATE()) AND jenis_simulasi=2")->row();
			$this->session->unset_userdata('setTahun');
		} else {
			$data['paguJur'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = $tahun AND jenis_simulasi=1")->row();
			$data['paguNon'] = $this->M_data->getQuery("SELECT * FROM tb_simulasi WHERE tahun_simulasi = $tahun AND jenis_simulasi=2")->row();
		}
		$this->load->view("admin/V_index", $data);
	}
}
