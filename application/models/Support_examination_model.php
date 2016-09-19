<?php

class Support_examination_model extends CI_Model {

    function getSupportExaminationColumnAutocomplete($search_name){
        $this->db->select('supportExaminationColumnName, supportExaminationColumnID');
        $this->db->from('tbl_cyberits_m_support_examination_column a');
        $this->db->like('a.supportExaminationColumnName', $search_name);

        $this->db->limit(10);

        $query = $this->db->get();
        return $query->result_array();
    }

    function createPoli($data){
        $this->db->insert('tbl_cyberits_m_poli',$data);
        $result=$this->db->affected_rows();
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