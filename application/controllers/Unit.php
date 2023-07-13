<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_unit');
		if ($this->session->userdata('status') != TRUE) {
			redirect(base_url("login"));
		}
	}

	public function index()
	{
		$this->load->view("admin/V_unit");
	}

	public function ajax_list()
	{
		$list = $this->M_unit->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			if ($unit->jenis_unit == 1) {
				$ju = 'Jurusan';
			} else {
				$ju = 'Non Jurusan';
			}
			$row = array();
			$row[] = $no;
			$row[] = $unit->nama_unit;
			$row[] = $ju;

			if ($unit->jenis_unit == 1) {
				$row[] = '
				<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="edit_unit(\'' . $unit->id_unit . '\')"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_unit(\'' . $unit->id_unit . '\')"> <i class="fa fa-trash"></i> Hapus</a>
				<a class="btn btn-success btn-sm" href="' . site_url('/prodi/' . $unit->id_unit . '') . '"><i class="fa fa-info"></i>  Lihat Prodi</a>
			';
			} else {
				$row[] = '
				<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="edit_unit(\'' . $unit->id_unit . '\')"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_unit(\'' . $unit->id_unit . '\')"> <i class="fa fa-trash"></i> Hapus</a>
				';
			}

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_unit->count_all(),
			"recordsFiltered" => $this->M_unit->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
			'nama_unit' => $this->input->post('nama_unit'),
			'jenis_unit' => $this->input->post('jenis_unit')
		);
		$this->M_unit->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->M_unit->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'nama_unit' => $this->input->post('nama_unit'),
			'jenis_unit' => $this->input->post('jenis_unit')
		);
		$this->M_unit->update(array('id_unit' => $this->input->post('id_unit')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->M_unit->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('nama_unit') == '') {
			$data['inputerror'][] = 'nama_unit';
			$data['error_string'][] = 'Nama unit harus diisi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('jenis_unit') == '') {
			$data['inputerror'][] = 'jenis_unit';
			$data['error_string'][] = 'Jenis unit harus diisi';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}
}
