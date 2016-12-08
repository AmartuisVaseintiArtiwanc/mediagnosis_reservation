<?php

class Patient_model extends CI_Model {

    function getPatientByID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->where('patientID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function getPatientByUserID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->where('userID',$id);
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

    function getPatientList(){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getPatientIDByUserID($userID){
        $this->db->select('patientID');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive',1);
        $this->db->where("userID", $userID);
        $query = $this->db->get();
        return $query->row();
    }

    function updatePatient($id,$data){
        $this->db->where('userID',$id);
        $this->db->update('tbl_cyberits_m_patients',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    public function checkTemporaryPatient($idNumber){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 1);
        $this->db->where('ktpID', $idNumber);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function checkIDNumberExists($idNumber){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 0);
        $this->db->where('ktpID', $idNumber);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function getTemporaryPatientID($idNumber){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 1);
        $this->db->where('ktpID', $idNumber);
        $query = $this->db->get();

        return $query->row();
    }

    function updatePatientByPatientID($patientID,$data){
        $this->db->where('patientID',$patientID);
        $this->db->update('tbl_cyberits_m_patients',$data);
        $result=$this->db->affected_rows();
        return $result;
    }    
}