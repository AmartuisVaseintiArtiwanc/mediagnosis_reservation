<?php
class Test_model extends CI_Model{

    // Check Today Reservation, to create Header Reservation if no reservation today
    function checkReservationToday($clinic,$poli){
        $date = date('Y-m-d', time());

        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where('poliID', $poli);
        $this->db->where('clinicID',$clinic);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    // Check Reservation, to create Header Reservation if no reservation by Certain Date
    function checkReservationByDate($clinic,$poli,$date){

        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where('poliID', $poli);
        $this->db->where('clinicID',$clinic);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $query = $this->db->get();

        return $query->row();
    }

    // Get Info Reservation (Poli, Clinic) by clinicID
    function getReservationClinicPoli($clinic){
        $date = date('Y-m-d', time());

        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_header_reservation a');
        $this->db->join('tbl_cyberits_m_poli b', 'a.poliID = b.poliID');
        $this->db->join('tbl_cyberits_m_clinics c', 'a.clinicID = c.clinicID');
        $this->db->where('a.clinicID',$clinic);
        $this->db->where('a.isActive', 1);
        $this->db->like('a.created',$date);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get Header Reservation by clinicID
    function getHeaderReservationData($clinic){
        $date = date('Y-m-d', time());

        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_header_reservation a');
        $this->db->join('tbl_cyberits_m_clinics c', 'a.clinicID = c.clinicID');
        $this->db->where('a.clinicID',$clinic);
        $this->db->where('a.isActive', 1);
        $this->db->like('a.created',$date);
        $query = $this->db->get();
        return $query->row();
    }

    // Get Header Medical Record (detail reservation, header reservation, clinic) by Detail Reservation
    function getHeaderMedicalRecordByDetail($detailID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_clinics c', 'c.clinicID = b.clinicID');
        $this->db->join('tbl_cyberits_m_poli d', 'd.poliID = b.poliID');
        $this->db->join('tbl_cyberits_m_doctors e', 'e.doctorID = a.doctorID');
        $this->db->where('a.detailReservationID',$detailID);

        //$this->db->limit(5, 0);
        $query = $this->db->get();
        return $query->row();
    }

    // Get Header Reservation (header reservation, poli, clinic) by Clinic, Poli
    function getHeaderReservationDataByDoctor($clinic,$poli){
        $date = date('Y-m-d', time());

        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_header_reservation a');
        $this->db->join('tbl_cyberits_m_clinics c', 'a.clinicID = c.clinicID');
        $this->db->join('tbl_cyberits_m_poli d', 'd.poliID = a.poliID');
        $this->db->where('a.clinicID',$clinic);
        $this->db->where('a.poliID',$poli);
        $this->db->where('a.isActive', 1);
        $this->db->like('a.created',$date);
        $query = $this->db->get();
        return $query->row();
    }

    // Get Latest Reservation Queue for Clinic Admin
    function getReservationLatestQueue($clinic){
        $date = date('Y-m-d', time());

        #Create where clause
        $this->db->select('reservationID');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where('clinicID',$clinic);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_poli c', 'b.poliID = c.poliID');
        $this->db->join('tbl_cyberits_m_doctors d', 'a.doctorID = d.doctorID');
        $this->db->like('a.created',$date);
        $this->db->where('a.status ',"late");
        $this->db->or_where('a.status ',"confirm");

        $this->db->where("a.reservationID IN ($where_clause)", NULL, FALSE);
        $this->db->order_by('a.created','desc');
        //$this->db->limit(5, 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get Next Reservation Queue for Clinic Admin
    function getReservationNextQueue($clinic){
        $date = date('Y-m-d', time());

        #Create where clause
        $this->db->select('reservationID');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where('clinicID',$clinic);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_poli c', 'b.poliID = c.poliID');
        $this->db->join('tbl_cyberits_m_patients e', 'a.patientID = e.patientID');

        $this->db->like('a.created',$date);
        $this->db->where('a.status ',"waiting");

        $this->db->where("a.reservationID IN ($where_clause)", NULL, FALSE);
        $this->db->order_by('a.detailReservationID','asc');
        //$this->db->limit(5, 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get Current Reservation Queue for User/Patient by PatientID
    function getPatientCurrentQueue($patientID){
        $date = date('Y-m-d', time());
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->where('a.patientID',$patientID);
        $this->db->like('a.created',$date);
        $this->db->where('a.status',"waiting");
        $query = $this->db->get();
        return $query->row();
    }

    // Get Current Reservation Queue for User/Patient by PatientID AND DetailReservationID
    function getPatientCurrentQueueByReservation($detailID,$patientID){
        $date = date('Y-m-d', time());
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->where('a.patientID',$patientID);
        $this->db->where('a.detailReservationID',$detailID);

        $query = $this->db->get();
        return $query->row();
    }

    // Get Current Reservation Queue on This Clinic by ClinicID
    function getClinicCurrentQueue($clinicID,$poliID){
        $date = date('Y-m-d', time());
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_header_reservation a');
        $this->db->where('a.clinicID',$clinicID);
        $this->db->where('a.poliID',$poliID);
        $this->db->like('a.created',$date);
        $query = $this->db->get();
        return $query->row();
    }

    // Get Current Reservation Queue for Admin to Confirm (status : check)
    function getCurrentQueue($clinic,$poli){
        $date = date('Y-m-d', time());

        #Create where clause
        $this->db->select('reservationID');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where('clinicID',$clinic);
        $this->db->where('poliID',$poli);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_poli c', 'b.poliID = c.poliID');
        $this->db->join('tbl_cyberits_m_doctors d', 'a.doctorID = d.doctorID');
        $this->db->join('tbl_cyberits_m_patients e', 'a.patientID = e.patientID');
        $this->db->like('a.created',$date);
        $this->db->where('a.status',"check");
        $this->db->where("a.reservationID IN ($where_clause)", NULL, FALSE);
        $this->db->order_by('a.created','asc');
        //$this->db->limit(5, 0);
        $query = $this->db->get();
        return $query->row();
    }

    function getCurrentQueueOLD($clinic){
        $date = date('Y-m-d', time());

        #Create where clause
        $this->db->select('reservationID');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where('clinicID',$clinic);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_poli c', 'b.poliID = c.poliID');
        $this->db->join('tbl_cyberits_m_doctors d', 'a.doctorID = d.doctorID');
        $this->db->join('tbl_cyberits_m_patients e', 'a.patientID = e.patientID');
        $this->db->like('a.created',$date);
        $this->db->where('a.status',"check");
        $this->db->where("a.reservationID IN ($where_clause)", NULL, FALSE);
        $this->db->order_by('a.created','asc');
        //$this->db->limit(5, 0);
        $query = $this->db->get();
        return $query->row();
    }

    // Get Current Reservation Queue for Doctor to Confirm (status : waiting)
    function getCurrentQueueDoctor($reservation){
        $date = date('Y-m-d', time());

        #Create where clause
        $this->db->select('currentQueue');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where("reservationID",$reservation);
        $this->db->where('isActive', 1);
        $this->db->like('created',$date);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_poli c', 'b.poliID = c.poliID');
        $this->db->join('tbl_cyberits_m_patients d', 'a.patientID = d.patientID');
        $this->db->like('a.created',$date);
        $this->db->where('a.status',"waiting");
        $this->db->where("a.reservationID",$reservation);
        $this->db->where("a.noQueue > ",$where_clause);
        $this->db->order_by('a.created','asc');
        //$this->db->limit(5, 0);
        $query = $this->db->get();
        return $query->row();
    }

    // Check Reservation Detail for Doctor by reservationDetailID (already taken or not)
    function checkReservationDetail($detailID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",'waiting');
        $query = $this->db->get();
        if($query->num_rows() == 0){
            return 0; // allready taken by other doctor
        }else{
            return 1; // available
        }
    }

    // Check Unfinished Reservation Detail for Doctor by DoctorID
    function checkUnfinishReservation($doctorID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.status",'confirm');
        $query = $this->db->get();
        return $query->row();
    }

    // Check Waiting Reservation Detail for Doctor by DoctorID (waiting Admin to confirm)
    function checkWaitingConfirmReservation($doctorID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.status",'check');
        $this->db->or_where('a.status', 'examine');
        $query = $this->db->get();
        return $query->row();
    }

    // Check Reservation for Doctor start Medical Record (already Confirm or Reject by Admin )
    function checkReservationByDoctorDetailID($detailID,$doctorID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.detailReservationID",$detailID);
        $query = $this->db->get();
        return $query->row();
    }

    // Check Reservation Role for Admin Clinic
    function checkReservationClinicAdminRole($detailID, $clinicID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_m_doctors dc', 'dc.doctorID = a.doctorID');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_clinics c', 'b.clinicID = c.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = b.poliID');
        $this->db->where("b.clinicID",$clinicID);
        $this->db->where("a.detailReservationID",$detailID);
        $query = $this->db->get();
        return $query->row();
    }

    // Check Medical Record OTP for Doctor
    function checkOTPMedicalRecord($detailID, $doctorID, $patientID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.patientID",$patientID);
        $this->db->where("a.status","confirm");
        $this->db->where("a.detailReservationID",$detailID);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return true; // return true
        }else{
            return false; // return false
        }
    }

    // Get Detail Reservation by detailReservationID, Doctor, Status
    function getReservationDetailDoctor($detailID,$doctorID,$status){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",$status);
        $query = $this->db->get();
        return $query->row();
    }

	// Get Detail Reservation by detailReservationID
    function getReservationDetailByID($detailID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.detailReservationID",$detailID);
        $query = $this->db->get();
        return $query->row();
    }
	
    // Get Detail Reservation status Waiting by detailReservationID
    function getReservationDetailWaitingByID($detailID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",'waiting');
        $query = $this->db->get();
        return $query->row();
    }

    // Get Detail Reservation by detailReservationID, status
    function getReservationDetailByIDStatus($detailID, $status){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",$status);
        $query = $this->db->get();
        return $query->row();
    }

    function getRatingReservationByPatient($patientID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_m_doctors dc', 'dc.doctorID = a.doctorID');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_clinics c', 'b.clinicID = c.clinicID');
        $this->db->where("a.patientID",$patientID);
        $this->db->where("a.status","done");
        $this->db->where("a.isRating","0");
        $query = $this->db->get();
        return $query->row();
    }

    // Insert Header Reservation
    function insertReservation($data){
        $this->db->insert('tbl_cyberits_t_header_reservation', $data);
        return $this->db->insert_id();
    }

    // Update Detail Reservation by detailReservationID
    function updateReservationDetail($data, $detailID){
        $this->db->where('detailReservationID',$detailID);
        $this->db->update('tbl_cyberits_t_detail_reservation',$data);

        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    // Update Header Reservation by ReservationID
    function updateReservationHeader($data, $headerID){
        $this->db->where('reservationID',$headerID);
        $this->db->update('tbl_cyberits_t_header_reservation',$data);

        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    // Update Header Reservation by Clinic, Poli
    function updateReservation($data, $clinicID, $poliID){
        $this->db->where('clinicID',$clinicID);
        $this->db->where('poliID',$poliID);
        $this->db->update('tbl_cyberits_t_header_reservation',$data);

        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    function deleteReservationDetail($id){
        $this->db->where('detailReservationID',$id);
        $this->db->delete('tbl_cyberits_t_detail_reservation');
    }
}
?>