<?php  
	class DReservation_model extends CI_Model{
		function insertReservation($data){
		    $this->db->insert('tbl_cyberits_t_detail_reservation', $data);
		    return $this->db->insert_id();
		}

		function checkReservationAvailability($patientID){
	    	$date = date('Y-m-d', time());

	    	$this->db->select('*');
	        $this->db->from('tbl_cyberits_t_detail_reservation');
	        $this->db->like('created',$date);
	        $this->db->where('isActive', 1);
	        $this->db->where('status !=', 'reject');
	        $this->db->where('status !=', 'late');
	        $this->db->where('status !=', 'done');
	        $this->db->where('patientID', $patientID);
	        
	        $query = $this->db->get();
	        if($query->num_rows()>0){
	            return 1; // allready exist
	        }else{
	            return 0; //blom ada
	        }
	    }

	     // Check Reservation Apakah sudah selesai dari panel admin satau belom

		function checkReservationAfterExamine($detailID){

		    $this->db->select('*');
		    $this->db->from('tbl_cyberits_t_detail_reservation a');
		    $this->db->where("a.detailReservationID",$detailID);
		    $this->db->where("a.status !=",'waiting');
		    $this->db->where("a.status !=",'check');
		    $query = $this->db->get();
		    if($query->num_rows() == 0){
		        return 0; // allready taken by other doctor
		    }else{
		        return 1; // available
		    }
		}

	}
?>