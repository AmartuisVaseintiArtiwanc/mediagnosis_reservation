<?php

class Patient_model extends CI_Model {

    function getPatientByID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->where('patientID',$id);
        $query = $this->db->get();
        return $query->row();
    }
}