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