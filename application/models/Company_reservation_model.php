<?php  
	class Company_reservation_model extends CI_Model{
		function insertCompanyReservation($data){
		    $this->db->insert('tbl_cyberits_t_company_reservation', $data);
		    return $this->db->insert_id();
		}
		
	}
?>