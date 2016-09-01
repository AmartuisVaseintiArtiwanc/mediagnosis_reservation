<?php 
	class Symptomps_model extends CI_Model{
		
		function getSymptompList(){
			$this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps s');
            $this->db->where('s.isActive', 1);
            $query = $this->db->get();
            return $query->result_array();
		}
	}
 ?>