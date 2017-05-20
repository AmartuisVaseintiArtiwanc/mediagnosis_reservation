<?php

class Unit_model extends CI_Model {
	
	function getAllActiveUnit(){
		$this->db->select('*');
        $this->db->from('tbl_cyberits_m_units');
        $this->db->where('isActive',1);
        $query = $this->db->get();
        return $query->result_array();
	}
}

?>