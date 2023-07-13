<?php

class M_login extends CI_Model
{
	//cek username dan password
	function auth_superadmin($username, $password)
	{
		$query = $this->db->query("SELECT * FROM tb_pengguna WHERE username='$username' AND password_pengguna=MD5('$password') AND level=2 LIMIT 1");
		return $query;
	}

	function auth_pimpinan($username, $password)
	{
		$query = $this->db->query("SELECT * FROM tb_pengguna WHERE username='$username' AND password_pengguna=MD5('$password') AND level=1 LIMIT 1");
		return $query;
	}

	function auth_adminjur($username, $password)
	{
		$query = $this->db->query("SELECT * FROM tb_pengguna WHERE username='$username' AND password_pengguna=MD5('$password') AND level=3 LIMIT 1");
		return $query;
	}

	function auth_adminnonJur($username, $password)
	{
		$query = $this->db->query("SELECT * FROM tb_pengguna WHERE username='$username' AND password_pengguna=MD5('$password') AND level=4 LIMIT 1");
		return $query;
	}
}
