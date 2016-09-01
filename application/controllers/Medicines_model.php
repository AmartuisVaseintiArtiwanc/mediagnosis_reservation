<?php  
	class Medicines_model extends CI_Model{
		function getMedicineList(){
			$this->db->select('*');
            $this->db->from('tbl_cyberits_m_medicines m');
            $this->db->where('m.isActive', 1);
            $query = $this->db->get();
            return $query->result_array();
		}
	}
?>