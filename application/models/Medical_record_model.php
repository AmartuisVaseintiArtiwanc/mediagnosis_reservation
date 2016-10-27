<?php

class Medical_record_model extends CI_Model {

    function getMedicalRecordListByPatient($patientID){
        $this->db->select('mr.medicalRecordID,doc.doctorID, doc.doctorName, mr.patientID,
         mr.detailReservationID, cl.clinicName, pl.poliName, dis.diseaseName, mr.created');
        $this->db->from('tbl_cyberits_t_medical_record mr');
        $this->db->join('tbl_cyberits_t_detail_medical_record  dmr', 'dmr.medicalRecordID = mr.medicalRecordID');
        $this->db->join('tbl_cyberits_m_diseases  dis', 'dmr.workingDiagnose = dis.diseaseID');
        $this->db->join('tbl_cyberits_t_detail_reservation  dr', 'mr.detailReservationID = dr.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation  hr', 'dr.reservationID = hr.reservationID');
        $this->db->join('tbl_cyberits_m_clinics  cl', 'cl.clinicID = hr.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = hr.poliID');
        $this->db->join('tbl_cyberits_m_doctors  doc', 'dr.doctorID = doc.doctorID');
        $this->db->where('mr.patientID', $patientID);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getMedicalRecordByID($medicalRecordID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_t_medical_record mr');
        $this->db->join('tbl_cyberits_t_patient_profile  b', 'mr.tPatientProfileID = b.tPatientProfileID');
        $this->db->join('tbl_cyberits_t_detail_reservation  dr', 'mr.detailReservationID = dr.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation  hr', 'dr.reservationID = hr.reservationID');
        $this->db->join('tbl_cyberits_m_clinics  cl', 'cl.clinicID = hr.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = hr.poliID');
        $this->db->join('tbl_cyberits_m_doctors  doc', 'dr.doctorID = doc.doctorID');
        $this->db->where('mr.medicalRecordID', $medicalRecordID);
        $query = $this->db->get();
        return $query->row();
    }

    function checkMedication($search_name){
        $this->db->select('medicationID');
        $this->db->from('tbl_cyberits_m_medication a');
        $this->db->where('a.medicationText', $search_name);

        $query = $this->db->get();
        return $query->row();
    }

    function createMedicalRecordHeader($data){
        $this->db->insert('tbl_cyberits_t_medical_record',$data);
        $result=$this->db->insert_id();
        return $result;
    }

}