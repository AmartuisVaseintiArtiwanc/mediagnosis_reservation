<?php

class Main_condition_model extends CI_Model {

    function getMainConditionAutocomplete($search_name){
        $this->db->select('mainConditionText, mainConditionID');
        $this->db->from('tbl_cyberits_m_main_condition a');
        $this->db->like('a.mainConditionText', $search_name);

        $this->db->limit(10);

        $query = $this->db->get();
        return $query->result_array();
    }

    function checkMainCondition($search_name){
        $this->db->select('mainConditionID as id');
        $this->db->from('tbl_cyberits_m_main_condition a');
        $this->db->where('a.mainConditionText', $search_name);

        $query = $this->db->get();
        return $query->row();
    }

    function createMainCondition($data){
        $this->db->insert('tbl_cyberits_m_main_condition',$data);
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