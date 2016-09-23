<?php

class Medical_record_detail_model extends CI_Model {

    function checkMedication($search_name){
        $this->db->select('medicationID');
        $this->db->from('tbl_cyberits_m_medication a');
        $this->db->where('a.medicationText', $search_name);

        $query = $this->db->get();
        return $query->row();
    }

    // MEDICAL RECORD DETAIL
    function createMedicalRecordDetail($data){
        $this->db->insert('tbl_cyberits_t_detail_medical_record',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    // ADDITIONAL CONDITION
    function createMedicalRecordDetailAdditonalCondition($data){
        $this->db->insert('tbl_cyberits_t_additional_condition',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    // SUPPORT DIAGNOSE
    function createMedicalRecordDetailSupportDiagnose($data){
        $this->db->insert('tbl_cyberits_t_support_diagnose',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    // PHYSICAL EXAMINATION
    function createMedicalRecordDetailPhysicalExamination($data){
        $this->db->insert('tbl_cyberits_t_physical_examination',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    // SUPPORT EXAMINATION
    function createMedicalRecordDetailSupportExamination($data){
        $this->db->insert('tbl_cyberits_t_support_examination',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    // MEDICATION
    function createMedicalRecordDetailMedication($data){
        $this->db->insert('tbl_cyberits_t_medication',$data);
        $result=$this->db->insert_id();
        return $result;
    }

    function updatePoli($data,$id){
        $this->db->where('poliID',$id);
        $this->db->update('tbl_cyberits_m_poli',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function deletePoli($id){
        $this->db->where('poliID',$id);
        $this->db->delete('tbl_cyberits_m_poli');
    }
}