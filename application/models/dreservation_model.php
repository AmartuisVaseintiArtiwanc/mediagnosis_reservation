<?php  
	class DReservation_model extends CI_Model{
		function insertReservation($data){
		    $this->db->insert('tbl_cyberits_t_detail_reservation', $data);
		    return $this->db->insert_id();
		}
	}
?>