<?php

class M_data extends CI_Model
{
	function input_data($data, $table)
	{
		$this->db->insert($table, $data);
	}

	public function simpanData($table, $fields)
	{
		$this->db->insert($table, $fields);
		return $this->db->insert_id();
	}

	function hapus_data($where, $table)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}

	function edit_data($where, $table)
	{
		return $this->db->get_where($table, $where);
	}

	function update_data($where, $data, $table)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
	}

	public function updateData($table, $fields, $where)
	{
		$this->db->set($fields);
		$this->db->where($where);
		return $this->db->update($table);
	}

	public function getQuery($q)
	{
		$query = $this->db->query($q);
		return $query;
	}
}
