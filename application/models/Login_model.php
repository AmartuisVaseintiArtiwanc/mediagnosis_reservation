<?php

class Login_model extends CI_Model {
	
	public function validate($username, $password)
	{
		$this->db->where('userName', $username);
		$this->db->where('Password', md5($password));
		$query = $this->db->get('tbl_cyberits_m_users');
		
		return $query->row();
		
	}

	public function validateByEmail($email, $password)
	{
		$this->db->select('*'); 
		$this->db->from('tbl_cyberits_m_users u');
		$this->db->join('tbl_cyberits_m_patients p', 'u.userID=p.userID');
		$this->db->where('email', $email);
		$this->db->where('Password', md5($password));
		$query = $this->db->get();
		
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