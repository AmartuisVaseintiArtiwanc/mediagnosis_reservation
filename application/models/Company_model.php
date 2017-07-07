<?php 

	class Company_model extends CI_Model{
		
		function getAllCompany(){
			$this->db->select('*');
			$this->db->from('tbl_cyberits_m_companies');
			$this->db->where('isActive',1);
			$query = $this->db->get();
			return $query->result_array();
		}
	}
?>