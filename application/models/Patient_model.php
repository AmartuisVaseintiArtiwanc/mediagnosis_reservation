<?php

class Patient_model extends CI_Model {

    function getPatientByID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->where('patientID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function insertTransProfilePatient($data){
        $this->db->insert('tbl_cyberits_t_patient_profile', $data);
        return $this->db->insert_id();
    }

    function checkTransProfilePatient($patientID){
        $this->db->select('a.ktpID,a.bpjsID,a.phoneNumber,a.address,a.dob,b.tPatientProfileID');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->join('tbl_cyberits_t_patient_profile  b', 'a.patientID = b.patientID');
        $this->db->where('b.patientID',$patientID);
        $this->db->where('a.ktpID','b.ktpID');
        $this->db->where('a.bpjsID','b.bpjsID');
        $this->db->where('a.phoneNumber','b.phoneNumber');
        $this->db->where('a.address','b.address');
        $this->db->where('a.dob','b.dob');
        $query = $this->db->get();
        return $query->row();
    }
}