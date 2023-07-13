<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referensi extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(['M_data', 'M_ref']);
		if ($this->session->userdata('status') != TRUE) {
			redirect(base_url("login"));
		}
	}

	public function index()
	{
		$this->load->view("admin/V_referensi");
	}

	public function ajax_list()
	{
		$list = $this->M_ref->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $ref) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ref->keterangan;
			$row[] = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="edit_ref(' . "'" . $ref->id_ref . "'" . ')"><i class="fa fa-edit"></i> Edit</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_ref(' . "'" . $ref->id_ref . "'" . ')"> <i class="fa fa-trash"></i> Hapus</a>
			<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="lihat_ref(' . "'" . $ref->nama_file . "'" . ')"><i class="fa fa-eye"></i> Lihat</a>
					 ';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_ref->count_all(),
			"recordsFiltered" => $this->M_ref->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
			'keterangan' => $this->input->post('keterangan')
		);
		if (!empty($_FILES['nama_file']['name'])) {
			$upload = $this->_do_upload();
			$data['nama_file'] = $upload;
		}
		$this->M_ref->save($data);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload()
	{
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'pdf|doc';
		$config['max_size']             = 10240; //set max size allowed in Kilobyte
		$config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('nama_file')) //upload and validate
		{
			$data['inputerror'][] = 'nama_file';
			$data['error_string'][] = 'Upload error: ' . $this->upload->display_errors('', ''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	public function ajax_edit($id)
	{
		$data = $this->M_ref->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'keterangan' => $this->input->post('keterangan')
		);
		if (!empty($_FILES['nama_file']['name'])) {
			$upload = $this->_do_upload();

			//delete file
			$ref = $this->M_ref->get_by_id($this->input->post('id_ref'));
			if (file_exists('uploads/' . $ref->nama_file) && $ref->nama_file)
				unlink('uploads/' . $ref->nama_file);

			$data['nama_file'] = $upload;
		}
		$this->M_ref->update(array('id_ref' => $this->input->post('id_ref')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$ref = $this->M_ref->get_by_id($id);
		if (file_exists('uploads/' . $ref->nama_file) && $ref->nama_file)
			unlink('uploads/' . $ref->nama_file);
		$this->M_ref->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('keterangan') == '') {
			$data['inputerror'][] = 'keterangan';
			$data['error_string'][] = 'Keterangan harus diisi';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}
}
