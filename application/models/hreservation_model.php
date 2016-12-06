<?php  
	class HReservation_model extends CI_Model{

		function checkReservationToday($clinicID, $poliID){

			$date = date('Y-m-d', time());

			$this->db->select('*');
	        $this->db->from('tbl_cyberits_t_header_reservation');
	        $this->db->like('created',$date);
	        $this->db->where('isActive', 1);
	        $this->db->where('clinicID',$clinicID);
	        $this->db->where('poliID',$poliID);
	        $query = $this->db->get();
	        if($query->num_rows()>0){
	            return 1; // allready exist
	        }else{
	            return 0; //blom ada
	        }
		}

		function getReservationTodayID($clinicID, $poliID){
			$date = date('Y-m-d', time());

			$this->db->select('*');
	        $this->db->from('tbl_cyberits_t_header_reservation');
	        $this->db->like('created',$date);
	        $this->db->where('isActive', 1);
	        $this->db->where('clinicID',$clinicID);
	        $this->db->where('poliID',$poliID);
	        $query = $this->db->get();
	        
	        return $query->row();
		}

		function insertReservation($data){
		    $this->db->insert('tbl_cyberits_t_header_reservation', $data);
		    return $this->db->insert_id();
		}

     	function updateReservation($data, $clinicID, $poliID){
     		$date = date('Y-m-d', time());


	        $this->db->where('clinicID',$clinicID);
	        $this->db->where('poliID',$poliID);
	        $this->db->where('isActive', 1);
	        $this->db->like('created',$date);
	        $this->db->update('tbl_cyberits_t_header_reservation',$data);

	        if ($this->db->affected_rows() == 1)
	            return TRUE;
	        else
	            return FALSE;
	    }

	}
?>