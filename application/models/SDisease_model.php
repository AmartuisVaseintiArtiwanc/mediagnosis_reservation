<?php

class SDisease_Model extends CI_Model {

    function getSettingDetailDisease($diseaseID){
		$this->db->select('*');
        $this->db->from('tbl_cyberits_s_diseasesymptomps a');
		$this->db->join('tbl_cyberits_m_symptomps b', 'a.symptompID = b.symptompID');
        $this->db->where('a.diseaseID',$diseaseID);
		$this->db->order_by('b.symptompName','asc');
        $query = $this->db->get();
		return $query->result_array();
	}
	
    function createSettingDisease($data){
        $this->db->insert('tbl_cyberits_s_diseasesymptomps',$data);	
		$result=$this->db->affected_rows();
		return $result;
    }
    
   	function updateSettingDisease($diseaseID,$symptompID,$data){
		$this->db->where('diseaseID',$diseaseID);
		$this->db->where('symptompID',$symptompID);
		$this->db->update('tbl_cyberits_s_diseasesymptomps',$data);
		$result=$this->db->affected_rows();
		return $result;
	}
    
    function deleteSettingDisease($diseaseID,$symptompID){
    	$this->db->where('diseaseID',$diseaseID);
		$this->db->where('symptompID',$symptompID);
        $this->db->delete('tbl_cyberits_s_diseasesymptomps');
	}
}