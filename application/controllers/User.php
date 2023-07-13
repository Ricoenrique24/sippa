<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(['M_data', 'M_user']);
		if ($this->session->userdata('status') != TRUE) {
			redirect(base_url("login"));
		}
	}

	public function index()
	{
		$data['unit'] = $this->M_data->getQuery("SELECT * FROM `tb_unit`")->result();
		$this->load->view("admin/V_user", $data);
	}

	public function ajax_list()
	{
		$list = $this->M_user->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			if ($user->level == 1) {
				$role = 'Pimpinan';
			} elseif ($user->level == 2) {
				$role = 'Superadmin';
			} elseif ($user->level == 3) {
				$role = 'Admin Jurusan';
			} else {
				$role = 'Admin Non Jurusan';
			}
			$row = array();
			$row[] = $no;
			$row[] = $user->username;
			$row[] = $user->nama_lengkap;
			$row[] = $role;
			$row[] = $user->nama_unit;
			$row[] = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="edit_user(' . "'" . $user->id_pengguna . "'" . ')"><i class="fa fa-edit"></i> Edit</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_user(' . "'" . $user->id_pengguna . "'" . ')"> <i class="fa fa-trash"></i> Hapus</a>
			<a class="btn btn-sm btn-warning" href="javascript:void(0)" onclick="reset_user(' . "'" . $user->id_pengguna . "'" . ')"> <i class="fa fa-repeat"></i> Reset Password</a>
					 ';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_user->count_all(),
			"recordsFiltered" => $this->M_user->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_add()
	{
		$this->_validate();
		$cekUname = $this->M_data->getQuery("SELECT * FROM tb_pengguna WHERE username='" . $this->input->post('username') . "'")->num_rows();
		if ($cekUname <= 0) {
			$data = array(
				'username' => $this->input->post('username'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'password_pengguna' => md5('polije123456'),
				'level' => $this->input->post('level'),
				'id_unit' => $this->input->post('id_unit')
			);
			$this->M_user->save($data);
			echo json_encode(array("status" => 1));
		} else {
			echo json_encode(array("status" => 2));
		}
	}

	public function ajax_edit($id)
	{
		$data = $this->M_user->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'username' => $this->input->post('username'),
			'nama_lengkap' => $this->input->post('nama_lengkap'),
			'level' => $this->input->post('level'),
			'id_unit' => $this->input->post('id_unit')
		);
		$this->M_user->update(array('id_pengguna' => $this->input->post('id_pengguna')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_user->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('username') == '') {
			$data['inputerror'][] = 'username';
			$data['error_string'][] = 'Username harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('nama_lengkap') == '') {
			$data['inputerror'][] = 'nama_lengkap';
			$data['error_string'][] = 'Nama harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('level') == '') {
			$data['inputerror'][] = 'level';
			$data['error_string'][] = 'Level harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('id_unit') == '') {
			$data['inputerror'][] = 'id_unit';
			$data['error_string'][] = 'Unit harus diisi';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function reset_password($id)
	{
		$data = array(
			'password_pengguna' => md5('polije123456'),
		);
		$this->M_user->update(array('id_pengguna' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}

	function ubah_passwd()
	{
		$this->load->view("admin/V_ubahPasswd");
	}

	function update_passwd()
	{
		$id = $this->input->post('id_pengguna');
		$baru = $this->input->post('pass_baru');
		$data = array(
			'password_pengguna' => md5($baru),
		);

		$where = array(
			'id_pengguna' => $id
		);

		$this->M_data->update_data($where, $data, 'tb_pengguna');
		echo $this->session->set_flashdata('msg', 'success-passwd');
		redirect('beranda');
	}
}
