<?php

class Login_model extends CI_Model {
	
	public function validate($username, $password)
	{
        $this->db->where('userName', $username);
		$this->db->where('Password', $password);
        $this->db->where('userRole !=', 'mediagnosis_admin');
		$query = $this->db->get('tbl_cyberits_m_users');
		
		return $query->row();
		
	}

    public function checkPassowrdByUserID($userID, $password)
    {
        $this->db->where('$userID', $userID);
        $this->db->where('Password', md5($password));
        $query = $this->db->get('tbl_cyberits_m_users');

        if($query->num_rows()>0){
            return 1; // password valid
        }else{
            return 0; //password tidak valid
        }

    }

	public function validateByEmail($email)
	{       
        $this->db->select('*');
		$this->db->from('tbl_cyberits_m_users u');
		//$this->db->join('tbl_cyberits_m_patients p', 'u.userID=p.userID');
        $this->db->group_start();
            $this->db->where('userName', $email);
            $this->db->or_where('email', $email);
        $this->db->group_end();
		//$this->db->where('Password', md5($password));
		$query = $this->db->get();
		
		return $query->row();
		
	}

    public function getUserDataByUserID($userID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_users u');
        //$this->db->join('tbl_cyberits_m_patients p', 'u.userID=p.userID');
        $this->db->where('userID', $userID);
        $this->db->where('isActive', 1);
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

	public function insertPatient($data)
	{
		$this->db->insert('tbl_cyberits_m_patients', $data);
		return $this->db->insert_id();
		
	}

    public function getUserDataByUsername($username){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_users');
        $this->db->where('isActive', 1);
        $this->db->where('userName', $username);
        $query = $this->db->get();
        return $query->row();

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

    public function checkUpdatedUsernameExists($userID,$username){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_users');
        $this->db->where('isActive', 1);
        $this->db->where('userName', $username);
        $this->db->where('userID !=', $userID);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 0; // allready exist
        }else{
            return 1; // blom ada = valid
        }
    }

    public function checkUpdatedEmailExists($userID, $email){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_users');
        $this->db->where('isActive', 1);
        $this->db->where('email', $email);
        $this->db->where('userID !=', $userID);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 0; // allready exist
        }else{
            return 1; //blom ada = valid
        }
    }

	public function getIDByEmail($email){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_users');
        $this->db->where('isActive', 1);
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->row();
	}

    function updateUser($id,$data){
        $this->db->where('userID',$id);
        $this->db->update('tbl_cyberits_m_users',$data);
        $result=$this->db->affected_rows();
        return $result;
    }
}