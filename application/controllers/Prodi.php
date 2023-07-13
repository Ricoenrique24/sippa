<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prodi extends CI_Controller
{
    private $id;

    // Getter untuk mendapatkan nilai ID
    public function getId()
    {
        return $this->id;
    }

    // Setter untuk mengatur nilai ID
    public function setId($id)
    {
        $this->id = $id;
    }

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_prodi');
        if ($this->session->userdata('status') != TRUE) {
            redirect(base_url("login"));
        }
    }

    private function getJudul()
    {
        $id = $this->getId();
        return $this->M_prodi->get_title($id);
    }

    public function index($id)
    {
        $this->setId($id);
        $title    = $this->getJudul();

        $this->load->view("admin/V_dataProdi", array('title' => $title, 'id'=>$id));
    }

    public function ajax_list($id)
    {
        $list = $this->M_prodi->get_datatables($id);
        $data = array();
        foreach ($list as $prodi) {
            $row = array();
            $row[] = $prodi->nama_prodi;
            $row[] = '
				<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="edit_unit(\'' . $prodi->id_prodi . '\')"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_unit(\'' . $prodi->id_prodi . '\')"> <i class="fa fa-trash"></i> Hapus</a>
			';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_prodi->count_all(),
            "recordsFiltered" => $this->M_prodi->count_filtered($id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_add()
    {
        $this->_validate();
        $data = array(
            'id_prodi'   => null,
            'id_unit'    => $this->input->post('id_unit'),
            'nama_prodi' => $this->input->post('nama_prodi')
        );
        $this->M_prodi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_edit($id)
    {
        $data = $this->M_prodi->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();
        $data = array(
            'id_prodi'    => $this->input->post('id_prodi'),
            'nama_prodi' => $this->input->post('nama_prodi')
        );
        $this->M_prodi->update(array('id_prodi' => $this->input->post('id_prodi')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->M_prodi->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama_prodi') == '') {
            $data['inputerror'][] = 'nama_prodi';
            $data['error_string'][] = 'Nama unit harus diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
