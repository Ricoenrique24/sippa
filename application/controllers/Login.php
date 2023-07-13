<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_login');
	}

	function index()
	{
		$this->load->view('V_login');
	}

	function aksi_login()
	{
		$username = htmlspecialchars($this->input->post('username', TRUE), ENT_QUOTES);
		$password = htmlspecialchars($this->input->post('password', TRUE), ENT_QUOTES);

		$cek_superadmin = $this->M_login->auth_superadmin($username, $password);
		$cek_pimpinan = $this->M_login->auth_pimpinan($username, $password);
		$cek_adminjur = $this->M_login->auth_adminjur($username, $password);
		$cek_adminnonJur = $this->M_login->auth_adminnonJur($username, $password);

		if ($cek_superadmin->num_rows() > 0) { //jika login sebagai superadmin
			$data = $cek_superadmin->row_array();
			$this->session->set_userdata('status', TRUE);
			$this->session->set_userdata('akses', 2);
			$this->session->set_userdata('ses_id', $data['id_pengguna']); //dalam kurung siku disesuaikan dengan kolom tabel di database
			$this->session->set_userdata('nama', $data['nama_lengkap']);
			redirect('beranda');
		} else if ($cek_pimpinan->num_rows() > 0) {
			$data = $cek_pimpinan->row_array();
			$this->session->set_userdata('status', TRUE);
			$this->session->set_userdata('akses', 1);
			$this->session->set_userdata('ses_id', $data['id_pengguna']);
			$this->session->set_userdata('nama', $data['nama_lengkap']);
			redirect('beranda');
		} else if ($cek_adminjur->num_rows() > 0) {
			$data = $cek_adminjur->row_array();
			$this->session->set_userdata('status', TRUE);
			$this->session->set_userdata('akses', 3);
			$this->session->set_userdata('ses_id', $data['id_pengguna']);
			$this->session->set_userdata('nama', $data['nama_lengkap']);
			redirect('pagu-anggaran-jurusan');
		} else if ($cek_adminnonJur->num_rows() > 0) {
			$data = $cek_adminnonJur->row_array();
			$this->session->set_userdata('status', TRUE);
			$this->session->set_userdata('akses', 4);
			$this->session->set_userdata('ses_id', $data['id_pengguna']);
			$this->session->set_userdata('nama', $data['nama_lengkap']);
			redirect('pagu-anggaran-nonjurusan');
		} else {
			//dialihkan ke halaman login ada notif salah
			echo $this->session->set_flashdata('msg', 'login-gagal');
			redirect(base_url('login'));
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
}
