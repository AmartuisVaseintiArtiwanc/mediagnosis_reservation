<?php
class Test_model extends CI_Model{

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

    function getPatientCurrentQueue($patientID){
        $date = date('Y-m-d', time());
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->like('a.patientID',$patientID);
        $this->db->like('a.created',$date);
        $this->db->where('a.status',"waiting");
        $query = $this->db->get();
        return $query->row();
    }

    function getCurrentQueue($clinic){
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

    function getCurrentQueueDoctor($reservation){
        $date = date('Y-m-d', time());

        #Create where clause
        $this->db->select('currentQueue');
        $this->db->from('tbl_cyberits_t_header_reservation');
        $this->db->where("a.reservationID",$reservation);
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

    function checkUnfinishReservation($doctorID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.status",'confirm');
        $query = $this->db->get();
        return $query->row();
    }

    function checkWaitingConfirmReservation($doctorID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.status",'check');
        $query = $this->db->get();
        return $query->row();
    }

    function checkReservationByDoctorDetailID($detailID,$doctorID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.detailReservationID",$detailID);
        $query = $this->db->get();
        return $query->row();
    }

    function getReservationDetailDoctor($detailID,$doctorID,$status){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.doctorID",$doctorID);
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",$status);
        $query = $this->db->get();
        return $query->row();
    }

    function getReservationDetailWaitingByID($detailID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",'waiting');
        $query = $this->db->get();
        return $query->row();
    }

    function getReservationDetailByIDStatus($detailID, $status){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->where("a.detailReservationID",$detailID);
        $this->db->where("a.status",$status);
        $query = $this->db->get();
        return $query->row();
    }

    function insertReservation($data){
        $this->db->insert('tbl_cyberits_t_header_reservation', $data);
        return $this->db->insert_id();
    }

    function updateReservationDetail($data, $detailID){
        $this->db->where('detailReservationID',$detailID);
        $this->db->update('tbl_cyberits_t_detail_reservation',$data);

        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    function updateReservationHeader($data, $headerID){
        $this->db->where('reservationID',$headerID);
        $this->db->update('tbl_cyberits_t_header_reservation',$data);

        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
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