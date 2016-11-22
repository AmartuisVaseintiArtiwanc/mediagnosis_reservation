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
		//$this->db->join('tbl_cyberits_m_patients p', 'u.userID=p.userID');
		$this->db->where('email', $email);
		$this->db->where('Password', md5($password));
		$query = $this->db->get();
		
		return $query->row();
		
	}

	public function getDetailPatientData($userID){
		$this->db->select('*'); 
		$this->db->from('tbl_cyberits_m_patients p');
		$this->db->where('userID', $userID);
		$query = $this->db->get();
		
		return $query->row();
	}

	public function getDetailDoctorData($userID){
		$this->db->select('*'); 
		$this->db->from('tbl_cyberits_m_doctors d');
		$this->db->where('userID', $userID);
		$query = $this->db->get();
		
		return $query->row();	
	}
	
	public function insertUser($data)
	{
		
		$this->db->insert('tbl_cyberits_m_users', $data);
		return $this->db->insert_id();
		
	}

	public function checkUsernameExists($username){

			$this->db->select('*');
	        $this->db->from('tbl_cyberits_m_users');
	        $this->db->where('isActive', 1);
	        $this->db->where('userName', $username);
	        $query = $this->db->get();

	        if($query->num_rows()>0){
	            return 1; // allready exist
	        }else{
	            return 0; //blom ada
	        }
	}

	public function checkEmailExists($email){

			$this->db->select('*');
	        $this->db->from('tbl_cyberits_m_users');
	        $this->db->where('isActive', 1);
	        $this->db->where('email', $email);
	        $query = $this->db->get();
	        
	        if($query->num_rows()>0){
	            return 1; // allready exist
	        }else{
	            return 0; //blom ada
	        }
	}
}