<?php

class Login_model extends CI_Model {
	
	public function validate($username, $password)
	{
		$this->db->where('userName', $username);
		$this->db->where('Password', md5($password));
		$query = $this->db->get('tbl_cyberits_m_users');
		
		return $query->row();
		
	}
	
	public function create_member()
	{
		
		$new_member_insert_data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email_address' => $this->input->post('email_address'),			
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password'))						
		);
		
		$insert = $this->db->insert('membership', $new_member_insert_data);
		return $insert;
	}
}