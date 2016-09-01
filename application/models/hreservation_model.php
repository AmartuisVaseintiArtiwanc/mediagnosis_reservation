<?php  
	class HReservation_model extends CI_Model{

		function checkReservationToday(){

			$date = date('Y-m-d', time());

			$this->db->select('*');
	        $this->db->from('tbl_cyberits_t_header_reservation');
	        $this->db->like('created',$date);
	        $this->db->where('isActive', 1);
	        $query = $this->db->get();
	        if($query->num_rows()>0){
	            return $query->row(); // allready exist
	        }else{
	            return 0; //blom ada
	        }
		}

		function insertReservation($data){
		    $this->db->insert('tbl_cyberits_t_header_reservation', $data);
		    return $this->db->insert_id();
		}

     	function updateReservation($data, $clinicID, $poliID){
	        $this->db->where('clinicID',$clinicID);
	        $this->db->where('poliID',$poliID);
	        $this->db->update('tbl_cyberits_t_header_reservation',$data);

	        if ($this->db->affected_rows() == 1)
	            return TRUE;
	        else
	            return FALSE;
	    }
	}
?>