<?php

class SPoli_Model extends CI_Model {

    function getSettingDetailPoli($poliID){
		$this->db->select('*');
        $this->db->from('tbl_cyberits_s_poli a');
		$this->db->join('tbl_cyberits_m_doctors b', 'a.doctorID = b.doctorID');
        $this->db->where('a.poliID',$poliID);
        $this->db->where('a.createdBy',$this->session->userdata('superUserID'));
		$this->db->order_by('b.doctorName','asc');
        $query = $this->db->get();
		return $query->result_array();
	}
	
    function createSettingPoli($data){
        $this->db->insert('tbl_cyberits_s_poli',$data);
		$result=$this->db->affected_rows();
		return $result;
    }
    
   	function updateSettingPoli($poliID,$doctorID,$data){
		$this->db->where('poliID',$poliID);
		$this->db->where('doctorID',$doctorID);
		$this->db->update('tbl_cyberits_s_poli',$data);
		$result=$this->db->affected_rows();
		return $result;
	}
    
    function deleteSettingPoli($poliID,$doctorID){
        $this->db->where('poliID',$poliID);
        $this->db->where('doctorID',$doctorID);
        $this->db->delete('tbl_cyberits_s_poli');
	}
}