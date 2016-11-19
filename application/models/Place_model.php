<?php

class Place_Model extends CI_Model {

    function getPlaceByID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_place a');
        $this->db->where('placeID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function createPlace($data){
        $this->db->insert('tbl_cyberits_m_place',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function updatePlace($data,$id){
        $this->db->where('placeID',$id);
        $this->db->update('tbl_cyberits_m_place',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function deletePlace($id){
        $this->db->where('placeID',$id);
        $this->db->delete('tbl_cyberits_m_place');
    }
}