<?php

class Login_admin_model extends CI_Model {
	
	public function validate($username, $password)
	{
        $this->db->where('userName', $username);
        $this->db->where('Password', $password);
        $this->db->where('userRole', 'mediagnosis_admin');
        $query = $this->db->get('tbl_cyberits_m_users');

        return $query->row();
		
	}

    public function getUserDataByUsername($username){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_users');
        $this->db->where('isActive', 1);
        $this->db->where('userName', $username);
        $query = $this->db->get();
        return $query->row();

    }

    public function getUserDataByUserRole($userRole){
        $this->db->select('userID, userName, userRole');
        $this->db->from('tbl_cyberits_m_users u');
        $this->db->where('userRole', $userRole);
        $this->db->where('isActive', 1);
        $query = $this->db->get();
        return $query->result_array();
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
}